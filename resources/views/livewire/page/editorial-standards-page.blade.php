<div class="bg-[#f7f4ee] text-slate-950">
    @php
        $station = \App\Support\Seo::station();
        $editorialEmail = trim((string) data_get($station, 'email', ''));
    @endphp

    <section class="relative isolate overflow-hidden bg-[#07182b] text-white">
        <div
            class="absolute inset-0 -z-10"
            style="background-image: radial-gradient(circle at 84% 18%, rgba(243, 106, 33, .2), transparent 32%), radial-gradient(circle at 8% 92%, rgba(45, 87, 125, .38), transparent 34%);"
        ></div>
        <div class="mx-auto max-w-[1200px] px-4 py-14 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-300">Trust and accountability</p>
            <h1 class="mt-4 max-w-4xl text-4xl font-black tracking-[-0.045em] sm:text-5xl lg:text-6xl">
                Editorial Standards
            </h1>
            <p class="mt-6 max-w-3xl text-base leading-7 text-slate-300 sm:text-lg">
                The principles that guide Glow 99.1 FM journalism, digital publishing, corrections, and community content.
            </p>
        </div>
    </section>

    <section class="bg-white py-14 sm:py-16">
        <div class="mx-auto grid max-w-[1200px] gap-10 px-4 sm:px-6 lg:grid-cols-[230px_minmax(0,1fr)] lg:gap-16 lg:px-8">
            <aside class="lg:sticky lg:top-28 lg:self-start">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#e95516]">On this page</p>
                <nav class="mt-4 border-l border-slate-200" aria-label="Editorial standards sections">
                    <a href="#accuracy" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Accuracy</a>
                    <a href="#sources" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Sources and attribution</a>
                    <a href="#bylines" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Bylines</a>
                    <a href="#corrections" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Corrections</a>
                    <a href="#independence" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Independence</a>
                    <a href="#community-content" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Community content</a>
                    <a href="#ai-assisted-work" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">AI-assisted work</a>
                    <a href="#editorial-contact" class="block border-l-2 border-transparent py-2 pl-4 text-sm font-bold text-slate-500 transition hover:border-[#e95516] hover:text-[#07182b]">Contact us</a>
                </nav>
            </aside>

            <article class="min-w-0">
                <section id="accuracy" class="scroll-mt-28 border-b border-slate-200 pb-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">01 · Accuracy</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Accuracy before speed</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        We aim to verify material facts, names, dates, quotations, images, and context before publication. Developing stories may change as reliable information becomes available; we distinguish confirmed information from allegations, estimates, and commentary.
                    </p>
                </section>

                <section id="sources" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">02 · Evidence</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Sources and attribution</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        We identify sources whenever it is safe and practical, link to primary documents where available, and clearly attribute reporting or media from other publishers. Anonymous sourcing should be reserved for information with genuine public value when naming a source could create a credible risk.
                    </p>
                </section>

                <section id="bylines" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">03 · Accountability</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Bylines and authorship</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        Original articles should identify the responsible author or Glow FM newsroom. Contributor information and team profiles help readers understand who created or reviewed a story. A byline reflects responsibility for the published work, not simply access to the publishing system.
                    </p>
                </section>

                <section id="corrections" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">04 · Corrections</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Correct the record clearly</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        When a material error is confirmed, we correct it promptly and preserve the article’s meaning and context. Significant corrections should be explained on the article. Routine spelling or formatting fixes that do not alter meaning may be made without a correction note.
                    </p>
                    <p class="mt-3 text-base leading-7 text-slate-600">
                        Readers can report a possible error through our contact form and should include the article link, the disputed detail, and supporting evidence.
                    </p>
                </section>

                <section id="independence" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">05 · Transparency</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Editorial and commercial separation</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        Advertising, sponsorship, paid partnerships, opinion, and promotional material should be labelled so audiences can distinguish them from independent reporting. Commercial relationships must not be presented as editorial endorsement.
                    </p>
                </section>

                <section id="community-content" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">06 · Participation</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Community contributions</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        Comments, reviews, listener messages, eyewitness material, and other audience submissions remain the contributor’s account until verified. We may moderate material that is unlawful, abusive, deceptive, unsafe, irrelevant, or invasive of another person’s privacy.
                    </p>
                </section>

                <section id="ai-assisted-work" class="scroll-mt-28 border-b border-slate-200 py-9">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-[#e95516]">07 · Technology</p>
                    <h2 class="mt-2 text-3xl font-black tracking-[-0.03em] text-[#07182b]">Responsible AI-assisted work</h2>
                    <p class="mt-4 text-base leading-7 text-slate-600">
                        Automated tools may assist with research organisation, transcription, translation, formatting, or production. A human editor remains responsible for checking facts, context, rights, privacy, and the final publication. We do not treat generated text as a source or use it to invent quotations, evidence, or people.
                    </p>
                </section>

                <section id="editorial-contact" class="scroll-mt-28 pt-9">
                    <div class="rounded-xl bg-[#07182b] p-7 text-white sm:p-8">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-orange-300">08 · Contact</p>
                        <h2 class="mt-2 text-3xl font-black tracking-[-0.03em]">Report a concern or correction</h2>
                        <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-300">
                            Tell us what needs review and include a link plus any source material that helps us verify the issue.
                        </p>
                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a
                                href="{{ route('contact') }}#contact-form"
                                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg bg-[#f36a21] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-[#ff7a30]"
                            >
                                Contact the newsroom
                                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                            </a>
                            @if($editorialEmail !== '')
                                <a
                                    href="mailto:{{ $editorialEmail }}?subject={{ rawurlencode('Editorial correction or concern') }}"
                                    class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/[0.06] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-white/10"
                                >
                                    <i class="fas fa-envelope text-orange-300" aria-hidden="true"></i>
                                    {{ $editorialEmail }}
                                </a>
                            @endif
                        </div>
                    </div>
                </section>
            </article>
        </div>
    </section>
</div>
