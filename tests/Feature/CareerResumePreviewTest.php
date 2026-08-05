<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\CareerApplicationResumeController;
use App\Models\Career\CareerApplication;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use ZipArchive;

class CareerResumePreviewTest extends TestCase
{
    public function test_pdf_resumes_are_served_inline(): void
    {
        Storage::fake('local');
        Storage::disk('local')->put('private/careers/resumes/cv.pdf', "%PDF-1.4\n%%EOF");

        $application = new CareerApplication([
            'resume_path' => 'private/careers/resumes/cv.pdf',
            'resume_original_name' => 'candidate-cv.pdf',
        ]);

        $response = app(CareerApplicationResumeController::class)->preview($application);

        $this->assertStringStartsWith('inline;', (string) $response->headers->get('Content-Disposition'));
        $this->assertTrue($response->headers->hasCacheControlDirective('private'));
        $this->assertTrue($response->headers->hasCacheControlDirective('no-store'));
    }

    public function test_docx_resumes_get_an_authenticated_html_preview(): void
    {
        Storage::fake('local');
        $path = Storage::disk('local')->path('private/careers/resumes/cv.docx');
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        $archive = new ZipArchive();
        $this->assertTrue($archive->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE));
        $archive->addFromString('word/document.xml', <<<'XML'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:body><w:p><w:r><w:t>Applicant experience and skills</w:t></w:r></w:p></w:body>
</w:document>
XML);
        $archive->close();

        $application = new CareerApplication([
            'resume_path' => 'private/careers/resumes/cv.docx',
            'resume_original_name' => 'candidate-cv.docx',
        ]);

        $response = app(CareerApplicationResumeController::class)->preview($application);

        $this->assertStringContainsString('Applicant experience and skills', $response->getContent());
        $this->assertStringContainsString('Secure DOCX preview', $response->getContent());
    }
}
