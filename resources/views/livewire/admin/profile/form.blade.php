<div>
    @if (session('success'))
        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-medium text-emerald-700 flash-auto-dismiss">{{ session('success') }}</div>
    @endif

    <div class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 p-6 text-white shadow-sm sm:p-8">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
            <div><p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-300">My professional profile</p><h2 class="mt-2 text-2xl font-bold">Control how you appear across Glow FM</h2><p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">Keep your contact information, biography, profile picture and public links accurate. Your official department and role remain managed by administration.</p></div>
            @if($publicProfileUrl)<a href="{{ $publicProfileUrl }}" target="_blank" class="inline-flex shrink-0 items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-bold text-slate-900"><i class="fas fa-arrow-up-right-from-square mr-2"></i>View public profile</a>@endif
        </div>
    </div>

    <form wire:submit="save" class="grid gap-6 xl:grid-cols-[20rem_1fr]">
        <aside class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-bold text-slate-900">Profile picture</h3>
                <div class="mx-auto mt-5 h-40 w-40 overflow-hidden rounded-full border-4 border-white bg-slate-100 shadow-lg">
                    @if($avatar_upload)<img src="{{ $avatar_upload->temporaryUrl() }}" alt="New profile picture preview" class="h-full w-full object-cover">@elseif($avatar)<img src="{{ $avatar }}" alt="Current profile picture" class="h-full w-full object-cover">@else<div class="flex h-full items-center justify-center text-5xl text-slate-300"><i class="fas fa-user"></i></div>@endif
                </div>
                <label class="mt-6 block cursor-pointer rounded-xl border-2 border-dashed border-slate-300 px-4 py-4 text-center text-sm font-semibold text-slate-700 hover:border-emerald-500 hover:text-emerald-700"><i class="fas fa-camera mr-2"></i>Choose a new picture<input type="file" wire:model="avatar_upload" accept="image/*" class="sr-only"></label>
                <div wire:loading wire:target="avatar_upload" class="mt-2 text-center text-xs text-emerald-600">Preparing preview…</div>
                @error('avatar_upload')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror
                <label class="mt-5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Or use an image URL</label><input type="url" wire:model="avatar" class="mt-2 w-full rounded-xl border-slate-300 text-sm" placeholder="https://…">@error('avatar')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </section>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm"><p class="text-xs font-bold uppercase tracking-wider text-slate-500">Official assignment</p><dl class="mt-4 space-y-4 text-sm"><div><dt class="text-slate-500">Department</dt><dd class="mt-1 font-bold text-slate-900">{{ $departmentLabel ?: 'Not assigned' }}</dd></div><div><dt class="text-slate-500">Role</dt><dd class="mt-1 font-bold text-slate-900">{{ $roleLabel ?: auth()->user()->role_label }}</dd></div></dl></section>
        </aside>

        <div class="space-y-6">
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                <h3 class="text-lg font-bold text-slate-900">About you</h3>
                <div class="mt-6 grid gap-6 md:grid-cols-2">
                    <div><label class="mb-2 block text-sm font-semibold text-slate-700">Full name</label><input wire:model="name" class="w-full rounded-xl border-slate-300">@error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="mb-2 block text-sm font-semibold text-slate-700">Email address</label><input type="email" wire:model="email" class="w-full rounded-xl border-slate-300">@error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror @if($hasStaffProfile)<label class="mt-3 flex items-center gap-3 text-sm text-slate-600"><input type="checkbox" wire:model="profile_visibility.email" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"><span><strong class="text-slate-800">Show publicly</strong> on my staff profile</span></label>@endif</div>
                    @if($hasStaffProfile)<div><label class="mb-2 block text-sm font-semibold text-slate-700">Phone number</label><input type="tel" wire:model="phone" class="w-full rounded-xl border-slate-300" placeholder="+234 …">@error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror<label class="mt-3 flex items-center gap-3 text-sm text-slate-600"><input type="checkbox" wire:model="profile_visibility.phone" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"><span><strong class="text-slate-800">Show publicly</strong> on my staff profile</span></label></div>@endif
                    <div class="md:col-span-2"><div class="flex justify-between"><label class="mb-2 block text-sm font-semibold text-slate-700">Professional bio</label><span class="text-xs text-slate-400">Up to 3,000 characters</span></div><textarea wire:model="bio" rows="7" class="w-full rounded-xl border-slate-300" placeholder="Tell listeners about your work, experience, interests and what you do at Glow FM."></textarea>@error('bio')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                </div>
                @if($hasStaffProfile)<div class="mt-6 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm leading-6 text-blue-800"><i class="fas fa-shield-halved mr-2"></i>Your email and phone remain private unless you switch on their individual public controls.</div>@endif
            </section>

            @if($hasStaffProfile)<section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8"><h3 class="text-lg font-bold text-slate-900">Social and professional links</h3><p class="mt-1 text-sm text-slate-500">Add a link, then independently choose whether visitors can see it.</p><div class="mt-6 grid gap-5 md:grid-cols-2">@foreach(['facebook' => ['fab fa-facebook','Facebook'], 'instagram' => ['fab fa-instagram','Instagram'], 'twitter' => ['fab fa-x-twitter','X / Twitter'], 'linkedin' => ['fab fa-linkedin','LinkedIn'], 'tiktok' => ['fab fa-tiktok','TikTok'], 'youtube' => ['fab fa-youtube','YouTube'], 'website' => ['fas fa-globe','Personal website']] as $platform => [$icon,$label])<div class="rounded-xl border border-slate-200 p-4"><label class="mb-2 block text-sm font-semibold text-slate-700"><i class="{{ $icon }} mr-2 text-slate-400"></i>{{ $label }}</label><input type="url" wire:model="social_links.{{ $platform }}" class="w-full rounded-xl border-slate-300" placeholder="https://…">@error('social_links.'.$platform)<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror<label class="mt-3 flex items-center gap-3 text-sm text-slate-600"><input type="checkbox" wire:model="profile_visibility.social.{{ $platform }}" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"><span>Show this link publicly</span></label></div>@endforeach</div></section>@else<section class="rounded-2xl border border-amber-200 bg-amber-50 p-6 text-sm leading-6 text-amber-900"><strong class="block">Public staff profile not linked</strong>Your account details can be updated here, but phone and social publishing become available after an administrator links your account to a staff profile.</section>@endif

            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8"><h3 class="text-lg font-bold text-slate-900">Account security</h3><p class="mt-1 text-sm text-slate-500">Leave both fields blank to keep your current password.</p><div class="mt-6 grid gap-6 md:grid-cols-2"><div><label class="mb-2 block text-sm font-semibold text-slate-700">New password</label><input type="password" wire:model="password" class="w-full rounded-xl border-slate-300">@error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div><div><label class="mb-2 block text-sm font-semibold text-slate-700">Confirm password</label><input type="password" wire:model="password_confirmation" class="w-full rounded-xl border-slate-300"></div></div></section>

            <div class="sticky bottom-4 flex justify-end rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-lg backdrop-blur"><button type="submit" wire:loading.attr="disabled" wire:target="save,avatar_upload" class="rounded-xl bg-emerald-600 px-7 py-3 font-bold text-white hover:bg-emerald-700 disabled:opacity-60"><span wire:loading.remove wire:target="save">Save profile changes</span><span wire:loading wire:target="save">Saving profile…</span></button></div>
        </div>
    </form>
</div>
