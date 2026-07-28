<div>
    @php
        $approvalStatus = $news->approval_status ?: 'pending';
        $approvalClasses = match ($approvalStatus) {
            'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'flagged' => 'bg-amber-100 text-amber-800 border-amber-200',
            'rejected' => 'bg-red-100 text-red-800 border-red-200',
            default => 'bg-gray-100 text-gray-700 border-gray-200',
        };

        $publicationLabel = match (true) {
            !$news->is_published => 'Draft',
            !$news->published_at => 'Publication date not set',
            $news->published_at->isFuture() => 'Scheduled',
            $approvalStatus !== 'approved' => 'Not publicly available',
            default => 'Published',
        };

        $publicationClasses = match ($publicationLabel) {
            'Published' => 'bg-blue-100 text-blue-800 border-blue-200',
            'Scheduled' => 'bg-violet-100 text-violet-800 border-violet-200',
            'Draft' => 'bg-gray-100 text-gray-700 border-gray-200',
            default => 'bg-amber-100 text-amber-800 border-amber-200',
        };
    @endphp

    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <a href="{{ route('admin.news.index') }}"
            class="inline-flex items-center text-sm font-semibold text-gray-600 transition hover:text-emerald-700">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to News Management
        </a>

        <div class="flex flex-wrap gap-2">
            @if($canManage)
                <a href="{{ route('admin.news.edit', $news->id) }}"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-100">
                    <i class="fas fa-edit mr-2"></i>
                    Edit News
                </a>
            @endif

            @if($publiclyVisible)
                <a href="{{ route('news.show', $news->slug) }}" target="_blank" rel="noopener"
                    class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                    <i class="fas fa-external-link-alt mr-2"></i>
                    View Public Article
                </a>
            @endif
        </div>
    </div>

    @if(!$publiclyVisible)
        <div class="mb-6 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-amber-900">
            <i class="fas fa-eye-slash mt-1 text-amber-600"></i>
            <div>
                <p class="font-semibold">This article is not publicly available.</p>
                <p class="mt-1 text-sm text-amber-800">
                    Dashboard users can review it here without being sent to a public 404 page. It will become publicly accessible only after it is approved, published, and its publication time has arrived.
                </p>
            </div>
        </div>
    @endif

    <article class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">
        @if($news->featured_image)
            <img src="{{ $news->featured_image }}" alt="{{ $news->title }}" class="max-h-[32rem] w-full object-cover">
        @endif

        <div class="px-5 py-6 sm:px-8 sm:py-8">
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $approvalClasses }}">
                    <i class="fas fa-shield-alt mr-1.5"></i>
                    Approval: {{ ucfirst($approvalStatus) }}
                </span>
                <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold {{ $publicationClasses }}">
                    <i class="fas fa-newspaper mr-1.5"></i>
                    {{ $publicationLabel }}
                </span>
                @if($news->is_featured)
                    <span class="inline-flex items-center rounded-full border border-purple-200 bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-800">
                        <i class="fas fa-star mr-1.5"></i>
                        Featured
                    </span>
                @endif
            </div>

            <h1 class="mt-5 text-2xl font-bold leading-tight text-gray-950 sm:text-4xl">{{ $news->title }}</h1>

            @if($news->excerpt)
                <p class="mt-4 text-base leading-7 text-gray-600 sm:text-lg">{{ $news->excerpt }}</p>
            @endif

            <dl class="mt-6 grid gap-4 rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Author</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $news->author?->name ?? 'Unknown' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Category</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $news->category?->name ?? 'Uncategorized' }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Created</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $news->created_at->format('M j, Y g:i A') }}</dd>
                </div>
                <div>
                    <dt class="text-xs font-semibold uppercase tracking-wide text-gray-500">Publication time</dt>
                    <dd class="mt-1 font-medium text-gray-900">{{ $news->published_at?->format('M j, Y g:i A') ?? 'Not set' }}</dd>
                </div>
            </dl>

            @if($news->approval_reason)
                <div class="mt-6 rounded-xl border {{ $approvalStatus === 'rejected' ? 'border-red-200 bg-red-50 text-red-900' : 'border-amber-200 bg-amber-50 text-amber-900' }} p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide">Review note</p>
                    <p class="mt-2 text-sm leading-6">{{ $news->approval_reason }}</p>
                    @if($news->reviewed_at)
                        <p class="mt-2 text-xs opacity-75">
                            Reviewed by {{ $news->reviewedBy?->name ?? 'Unknown' }} on {{ $news->reviewed_at->format('M j, Y g:i A') }}
                        </p>
                    @endif
                </div>
            @endif

            <div class="prose prose-lg mt-8 max-w-none text-gray-800">
                {!! $news->content !!}
            </div>

            @if($news->gallery && count($news->gallery) > 0)
                <section class="mt-10 border-t border-gray-200 pt-8">
                    <h2 class="text-lg font-semibold text-gray-900">Gallery</h2>
                    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach($news->gallery as $image)
                            <img src="{{ $image }}" alt="{{ $news->title }} gallery image {{ $loop->iteration }}"
                                class="h-56 w-full rounded-xl object-cover">
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </article>
</div>
