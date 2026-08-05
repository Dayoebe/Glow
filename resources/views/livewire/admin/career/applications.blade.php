<div class="space-y-6">
    @php
        $typeLabels = ['' => 'All applicants', 'job' => 'Job applicants', 'internship' => 'Interns', 'volunteer' => 'Volunteers'];
        $statusStyles = [
            'new' => 'bg-emerald-50 text-emerald-700',
            'reviewing' => 'bg-blue-50 text-blue-700',
            'shortlisted' => 'bg-amber-50 text-amber-700',
            'rejected' => 'bg-red-50 text-red-700',
            'hired' => 'bg-violet-50 text-violet-700',
            'archived' => 'bg-slate-100 text-slate-600',
        ];
        $typeStyles = ['job' => 'bg-blue-50 text-blue-700', 'internship' => 'bg-violet-50 text-violet-700', 'volunteer' => 'bg-orange-50 text-orange-700'];
    @endphp

    <section class="relative overflow-hidden rounded-2xl bg-[#0b2f3a] px-6 py-7 text-white shadow-sm sm:px-8">
        <div class="pointer-events-none absolute -right-16 -top-20 h-64 w-64 rounded-full bg-emerald-400/10"></div>
        <div class="pointer-events-none absolute -bottom-28 right-28 h-60 w-60 rounded-full bg-orange-400/10"></div>
        <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-emerald-300">Talent pipeline</p>
                <h2 class="mt-2 text-3xl font-black tracking-tight sm:text-4xl">Meet the people who want to build Glow.</h2>
                <p class="mt-3 max-w-xl text-sm leading-6 text-slate-300">Review complete applications, preview CVs securely and move candidates through a clear hiring workflow.</p>
            </div>
            <a href="{{ route('admin.careers.index') }}" class="inline-flex w-full items-center justify-center rounded-xl border border-white/15 bg-white/10 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-white/15 sm:w-auto"><i class="fas fa-briefcase mr-2"></i>Manage open roles</a>
        </div>
        <div class="relative mt-7 grid grid-cols-2 gap-3 lg:grid-cols-5">
            @foreach([
                ['label' => 'Applications', 'value' => $stats['total'], 'icon' => 'fa-users'],
                ['label' => 'New', 'value' => $stats['new'], 'icon' => 'fa-sparkles'],
                ['label' => 'In review', 'value' => $stats['reviewing'], 'icon' => 'fa-magnifying-glass'],
                ['label' => 'Shortlisted', 'value' => $stats['shortlisted'], 'icon' => 'fa-star'],
                ['label' => 'Hired', 'value' => $stats['hired'], 'icon' => 'fa-user-check'],
            ] as $stat)
                <div class="rounded-xl border border-white/10 bg-white/[0.07] p-4"><div class="flex items-center justify-between gap-3"><div><p class="text-2xl font-black">{{ number_format($stat['value']) }}</p><p class="mt-1 text-xs font-semibold text-slate-300">{{ $stat['label'] }}</p></div><i class="fas {{ $stat['icon'] }} text-emerald-300"></i></div></div>
            @endforeach
        </div>
    </section>

    <nav class="overflow-x-auto rounded-2xl border border-slate-200 bg-white p-2 shadow-sm" aria-label="Application types">
        <div class="flex min-w-max gap-1">
            @foreach($typeLabels as $type => $label)
                <a href="{{ $type === '' ? route('admin.careers.applications') : route('admin.careers.applications.type', $type) }}" class="inline-flex items-center gap-2 rounded-xl px-4 py-2.5 text-sm font-bold transition {{ $applicationType === $type ? 'bg-[#0b2f3a] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                    <i class="fas {{ $type === 'job' ? 'fa-briefcase' : ($type === 'internship' ? 'fa-graduation-cap' : ($type === 'volunteer' ? 'fa-hand-holding-heart' : 'fa-users')) }} text-xs {{ $applicationType === $type ? 'text-emerald-300' : 'text-slate-400' }}"></i>{{ $label }}
                </a>
            @endforeach
        </div>
    </nav>

    <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_210px_170px_150px]">
            <div class="relative"><i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i><input type="search" wire:model.live.debounce.300ms="search" placeholder="Search name, email, code, skills or location…" class="w-full rounded-xl border-slate-300 py-2.5 pl-10 pr-4 text-sm focus:border-emerald-500 focus:ring-emerald-500"></div>
            <select wire:model.live="filterPosition" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="">All positions</option>@foreach($positions as $position)<option value="{{ $position->id }}">{{ $position->title }}</option>@endforeach</select>
            <select wire:model.live="filterStatus" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="">All statuses</option>@foreach(['new', 'reviewing', 'shortlisted', 'rejected', 'hired', 'archived'] as $status)<option value="{{ $status }}">{{ \Illuminate\Support\Str::headline($status) }}</option>@endforeach</select>
            <select wire:model.live="sortBy" class="w-full rounded-xl border-slate-300 py-2.5 text-sm focus:border-emerald-500 focus:ring-emerald-500"><option value="newest">Newest</option><option value="oldest">Oldest</option><option value="name">Name A–Z</option><option value="status">Status</option></select>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4 text-xs text-slate-500"><span><strong class="text-slate-800">{{ $applications->total() }}</strong> {{ \Illuminate\Support\Str::plural('applicant', $applications->total()) }}</span>@if($hasFilters)<button wire:click="clearFilters" class="font-bold text-[#d94d16] hover:text-[#b83c0f]"><i class="fas fa-rotate-left mr-1"></i>Reset filters</button>@endif</div>
    </section>

    <section class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        @forelse($applications as $application)
            <article class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:border-emerald-200 hover:shadow-md" wire:key="application-{{ $application->id }}">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[#0b2f3a] text-base font-black text-white">{{ \Illuminate\Support\Str::of($application->full_name)->explode(' ')->filter()->take(2)->map(fn ($part) => \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($part, 0, 1)))->implode('') }}</div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-start justify-between gap-2"><div class="min-w-0"><h3 class="truncate text-lg font-black text-slate-900">{{ $application->full_name }}</h3><p class="mt-0.5 truncate text-xs text-slate-500">{{ $application->email }}{{ $application->phone ? ' · '.$application->phone : '' }}</p></div><span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $statusStyles[$application->status] ?? 'bg-slate-100 text-slate-600' }}">{{ \Illuminate\Support\Str::headline($application->status) }}</span></div>
                        <div class="mt-3 flex flex-wrap gap-2"><span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $typeStyles[$application->application_type] ?? 'bg-slate-100 text-slate-600' }}">{{ $application->application_type }}</span>@if($application->department)<span class="rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-bold text-emerald-700">{{ $application->department }}</span>@endif</div>
                    </div>
                </div>
                <div class="mt-5 rounded-xl bg-slate-50 p-4">
                    <p class="text-sm font-extrabold text-slate-900">{{ $application->position?->title ?: \Illuminate\Support\Str::headline($application->application_type).' programme' }}</p>
                    <div class="mt-2 flex flex-wrap gap-x-5 gap-y-1 text-xs text-slate-500">@if($application->location)<span><i class="fas fa-location-dot mr-1.5 text-slate-400"></i>{{ $application->location }}</span>@endif @if($application->years_experience !== null)<span><i class="fas fa-briefcase mr-1.5 text-slate-400"></i>{{ $application->years_experience }} years experience</span>@endif @if($application->available_from)<span><i class="fas fa-calendar mr-1.5 text-slate-400"></i>Available {{ $application->available_from->format('M j, Y') }}</span>@endif</div>
                </div>
                @if($application->skills)<p class="mt-4 line-clamp-2 text-sm leading-5 text-slate-500"><span class="font-bold text-slate-700">Skills:</span> {{ $application->skills }}</p>@endif
                <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                    <div><p class="text-[10px] font-bold uppercase tracking-wide text-slate-400">Applied {{ $application->created_at->diffForHumans() }}</p><p class="mt-1 text-xs font-semibold text-slate-600">{{ $application->application_code }}</p></div>
                    <div class="flex items-center gap-1"><button wire:click="openApplication({{ $application->id }})" class="rounded-lg bg-[#0b2f3a] px-3.5 py-2 text-xs font-extrabold text-white hover:bg-[#123f4d]"><i class="fas fa-folder-open mr-1.5"></i>Review application</button><button wire:click="deleteApplication({{ $application->id }})" wire:confirm="Permanently delete {{ $application->full_name }}’s application and CV?" class="rounded-lg px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50"><i class="fas fa-trash"></i></button></div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"><span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-xl text-slate-400"><i class="fas fa-user-group"></i></span><h3 class="mt-4 text-lg font-black text-slate-900">No applications found</h3><p class="mt-2 text-sm text-slate-500">Try a different application type or reset your filters.</p></div>
        @endforelse
    </section>

    @if($applications->hasPages())<div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">{{ $applications->links() }}</div>@endif

    @if($showApplicationModal && $selectedApplication)
        @php
            $resumeName = $selectedApplication->resume_original_name ?: basename((string) $selectedApplication->resume_path);
            $resumeExtension = \Illuminate\Support\Str::lower(pathinfo($resumeName, PATHINFO_EXTENSION));
            $resumePreviewable = in_array($resumeExtension, ['pdf', 'docx'], true);
        @endphp
        <div class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true" aria-labelledby="application-title">
            <div class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm" wire:click="closeApplication"></div>
            <div class="relative z-10 mx-auto my-4 w-[calc(100%-1.5rem)] max-w-[1500px] overflow-hidden rounded-2xl bg-slate-100 shadow-2xl sm:my-8">
                <header class="flex flex-col gap-4 bg-[#0b2f3a] px-5 py-5 text-white sm:flex-row sm:items-center sm:justify-between sm:px-7">
                    <div class="min-w-0"><div class="flex flex-wrap items-center gap-2"><h2 id="application-title" class="truncate text-2xl font-black">{{ $selectedApplication->full_name }}</h2><span class="rounded-full px-2.5 py-1 text-[10px] font-extrabold uppercase {{ $typeStyles[$selectedApplication->application_type] ?? 'bg-white/10 text-white' }}">{{ $selectedApplication->application_type }}</span></div><p class="mt-1 text-sm text-slate-300">{{ $selectedApplication->position?->title ?: \Illuminate\Support\Str::headline($selectedApplication->application_type).' application' }} · {{ $selectedApplication->application_code }}</p></div>
                    <div class="flex items-center gap-3"><select wire:change="setStatus({{ $selectedApplication->id }}, $event.target.value)" class="rounded-xl border-white/15 bg-white/10 py-2 text-sm font-bold text-white focus:border-emerald-400 focus:ring-emerald-400">@foreach(['new', 'reviewing', 'shortlisted', 'rejected', 'hired', 'archived'] as $status)<option class="text-slate-900" value="{{ $status }}" @selected($selectedApplication->status === $status)>{{ \Illuminate\Support\Str::headline($status) }}</option>@endforeach</select><button wire:click="closeApplication" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 text-white hover:bg-white/20" aria-label="Close application"><i class="fas fa-xmark"></i></button></div>
                </header>

                <div class="grid max-h-[calc(100vh-9rem)] overflow-y-auto xl:grid-cols-[minmax(0,1fr)_minmax(440px,0.75fr)]">
                    <main class="space-y-5 p-4 sm:p-6">
                        <section class="rounded-2xl bg-white p-5 shadow-sm"><h3 class="text-sm font-black uppercase tracking-wide text-slate-900"><i class="fas fa-address-card mr-2 text-emerald-600"></i>Contact & identity</h3><dl class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">@foreach([['Email', $selectedApplication->email], ['Phone', $selectedApplication->phone], ['Location', $selectedApplication->location], ['Department', $selectedApplication->department], ['Applied', $selectedApplication->created_at?->format('M j, Y · g:i A')], ['Consent', $selectedApplication->consent ? 'Provided' : 'Not recorded']] as [$label, $value])<div><dt class="text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ $label }}</dt><dd class="mt-1 break-words text-sm font-semibold text-slate-700">{{ $value ?: 'Not provided' }}</dd></div>@endforeach</dl>@if($selectedApplication->linkedin_url || $selectedApplication->portfolio_url)<div class="mt-4 flex flex-wrap gap-3 border-t border-slate-100 pt-4">@if($selectedApplication->linkedin_url)<a href="{{ $selectedApplication->linkedin_url }}" target="_blank" rel="noopener" class="text-sm font-bold text-blue-700"><i class="fab fa-linkedin mr-1.5"></i>LinkedIn</a>@endif @if($selectedApplication->portfolio_url)<a href="{{ $selectedApplication->portfolio_url }}" target="_blank" rel="noopener" class="text-sm font-bold text-emerald-700"><i class="fas fa-arrow-up-right-from-square mr-1.5"></i>Portfolio</a>@endif</div>@endif</section>

                        <section class="rounded-2xl bg-white p-5 shadow-sm"><h3 class="text-sm font-black uppercase tracking-wide text-slate-900"><i class="fas fa-graduation-cap mr-2 text-violet-600"></i>Education & experience</h3><dl class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">@foreach([['Education level', $selectedApplication->education_level], ['Institution', $selectedApplication->institution], ['Course of study', $selectedApplication->course_of_study], ['Experience', $selectedApplication->years_experience !== null ? $selectedApplication->years_experience.' years' : null], ['Current company', $selectedApplication->current_company], ['Current role', $selectedApplication->current_role], ['Expected salary', $selectedApplication->expected_salary ? '₦'.number_format((float) $selectedApplication->expected_salary, 2) : null], ['Available from', $selectedApplication->available_from?->format('M j, Y')], ['Availability', $selectedApplication->availability], ['Commitment', $selectedApplication->commitment_length]] as [$label, $value])<div><dt class="text-[10px] font-bold uppercase tracking-wide text-slate-400">{{ $label }}</dt><dd class="mt-1 text-sm font-semibold text-slate-700">{{ $value ?: 'Not provided' }}</dd></div>@endforeach</dl></section>

                        @foreach([['Skills', $selectedApplication->skills, 'fa-screwdriver-wrench', 'text-blue-600'], ['Cover letter', $selectedApplication->cover_letter, 'fa-envelope-open-text', 'text-orange-600'], ['Motivation', $selectedApplication->motivation, 'fa-lightbulb', 'text-amber-600'], ['What they offer', $selectedApplication->contribution, 'fa-hand-sparkles', 'text-emerald-600']] as [$heading, $content, $icon, $color])
                            @if($content)<section class="rounded-2xl bg-white p-5 shadow-sm"><h3 class="text-sm font-black uppercase tracking-wide text-slate-900"><i class="fas {{ $icon }} mr-2 {{ $color }}"></i>{{ $heading }}</h3><div class="mt-4 whitespace-pre-line text-sm leading-7 text-slate-600">{{ $content }}</div></section>@endif
                        @endforeach

                        <section class="rounded-2xl bg-white p-5 shadow-sm"><h3 class="text-sm font-black uppercase tracking-wide text-slate-900"><i class="fas fa-shield-halved mr-2 text-slate-500"></i>Review history</h3><dl class="mt-4 grid gap-4 sm:grid-cols-3"><div><dt class="text-[10px] font-bold uppercase text-slate-400">Status</dt><dd class="mt-1 text-sm font-semibold text-slate-700">{{ \Illuminate\Support\Str::headline($selectedApplication->status) }}</dd></div><div><dt class="text-[10px] font-bold uppercase text-slate-400">Last reviewed by</dt><dd class="mt-1 text-sm font-semibold text-slate-700">{{ $selectedApplication->reviewedBy?->name ?? 'Not reviewed' }}</dd></div><div><dt class="text-[10px] font-bold uppercase text-slate-400">Reviewed at</dt><dd class="mt-1 text-sm font-semibold text-slate-700">{{ $selectedApplication->reviewed_at?->format('M j, Y · g:i A') ?? '—' }}</dd></div></dl></section>
                    </main>

                    <aside class="space-y-5 border-t border-slate-200 bg-slate-200/60 p-4 sm:p-6 xl:sticky xl:top-0 xl:border-l xl:border-t-0">
                        <section class="overflow-hidden rounded-2xl bg-white shadow-sm">
                            <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-4 py-3"><div class="min-w-0"><p class="text-xs font-black uppercase tracking-wide text-slate-900">CV preview</p><p class="mt-0.5 truncate text-[10px] text-slate-500">{{ $resumeName }}</p></div><a href="{{ route('admin.careers.applications.resume', $selectedApplication) }}" class="shrink-0 rounded-lg bg-slate-100 px-3 py-2 text-xs font-bold text-slate-700 hover:bg-slate-200"><i class="fas fa-download mr-1.5"></i>Download</a></div>
                            @if($resumePreviewable)
                                <iframe src="{{ route('admin.careers.applications.resume.preview', $selectedApplication) }}" title="CV for {{ $selectedApplication->full_name }}" class="h-[620px] w-full bg-slate-100"></iframe>
                            @else
                                <div class="flex h-80 flex-col items-center justify-center p-8 text-center"><span class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-2xl text-blue-600"><i class="fas fa-file-word"></i></span><h4 class="mt-4 font-black text-slate-900">Legacy {{ strtoupper($resumeExtension ?: 'document') }} file</h4><p class="mt-2 max-w-xs text-sm leading-6 text-slate-500">This older format cannot be rendered safely inside the browser. Open the original file with the download button.</p></div>
                            @endif
                        </section>

                        <section class="rounded-2xl bg-white p-5 shadow-sm"><div class="flex items-center justify-between"><h3 class="text-sm font-black uppercase tracking-wide text-slate-900"><i class="fas fa-note-sticky mr-2 text-blue-600"></i>Private notes</h3><span class="text-[10px] font-bold text-slate-400">Only admins see this</span></div><textarea rows="7" wire:model="admin_notes" placeholder="Interview impressions, follow-up items, references…" class="mt-4 w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500"></textarea>@error('admin_notes')<p class="mt-2 text-xs text-red-600">{{ $message }}</p>@enderror<button wire:click="saveNotes" class="mt-3 w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-extrabold text-white hover:bg-emerald-700"><i class="fas fa-check mr-2"></i>Save private notes</button></section>
                    </aside>
                </div>
            </div>
        </div>
    @endif

    @if(session()->has('success'))<div class="flash-auto-dismiss fixed bottom-4 right-4 z-[60] max-w-sm rounded-xl bg-emerald-600 px-5 py-3 text-sm font-semibold text-white shadow-xl"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</div>@endif
</div>
