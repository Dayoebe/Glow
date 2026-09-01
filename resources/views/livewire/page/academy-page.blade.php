<div class="bg-[#f7f3ea] text-[#07172f]">
    @php $field = 'mt-2 w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#ef6c2f] focus:ring-2 focus:ring-[#ef6c2f]/20'; @endphp

    <section class="relative overflow-hidden bg-[#07172f] text-white">
        <div class="absolute -right-24 top-8 h-80 w-80 rounded-full bg-[#ef6c2f]/20 blur-3xl"></div>
        <div class="absolute -left-28 bottom-0 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl"></div>
        <div class="relative mx-auto grid max-w-7xl gap-12 px-5 py-20 sm:px-8 lg:grid-cols-[1.15fr_.85fr] lg:items-center lg:py-28">
            <div>
                <p class="text-xs font-black uppercase tracking-[.25em] text-[#ff9b63]">Learn inside a working radio station</p>
                <h1 class="font-display mt-5 max-w-4xl text-5xl font-semibold leading-[1.02] sm:text-7xl">Turn your passion for broadcasting into practical skill.</h1>
                <p class="mt-7 max-w-2xl text-lg leading-8 text-slate-300">Glow FM Academy is an in-person broadcasting programme in Akure for aspiring presenters, journalists, producers and digital storytellers. Learn through guided classes, studio practice, assignments and constructive feedback.</p>
                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="#apply" class="rounded-full bg-[#ef6c2f] px-7 py-3.5 text-sm font-black text-white transition hover:bg-[#d95318]">Apply to the Academy <i class="fas fa-arrow-right ml-2"></i></a>
                    <a href="#curriculum" class="rounded-full border border-white/25 px-7 py-3.5 text-sm font-bold transition hover:bg-white/10">Explore the curriculum</a>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                @foreach([['3','months','Foundation'],['6','months','Skills builder'],['9','months','Advanced'],['12','months','Professional']] as $option)
                    <div class="rounded-2xl border border-white/10 bg-white/[.07] p-6 backdrop-blur">
                        <p class="text-4xl font-black text-[#ff9b63]">{{ $option[0] }}</p>
                        <p class="text-sm font-bold uppercase tracking-wider text-white">{{ $option[1] }}</p>
                        <p class="mt-4 text-xs text-slate-400">{{ $option[2] }} pathway</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-16 sm:px-8 lg:py-24" id="curriculum">
        <div class="max-w-3xl">
            <p class="text-xs font-black uppercase tracking-[.22em] text-[#d95318]">What you will learn</p>
            <h2 class="font-display mt-3 text-4xl font-semibold sm:text-5xl">A complete introduction to modern broadcasting</h2>
            <p class="mt-5 text-lg leading-8 text-slate-600">Your exact depth and practical workload grow with the duration you select. Every pathway begins with the fundamentals and develops confidence through repeated studio practice.</p>
        </div>
        <div class="mt-12 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach([
                ['fa-microphone-lines','Presentation & voice','Microphone technique, vocal delivery, scripting, ad-libbing, interviewing and building a confident on-air personality.'],
                ['fa-newspaper','News & journalism','News judgment, research, fact-checking, writing for radio, bulletins, field reporting and responsible media practice.'],
                ['fa-sliders','Studio production','Console basics, recording, audio editing, programme clocks, jingles, links and producing a polished radio segment.'],
                ['fa-listen','Programming','Audience awareness, programme planning, rundowns, music scheduling, formats, timing and maintaining a compelling show.'],
                ['fa-mobile-screen-button','Digital broadcasting','Podcasting, livestreams, social video, content repurposing and presenting consistently across radio and digital channels.'],
                ['fa-briefcase','Career readiness','Demo preparation, personal branding, teamwork, newsroom discipline, audition practice and professional feedback.'],
            ] as [$icon,$title,$copy])
                <article class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#07172f] text-[#ff9b63]"><i class="fas {{ $icon }}"></i></div>
                    <h3 class="font-display mt-5 text-2xl font-semibold">{{ $title }}</h3>
                    <p class="mt-3 text-sm leading-7 text-slate-600">{{ $copy }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-white">
        <div class="mx-auto grid max-w-7xl gap-12 px-5 py-16 sm:px-8 lg:grid-cols-2 lg:py-24">
            <div>
                <p class="text-xs font-black uppercase tracking-[.22em] text-[#d95318]">How the programme works</p>
                <h2 class="font-display mt-3 text-4xl font-semibold">Learn, practise, receive feedback, repeat.</h2>
                <div class="mt-8 space-y-6">
                    @foreach([['01','Guided learning','Understand the principles behind strong, responsible broadcasting.'],['02','Studio practice','Work with microphones, scripts, studio workflows and production tools.'],['03','Real assignments','Plan and create bulletins, interviews, links, shows and digital content.'],['04','Feedback & portfolio','Improve through reviews and assemble work that demonstrates your progress.']] as [$number,$title,$copy])
                        <div class="flex gap-5"><span class="text-sm font-black text-[#ef6c2f]">{{ $number }}</span><div><h3 class="font-bold">{{ $title }}</h3><p class="mt-1 text-sm leading-6 text-slate-600">{{ $copy }}</p></div></div>
                    @endforeach
                </div>
            </div>
            <aside class="rounded-3xl bg-[#07172f] p-8 text-white sm:p-10">
                <h3 class="font-display text-3xl font-semibold">Before you apply</h3>
                <ul class="mt-7 space-y-4 text-sm leading-6 text-slate-300">
                    <li><i class="fas fa-location-dot mr-3 text-[#ff9b63]"></i>Training is delivered in person at Glow FM in Akure.</li>
                    <li><i class="fas fa-calendar-check mr-3 text-[#ff9b63]"></i>Choose a 3, 6, 9 or 12-month pathway.</li>
                    <li><i class="fas fa-heart mr-3 text-[#ff9b63]"></i>No previous radio job is required; curiosity and commitment matter.</li>
                    <li><i class="fas fa-circle-info mr-3 text-[#ff9b63]"></i>Submitting this form is an application, not automatic admission.</li>
                    <li><i class="fas fa-phone mr-3 text-[#ff9b63]"></i>Shortlisted applicants will be contacted with schedule, fees and admission details.</li>
                </ul>
            </aside>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-5 py-16 sm:px-8 lg:py-24" id="apply">
        <div class="rounded-3xl bg-white p-6 shadow-[0_24px_80px_rgba(7,23,47,.1)] sm:p-10 lg:p-14">
            <p class="text-xs font-black uppercase tracking-[.22em] text-[#d95318]">Admissions</p>
            <h2 class="font-display mt-3 text-4xl font-semibold">Apply to Glow FM Academy</h2>
            <p class="mt-4 max-w-2xl leading-7 text-slate-600">Tell us about your interests and preferred pathway. Fields marked * are required.</p>

            @if(session()->has('success'))
                <div class="mt-7 rounded-xl border border-emerald-200 bg-emerald-50 p-5 text-sm text-emerald-800" role="status"><i class="fas fa-circle-check mr-2"></i>{{ session('success') }}</div>
            @endif

            <form wire:submit.prevent="submit" class="mt-10 space-y-9">
                <fieldset><legend class="font-display text-2xl font-semibold">Your details</legend><div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <label><span class="text-sm font-bold">Full name *</span><input wire:model="full_name" autocomplete="name" class="{{ $field }}">@error('full_name')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                    <label><span class="text-sm font-bold">Email address *</span><input type="email" wire:model="email" autocomplete="email" class="{{ $field }}">@error('email')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                    <label><span class="text-sm font-bold">Phone number *</span><input wire:model="phone" autocomplete="tel" class="{{ $field }}">@error('phone')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                    <label><span class="text-sm font-bold">Current location *</span><input wire:model="location" class="{{ $field }}">@error('location')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                </div></fieldset>

                <fieldset class="border-t border-slate-200 pt-8"><legend class="font-display text-2xl font-semibold">Programme preference</legend><div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <label><span class="text-sm font-bold">Preferred pathway *</span><select wire:model="department" class="{{ $field }}"><option value="">Select a pathway</option>@foreach(['Presentation & On-Air','News & Journalism','Production & Audio','Digital Broadcasting'] as $track)<option>{{ $track }}</option>@endforeach</select>@error('department')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                    <label><span class="text-sm font-bold">Programme duration *</span><select wire:model="commitment_length" class="{{ $field }}"><option value="">Select duration</option>@foreach(['3 months','6 months','9 months','12 months'] as $duration)<option>{{ $duration }}</option>@endforeach</select>@error('commitment_length')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                    <label><span class="text-sm font-bold">Preferred start date *</span><input type="date" min="{{ now()->toDateString() }}" wire:model="available_from" class="{{ $field }}">@error('available_from')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                    <label><span class="text-sm font-bold">Best class time *</span><select wire:model="availability" class="{{ $field }}"><option value="">Select availability</option>@foreach(['Weekday mornings','Weekday afternoons','Weekday evenings','Weekends','Flexible'] as $time)<option>{{ $time }}</option>@endforeach</select>@error('availability')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                </div></fieldset>

                <fieldset class="border-t border-slate-200 pt-8"><legend class="font-display text-2xl font-semibold">Background and goals</legend><div class="mt-5 grid gap-5 sm:grid-cols-2">
                    <label><span class="text-sm font-bold">Highest education level *</span><select wire:model="education_level" class="{{ $field }}"><option value="">Select level</option>@foreach(['Secondary school','OND/NCE','HND/Bachelor degree','Postgraduate','Professional certification','Other'] as $level)<option>{{ $level }}</option>@endforeach</select>@error('education_level')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                    <label><span class="text-sm font-bold">School / institution</span><input wire:model="institution" class="{{ $field }}"></label>
                </div>
                    <label class="mt-5 block"><span class="text-sm font-bold">Broadcasting, media or communication experience *</span><textarea rows="4" wire:model="skills" class="{{ $field }}" placeholder="It is fine to say you are a complete beginner. Tell us about any relevant experience or strengths."></textarea>@error('skills')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                    <label class="mt-5 block"><span class="text-sm font-bold">Why do you want to learn broadcasting? *</span><textarea rows="5" wire:model="motivation" class="{{ $field }}"></textarea>@error('motivation')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                    <label class="mt-5 block"><span class="text-sm font-bold">What would you like to be able to do after the programme? *</span><textarea rows="4" wire:model="contribution" class="{{ $field }}"></textarea>@error('contribution')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                    <label class="mt-5 block"><span class="text-sm font-bold">Résumé or profile <small class="font-normal text-slate-500">(optional, PDF/DOC, max 5 MB)</small></span><input type="file" wire:model="resume" accept=".pdf,.doc,.docx" class="{{ $field }}">@error('resume')<small class="mt-1 block text-red-600">{{ $message }}</small>@enderror</label>
                </fieldset>

                <label class="flex items-start gap-3 rounded-xl bg-slate-50 p-4 text-sm leading-6"><input type="checkbox" wire:model="consent" class="mt-1"><span>I confirm that this information is accurate and consent to Glow FM using it to assess my Academy application and contact me about admission. *</span></label>@error('consent')<small class="block text-red-600">{{ $message }}</small>@enderror
                <button class="inline-flex w-full items-center justify-center gap-3 rounded-xl bg-[#ef6c2f] px-7 py-4 font-black text-white transition hover:bg-[#d95318] disabled:cursor-wait disabled:opacity-60" wire:loading.attr="disabled">Submit Academy application <i class="fas fa-arrow-right" wire:loading.remove></i><i class="fas fa-circle-notch fa-spin" wire:loading></i></button>
            </form>
        </div>
    </section>
</div>
