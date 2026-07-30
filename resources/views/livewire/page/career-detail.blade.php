<div class="min-h-screen bg-[#f6f2e9] text-[#0b1830]">
    @php
        $richTextSanitizer = app(\App\Support\RichTextSanitizer::class);
        $renderRichText = fn ($value) => $richTextSanitizer->sanitizeWithLineBreaks((string) $value);
        $fieldClass = 'w-full border border-[#0b1830]/20 bg-white px-3.5 py-3 text-sm text-[#0b1830] outline-none transition placeholder:text-slate-400 focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]';
    @endphp

    <section class="bg-[#07172f] text-white">
        <div class="mx-auto max-w-7xl px-5 py-6 sm:px-8 lg:px-10">
            <a href="{{ route('careers.index') }}"
                class="inline-flex items-center gap-2 text-xs font-semibold text-slate-400 transition hover:text-white">
                <i class="fas fa-arrow-left text-[0.65rem]" aria-hidden="true"></i>
                All careers
            </a>
        </div>

        <div class="mx-auto grid max-w-7xl gap-10 px-5 pb-16 sm:px-8 lg:grid-cols-[1fr_20rem] lg:px-10 lg:pb-20">
            <div class="max-w-4xl">
                <p class="text-xs font-bold uppercase tracking-[0.22em] text-[#ff8a2a]">{{ $position->department ?: 'Glow FM' }}</p>
                <h1 class="font-display mt-3 text-4xl font-semibold leading-[1.02] tracking-tight sm:text-5xl lg:text-6xl">
                    {{ $position->title }}
                </h1>
                @if($position->excerpt)
                    <p class="mt-6 max-w-3xl text-lg leading-8 text-slate-300">{{ $position->excerpt }}</p>
                @endif
                <a href="#apply"
                    class="mt-8 inline-flex items-center gap-3 bg-[#f36b21] px-6 py-3.5 text-sm font-bold text-white transition hover:bg-[#ff7d32] focus:outline-none focus:ring-2 focus:ring-white">
                    Apply for this role
                    <i class="fas fa-arrow-down text-xs" aria-hidden="true"></i>
                </a>
            </div>

            <aside class="border-t border-white/20 pt-5 lg:border-l lg:border-t-0 lg:pl-7 lg:pt-0">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">Role snapshot</p>
                <dl class="mt-4 space-y-4 text-sm">
                    <div>
                        <dt class="text-slate-400">Type</dt>
                        <dd class="mt-1 font-semibold">
                            {{ \Illuminate\Support\Str::of($position->employment_type)->replace('-', ' ')->title() }}
                            · {{ \Illuminate\Support\Str::of($position->workplace_type)->replace('-', ' ')->title() }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Location</dt>
                        <dd class="mt-1 font-semibold">{{ $position->location_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Compensation</dt>
                        <dd class="mt-1 font-semibold">{{ $position->salary_range_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Deadline</dt>
                        <dd class="mt-1 font-semibold">{{ $position->application_deadline?->format('M j, Y') ?: 'Open until filled' }}</dd>
                    </div>
                </dl>
            </aside>
        </div>
    </section>

    <main class="py-14 lg:py-20">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 sm:px-8 lg:grid-cols-[minmax(0,1fr)_24rem] lg:px-10">
            <div class="min-w-0">
                <article>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">The opportunity</p>
                    <h2 class="font-display mt-2 text-3xl font-semibold tracking-tight">Role overview</h2>
                    <div class="prose prose-lg mt-6 max-w-none text-slate-700">{!! $renderRichText($position->description) !!}</div>
                </article>

                @if(!empty($position->responsibilities))
                    <article class="mt-12 border-t border-[#0b1830]/10 pt-10">
                        <h2 class="font-display text-3xl font-semibold tracking-tight">What you’ll do</h2>
                        <div class="prose prose-lg mt-6 max-w-none text-slate-700">{!! $renderRichText($position->responsibilities) !!}</div>
                    </article>
                @endif

                @if(!empty($position->requirements))
                    <article class="mt-12 border-t border-[#0b1830]/10 pt-10">
                        <h2 class="font-display text-3xl font-semibold tracking-tight">What you’ll bring</h2>
                        <div class="prose prose-lg mt-6 max-w-none text-slate-700">{!! $renderRichText($position->requirements) !!}</div>
                    </article>
                @endif

                @if(!empty($position->benefits))
                    <article class="mt-12 border-t border-[#0b1830]/10 pt-10">
                        <h2 class="font-display text-3xl font-semibold tracking-tight">What we offer</h2>
                        <div class="prose prose-lg mt-6 max-w-none text-slate-700">{!! $renderRichText($position->benefits) !!}</div>
                    </article>
                @endif

                @if($relatedPositions->isNotEmpty())
                    <section class="mt-14 border-t border-[#0b1830]/10 pt-10">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-[#d95318]">More opportunities</p>
                        <h2 class="font-display mt-2 text-3xl font-semibold">Other open roles</h2>
                        <div class="mt-6 divide-y divide-[#0b1830]/10 border-y border-[#0b1830]/10">
                            @foreach($relatedPositions as $related)
                                <a href="{{ route('careers.show', $related->slug) }}"
                                    class="group flex items-center justify-between gap-5 py-5 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#f36b21]">
                                    <span>
                                        <strong class="font-display block text-xl font-semibold transition group-hover:text-[#d95318]">{{ $related->title }}</strong>
                                        <small class="mt-1 block text-xs text-slate-500">{{ $related->department ?: 'General' }} · {{ $related->location_label }}</small>
                                    </span>
                                    <i class="fas fa-arrow-right text-xs text-[#d95318]" aria-hidden="true"></i>
                                </a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </div>

            <aside id="apply" class="scroll-mt-28 self-start border-t-4 border-[#f36b21] bg-white p-6 sm:p-8 lg:sticky lg:top-28">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#d95318]">Application</p>
                <h2 class="font-display mt-2 text-3xl font-semibold">Apply now</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">Fields marked with an asterisk are required.</p>

                @if(session()->has('success'))
                    <div class="flash-auto-dismiss mt-5 border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm leading-6 text-emerald-800" role="status">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="flash-auto-dismiss mt-5 border border-red-200 bg-red-50 px-4 py-3 text-sm leading-6 text-red-800" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @if($position->isAcceptingApplications())
                    <form wire:submit.prevent="submitApplication" class="mt-7 space-y-5">
                        <label class="block">
                            <span class="text-sm font-semibold">Full name <span class="text-[#d95318]">*</span></span>
                            <input type="text" wire:model="full_name" autocomplete="name" class="{{ $fieldClass }}">
                            @error('full_name') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                        </label>

                        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
                            <label class="block">
                                <span class="text-sm font-semibold">Email <span class="text-[#d95318]">*</span></span>
                                <input type="email" wire:model="email" autocomplete="email" class="{{ $fieldClass }}">
                                @error('email') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="block">
                                <span class="text-sm font-semibold">Phone</span>
                                <input type="tel" wire:model="phone" autocomplete="tel" class="{{ $fieldClass }}">
                                @error('phone') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </label>
                        </div>

                        <label class="block">
                            <span class="text-sm font-semibold">Current location</span>
                            <input type="text" wire:model="location" autocomplete="address-level2" class="{{ $fieldClass }}">
                            @error('location') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                        </label>

                        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
                            <label class="block">
                                <span class="text-sm font-semibold">LinkedIn profile</span>
                                <input type="url" wire:model="linkedin_url" inputmode="url" placeholder="https://" class="{{ $fieldClass }}">
                                @error('linkedin_url') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="block">
                                <span class="text-sm font-semibold">Portfolio</span>
                                <input type="url" wire:model="portfolio_url" inputmode="url" placeholder="https://" class="{{ $fieldClass }}">
                                @error('portfolio_url') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </label>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
                            <label class="block">
                                <span class="text-sm font-semibold">Years of experience</span>
                                <input type="number" min="0" max="60" wire:model="years_experience" class="{{ $fieldClass }}">
                                @error('years_experience') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="block">
                                <span class="text-sm font-semibold">Expected salary</span>
                                <input type="number" min="0" step="0.01" wire:model="expected_salary" class="{{ $fieldClass }}">
                                @error('expected_salary') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </label>
                        </div>

                        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-1">
                            <label class="block">
                                <span class="text-sm font-semibold">Current company</span>
                                <input type="text" wire:model="current_company" autocomplete="organization" class="{{ $fieldClass }}">
                                @error('current_company') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </label>
                            <label class="block">
                                <span class="text-sm font-semibold">Current role</span>
                                <input type="text" wire:model="current_role" class="{{ $fieldClass }}">
                                @error('current_role') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                            </label>
                        </div>

                        <label class="block">
                            <span class="text-sm font-semibold">Available from</span>
                            <input type="date" wire:model="available_from" class="{{ $fieldClass }}">
                            @error('available_from') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                        </label>

                        <label class="block">
                            <span class="text-sm font-semibold">Cover letter</span>
                            <textarea rows="6" wire:model="cover_letter" class="{{ $fieldClass }}"
                                placeholder="Tell us why this role is a fit"></textarea>
                            @error('cover_letter') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                        </label>

                        <label class="block">
                            <span class="text-sm font-semibold">Resume <span class="text-[#d95318]">*</span></span>
                            <span class="mt-1 block text-xs text-slate-500">PDF, DOC or DOCX · maximum 5 MB</span>
                            <input type="file" wire:model="resume" accept=".pdf,.doc,.docx"
                                class="mt-2 block w-full border border-[#0b1830]/20 bg-[#f6f2e9] p-3 text-xs file:mr-3 file:border-0 file:bg-[#0b1830] file:px-3 file:py-2 file:text-xs file:font-bold file:text-white">
                            <span wire:loading wire:target="resume" class="mt-2 block text-xs text-slate-500">Uploading resume…</span>
                            @error('resume') <span class="mt-1 block text-xs text-red-600">{{ $message }}</span> @enderror
                        </label>

                        <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-3 bg-[#f36b21] px-5 py-3.5 text-sm font-bold text-white transition hover:bg-[#d95318] disabled:cursor-wait disabled:opacity-70"
                            wire:loading.attr="disabled" wire:target="submitApplication">
                            <span wire:loading.remove wire:target="submitApplication">Submit application</span>
                            <span wire:loading wire:target="submitApplication">Submitting…</span>
                            <i wire:loading.remove wire:target="submitApplication" class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                        </button>
                    </form>
                @else
                    <div class="mt-6 border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-800">
                        This position is no longer accepting applications.
                    </div>
                @endif
            </aside>
        </div>
    </main>
</div>
