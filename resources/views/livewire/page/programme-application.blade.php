<div class="min-h-screen bg-[#f6f2e9] text-[#0b1830]">
    @php $field = 'mt-2 w-full border border-[#0b1830]/20 bg-white px-4 py-3 text-sm outline-none focus:border-[#f36b21] focus:ring-1 focus:ring-[#f36b21]'; @endphp
    <section class="bg-[#07172f] text-white">
        <div class="mx-auto max-w-5xl px-5 py-16 sm:px-8 lg:py-20">
            <a href="{{ route('careers.index') }}" class="text-sm text-slate-300 hover:text-white"><i class="fas fa-arrow-left mr-2"></i>Careers</a>
            <p class="mt-10 text-xs font-bold uppercase tracking-[0.22em] text-[#ff8a2a]">Join the Glow community</p>
            <h1 class="font-display mt-3 text-4xl font-semibold sm:text-6xl">{{ $label }} application</h1>
            <p class="mt-5 max-w-2xl text-lg leading-8 text-slate-300">
                {{ $type === 'internship' ? 'Build practical media experience, learn from our team and contribute to real station work.' : 'Share your time, skills and ideas while helping Glow FM serve its community.' }}
            </p>
        </div>
    </section>

    <main class="mx-auto grid max-w-5xl gap-10 px-5 py-14 sm:px-8 lg:grid-cols-[16rem_1fr] lg:py-20">
        <aside class="self-start border-t-4 border-[#f36b21] bg-white p-6 lg:sticky lg:top-28">
            <h2 class="font-display text-xl font-semibold">Before you apply</h2>
            <ul class="mt-4 space-y-3 text-sm leading-6 text-slate-600">
                <li><i class="fas fa-check mr-2 text-emerald-600"></i>Choose your preferred department.</li>
                <li><i class="fas fa-check mr-2 text-emerald-600"></i>Explain the value you can offer.</li>
                <li><i class="fas fa-check mr-2 text-emerald-600"></i>Upload a current résumé.</li>
                <li><i class="fas fa-lock mr-2 text-emerald-600"></i>Your document is stored privately.</li>
            </ul>
        </aside>

        <section class="bg-white p-6 shadow-sm sm:p-10">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[#d95318]">Tell us about yourself</p>
            <h2 class="font-display mt-2 text-3xl font-semibold">Application details</h2>
            @if(session()->has('success'))
                <div class="mt-6 border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800" role="status">{{ session('success') }}</div>
            @endif
            <form wire:submit.prevent="submit" class="mt-8 space-y-8">
                <div class="grid gap-5 sm:grid-cols-2">
                    <label class="block"><span class="text-sm font-semibold">Full name *</span><input wire:model="full_name" autocomplete="name" class="{{ $field }}">@error('full_name')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                    <label class="block"><span class="text-sm font-semibold">Email *</span><input type="email" wire:model="email" autocomplete="email" class="{{ $field }}">@error('email')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                    <label class="block"><span class="text-sm font-semibold">Phone *</span><input wire:model="phone" autocomplete="tel" class="{{ $field }}">@error('phone')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                    <label class="block"><span class="text-sm font-semibold">Current location *</span><input wire:model="location" class="{{ $field }}">@error('location')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                </div>

                <div class="border-t border-slate-200 pt-7">
                    <h3 class="font-display text-2xl font-semibold">Interest and background</h3>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <label><span class="text-sm font-semibold">Preferred department *</span><select wire:model="department" class="{{ $field }}"><option value="">Select department</option>@foreach(['On-Air & Presentation','Programming & Production','News & Editorial','Digital Media & Content','Marketing & Communications','Sales & Partnerships','Events & Community','Engineering & Technical','Administration & Operations'] as $department)<option>{{ $department }}</option>@endforeach</select>@error('department')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                        <label><span class="text-sm font-semibold">Education level *</span><select wire:model="education_level" class="{{ $field }}"><option value="">Select level</option>@foreach(['Secondary school','OND/NCE','HND/Bachelor degree','Postgraduate','Professional certification','Other'] as $level)<option>{{ $level }}</option>@endforeach</select>@error('education_level')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                        <label><span class="text-sm font-semibold">School / institution</span><input wire:model="institution" class="{{ $field }}"></label>
                        <label><span class="text-sm font-semibold">Course of study</span><input wire:model="course_of_study" class="{{ $field }}"></label>
                    </div>
                    <label class="mt-5 block"><span class="text-sm font-semibold">Skills and experience *</span><textarea rows="4" wire:model="skills" class="{{ $field }}" placeholder="List relevant technical, creative, communication or community skills."></textarea>@error('skills')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                    <label class="mt-5 block"><span class="text-sm font-semibold">Why do you want to join Glow FM? *</span><textarea rows="5" wire:model="motivation" class="{{ $field }}"></textarea>@error('motivation')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                    <label class="mt-5 block"><span class="text-sm font-semibold">What can you offer the team and our audience? *</span><textarea rows="5" wire:model="contribution" class="{{ $field }}"></textarea>@error('contribution')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                </div>

                <div class="border-t border-slate-200 pt-7">
                    <h3 class="font-display text-2xl font-semibold">Availability and documents</h3>
                    <div class="mt-5 grid gap-5 sm:grid-cols-2">
                        <label><span class="text-sm font-semibold">Available from *</span><input type="date" wire:model="available_from" min="{{ now()->toDateString() }}" class="{{ $field }}">@error('available_from')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                        <label><span class="text-sm font-semibold">Weekly availability *</span><select wire:model="availability" class="{{ $field }}"><option value="">Select availability</option><option>Weekdays - full time</option><option>Weekdays - part time</option><option>Weekends</option><option>Flexible / shift based</option></select>@error('availability')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                        <label><span class="text-sm font-semibold">Commitment length *</span><select wire:model="commitment_length" class="{{ $field }}"><option value="">Select duration</option><option>1-3 months</option><option>3-6 months</option><option>6-12 months</option><option>More than 1 year</option><option>Ongoing / flexible</option></select>@error('commitment_length')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                        <label><span class="text-sm font-semibold">LinkedIn profile</span><input type="url" wire:model="linkedin_url" placeholder="https://" class="{{ $field }}"></label>
                        <label><span class="text-sm font-semibold">Portfolio / work samples</span><input type="url" wire:model="portfolio_url" placeholder="https://" class="{{ $field }}"></label>
                        <label><span class="text-sm font-semibold">Résumé * <small class="font-normal text-slate-500">(PDF/DOC, max 5 MB)</small></span><input type="file" wire:model="resume" accept=".pdf,.doc,.docx" class="{{ $field }}">@error('resume')<small class="text-red-600">{{ $message }}</small>@enderror</label>
                    </div>
                </div>

                <label class="flex items-start gap-3 text-sm leading-6"><input type="checkbox" wire:model="consent" class="mt-1"><span>I confirm that this information is accurate and consent to Glow FM using it to assess and contact me about this application. *</span></label>@error('consent')<small class="text-red-600">{{ $message }}</small>@enderror
                <button class="inline-flex w-full items-center justify-center gap-3 bg-[#f36b21] px-6 py-4 font-bold text-white hover:bg-[#d95318] disabled:opacity-60" wire:loading.attr="disabled">Submit {{ strtolower($label) }} application <i class="fas fa-arrow-right"></i></button>
            </form>
        </section>
    </main>
</div>
