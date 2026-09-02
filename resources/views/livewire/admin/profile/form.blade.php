<div class="mx-auto max-w-7xl space-y-6">
    @if (session('success'))
        <div class="flash-auto-dismiss fixed bottom-[calc(env(safe-area-inset-bottom)+6.75rem)] right-4 z-[70] flex max-w-[calc(100vw-2rem)] items-center gap-3 rounded-2xl bg-emerald-600 px-5 py-4 text-sm font-semibold text-white shadow-2xl shadow-emerald-950/20 lg:bottom-5" role="status" aria-live="polite">
            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-white/20"><i class="fas fa-check" aria-hidden="true"></i></span>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <section class="overflow-hidden rounded-3xl border border-emerald-700 bg-emerald-700 text-white shadow-sm">
        <div class="relative px-6 py-8 sm:px-8 lg:px-10">
            <div class="pointer-events-none absolute -right-14 -top-20 h-56 w-56 rounded-full border-[36px] border-white/10"></div>
            <div class="relative flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1.5 text-xs font-bold uppercase tracking-[0.18em] text-emerald-50"><i class="fas fa-id-card" aria-hidden="true"></i> My profile</div>
                    <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Make your Glow FM profile feel like you.</h2>
                    <p class="mt-3 max-w-2xl text-sm leading-6 text-emerald-50/90 sm:text-base">Update your photo, biography and contact details. You decide which contact details and links are visible to listeners.</p>
                </div>
                @if ($publicProfileUrl)
                    <a href="{{ $publicProfileUrl }}" target="_blank" rel="noopener" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-bold text-emerald-800 shadow-sm transition hover:bg-emerald-50 sm:w-auto"><i class="fas fa-arrow-up-right-from-square" aria-hidden="true"></i> View public profile</a>
                @endif
            </div>
        </div>
    </section>

    <form wire:submit="save" class="grid items-start gap-6 xl:grid-cols-[21rem_minmax(0,1fr)]">
        <aside class="space-y-6 xl:sticky xl:top-24">
            <section class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5"><h3 class="font-bold text-slate-900">Profile photo</h3><p class="mt-1 text-xs leading-5 text-slate-500">Use a clear, square image for the best result.</p></div>
                <div class="p-6" x-data="{ uploading: false, progress: 0, fileName: '' }"
                    x-on:livewire-upload-start="uploading = true; progress = 0"
                    x-on:livewire-upload-finish="uploading = false; progress = 100"
                    x-on:livewire-upload-error="uploading = false; progress = 0"
                    x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <div class="relative mx-auto h-44 w-44">
                        <div class="h-full w-full overflow-hidden rounded-3xl border-4 border-white bg-slate-100 shadow-lg ring-1 ring-slate-200">
                            @if ($avatar_upload)
                                <img src="{{ $avatar_upload->temporaryUrl() }}" alt="New profile picture preview" class="h-full w-full object-cover">
                            @elseif ($avatar)
                                <img src="{{ $avatar }}" alt="Current profile picture" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center bg-emerald-50 text-5xl text-emerald-300"><i class="fas fa-user" aria-hidden="true"></i></div>
                            @endif
                        </div>
                        <span class="absolute -bottom-2 -right-2 flex h-11 w-11 items-center justify-center rounded-2xl border-4 border-white bg-emerald-600 text-white shadow-md"><i class="fas fa-camera" aria-hidden="true"></i></span>
                    </div>
                    <label class="mt-7 block cursor-pointer rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 p-4 transition hover:border-emerald-500 hover:bg-emerald-50/50 focus-within:border-emerald-500 focus-within:ring-4 focus-within:ring-emerald-100">
                        <input type="file" wire:model="avatar_upload" accept="image/jpeg,image/png,image/webp" class="sr-only"
                            x-on:change="fileName = $event.target.files[0]?.name ?? ''">
                        <span class="flex items-center gap-3">
                            <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-slate-900 text-white"><i class="fas fa-cloud-arrow-up" aria-hidden="true"></i></span>
                            <span class="min-w-0">
                                <strong class="block text-sm text-slate-900" x-text="fileName || 'Choose a new photo'"></strong>
                                <span class="mt-0.5 block text-xs text-slate-500" x-text="fileName ? 'Click to choose a different image' : 'JPG, PNG or WebP · maximum 5 MB'"></span>
                            </span>
                        </span>
                    </label>

                    <div x-cloak x-show="uploading" x-transition.opacity class="mt-4" role="status" aria-live="polite">
                        <div class="mb-2 flex items-center justify-between text-xs font-semibold text-slate-600"><span>Uploading photo…</span><span x-text="`${progress}%`"></span></div>
                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-200">
                            <div class="h-full rounded-full bg-emerald-600 transition-all duration-200" :style="`width: ${progress}%`"></div>
                        </div>
                    </div>
                    <div wire:loading.delay wire:target="avatar_upload" x-show="!uploading" class="mt-3 flex items-center gap-2 text-xs font-semibold text-emerald-700"><i class="fas fa-circle-notch fa-spin" aria-hidden="true"></i> Processing image preview…</div>
                    @error('avatar_upload')<p class="mt-2 text-sm text-red-600">{{ $message }}</p>@enderror

                    <details class="mt-4 rounded-xl border border-slate-200 bg-white">
                        <summary class="cursor-pointer px-4 py-3 text-xs font-semibold text-slate-600 hover:text-emerald-700">Use an image link instead</summary>
                        <div class="border-t border-slate-100 p-4">
                            <label class="block text-xs font-bold uppercase tracking-wide text-slate-500">Direct image URL</label>
                            <div class="relative mt-2"><i class="fas fa-link absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400" aria-hidden="true"></i><input type="url" wire:model="avatar" class="w-full rounded-xl border-slate-300 py-2.5 pl-9 pr-3 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="https://example.com/photo.jpg"></div>
                            @error('avatar')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </details>
                </div>
            </section>

            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-3"><span class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700"><i class="fas fa-briefcase" aria-hidden="true"></i></span><div><h3 class="font-bold text-slate-900">Official assignment</h3><p class="text-xs text-slate-500">Managed by administration</p></div></div>
                <dl class="mt-5 divide-y divide-slate-100 rounded-2xl bg-slate-50 px-4 text-sm">
                    <div class="py-4"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Department</dt><dd class="mt-1 font-bold text-slate-900">{{ $departmentLabel ?: 'Not assigned' }}</dd></div>
                    <div class="py-4"><dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">Role</dt><dd class="mt-1 font-bold text-slate-900">{{ $roleLabel ?: auth()->user()->role_label }}</dd></div>
                </dl>
            </section>
        </aside>

        <div class="min-w-0 space-y-6">
            <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5 sm:px-8"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700"><i class="fas fa-user-pen" aria-hidden="true"></i></span><div><h3 class="text-lg font-bold text-slate-900">Personal information</h3><p class="mt-1 text-sm text-slate-500">The details used for your account and staff profile.</p></div></div>
                <div class="space-y-6 p-6 sm:p-8">
                    <div class="grid gap-6 md:grid-cols-2">
                        <div><label class="mb-2 block text-sm font-semibold text-slate-700">Full name</label><input wire:model="name" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Your full name">@error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Email address</label><input type="email" wire:model="email" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="you@example.com">@error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            @if ($hasStaffProfile)<label class="mt-3 flex cursor-pointer items-center justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3 text-sm"><span><strong class="block text-slate-800">Show email publicly</strong><span class="text-xs text-slate-500">Off by default</span></span><input type="checkbox" wire:model="profile_visibility.email" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"></label>@endif
                        </div>
                        @if ($hasStaffProfile)
                            <div>
                                <label class="mb-2 block text-sm font-semibold text-slate-700">Phone number</label><input type="tel" wire:model="phone" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="+234 …">@error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                <label class="mt-3 flex cursor-pointer items-center justify-between gap-4 rounded-xl bg-slate-50 px-4 py-3 text-sm"><span><strong class="block text-slate-800">Show phone publicly</strong><span class="text-xs text-slate-500">Off by default</span></span><input type="checkbox" wire:model="profile_visibility.phone" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"></label>
                            </div>
                        @endif
                        <div class="md:col-span-2">
                            <div class="mb-2 flex items-center justify-between gap-3"><label class="text-sm font-semibold text-slate-700">Professional bio</label><span class="text-xs text-slate-400">Maximum 3,000 characters</span></div>
                            <textarea wire:model="bio" rows="7" class="w-full resize-y rounded-xl border-slate-300 leading-6 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Tell listeners about your work, experience, interests and what you do at Glow FM."></textarea>@error('bio')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    @if ($hasStaffProfile)<div class="flex gap-3 rounded-2xl border border-sky-100 bg-sky-50 p-4 text-sm leading-6 text-sky-900"><i class="fas fa-shield-halved mt-1 text-sky-600" aria-hidden="true"></i><p><strong>Your privacy is protected.</strong> Your email and phone stay private until you enable their individual public controls.</p></div>@endif
                </div>
            </section>

            @if ($hasStaffProfile)
                <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5 sm:px-8"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-violet-50 text-violet-700"><i class="fas fa-link" aria-hidden="true"></i></span><div><h3 class="text-lg font-bold text-slate-900">Social and professional links</h3><p class="mt-1 text-sm text-slate-500">Add your pages and choose exactly which ones listeners can see.</p></div></div>
                    <div class="grid gap-4 p-6 sm:grid-cols-2 sm:p-8">
                        @foreach (['facebook' => ['fab fa-facebook-f', 'Facebook'], 'instagram' => ['fab fa-instagram', 'Instagram'], 'twitter' => ['fab fa-x-twitter', 'X / Twitter'], 'linkedin' => ['fab fa-linkedin-in', 'LinkedIn'], 'tiktok' => ['fab fa-tiktok', 'TikTok'], 'youtube' => ['fab fa-youtube', 'YouTube'], 'website' => ['fas fa-globe', 'Personal website']] as $platform => [$icon, $label])
                            <div class="rounded-2xl border border-slate-200 p-4 transition focus-within:border-emerald-300 focus-within:ring-4 focus-within:ring-emerald-50">
                                <label class="mb-3 flex items-center gap-3 text-sm font-bold text-slate-800"><span class="flex h-9 w-9 items-center justify-center rounded-xl bg-slate-100 text-slate-600"><i class="{{ $icon }}" aria-hidden="true"></i></span>{{ $label }}</label>
                                <input type="url" wire:model="social_links.{{ $platform }}" class="w-full rounded-xl border-slate-300 text-sm focus:border-emerald-500 focus:ring-emerald-500" placeholder="https://…">@error('social_links.' . $platform)<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                <label class="mt-3 flex cursor-pointer items-center justify-between gap-3 text-sm text-slate-600"><span>Visible on public profile</span><input type="checkbox" wire:model="profile_visibility.social.{{ $platform }}" class="h-5 w-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"></label>
                            </div>
                        @endforeach
                    </div>
                </section>
            @else
                <section class="flex gap-4 rounded-3xl border border-amber-200 bg-amber-50 p-6 text-sm leading-6 text-amber-950"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700"><i class="fas fa-link-slash" aria-hidden="true"></i></span><p><strong class="block text-base">Public staff profile not linked</strong>Your account details can be updated here. Phone and social publishing will become available after an administrator links your account to a staff profile.</p></section>
            @endif

            <section class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="flex items-start gap-4 border-b border-slate-100 px-6 py-5 sm:px-8"><span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-amber-50 text-amber-700"><i class="fas fa-lock" aria-hidden="true"></i></span><div><h3 class="text-lg font-bold text-slate-900">Account security</h3><p class="mt-1 text-sm text-slate-500">Only enter a password when you want to replace your current one.</p></div></div>
                <div class="grid gap-6 p-6 sm:p-8 md:grid-cols-2">
                    <div><label class="mb-2 block text-sm font-semibold text-slate-700">New password</label><input type="password" wire:model="password" autocomplete="new-password" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Leave blank to keep current password">@error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror</div>
                    <div><label class="mb-2 block text-sm font-semibold text-slate-700">Confirm new password</label><input type="password" wire:model="password_confirmation" autocomplete="new-password" class="w-full rounded-xl border-slate-300 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Repeat new password"></div>
                </div>
            </section>

            <div class="sticky bottom-[calc(env(safe-area-inset-bottom)+6.25rem)] z-20 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-xl backdrop-blur sm:flex-row sm:items-center sm:justify-between lg:bottom-4">
                <p class="hidden text-sm text-slate-500 sm:block"><i class="fas fa-circle-info mr-2 text-emerald-600" aria-hidden="true"></i>Your changes apply across your Glow FM profile.</p>
                <button type="submit" wire:loading.attr="disabled" wire:target="save,avatar_upload" class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-7 py-3 font-bold text-white shadow-sm transition hover:bg-emerald-700 disabled:cursor-wait disabled:opacity-60 sm:w-auto"><span wire:loading.remove wire:target="save"><i class="fas fa-floppy-disk mr-2" aria-hidden="true"></i>Save profile changes</span><span wire:loading wire:target="save"><i class="fas fa-spinner fa-spin mr-2" aria-hidden="true"></i>Saving profile…</span></button>
            </div>
        </div>
    </form>
</div>
