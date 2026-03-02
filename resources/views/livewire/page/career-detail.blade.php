<div>
    @php
        $allowedRichTextTags = '<p><br><strong><em><b><i><u><s><strike><ul><ol><li><a><span><div><h1><h2><h3><h4><h5><h6><blockquote>';
        $renderRichText = function ($value) use ($allowedRichTextTags) {
            $html = trim((string) $value);
            if ($html === '') {
                return '';
            }

            $clean = strip_tags($html, $allowedRichTextTags);
            if ($clean === strip_tags($clean)) {
                return nl2br(e($clean));
            }

            return $clean;
        };
    @endphp

    <section class="bg-slate-900 text-white py-16">
        <div class="container mx-auto px-4">
            <a href="{{ route('careers.index') }}" class="inline-flex items-center text-sm text-emerald-300 hover:text-emerald-200 mb-5">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to careers
            </a>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <div class="lg:col-span-2">
                    <p class="text-xs uppercase tracking-[0.3em] text-emerald-300 font-semibold">{{ $position->department ?: 'Glow FM' }}</p>
                    <h1 class="mt-3 text-4xl md:text-5xl font-black leading-tight">{{ $position->title }}</h1>
                    <p class="mt-4 text-lg text-slate-200 leading-relaxed">{{ $position->excerpt ?: 'Join our team and help shape the sound of our community.' }}</p>
                </div>

                <aside class="bg-slate-800/70 border border-slate-700 rounded-2xl p-5 space-y-3">
                    <p class="text-xs text-slate-400 uppercase tracking-wide">Role Snapshot</p>
                    <p class="text-sm"><i class="fas fa-briefcase mr-2 text-emerald-400"></i>{{ \Illuminate\Support\Str::of($position->employment_type)->replace('-', ' ')->title() }} • {{ \Illuminate\Support\Str::of($position->workplace_type)->replace('-', ' ')->title() }}</p>
                    <p class="text-sm"><i class="fas fa-location-dot mr-2 text-emerald-400"></i>{{ $position->location_label }}</p>
                    <p class="text-sm"><i class="fas fa-money-bill-wave mr-2 text-emerald-400"></i>{{ $position->salary_range_label }}</p>
                    <p class="text-sm"><i class="fas fa-hourglass-half mr-2 text-emerald-400"></i>{{ $position->application_deadline?->format('M d, Y') ?: 'No deadline' }}</p>
                    <p class="text-sm"><i class="fas fa-users mr-2 text-emerald-400"></i>{{ $position->positions_available }} position(s)</p>
                    <span class="inline-flex mt-2 px-3 py-1 text-xs rounded-full font-semibold {{ $position->isAcceptingApplications() ? 'bg-emerald-500/20 text-emerald-300' : 'bg-amber-500/20 text-amber-300' }}">
                        {{ $position->isAcceptingApplications() ? 'Accepting Applications' : 'Applications Closed' }}
                    </span>
                </aside>
            </div>
        </div>
    </section>

    <section class="py-12 bg-white">
        <div class="container mx-auto px-4 grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2 space-y-8">
                <article class="bg-white border border-gray-200 rounded-2xl p-6">
                    <h2 class="text-2xl font-bold text-gray-900">Role Overview</h2>
                    <div class="mt-4 prose max-w-none text-gray-700 leading-relaxed">{!! $renderRichText($position->description) !!}</div>
                </article>

                @if(!empty($position->responsibilities))
                    <article class="bg-white border border-gray-200 rounded-2xl p-6">
                        <h2 class="text-2xl font-bold text-gray-900">Key Responsibilities</h2>
                        <div class="mt-4 prose max-w-none text-gray-700 leading-relaxed">{!! $renderRichText($position->responsibilities) !!}</div>
                    </article>
                @endif

                @if(!empty($position->requirements))
                    <article class="bg-white border border-gray-200 rounded-2xl p-6">
                        <h2 class="text-2xl font-bold text-gray-900">Requirements</h2>
                        <div class="mt-4 prose max-w-none text-gray-700 leading-relaxed">{!! $renderRichText($position->requirements) !!}</div>
                    </article>
                @endif

                @if(!empty($position->benefits))
                    <article class="bg-white border border-gray-200 rounded-2xl p-6">
                        <h2 class="text-2xl font-bold text-gray-900">Benefits</h2>
                        <div class="mt-4 prose max-w-none text-gray-700 leading-relaxed">{!! $renderRichText($position->benefits) !!}</div>
                    </article>
                @endif
            </div>

            <div class="space-y-6">
                <div id="apply" class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                    <h3 class="text-xl font-bold text-gray-900">Apply For This Role</h3>
                    <p class="mt-1 text-sm text-gray-600">Complete the form below and upload your resume.</p>

                    @if($position->isAcceptingApplications())
                        <form wire:submit.prevent="submitApplication" class="mt-5 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="full_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @error('full_name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" wire:model="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                <input type="text" wire:model="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Location</label>
                                <input type="text" wire:model="location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @error('location') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">LinkedIn URL</label>
                                    <input type="url" wire:model="linkedin_url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    @error('linkedin_url') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Portfolio URL</label>
                                    <input type="url" wire:model="portfolio_url" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    @error('portfolio_url') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Years of Experience</label>
                                    <input type="number" min="0" wire:model="years_experience" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    @error('years_experience') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Expected Salary</label>
                                    <input type="number" min="0" step="0.01" wire:model="expected_salary" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    @error('expected_salary') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Company</label>
                                    <input type="text" wire:model="current_company" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    @error('current_company') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Role</label>
                                    <input type="text" wire:model="current_role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                    @error('current_role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Available From</label>
                                <input type="date" wire:model="available_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @error('available_from') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cover Letter</label>
                                <textarea rows="5" wire:model="cover_letter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Tell us why you are a great fit for this role"></textarea>
                                @error('cover_letter') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Resume <span class="text-red-500">*</span> (PDF/DOC, max 5MB)</label>
                                <input type="file" wire:model="resume" accept=".pdf,.doc,.docx" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                @error('resume') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <button type="submit" class="w-full px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg transition-colors">
                                <span wire:loading.remove wire:target="submitApplication">Submit Application</span>
                                <span wire:loading wire:target="submitApplication">Submitting...</span>
                            </button>
                        </form>
                    @else
                        <div class="mt-4 p-4 rounded-lg bg-amber-50 border border-amber-200 text-amber-700 text-sm">
                            This role is currently closed for new applications.
                        </div>
                    @endif
                </div>

                @if($relatedPositions->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-2xl p-6">
                        <h3 class="text-lg font-bold text-gray-900">Other Open Roles</h3>
                        <div class="mt-4 space-y-3">
                            @foreach($relatedPositions as $related)
                                <a href="{{ route('careers.show', $related->slug) }}" class="block p-3 rounded-lg border border-gray-200 hover:border-emerald-300 hover:bg-emerald-50/40 transition-colors">
                                    <p class="font-semibold text-gray-900">{{ $related->title }}</p>
                                    <p class="text-xs text-gray-600 mt-1">{{ $related->department ?: 'General' }} • {{ $related->location_label }}</p>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
</div>
