<?php

namespace Tests\Unit\Support;

use App\Support\RichTextSanitizer;
use PHPUnit\Framework\TestCase;

final class RichTextSanitizerTest extends TestCase
{
    private RichTextSanitizer $sanitizer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sanitizer = new RichTextSanitizer;
    }

    public function test_it_removes_executable_markup_event_handlers_and_dangerous_urls(): void
    {
        $clean = $this->sanitizer->sanitize(<<<'HTML'
            <h2 onclick="alert(1)">Trusted heading</h2>
            <script>alert('xss')</script>
            <a href="javascript:alert(2)" onmouseover="alert(3)">Unsafe link</a>
            <img src="javascript:alert(4)" onerror="alert(5)" alt="Story image">
            <iframe srcdoc="<script>alert(6)</script>"></iframe>
            <svg onload="alert(7)"><circle></circle></svg>
            HTML);

        $this->assertStringContainsString('<h2>Trusted heading</h2>', $clean);
        $this->assertStringContainsString('Unsafe link</a>', $clean);
        $this->assertStringNotContainsString('<script', strtolower($clean));
        $this->assertStringNotContainsString('<iframe', strtolower($clean));
        $this->assertStringNotContainsString('<svg', strtolower($clean));
        $this->assertStringNotContainsString('javascript:', strtolower($clean));
        $this->assertDoesNotMatchRegularExpression('/\son[a-z]+\s*=/i', $clean);
    }

    public function test_it_preserves_editor_formatting_safe_links_and_safe_media(): void
    {
        $clean = $this->sanitizer->sanitize(<<<'HTML'
            <h2>Election update</h2>
            <p><strong>Confirmed</strong> by <em>Glow News</em>.</p>
            <blockquote cite="https://example.com/source">A verified quote.</blockquote>
            <ol start="2"><li>First update</li><li>Second update</li></ol>
            <a href="/news/election-update" target="_blank" title="Read more">Read the full story</a>
            <figure><img src="/storage/news/election.jpg" alt="Election officials" width="1200"><figcaption>Officials at work</figcaption></figure>
            HTML);

        $this->assertStringContainsString('<h2>Election update</h2>', $clean);
        $this->assertStringContainsString('<strong>Confirmed</strong>', $clean);
        $this->assertStringContainsString('<em>Glow News</em>', $clean);
        $this->assertStringContainsString('<blockquote cite="https://example.com/source">', $clean);
        $this->assertStringContainsString('<ol start="2">', $clean);
        $this->assertStringContainsString('href="/news/election-update"', $clean);
        $this->assertStringContainsString('rel="noopener noreferrer"', $clean);
        $this->assertStringContainsString('src="/storage/news/election.jpg"', $clean);
        $this->assertStringContainsString('loading="lazy"', $clean);
        $this->assertStringContainsString('<figcaption>Officials at work</figcaption>', $clean);
    }

    public function test_plain_text_is_encoded_and_line_breaks_are_preserved(): void
    {
        $clean = $this->sanitizer->sanitizeWithLineBreaks("Line one\nLine <unsafe> & two");

        $this->assertSame('Line one<br />'."\n".'Line &lt;unsafe&gt; &amp; two', $clean);
    }
}
