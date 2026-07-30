<div class="bg-[#f7f4ee] text-slate-950">
    @normalizeArray($contactContent)

    @php
        $phone = trim((string) data_get($contactContent, 'contact_info.phone', ''));
        $phoneHref = preg_replace('/[^0-9+]/', '', $phone);
        $email = trim((string) data_get($contactContent, 'contact_info.email', ''));
        $address = trim((string) data_get($contactContent, 'contact_info.address', ''));
        $hours = (array) data_get($contactContent, 'contact_info.hours', []);

        $departments = collect((array) data_get($contactContent, 'departments', []))
            ->filter(fn ($department) => is_array($department) && !empty($department['name']));
        $faqs = collect((array) data_get($contactContent, 'faqs', []))
            ->filter(fn ($faq) => is_array($faq) && !empty($faq['question']) && !empty($faq['answer']));
        $socials = collect((array) data_get($contactContent, 'socials', []))
            ->filter(function ($social) {
                if (!is_array($social)) {
                    return false;
                }

                $url = trim((string) ($social['url'] ?? ''));

                return $url !== ''
                    && $url !== '#'
                    && \Illuminate\Support\Str::startsWith($url, ['https://', 'http://']);
            });

        $configuredMap = trim((string) data_get($contactContent, 'contact_info.map_embed', ''));
        $mapEmbed = \Illuminate\Support\Str::startsWith($configuredMap, ['https://', 'http://'])
            ? $configuredMap
            : ($address !== '' ? 'https://www.google.com/maps?q=' . urlencode($address) . '&output=embed' : '');
        $directions = $address !== ''
            ? 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($address)
            : '';
    @endphp

    <section class="relative isolate overflow-hidden bg-[#07182b] text-white">
        <div
            class="absolute inset-0 -z-10"
            style="background-image: radial-gradient(circle at 85% 18%, rgba(243, 106, 33, .22), transparent 32%), radial-gradient(circle at 8% 92%, rgba(45, 87, 125, .38), transparent 34%);"
        ></div>
        <div class="mx-auto grid max-w-[1440px] gap-9 px-4 py-14 sm:px-6 sm:py-16 lg:grid-cols-[minmax(0,1.1fr)_minmax(320px,0.9fr)] lg:items-end lg:gap-16 lg:px-8 lg:py-20">
            <div class="max-w-4xl">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-300">Contact Glow FM</p>
                <h1 class="mt-4 text-4xl font-black leading-[1.02] tracking-[-0.045em] sm:text-5xl lg:text-6xl">
                    {{ data_get($contactContent, 'header_title', 'Get In Touch') }}
                </h1>
                @if(trim((string) data_get($contactContent, 'header_subtitle', '')) !== '')
                    <p class="mt-6 max-w-3xl text-base leading-7 text-slate-300 sm:text-lg">
                        {{ data_get($contactContent, 'header_subtitle') }}
                    </p>
                @endif
            </div>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                @if($phone !== '')
                    <a href="tel:{{ $phoneHref }}" class="flex items-center gap-4 rounded-xl border border-white/15 bg-white/[0.06] p-4 transition hover:border-orange-400/50 hover:bg-white/10">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-[#f36a21] text-white">
                            <i class="fas fa-phone text-sm" aria-hidden="true"></i>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-400">Call the station</span>
                            <span class="mt-1 block truncate text-sm font-black text-white">{{ $phone }}</span>
                        </span>
                    </a>
                @endif
                @if($email !== '')
                    <a href="mailto:{{ $email }}" class="flex items-center gap-4 rounded-xl border border-white/15 bg-white/[0.06] p-4 transition hover:border-orange-400/50 hover:bg-white/10">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-white/10 text-orange-300">
                            <i class="fas fa-envelope text-sm" aria-hidden="true"></i>
                        </span>
                        <span class="min-w-0">
                            <span class="block text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-400">Email</span>
                            <span class="mt-1 block truncate text-sm font-black text-white">{{ $email }}</span>
                        </span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    <section class="border-b border-slate-200 bg-white py-7">
        <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8">
            <x-ad-slot placement="contact" />
        </div>
    </section>

    <section class="bg-white py-16 sm:py-20">
        <div class="mx-auto grid max-w-[1440px] gap-10 px-4 sm:px-6 lg:grid-cols-[minmax(0,1.35fr)_minmax(300px,0.65fr)] lg:gap-12 lg:px-8">
            <div id="contact-form" class="scroll-mt-28">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Send a message</p>
                <h2 class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">How can we help?</h2>
                <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                    Choose the most relevant topic and give us the details the station team needs to respond.
                </p>

                @if($successMessage)
                    <div class="flash-auto-dismiss mt-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-900" role="status">
                        <i class="fas fa-check-circle mt-0.5 text-emerald-600" aria-hidden="true"></i>
                        <p class="text-sm font-semibold">{{ $successMessage }}</p>
                    </div>
                @endif

                @if($errorMessage)
                    <div class="flash-auto-dismiss mt-6 flex items-start gap-3 rounded-lg border border-red-200 bg-red-50 p-4 text-red-900" role="alert">
                        <i class="fas fa-exclamation-circle mt-0.5 text-red-600" aria-hidden="true"></i>
                        <p class="text-sm font-semibold">{{ $errorMessage }}</p>
                    </div>
                @endif

                <form wire:submit.prevent="submitForm" class="mt-8 space-y-6" novalidate>
                    <div>
                        <label for="inquiry_type" class="mb-2 block text-sm font-bold text-[#07182b]">
                            Inquiry type <span class="text-[#e95516]" aria-hidden="true">*</span>
                        </label>
                        <select
                            wire:model="inquiry_type"
                            id="inquiry_type"
                            required
                            class="min-h-12 w-full rounded-lg border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 outline-none transition focus:border-[#e95516] focus:ring-2 focus:ring-orange-100 @error('inquiry_type') border-red-500 @enderror"
                        >
                            <option value="general">General inquiry</option>
                            <option value="advertising">Advertising</option>
                            <option value="programming">Programming and shows</option>
                            <option value="technical">Technical support</option>
                            <option value="events">Events</option>
                            <option value="careers">Careers</option>
                            <option value="feedback">Feedback</option>
                        </select>
                        @error('inquiry_type')
                            <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-bold text-[#07182b]">
                                Full name <span class="text-[#e95516]" aria-hidden="true">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="name"
                                id="name"
                                required
                                autocomplete="name"
                                placeholder="Your full name"
                                class="min-h-12 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#e95516] focus:ring-2 focus:ring-orange-100 @error('name') border-red-500 @enderror"
                            >
                            @error('name')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-bold text-[#07182b]">
                                Email address <span class="text-[#e95516]" aria-hidden="true">*</span>
                            </label>
                            <input
                                type="email"
                                wire:model="email"
                                id="email"
                                required
                                autocomplete="email"
                                placeholder="you@example.com"
                                class="min-h-12 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#e95516] focus:ring-2 focus:ring-orange-100 @error('email') border-red-500 @enderror"
                            >
                            @error('email')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label for="phone" class="mb-2 block text-sm font-bold text-[#07182b]">Phone number <span class="font-medium text-slate-400">(optional)</span></label>
                            <input
                                type="tel"
                                wire:model="phone"
                                id="phone"
                                autocomplete="tel"
                                placeholder="+234"
                                class="min-h-12 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#e95516] focus:ring-2 focus:ring-orange-100 @error('phone') border-red-500 @enderror"
                            >
                            @error('phone')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="subject" class="mb-2 block text-sm font-bold text-[#07182b]">
                                Subject <span class="text-[#e95516]" aria-hidden="true">*</span>
                            </label>
                            <input
                                type="text"
                                wire:model="subject"
                                id="subject"
                                required
                                placeholder="A short summary"
                                class="min-h-12 w-full rounded-lg border border-slate-300 px-4 py-3 text-sm text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#e95516] focus:ring-2 focus:ring-orange-100 @error('subject') border-red-500 @enderror"
                            >
                            @error('subject')
                                <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="message" class="mb-2 block text-sm font-bold text-[#07182b]">
                            Message <span class="text-[#e95516]" aria-hidden="true">*</span>
                        </label>
                        <textarea
                            wire:model="message"
                            id="message"
                            rows="7"
                            required
                            placeholder="Tell us what you need help with"
                            class="w-full resize-y rounded-lg border border-slate-300 px-4 py-3 text-sm leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#e95516] focus:ring-2 focus:ring-orange-100 @error('message') border-red-500 @enderror"
                        ></textarea>
                        @error('message')
                            <p class="mt-1.5 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="submitForm"
                        class="inline-flex min-h-12 w-full items-center justify-center gap-2 rounded-lg bg-[#e95516] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-[#d94e12] disabled:cursor-wait disabled:opacity-70 sm:w-auto"
                    >
                        <span wire:loading.remove wire:target="submitForm">Send message</span>
                        <span wire:loading wire:target="submitForm">Sending…</span>
                        <i wire:loading.remove wire:target="submitForm" class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                    </button>
                </form>
            </div>

            <aside class="space-y-5 lg:sticky lg:top-28 lg:self-start">
                <div class="rounded-xl bg-[#07182b] p-6 text-white sm:p-7">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-orange-300">Station details</p>
                    <div class="mt-5 divide-y divide-white/10">
                        @if($address !== '')
                            <div class="flex gap-3 py-4 first:pt-0">
                                <i class="fas fa-map-marker-alt mt-1 w-4 text-orange-300" aria-hidden="true"></i>
                                <p class="text-sm leading-6 text-slate-300">{{ $address }}</p>
                            </div>
                        @endif
                        @if($phone !== '')
                            <a href="tel:{{ $phoneHref }}" class="flex gap-3 py-4 text-sm text-slate-300 transition hover:text-white">
                                <i class="fas fa-phone mt-1 w-4 text-orange-300" aria-hidden="true"></i>
                                <span>{{ $phone }}</span>
                            </a>
                        @endif
                        @if($email !== '')
                            <a href="mailto:{{ $email }}" class="flex min-w-0 gap-3 py-4 text-sm text-slate-300 transition hover:text-white">
                                <i class="fas fa-envelope mt-1 w-4 shrink-0 text-orange-300" aria-hidden="true"></i>
                                <span class="break-all">{{ $email }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                @if(array_filter($hours))
                    <div class="rounded-xl border border-slate-200 bg-[#f7f4ee] p-6">
                        <h3 class="text-lg font-black text-[#07182b]">Office hours</h3>
                        <dl class="mt-4 space-y-3 text-sm">
                            @if(!empty($hours['weekdays']))
                                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                                    <dt class="font-medium text-slate-500">Weekdays</dt>
                                    <dd class="text-right font-bold text-[#07182b]">{{ $hours['weekdays'] }}</dd>
                                </div>
                            @endif
                            @if(!empty($hours['saturday']))
                                <div class="flex justify-between gap-4 border-b border-slate-200 pb-3">
                                    <dt class="font-medium text-slate-500">Saturday</dt>
                                    <dd class="text-right font-bold text-[#07182b]">{{ $hours['saturday'] }}</dd>
                                </div>
                            @endif
                            @if(!empty($hours['sunday']))
                                <div class="flex justify-between gap-4">
                                    <dt class="font-medium text-slate-500">Sunday</dt>
                                    <dd class="text-right font-bold text-[#07182b]">{{ $hours['sunday'] }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif

                @if($socials->isNotEmpty())
                    <div class="rounded-xl border border-slate-200 bg-white p-6">
                        <h3 class="text-lg font-black text-[#07182b]">Follow Glow FM</h3>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($socials as $social)
                                <a
                                    href="{{ $social['url'] }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    aria-label="{{ $social['name'] ?? 'Glow FM social profile' }}"
                                    class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-[#173b5f] transition hover:border-[#e95516] hover:text-[#e95516]"
                                >
                                    <i class="{{ $social['icon'] ?? 'fas fa-link' }}" aria-hidden="true"></i>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </section>

    @if($departments->isNotEmpty())
        <section class="border-y border-slate-200 bg-[#f7f4ee] py-16 sm:py-20" aria-labelledby="departments-heading">
            <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl">
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Direct your inquiry</p>
                    <h2 id="departments-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">Contact by team</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Use the details supplied for the team most relevant to your message.</p>
                </div>

                <div class="mt-9 grid border-l border-t border-slate-200 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($departments as $department)
                        @php
                            $departmentEmail = trim((string) ($department['email'] ?? ''));
                            $departmentPhone = trim((string) ($department['phone'] ?? ''));
                            $departmentPhoneHref = preg_replace('/[^0-9+]/', '', $departmentPhone);
                        @endphp
                        <article class="border-b border-r border-slate-200 bg-white p-6">
                            <i class="{{ $department['icon'] ?? 'fas fa-circle' }} text-lg text-[#e95516]" aria-hidden="true"></i>
                            <h3 class="mt-4 text-lg font-black text-[#07182b]">{{ $department['name'] }}</h3>
                            @if(!empty($department['description']))
                                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $department['description'] }}</p>
                            @endif
                            @if($departmentEmail !== '' || $departmentPhone !== '')
                                <div class="mt-4 space-y-2 border-t border-slate-100 pt-4 text-xs font-bold">
                                    @if($departmentEmail !== '')
                                        <a href="mailto:{{ $departmentEmail }}" class="flex min-w-0 items-center gap-2 text-[#173b5f] transition hover:text-[#e95516]">
                                            <i class="fas fa-envelope w-4 shrink-0 text-[#e95516]" aria-hidden="true"></i>
                                            <span class="truncate">{{ $departmentEmail }}</span>
                                        </a>
                                    @endif
                                    @if($departmentPhone !== '')
                                        <a href="tel:{{ $departmentPhoneHref }}" class="flex items-center gap-2 text-[#173b5f] transition hover:text-[#e95516]">
                                            <i class="fas fa-phone w-4 text-[#e95516]" aria-hidden="true"></i>
                                            <span>{{ $departmentPhone }}</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($mapEmbed !== '' || $faqs->isNotEmpty())
        <section class="bg-white py-16 sm:py-20">
            <div class="mx-auto grid max-w-[1440px] gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-12 lg:px-8">
                @if($mapEmbed !== '')
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Visit the station</p>
                        <h2 class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b]">Find Glow FM</h2>
                        @if($address !== '')
                            <p class="mt-3 text-sm leading-6 text-slate-600">{{ $address }}</p>
                        @endif
                        <div class="mt-6 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                            <iframe
                                src="{{ $mapEmbed }}"
                                title="Map showing Glow FM"
                                width="100%"
                                height="390"
                                style="border:0;"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                                class="block w-full"
                            ></iframe>
                        </div>
                        @if($directions !== '')
                            <a
                                href="{{ $directions }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="mt-5 inline-flex items-center gap-2 text-sm font-extrabold text-[#173b5f] transition hover:text-[#e95516]"
                            >
                                Open directions
                                <i class="fas fa-external-link-alt text-[10px]" aria-hidden="true"></i>
                            </a>
                        @endif
                    </div>
                @endif

                @if($faqs->isNotEmpty())
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Useful answers</p>
                        <h2 class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b]">Frequently asked</h2>
                        <div class="mt-6 divide-y divide-slate-200 border-y border-slate-200">
                            @foreach($faqs as $faq)
                                <div x-data="{ open: false }">
                                    <button
                                        type="button"
                                        @click="open = !open"
                                        :aria-expanded="open"
                                        class="flex w-full items-center justify-between gap-5 py-5 text-left"
                                    >
                                        <span class="font-black text-[#07182b]">{{ $faq['question'] }}</span>
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[#f7f4ee] text-[#e95516]">
                                            <i class="fas fa-plus text-xs transition-transform" :class="open && 'rotate-45'" aria-hidden="true"></i>
                                        </span>
                                    </button>
                                    <div x-show="open" x-collapse>
                                        <p class="pb-5 pr-10 text-sm leading-6 text-slate-600">{{ $faq['answer'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="#contact-form" class="mt-6 inline-flex items-center gap-2 text-sm font-extrabold text-[#173b5f] transition hover:text-[#e95516]">
                            Send a message
                            <i class="fas fa-arrow-up text-xs" aria-hidden="true"></i>
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <script type="text/javascript">
        var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
        (function () {
            var s1 = document.createElement("script"), s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = 'https://embed.tawk.to/693189a9305d681979d1417d/1jbko3gdn';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
</div>
