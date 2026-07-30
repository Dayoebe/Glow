<div class="bg-[#f7f4ee] text-slate-950">
    <section class="relative isolate overflow-hidden bg-[#07182b] py-16 text-white sm:py-20">
        <div
            class="absolute inset-0 -z-10"
            style="background-image: radial-gradient(circle at 75% 20%, rgba(243, 106, 33, .23), transparent 32%), radial-gradient(circle at 10% 95%, rgba(45, 87, 125, .4), transparent 35%);"
        ></div>
        <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:px-8">
            <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-xl bg-[#f36a21] text-white shadow-[0_12px_30px_rgba(243,106,33,0.24)]">
                <i class="fas fa-check text-xl" aria-hidden="true"></i>
            </span>
            <p class="mt-6 text-xs font-extrabold uppercase tracking-[0.2em] text-orange-300">Message received</p>
            <h1 class="mt-3 text-4xl font-black tracking-[-0.04em] sm:text-5xl">Thank you for contacting Glow FM.</h1>
            <p class="mx-auto mt-5 max-w-2xl text-base leading-7 text-slate-300">
                Your message has been submitted to the station team for review.
            </p>
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a
                    href="{{ route('home') }}"
                    class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg bg-[#f36a21] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-[#ff7a30]"
                >
                    Back to homepage
                    <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                </a>
                <a
                    href="{{ route('shows.index') }}"
                    class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-white/20 bg-white/[0.06] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-white/10"
                >
                    Explore shows
                </a>
            </div>
        </div>
    </section>

    <section class="border-b border-slate-200 bg-white py-7">
        <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8">
            <x-ad-slot placement="contact-success" />
        </div>
    </section>

    <section class="bg-white py-12 sm:py-14">
        <div class="mx-auto grid max-w-4xl gap-5 px-4 sm:grid-cols-2 sm:px-6 lg:px-8">
            <a href="{{ route('news') }}" class="group rounded-xl border border-slate-200 p-6 transition hover:border-orange-300">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#e95516]">Keep reading</p>
                <h2 class="mt-2 text-xl font-black text-[#07182b] transition group-hover:text-[#d94e12]">Latest Glow FM news</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Browse the newest reports and station updates.</p>
            </a>
            <a href="{{ route('listen.live') }}" class="group rounded-xl border border-slate-200 p-6 transition hover:border-orange-300">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-[#e95516]">On air</p>
                <h2 class="mt-2 text-xl font-black text-[#07182b] transition group-hover:text-[#d94e12]">Listen to Glow 99.1 FM</h2>
                <p class="mt-2 text-sm leading-6 text-slate-600">Open the live listening page and join the broadcast.</p>
            </a>
        </div>
    </section>
</div>
