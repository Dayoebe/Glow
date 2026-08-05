<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career\CareerApplication;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class CareerApplicationResumeController extends Controller
{
    public function preview(CareerApplication $application)
    {
        $path = trim((string) $application->resume_path);
        abort_if($path === '', 404);

        $extension = Str::lower(pathinfo($application->resume_original_name ?: $path, PATHINFO_EXTENSION));
        if ($extension === 'docx' && !Str::startsWith($path, ['http://', 'https://'])) {
            [$disk, $diskPath] = $this->locate($path);
            abort_if(!$disk || !$diskPath, 404);

            return response()->view('admin.careers.resume-docx-preview', [
                'application' => $application,
                'paragraphs' => $this->docxParagraphs(Storage::disk($disk)->path($diskPath)),
            ], 200, [
                'Cache-Control' => 'private, no-store, max-age=0',
                'X-Content-Type-Options' => 'nosniff',
            ]);
        }

        return $this->serve($application, 'inline');
    }

    public function download(CareerApplication $application)
    {
        return $this->serve($application, 'attachment');
    }

    private function serve(CareerApplication $application, string $disposition)
    {
        $path = trim((string) $application->resume_path);
        abort_if($path === '', 404);

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return redirect()->away($path);
        }

        [$disk, $diskPath] = $this->locate($path);
        abort_if(!$disk || !$diskPath, 404);

        $filename = str_replace(["\r", "\n"], '', basename($application->resume_original_name ?: $diskPath));
        $mimeType = Storage::disk($disk)->mimeType($diskPath) ?: 'application/octet-stream';
        $absolutePath = Storage::disk($disk)->path($diskPath);

        $response = response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => $disposition . '; filename="' . addcslashes($filename, '"\\') . '"',
            'X-Content-Type-Options' => 'nosniff',
        ]);

        $response->setPrivate();
        $response->setMaxAge(0);
        $response->headers->addCacheControlDirective('no-store');

        return $response;
    }

    private function locate(string $path): array
    {
        if (Storage::disk('local')->exists($path)) {
            return ['local', $path];
        }

        if (Storage::disk('public')->exists($path)) {
            return ['public', $path];
        }

        $urlPath = ltrim((string) parse_url($path, PHP_URL_PATH), '/');
        if (Str::startsWith($urlPath, 'storage/')) {
            $publicPath = Str::after($urlPath, 'storage/');
            if (Storage::disk('public')->exists($publicPath)) {
                return ['public', $publicPath];
            }
        }

        return [null, null];
    }

    private function docxParagraphs(string $absolutePath): array
    {
        $archive = new ZipArchive();
        abort_unless($archive->open($absolutePath) === true, 422, 'This DOCX file could not be opened.');

        $documentXml = $archive->getFromName('word/document.xml');
        $archive->close();
        abort_if($documentXml === false, 422, 'This DOCX file does not contain a readable document.');
        abort_if(strlen($documentXml) > 5 * 1024 * 1024, 422, 'This DOCX file is too large to preview safely.');

        $document = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $loaded = $document->loadXML($documentXml, LIBXML_NONET | LIBXML_NOERROR | LIBXML_NOWARNING);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        abort_unless($loaded, 422, 'This DOCX file could not be parsed.');

        $xpath = new \DOMXPath($document);
        $xpath->registerNamespace('w', 'http://schemas.openxmlformats.org/wordprocessingml/2006/main');
        $paragraphs = [];

        foreach ($xpath->query('//w:body/w:p') as $paragraph) {
            $parts = [];
            foreach ($xpath->query('.//w:t|.//w:tab|.//w:br', $paragraph) as $node) {
                $parts[] = match ($node->localName) {
                    'tab' => "\t",
                    'br' => "\n",
                    default => $node->textContent,
                };
            }

            $text = trim(implode('', $parts));
            if ($text !== '') {
                $paragraphs[] = $text;
            }
        }

        return $paragraphs;
    }
}
