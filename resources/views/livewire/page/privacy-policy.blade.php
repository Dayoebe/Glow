<div class="bg-[#f7f4ee] text-slate-950">
    @php
        $station = \App\Support\Seo::station();
        $privacyEmail = trim((string) data_get($station, 'email', ''));
    @endphp

    <section class="relative isolate overflow-hidden bg-[#07182b] text-white">
        <div
            class="absolute inset-0 -z-10"
            style="background-image: radial-gradient(circle at 84% 18%, rgba(243, 106, 33, .2), transparent 32%), radial-gradient(circle at 8% 92%, rgba(45, 87, 125, .38), transparent 34%);"
        ></div>
        <div class="mx-auto max-w-[1200px] px-4 py-14 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-300">Your information</p>
            <h1 class="mt-4 text-4xl font-black tracking-[-0.045em] sm:text-5xl lg:text-6xl">Privacy Policy</h1>
            <p class="mt-6 max-w-3xl text-base leading-7 text-slate-300 sm:text-lg">
                How Glow FM collects, uses, and protects information across its radio, news, blog, podcast, and account services.
            </p>
        </div>
    </section>

    <section class="bg-white py-14 sm:py-16">
        <div class="mx-auto grid max-w-[1200px] gap-10 px-4 sm:px-6 lg:grid-cols-[230px_minmax(0,1fr)] lg:gap-16 lg:px-8">
            <aside class="lg:sticky lg:top-28 lg:self-start">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#e95516]">On this page</p>
                <nav class="mt-4 border-l border-slate-200" aria-label="Privacy policy sections">
                    <a href="#overview" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Overview</a>
                    <a href="#information-we-collect" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Information we collect</a>
                    <a href="#how-we-use-information" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">How we use it</a>
                    <a href="#cookies" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Cookies</a>
                    <a href="#third-party-services" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Third-party services</a>
                    <a href="#your-choices" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Your choices</a>
                    <a href="#data-retention" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Data retention</a>
                    <a href="#privacy-contact" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Contact</a>
                </nav>
            </aside>

            <article class="min-w-0">
                <section id="overview" class="scroll-mt-28 border-b border-slate-200 pb-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">01 · Overview</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">What this policy covers</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        Glow FM operates a media platform that includes live radio streaming, show schedules, news, blog posts, and podcasts. This policy explains what we collect, why we collect it, and the choices available to you.
                    </p>
                </section>

                <section id="information-we-collect" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">02 · Collection</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Information we collect</h2>
                    <ul class="mt-5 space-y-3 text-base leading-7 text-slate-600">
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Account details such as name, email, and profile data when you register.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Content you submit, including show reviews, ratings, comments, and contact messages.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Newsletter subscription details such as your email address and subscription preferences.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Usage data such as pages viewed, interactions, and device or browser information.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Media uploads used for approved administrative content and programme assets.</span>
                        </li>
                    </ul>
                </section>

                <section id="how-we-use-information" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">03 · Use</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">How we use your information</h2>
                    <ul class="mt-5 space-y-3 text-base leading-7 text-slate-600">
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Deliver live radio and on-demand content.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Publish and manage news, blog posts, podcasts, and programme schedules.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Moderate reviews, ratings, and community interactions.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Send newsletters and important updates when you opt in.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Measure site performance and improve the listening experience.</span>
                        </li>
                    </ul>
                </section>

                <section id="cookies" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">04 · Cookies</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Cookies and similar technologies</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        We use cookies to keep you logged in, remember preferences, and measure site performance. Some cookies may be used for advertising or analytics with your consent.
                    </p>
                    <p class="mt-3 text-base leading-7 text-slate-600">
                        You can manage consent through the site’s consent controls or your browser settings.
                    </p>
                </section>

                <section id="third-party-services" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">05 · Providers</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Third-party services</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        Glow FM may use providers for analytics, advertising where applicable, media hosting, email delivery, and other infrastructure needed to operate the platform.
                    </p>
                </section>

                <section id="your-choices" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">06 · Control</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Your choices</h2>
                    <ul class="mt-5 space-y-3 text-base leading-7 text-slate-600">
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Update or delete profile information through your account settings where those controls are available.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Unsubscribe from newsletters using the link included in newsletter emails.</span>
                        </li>
                        <li class="flex gap-3">
                            <i class="fas fa-check mt-2 text-[10px] text-[#e95516]" aria-hidden="true"></i>
                            <span>Control cookie consent through the site’s consent controls.</span>
                        </li>
                    </ul>
                </section>

                <section id="data-retention" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">07 · Retention</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Data retention</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        We retain personal data only as long as needed for the purposes described above or as required by law.
                    </p>
                </section>

                <section id="privacy-contact" class="scroll-mt-28 pt-9">
                    <div class="rounded-xl bg-[#07182b] p-7 text-white sm:p-8">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-orange-300">08 · Contact</p>
                        <h2 class="mt-2 text-3xl font-black tracking-[-0.03em]">Questions about privacy?</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-300">
                            Contact Glow FM if you have a question about this policy or how your information is handled.
                        </p>
                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a
                                href="{{ route('contact') }}#contact-form"
                                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg bg-[#f36a21] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-[#ff7a30]"
                            >
                                Open contact form
                                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                            </a>
                            @if($privacyEmail !== '')
                                <a
                                    href="mailto:{{ $privacyEmail }}"
                                    class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/[0.06] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-white/10"
                                >
                                    <i class="fas fa-envelope text-orange-300" aria-hidden="true"></i>
                                    {{ $privacyEmail }}
                                </a>
                            @endif
                        </div>
                    </div>
                </section>
            </article>
        </div>
    </section>
</div>
