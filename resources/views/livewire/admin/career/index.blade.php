<div class="space-y-6">
    @php
        $statusStyles = ['open' => 'bg-emerald-50 text-emerald-700', 'paused' => 'bg-amber-50 text-amber-700', 'closed' => 'bg-red-50 text-red-700'];
    @endphp

    <section class="relative overflow-hidden rounded-2xl bg-[#0b2f3a] px-6 py-7 text-white shadow-sm sm:px-8">
        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-emerald-400/10"></div>
        <div class="pointer-events-none absolute -bottom-28 right-28 h-60 w-60 rounded-full bg-orange-400/10"></div>
        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-emerald-300">Opportunities</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Create roles that attract exceptional people.</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">Publish openings, manage deadlines and follow each role’s applicant pipeline from one clear workspace.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                <a href="{{ route('admin.careers.applications') }}" class="inline-flex items-center justify-center rounded-xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-extrabold text-white hover:bg-white/15"><i class="fas fa-users mr-2"></i>Review applicants</a>
                <a href="{{ route('admin.careers.create') }}" class="inline-flex items-center justify-center rounded-xl bg-[#ed5a1f] px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-black/10 hover:bg-[#d94d16]"><i class="fas fa-plus mr-2"></i>Create role</a>
            </div>
        </div>
        <div class="relative mt-7 grid grid-cols-2 gap-3 lg:grid-cols-5">
            @foreach([
                ['label' => 'All roles', 'value' => $stats['total'], 'icon' => 'fa-briefcase'],
                ['label' => 'Published', 'value' => $stats['published'], 'icon' => 'fa-globe'],
                ['label' => 'Accepting now', 'value' => $stats['accepting'], 'icon' => 'fa-door-open'],
                ['label' => 'Drafts', 'value' => $stats['draft'], 'icon' => 'fa-file-pen'],
                ['label' => 'Applications', 'value' => $stats['applications'], 'icon' => 'fa-user-group'],
            ] as $stat)
                <div class="rounded-xl border border-white/10 bg-white/[0.07] p-4"><div class="flex items-center justify-between gap-3"><div><p class="text-2xl font-black">{{ number_format($stat['value']) }}</p><p class="mt-1 text-xs font-semibold text-slate-300">{{ $stat['label'] }}</p></div><i class="fas {{ $stat['icon'] }} text-emerald-300"></i></div></div>
            @endforeach
        </div>
    </section>

    <nav class="overflow-x-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-sm" aria-label="Career management sections">
        <div class="flex min-w-max gap-1">
            <a href="{{ route('admin.careers.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-[#0b2f3a] px-4 py-2.5 text-sm font-bold text-white shadow-sm"><i class="fas fa-briefcase text-xs text-emerald-300"></i>Open roles</a>
            <a href="{{ route('admin.careers.applications') }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100 hover:text-slate-900"><i class="fas fa-users text-xs text-slate-400"></i>All applicants</a>
            <a href="{{ route('admin.careers.applications.type', 'job') }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100"><i class="fas fa-user-tie text-xs text-slate-400"></i>Job applicants</a>
            <a href="{{ route('admin.careers.applications.type', 'internship') }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100"><i class="fas fa-graduation-cap text-xs text-slate-400"></i>Interns</a>
            <a href="{{ route('admin.careers.applications.type', 'volunteer') }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 hover:bg-slate-100"><i class="fas fa-hand-holding-heart text-xs text-slate-400"></i>Volunteers</a>
        </div>
    </nav>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_190px_170px_170px_150px]">
            <div class="relative"><i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input type="search" wire:model.live.debounce.300ms="search" placeholder="Search title, department or location…" class="w-full rounded-xl border-slate-300 py-2.5 pl-10 pr-4 text-sm focus:border-emerald-500 focus:ring-emerald-500"></div>
            <select wire:model.live="filterDepartment" class="rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="">All departments</option>@foreach($departmentOptions as $department)<option value="{{ $department }}">{{ $department }}</option>@endforeach</select>
            <select wire:model.live="filterType" class="rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="">All role types</option>@foreach($typeOptions as $type)<option value="{{ $type }}">{{ \Illuminate\Support\Str::headline($type) }}</option>@endforeach</select>
            <select wire:model.live="filterStatus" class="rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="">All statuses</option><option value="published">Published</option><option value="draft">Draft</option><option value="open">Open</option><option value="paused">Paused</option><option value="closed">Closed</option><option value="featured">Featured</option></select>
            <select wire:model.live="sortBy" class="rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="newest">Newest</option><option value="oldest">Oldest</option><option value="title">Title A–Z</option><option value="deadline">Deadline</option><option value="applications">Applications</option></select>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500"><span><strong class="text-slate-800">{{ $positions->total() }}</strong> {{ \Illuminate\Support\Str::plural('role', $positions->total()) }}</span>@if($hasFilters)<button wire:click="clearFilters" class="font-bold text-[#d94d16] hover:text-[#b83c0f]"><i class="fas fa-rotate-left mr-1"></i>Reset filters</button>@endif</div>
    </section>

    <section class="grid grid-cols-1 gap-5 xl:grid-cols-2">
        @forelse($positions as $position)
            @php
                $deadlinePassed = $position->application_deadline?->lt(now()->startOfDay()) ?? false;
                $accepting = $position->is_published && $position->isAcceptingApplications();
            @endphp
            <article class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:border-emerald-200 hover:shadow-md" wire:key="position-{{ $position->id }}">
                <div class="h-1 bg-gradient-to-r from-emerald-500 via-[#0b2f3a] to-[#ed5a1f]"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0"><div class="flex flex-wrap items-center gap-2"><span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $statusStyles[$position->status] ?? 'bg-slate-100 text-slate-600' }}">{{ $position->status }}</span><span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $position->is_published ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-600' }}">{{ $position->is_published ? 'Published' : 'Draft' }}</span>@if($position->is_featured)<span class="rounded-full bg-violet-50 px-2.5 py-1 text-[10px] font-extrabold uppercase text-violet-700"><i class="fas fa-star mr-1"></i>Featured</span>@endif</div><h3 class="mt-3 text-xl font-black text-slate-900">{{ $position->title }}</h3><p class="mt-1 text-sm font-semibold text-emerald-700">{{ $position->department ?: 'General' }}</p></div>
                        <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl {{ $accepting ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }}"><i class="fas {{ $accepting ? 'fa-door-open' : 'fa-door-closed' }} text-lg"></i></span>
                    </div>

                    <p class="mt-4 line-clamp-2 min-h-[40px] text-sm leading-5 text-slate-500">{{ $position->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($position->description), 180) }}</p>

                    <div class="mt-5 grid grid-cols-2 gap-3 sm:grid-cols-4">
                        @foreach([['Type', \Illuminate\Support\Str::headline($position->employment_type), 'fa-clock'], ['Workplace', \Illuminate\Support\Str::headline($position->workplace_type), 'fa-building'], ['Location', $position->location_label, 'fa-location-dot'], ['Openings', $position->positions_available, 'fa-people-group']] as [$label, $value, $icon])
                            <div class="rounded-xl bg-slate-50 p-3"><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400"><i class="fas {{ $icon }} mr-1"></i>{{ $label }}</p><p class="mt-1 truncate text-xs font-extrabold text-slate-700" title="{{ $value }}">{{ $value }}</p></div>
                        @endforeach
                    </div>

                    <div class="mt-5 grid grid-cols-3 divide-x divide-slate-200 rounded-xl border border-slate-100 py-3 text-center">
                        <div><p class="text-lg font-black text-slate-900">{{ $position->applications_count }}</p><p class="text-[10px] text-slate-400">Applicants</p></div><div><p class="text-lg font-black text-emerald-700">{{ $position->new_applications_count }}</p><p class="text-[10px] text-slate-400">New</p></div><div><p class="text-lg font-black text-amber-700">{{ $position->shortlisted_applications_count }}</p><p class="text-[10px] text-slate-400">Shortlisted</p></div>
                    </div>

                    <div class="mt-4 flex flex-col gap-2 rounded-xl px-3 py-2.5 text-xs font-semibold {{ $deadlinePassed ? 'bg-red-50 text-red-700' : ($position->application_deadline ? 'bg-blue-50 text-blue-700' : 'bg-slate-50 text-slate-600') }} sm:flex-row sm:items-center sm:justify-between"><span><i class="fas fa-calendar-day mr-1.5"></i>{{ $position->application_deadline ? ($deadlinePassed ? 'Deadline passed '.$position->application_deadline->diffForHumans() : 'Deadline '.$position->application_deadline->format('M j, Y')) : 'No application deadline' }}</span><span>{{ $accepting ? 'Accepting applications' : 'Not accepting applications' }}</span></div>

                    <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                        <div class="flex flex-wrap gap-1"><button wire:click="togglePublish({{ $position->id }})" wire:confirm="{{ $position->is_published ? 'Move this role back to draft?' : 'Publish this role publicly?' }}" class="rounded-lg px-3 py-2 text-xs font-bold {{ $position->is_published ? 'text-slate-600 hover:bg-slate-100' : 'text-blue-700 hover:bg-blue-50' }}">{{ $position->is_published ? 'Unpublish' : 'Publish' }}</button><button wire:click="toggleFeatured({{ $position->id }})" class="rounded-lg px-3 py-2 text-xs font-bold text-violet-700 hover:bg-violet-50">{{ $position->is_featured ? 'Unfeature' : 'Feature' }}</button><select wire:change="setStatus({{ $position->id }}, $event.target.value)" class="rounded-lg border-slate-200 py-1.5 text-xs font-bold text-slate-700"><option value="open" @selected($position->status === 'open')>Open</option><option value="paused" @selected($position->status === 'paused')>Paused</option><option value="closed" @selected($position->status === 'closed')>Closed</option></select></div>
                        <div class="flex items-center gap-1">@if($accepting)<a href="{{ route('careers.show', $position->slug) }}" target="_blank" class="rounded-lg px-3 py-2 text-xs font-bold text-blue-700 hover:bg-blue-50"><i class="fas fa-arrow-up-right-from-square mr-1"></i>View</a>@endif<a href="{{ route('admin.careers.edit', $position->id) }}" class="rounded-lg px-3 py-2 text-xs font-bold text-emerald-700 hover:bg-emerald-50">Edit</a>@if($position->applications_count === 0)<button wire:click="confirmDelete({{ $position->id }})" class="rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50" title="Delete role"><i class="fas fa-trash"></i></button>@else<span class="cursor-not-allowed rounded-lg px-3 py-2 text-xs font-bold text-slate-300" title="Close or pause this role to preserve its applicant history"><i class="fas fa-trash"></i></span>@endif</div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"><span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400"><i class="fas fa-briefcase"></i></span><h3 class="mt-4 text-lg font-black text-slate-900">No roles found</h3><p class="mt-2 text-sm text-slate-500">Create a new opportunity or reset the filters.</p><a href="{{ route('admin.careers.create') }}" class="mt-5 inline-flex rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-emerald-700">Create a role</a></div>
        @endforelse
    </section>

    @if($positions->hasPages())<div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">{{ $positions->links() }}</div>@endif

    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true"><div class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div><div class="relative z-10 mx-auto my-20 w-[calc(100%-2rem)] max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl"><div class="p-6"><span class="flex h-12 w-12 items-center justify-center rounded-xl bg-red-50 text-red-600"><i class="fas fa-trash"></i></span><h3 class="mt-4 text-xl font-black text-slate-900">Delete empty role?</h3><p class="mt-2 text-sm leading-6 text-slate-500">This role has no applicant history. Deleting it cannot be undone.</p></div><div class="flex justify-end gap-2 border-t border-slate-100 bg-slate-50 px-6 py-4"><button wire:click="$set('showDeleteModal', false)" class="rounded-xl border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700">Cancel</button><button wire:click="deletePosition" class="rounded-xl bg-red-600 px-4 py-2.5 text-sm font-bold text-white hover:bg-red-700">Delete role</button></div></div></div>
    @endif

    @if(session()->has('success'))<div class="flash-auto-dismiss fixed bottom-4 right-4 z-[60] max-w-sm rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-xl"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>@endif
    @if(session()->has('error'))<div class="flash-auto-dismiss fixed bottom-4 right-4 z-[60] max-w-sm rounded-xl bg-red-600 px-5 py-3 text-sm font-semibold text-white shadow-xl"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</div>@endif
</div>
