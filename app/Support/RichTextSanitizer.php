<?php

namespace App\Support;

use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

final class RichTextSanitizer
{
    private HtmlSanitizerInterface $sanitizer;

    public function __construct(?HtmlSanitizerInterface $sanitizer = null)
    {
        $this->sanitizer = $sanitizer ?? new HtmlSanitizer($this->configuration());
    }

    public function sanitize(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        // Reserve the document's single H1 for the page title. Editors may paste
        // full documents, so normalize body-level H1 elements before sanitizing.
        $html = preg_replace_callback(
            '/<\s*(\/?)\s*h1\b[^>]*>/i',
            static fn (array $matches): string => $matches[1] === '/' ? '</h2>' : '<h2>',
            $html,
        ) ?? $html;

        return $this->sanitizer->sanitize($html);
    }

    public function sanitizeWithLineBreaks(?string $html): string
    {
        $html = trim((string) $html);

        if ($html === '') {
            return '';
        }

        $allowedTagPattern = implode('|', array_map(
            static fn (string $element): string => preg_quote($element, '/'),
            array_keys($this->elements()),
        ));

        if (! preg_match('/<\/?(?:'.$allowedTagPattern.')(?=[\s>\/])/i', $html)) {
            return nl2br(htmlspecialchars(
                $html,
                ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
                'UTF-8',
            ));
        }

        return $this->sanitize($html);
    }

    private function configuration(): HtmlSanitizerConfig
    {
        $config = (new HtmlSanitizerConfig)
            ->allowLinkSchemes(['http', 'https', 'mailto', 'tel'])
            ->allowRelativeLinks()
            ->allowMediaSchemes(['http', 'https'])
            ->allowRelativeMedias()
            ->withMaxInputLength(2_000_000);

        foreach ($this->elements() as $element => $attributes) {
            $config = $config->allowElement($element, $attributes);
        }

        return $config
            ->forceAttribute('a', 'rel', 'noopener noreferrer')
            ->forceAttribute('img', 'loading', 'lazy')
            ->forceAttribute('audio', 'controls', 'controls')
            ->forceAttribute('video', 'controls', 'controls');
    }

    /**
     * @return array<string, list<string>>
     */
    private function elements(): array
    {
        return [
            'p' => [],
            'br' => [],
            'div' => [],
            'span' => [],
            'h1' => [],
            'h2' => [],
            'h3' => [],
            'h4' => [],
            'h5' => [],
            'h6' => [],
            'strong' => [],
            'b' => [],
            'em' => [],
            'i' => [],
            'u' => [],
            's' => [],
            'strike' => [],
            'mark' => [],
            'small' => [],
            'sub' => [],
            'sup' => [],
            'ul' => [],
            'ol' => ['start', 'reversed'],
            'li' => ['value'],
            'blockquote' => ['cite'],
            'pre' => [],
            'code' => [],
            'hr' => [],
            'figure' => [],
            'figcaption' => [],
            'table' => [],
            'thead' => [],
            'tbody' => [],
            'tfoot' => [],
            'tr' => [],
            'th' => ['colspan', 'rowspan', 'scope'],
            'td' => ['colspan', 'rowspan', 'headers'],
            'details' => ['open'],
            'summary' => [],
            'time' => ['datetime'],
            'a' => ['href', 'title', 'target', 'hreflang'],
            'img' => ['src', 'alt', 'title', 'width', 'height', 'loading'],
            'audio' => ['src', 'controls', 'preload'],
            'video' => ['src', 'poster', 'controls', 'preload', 'width', 'height'],
            'source' => ['src', 'type'],
        ];
    }
}
