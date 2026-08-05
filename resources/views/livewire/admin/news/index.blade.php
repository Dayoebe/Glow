<div class="space-y-6">
    @php
        $canReview = $this->canReview();
    @endphp

    <section class="relative overflow-hidden rounded-2xl bg-[#0b2f3a] px-6 py-7 text-white shadow-sm sm:px-8">
        <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-emerald-400/10"></div>
        <div class="pointer-events-none absolute -bottom-28 right-1/3 h-60 w-60 rounded-full bg-orange-400/10"></div>
        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-emerald-300">Glow Newsroom</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Shape the stories people see first.</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">Write, review, publish and curate several featured stories without losing sight of approvals or audience response.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('admin.news.analytics') }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-extrabold text-white hover:bg-white/15"><i class="fas fa-chart-line mr-2"></i>Analytics</a>
                <a href="{{ route('admin.news.create') }}" class="inline-flex items-center justify-center rounded-xl bg-[#ed5a1f] px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-black/10 hover:bg-[#d94d16]"><i class="fas fa-plus mr-2"></i>Write article</a>
            </div>
        </div>
        <div class="relative mt-7 grid grid-cols-2 gap-3 lg:grid-cols-5">
            @foreach([
                ['label' => 'All articles', 'value' => $stats['total'], 'icon' => 'fa-newspaper'],
                ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'fa-circle-check'],
                ['label' => 'Featured', 'value' => $stats['featured'], 'icon' => 'fa-star'],
                ['label' => 'Awaiting review', 'value' => $stats['pending'], 'icon' => 'fa-hourglass-half'],
                ['label' => 'Drafts', 'value' => $stats['draft'], 'icon' => 'fa-file-pen'],
            ] as $stat)
                <div class="rounded-xl border border-white/10 bg-white/[0.07] p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-2xl font-black">{{ number_format($stat['value']) }}</p>
                            <p class="mt-1 text-xs font-semibold text-slate-300">{{ $stat['label'] }}</p>
                        </div>
                        <i class="fas {{ $stat['icon'] }} text-emerald-300"></i>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <nav class="overflow-x-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-sm" aria-label="News management sections">
        <div class="flex min-w-max gap-1">
            <a href="{{ route('admin.news.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#0b2f3a] px-4 py-2.5 text-sm font-bold text-white"><i class="fas fa-layer-group text-xs text-emerald-300"></i>Articles</a>
            <a href="{{ route('admin.news.categories') }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100"><i class="fas fa-tags text-xs text-slate-400"></i>Categories</a>
            <a href="{{ route('admin.news.analytics') }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100"><i class="fas fa-chart-column text-xs text-slate-400"></i>Analytics</a>
            <a href="{{ route('news') }}" target="_blank" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100"><i class="fas fa-arrow-up-right-from-square text-xs text-slate-400"></i>Public newsroom</a>
        </div>
    </nav>

    @if($canReview)
        <section class="rounded-2xl border border-violet-200 bg-violet-50 p-4 sm:flex sm:items-center sm:justify-between sm:gap-5">
            <div class="flex gap-3"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-violet-600 text-white"><i class="fas fa-stars"></i></span><div><h3 class="text-sm font-black text-violet-950">Multiple featured stories are enabled</h3><p class="mt-1 text-xs leading-5 text-violet-700">Keep one lead hero and add as many secondary or sidebar stories as needed. Choosing a new hero moves the previous hero out of that slot but keeps it featured.</p></div></div>
            <div class="mt-3 shrink-0 rounded-xl bg-white px-4 py-2 text-xs font-bold text-violet-700 shadow-sm sm:mt-0">{{ $stats['hero'] }} hero · {{ $stats['featured'] }} featured</div>
        </section>
    @endif

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_190px_190px_160px]">
            <div class="relative"><i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input type="search" wire:model.live.debounce.300ms="search" placeholder="Search title, excerpt or story content…" class="w-full rounded-xl border-slate-300 py-2.5 pl-10 pr-4 text-sm focus:border-emerald-500 focus:ring-emerald-500"></div>
            <select wire:model.live="filterCategory" class="rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500">
                <option value="">All categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <select wire:model.live="filterStatus" class="rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="">All workflows</option><option value="published">Published</option><option value="draft">Draft</option><option value="featured">Featured</option><option value="pending">Pending approval</option><option value="approved">Approved</option><option value="flagged">Flagged</option><option value="rejected">Rejected</option></select>
            <select wire:model.live="sortBy" class="rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="newest">Newest</option><option value="oldest">Oldest</option><option value="title">Title A–Z</option><option value="views">Most viewed</option><option value="featured">Featured first</option></select>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500"><span><strong class="text-slate-800">{{ $newsArticles->total() }}</strong> {{ \Illuminate\Support\Str::plural('article', $newsArticles->total()) }}</span>@if($hasFilters)<button wire:click="clearFilters" class="font-bold text-[#d94d16] hover:text-[#b83c0f]"><i class="fas fa-rotate-left mr-1"></i>Reset filters</button>@endif</div>
    </section>

    <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">
        @if($newsArticles->isEmpty())
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"><span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400"><i class="fas fa-newspaper"></i></span><h3 class="mt-4 text-lg font-black text-slate-900">No articles found</h3><p class="mt-2 text-sm text-slate-500">Start a new story or reset the current filters.</p><a href="{{ route('admin.news.create') }}" class="mt-5 inline-flex rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white">Write an article</a></div>
        @else
          @foreach($newsArticles as $article)
            @php
                $canManage = $this->canManageNews($article);
                $isPublic = $article->is_published && $article->approval_status === 'approved' && $article->published_at?->lte(now());
                $approvalClass = match($article->approval_status) { 'approved' => 'bg-emerald-50 text-emerald-700', 'flagged' => 'bg-amber-50 text-amber-700', 'rejected' => 'bg-red-50 text-red-700', default => 'bg-slate-100 text-slate-600' };
            @endphp
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-emerald-200 hover:shadow-md" wire:key="news-{{ $article->id }}">
                <div class="grid sm:grid-cols-[190px_minmax(0,1fr)]">
                    <div class="relative min-h-52 overflow-hidden bg-[#0b2f3a] sm:min-h-full">
                        @if($article->featured_image)<img src="{{ $article->featured_image }}" alt="" class="absolute inset-0 h-full w-full object-cover">@else<div class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-[#0b2f3a] to-emerald-800 px-5 text-center text-white"><i class="fas fa-radio mb-3 text-2xl text-emerald-300"></i><span class="text-xs font-black uppercase tracking-widest">Glow FM News</span></div>@endif
                        <div class="absolute inset-x-0 top-0 flex flex-wrap gap-1.5 p-3">@if($article->is_featured)<span class="rounded-full bg-violet-600 px-2.5 py-1 text-[10px] font-black uppercase text-white shadow"><i class="fas fa-star mr-1"></i>{{ ucfirst($article->featured_position ?: 'featured') }}</span>@endif @if($article->breaking && $article->breaking !== 'no')<span class="rounded-full bg-red-600 px-2.5 py-1 text-[10px] font-black uppercase text-white shadow">Breaking</span>@endif</div>
                    </div>
                    <div class="flex min-w-0 flex-col p-5">
                        <div class="flex flex-wrap items-center gap-2"><span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $article->is_published ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }}">{{ $article->is_published ? 'Published' : 'Draft' }}</span><span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $approvalClass }}">{{ $article->approval_status ?: 'pending' }}</span><span class="text-[11px] font-semibold text-slate-400">{{ $article->category?->name ?? 'Uncategorised' }}</span></div>
                        <h3 class="mt-3 line-clamp-2 text-lg font-black leading-6 text-slate-900">{{ $article->title }}</h3>
                        <p class="mt-2 line-clamp-2 text-xs leading-5 text-slate-500">{{ $article->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($article->content), 140) }}</p>
                        <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-semibold text-slate-500"><span><i class="fas fa-user mr-1 text-slate-400"></i>{{ $article->author?->name ?? 'Unknown' }}</span><span><i class="fas fa-calendar mr-1 text-slate-400"></i>{{ $article->created_at->format('M j, Y') }}</span><span><i class="fas fa-eye mr-1 text-slate-400"></i>{{ number_format($article->views) }}</span><span><i class="fas fa-comment mr-1 text-slate-400"></i>{{ number_format($article->comments_count) }}</span><span><i class="fas fa-face-smile mr-1 text-slate-400"></i>{{ number_format($article->reactions_count) }}</span></div>
                        @if($article->approval_reason)<p class="mt-3 rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-600"><strong>Review note:</strong> {{ $article->approval_reason }}</p>@endif
                        <div class="mt-auto flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                            <div class="flex flex-wrap gap-1">@if($canReview)<button wire:click="togglePublish({{ $article->id }})" class="rounded-lg px-2.5 py-2 text-xs font-bold {{ $article->is_published ? 'text-slate-600 hover:bg-slate-100' : 'text-blue-700 hover:bg-blue-50' }}">{{ $article->is_published ? 'Unpublish' : 'Publish' }}</button><select wire:change="setFeaturedPlacement({{ $article->id }}, $event.target.value)" class="rounded-lg border-slate-200 py-1.5 text-xs font-bold text-violet-700" title="Featured placement"><option value="none" @selected(!$article->is_featured)>Not featured</option><option value="hero" @selected($article->featured_position === 'hero')>Lead hero</option><option value="secondary" @selected($article->featured_position === 'secondary')>Secondary</option><option value="sidebar" @selected($article->featured_position === 'sidebar')>Sidebar</option></select>@endif</div>
                            <div class="flex items-center gap-1">@if($isPublic)<a href="{{ route('news.show', $article->slug) }}" target="_blank" class="rounded-lg px-2.5 py-2 text-xs font-bold text-blue-700 hover:bg-blue-50">View</a>@elseif($canManage)<a href="{{ route('admin.news.show', $article->id) }}" class="rounded-lg px-2.5 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100">Preview</a>@endif @if($canManage)<a href="{{ route('admin.news.edit', $article->id) }}" class="rounded-lg px-2.5 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-50">Edit</a><button wire:click="deleteNews({{ $article->id }})" wire:confirm="Delete this article and all of its comments and interactions?" class="rounded-lg px-2.5 py-2 text-xs font-bold text-red-600 hover:bg-red-50"><i class="fas fa-trash"></i></button>@endif</div>
                        </div>
                        @if($canReview)<div class="mt-2 flex justify-end gap-1"><button wire:click="startApproval({{ $article->id }}, 'approved')" class="rounded-lg px-2 py-1.5 text-[11px] font-bold text-emerald-700 hover:bg-emerald-50">Approve</button><button wire:click="startApproval({{ $article->id }}, 'flagged')" class="rounded-lg px-2 py-1.5 text-[11px] font-bold text-amber-700 hover:bg-amber-50">Flag</button><button wire:click="startApproval({{ $article->id }}, 'rejected')" class="rounded-lg px-2 py-1.5 text-[11px] font-bold text-red-700 hover:bg-red-50">Reject</button></div>@endif
                    </div>
                </div>
                @if($approvalFormId === $article->id)<div class="border-t border-amber-200 bg-amber-50 p-5"><label class="text-sm font-black text-slate-900">Why is this article being {{ $approvalAction }}?</label><textarea wire:model="approvalReason" rows="3" class="mt-2 w-full rounded-xl border-amber-200 text-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Add a useful note for the writer…"></textarea>@error('approvalReason')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror<div class="mt-3 flex gap-2"><button wire:click="submitApprovalForm" class="rounded-lg bg-[#0b2f3a] px-4 py-2 text-xs font-bold text-white">Submit review</button><button wire:click="cancelApprovalForm" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700">Cancel</button></div></div>@endif
            </article>
          @endforeach
        @endif
    </section>

    @if($newsArticles->hasPages())<div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">{{ $newsArticles->links() }}</div>@endif
    @if(session()->has('success'))<div class="flash-auto-dismiss fixed bottom-4 right-4 z-[60] max-w-sm rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-xl"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>@endif
    @if(session()->has('error'))<div class="flash-auto-dismiss fixed bottom-4 right-4 z-[60] max-w-sm rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-xl"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>@endif
</div>
