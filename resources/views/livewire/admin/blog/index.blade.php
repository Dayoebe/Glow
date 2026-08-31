<div class="space-y-6">
    @php $canReview = $this->canReview(); @endphp

    <section class="relative overflow-hidden rounded-2xl bg-[#0b2f3a] px-6 py-7 text-white shadow-sm sm:px-8">
        <div class="pointer-events-none absolute -right-20 -top-24 h-72 w-72 rounded-full bg-violet-400/10"></div>
        <div class="pointer-events-none absolute -bottom-28 right-1/3 h-60 w-60 rounded-full bg-[#ed5a1f]/10"></div>
        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-violet-300">Glow Blog Studio</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Turn ideas into stories worth sharing.</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">Draft, review, publish and measure long-form stories from one focused editorial workspace.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('admin.blog.analytics') }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-extrabold text-white hover:bg-white/15"><i class="fas fa-chart-line mr-2"></i>Analytics</a>
                <a href="{{ route('admin.blog.create') }}" class="inline-flex items-center justify-center rounded-xl bg-[#ed5a1f] px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-black/10 hover:bg-[#d94d16]"><i class="fas fa-pen-nib mr-2"></i>Write a post</a>
            </div>
        </div>
        <div class="relative mt-7 grid grid-cols-2 gap-3 lg:grid-cols-5">
            @foreach([
                ['label' => 'All posts', 'value' => $stats['total'], 'icon' => 'fa-blog'],
                ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'fa-circle-check'],
                ['label' => 'Featured', 'value' => $stats['featured'], 'icon' => 'fa-star'],
                ['label' => 'Awaiting review', 'value' => $stats['pending'], 'icon' => 'fa-hourglass-half'],
                ['label' => 'Drafts', 'value' => $stats['draft'], 'icon' => 'fa-file-pen'],
            ] as $stat)
                <div class="rounded-xl border border-white/10 bg-white/[0.07] p-4">
                    <div class="flex items-center justify-between gap-3"><div><p class="text-2xl font-black">{{ number_format($stat['value']) }}</p><p class="mt-1 text-xs font-semibold text-slate-300">{{ $stat['label'] }}</p></div><i class="fas {{ $stat['icon'] }} text-violet-300"></i></div>
                </div>
            @endforeach
        </div>
    </section>

    <x-admin.blog-workspace-nav />

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_190px_190px_160px]">
            <div class="relative"><i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input type="search" wire:model.live.debounce.300ms="search" placeholder="Search title, excerpt or post content…" class="w-full rounded-xl border-slate-300 py-2.5 pl-10 pr-4 text-sm focus:border-violet-500 focus:ring-violet-500"></div>
            <select wire:model.live="filterCategory" class="rounded-xl border-slate-300 py-2.5 text-sm focus:border-violet-500 focus:ring-violet-500"><option value="">All categories</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach</select>
            <select wire:model.live="filterStatus" class="rounded-xl border-slate-300 py-2.5 text-sm focus:border-violet-500 focus:ring-violet-500"><option value="">All workflows</option><option value="published">Published</option><option value="draft">Draft</option><option value="featured">Featured</option><option value="pending">Pending approval</option><option value="approved">Approved</option><option value="flagged">Flagged</option><option value="rejected">Rejected</option></select>
            <select wire:model.live="sortBy" class="rounded-xl border-slate-300 py-2.5 text-sm focus:border-violet-500 focus:ring-violet-500"><option value="newest">Newest</option><option value="oldest">Oldest</option><option value="title">Title A–Z</option><option value="views">Most viewed</option><option value="featured">Featured first</option></select>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500"><span><strong class="text-slate-800">{{ $posts->total() }}</strong> {{ \Illuminate\Support\Str::plural('post', $posts->total()) }}</span>@if($hasFilters)<button wire:click="clearFilters" class="font-bold text-[#d94d16] hover:text-[#b83c0f]"><i class="fas fa-rotate-left mr-1"></i>Reset filters</button>@endif</div>
    </section>

    <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">
        @if($posts->isEmpty())
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"><span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400"><i class="fas fa-feather-pointed"></i></span><h3 class="mt-4 text-lg font-black text-slate-900">No blog posts found</h3><p class="mt-2 text-sm text-slate-500">Start a new story or reset the current filters.</p><a href="{{ route('admin.blog.create') }}" class="mt-5 inline-flex rounded-xl bg-violet-600 px-4 py-2.5 text-sm font-bold text-white">Write a post</a></div>
        @else
            @foreach($posts as $post)
                @php
                    $canManage = $this->canManagePost($post);
                    $isPublic = $post->is_published && $post->approval_status === 'approved' && (!$post->published_at || $post->published_at->lte(now()));
                    $approvalClass = match($post->approval_status) { 'approved' => 'bg-emerald-50 text-emerald-700', 'flagged' => 'bg-amber-50 text-amber-700', 'rejected' => 'bg-red-50 text-red-700', default => 'bg-slate-100 text-slate-600' };
                @endphp
                <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-violet-200 hover:shadow-md" wire:key="blog-post-{{ $post->id }}">
                    <div class="grid sm:grid-cols-[190px_minmax(0,1fr)]">
                        <div class="relative min-h-52 overflow-hidden bg-[#0b2f3a] sm:min-h-full">
                            @if($post->featured_image)<img src="{{ $post->featured_image }}" alt="" class="absolute inset-0 h-full w-full object-cover">@else<div class="absolute inset-0 flex flex-col items-center justify-center bg-gradient-to-br from-[#0b2f3a] to-violet-900 px-5 text-center text-white"><i class="fas fa-feather-pointed mb-3 text-2xl text-violet-300"></i><span class="text-xs font-black uppercase tracking-widest">Glow Blog</span></div>@endif
                            @if($post->is_featured)<span class="absolute left-3 top-3 rounded-full bg-violet-600 px-2.5 py-1 text-[10px] font-black uppercase text-white shadow"><i class="fas fa-star mr-1"></i>Featured</span>@endif
                        </div>
                        <div class="flex min-w-0 flex-col p-5">
                            <div class="flex flex-wrap items-center gap-2"><span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $post->is_published ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }}">{{ $post->is_published ? 'Published' : 'Draft' }}</span><span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $approvalClass }}">{{ $post->approval_status ?: 'pending' }}</span><span class="text-[11px] font-semibold text-slate-400">{{ $post->category?->name ?? 'Uncategorised' }}</span>@if($post->series)<span class="text-[11px] font-bold text-violet-600"><i class="fas fa-layer-group mr-1"></i>{{ $post->series }}</span>@endif</div>
                            <h3 class="mt-3 line-clamp-2 text-lg font-black leading-6 text-slate-900">{{ $post->title }}</h3>
                            <p class="mt-2 line-clamp-2 text-xs leading-5 text-slate-500">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 140) }}</p>
                            <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-semibold text-slate-500"><span><i class="fas fa-user mr-1 text-slate-400"></i>{{ $post->author?->name ?? 'Unknown' }}</span><span><i class="fas fa-calendar mr-1 text-slate-400"></i>{{ $post->created_at->format('M j, Y') }}</span><span><i class="fas fa-clock mr-1 text-slate-400"></i>{{ $post->read_time ?: 1 }} min</span><span><i class="fas fa-eye mr-1 text-slate-400"></i>{{ number_format($post->views) }}</span><span><i class="fas fa-comment mr-1 text-slate-400"></i>{{ number_format($post->comments_count) }}</span><span><i class="fas fa-share mr-1 text-slate-400"></i>{{ number_format($post->shares) }}</span></div>
                            @if($post->approval_reason)<p class="mt-3 rounded-lg bg-slate-50 px-3 py-2 text-xs text-slate-600"><strong>Review note:</strong> {{ $post->approval_reason }}</p>@endif
                            <div class="mt-auto flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                                <div>@if($canReview)<button wire:click="togglePublish({{ $post->id }})" class="rounded-lg px-2.5 py-2 text-xs font-bold {{ $post->is_published ? 'text-slate-600 hover:bg-slate-100' : 'text-blue-700 hover:bg-blue-50' }}">{{ $post->is_published ? 'Unpublish' : 'Publish' }}</button><button wire:click="toggleFeatured({{ $post->id }})" class="rounded-lg px-2.5 py-2 text-xs font-bold text-violet-700 hover:bg-violet-50">{{ $post->is_featured ? 'Unfeature' : 'Feature' }}</button>@endif</div>
                                <div class="flex items-center gap-1">@if($isPublic)<a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="rounded-lg px-2.5 py-2 text-xs font-bold text-blue-700 hover:bg-blue-50">View</a>@elseif($canManage)<a href="{{ route('admin.blog.preview', $post->slug) }}" class="rounded-lg px-2.5 py-2 text-xs font-bold text-slate-600 hover:bg-slate-100">Preview</a>@endif @if($canManage)<a href="{{ route('admin.blog.edit', $post->id) }}" class="rounded-lg px-2.5 py-2 text-xs font-bold text-violet-700 hover:bg-violet-50">Edit</a><button wire:click="confirmDelete({{ $post->id }})" class="rounded-lg px-2.5 py-2 text-xs font-bold text-red-600 hover:bg-red-50" aria-label="Delete {{ $post->title }}"><i class="fas fa-trash"></i></button>@endif</div>
                            </div>
                            @if($canReview)
                                <div class="mt-3 flex flex-wrap justify-end gap-2 border-t border-slate-100 pt-3" aria-label="Review actions">
                                    @if($post->approval_status !== 'approved')
                                        <button wire:click="startApproval({{ $post->id }}, 'approved')" wire:loading.attr="disabled" class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-700 shadow-sm transition hover:border-emerald-300 hover:bg-emerald-100 disabled:opacity-50"><i class="fas fa-check-circle" aria-hidden="true"></i>Approve</button>
                                    @endif
                                    @if($post->approval_status !== 'flagged')
                                        <button wire:click="startApproval({{ $post->id }}, 'flagged')" wire:loading.attr="disabled" class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-xs font-bold text-amber-700 shadow-sm transition hover:border-amber-300 hover:bg-amber-100 disabled:opacity-50"><i class="fas fa-flag" aria-hidden="true"></i>Flag</button>
                                    @endif
                                    @if($post->approval_status !== 'rejected')
                                        <button wire:click="startApproval({{ $post->id }}, 'rejected')" wire:loading.attr="disabled" class="inline-flex items-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-bold text-red-700 shadow-sm transition hover:border-red-300 hover:bg-red-100 disabled:opacity-50"><i class="fas fa-times-circle" aria-hidden="true"></i>Reject</button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($approvalFormId === $post->id)<div class="border-t border-amber-200 bg-amber-50 p-5"><label class="text-sm font-black text-slate-900">Why is this post being {{ $approvalAction }}?</label><textarea wire:model="approvalReason" rows="3" class="mt-2 w-full rounded-xl border-amber-200 text-sm focus:border-amber-500 focus:ring-amber-500" placeholder="Add a useful note for the writer…"></textarea>@error('approvalReason')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror<div class="mt-3 flex gap-2"><button wire:click="submitApprovalForm" class="rounded-lg bg-[#0b2f3a] px-4 py-2 text-xs font-bold text-white">Submit review</button><button wire:click="cancelApprovalForm" class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-xs font-bold text-slate-700">Cancel</button></div></div>@endif
                </article>
            @endforeach
        @endif
    </section>

    @if($posts->hasPages())<div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">{{ $posts->links() }}</div>@endif

    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true"><div class="flex min-h-screen items-center justify-center px-4 py-8"><button type="button" class="fixed inset-0 bg-slate-950/60 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)" aria-label="Close dialog"></button><div class="relative z-10 w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"><span class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600"><i class="fas fa-trash"></i></span><h3 class="mt-4 text-xl font-black text-slate-900">Delete this blog post?</h3><p class="mt-2 text-sm leading-6 text-slate-500">The post, its comments and engagement history will be permanently removed.</p><div class="mt-6 flex justify-end gap-2"><button wire:click="$set('showDeleteModal', false)" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700">Keep post</button><button wire:click="deletePost" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-700">Delete post</button></div></div></div></div>
    @endif

    @if(session()->has('success'))<div class="flash-auto-dismiss fixed bottom-4 right-4 z-[60] max-w-sm rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-xl"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>@endif
    @if(session()->has('error'))<div class="flash-auto-dismiss fixed bottom-4 right-4 z-[60] max-w-sm rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-xl"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>@endif
</div>
