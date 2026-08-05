<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $application->resume_original_name ?: 'CV preview' }}</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #e8edf1; color: #172033; font: 15px/1.65 Arial, sans-serif; }
        .toolbar { position: sticky; top: 0; padding: 10px 18px; background: #0b2f3a; color: #fff; font-size: 12px; font-weight: 700; }
        .paper { width: min(820px, calc(100% - 28px)); min-height: 100vh; margin: 22px auto; padding: 54px 62px; background: #fff; box-shadow: 0 10px 30px rgba(15, 23, 42, .12); }
        p { margin: 0 0 12px; white-space: pre-wrap; }
        .empty { color: #64748b; text-align: center; }
        @media (max-width: 640px) { .paper { margin: 10px auto; padding: 30px 24px; } }
    </style>
</head>
<body>
    <div class="toolbar">Secure DOCX preview · {{ $application->resume_original_name }}</div>
    <main class="paper">
        @forelse($paragraphs as $paragraph)
            <p>{{ $paragraph }}</p>
        @empty
            <p class="empty">No readable text was found in this document. Use the download button to open the original file.</p>
        @endforelse
    </main>
</body>
</html>
