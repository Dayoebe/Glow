<div class="bg-[#f7f4ee] text-slate-950">
    @normalizeArray($aboutContent)

    @php
        $richTextSanitizer = app(\App\Support\RichTextSanitizer::class);
        $isUsableUrl = function ($value) {
            if (!is_string($value)) {
                return false;
            }

            $url = trim($value);

            return $url !== ''
                && $url !== '#'
                && \Illuminate\Support\Str::startsWith($url, ['/', 'https://', 'http://', 'mailto:', 'tel:']);
        };
        $isExternalUrl = fn ($value) => is_string($value)
            && \Illuminate\Support\Str::startsWith(trim($value), ['https://', 'http://']);

        $sanitizeRichText = fn ($value) => $richTextSanitizer->sanitizeWithLineBreaks((string) $value);

        $storyParagraphs = collect((array) data_get($aboutContent, 'story_paragraphs', []))->filter(fn ($item) => trim((string) $item) !== '');
        $storyBadges = collect((array) data_get($aboutContent, 'story_badges', []))->filter(fn ($item) => trim((string) $item) !== '');
        $values = collect((array) data_get($aboutContent, 'values', []))->filter(fn ($item) => is_array($item) && !empty($item['title']));
        $milestones = collect((array) data_get($aboutContent, 'milestones', []))->filter(fn ($item) => is_array($item) && !empty($item['title']));
        $team = collect((array) data_get($aboutContent, 'team', []))->filter(fn ($item) => is_array($item) && !empty($item['name']));
        $achievements = collect((array) data_get($aboutContent, 'achievements', []))->filter(fn ($item) => is_array($item) && !empty($item['award']));
        $partners = collect((array) data_get($aboutContent, 'partners', []))->filter(fn ($item) => is_array($item) && !empty($item['name']));
        $displayStats = collect((array) data_get($aboutContent, 'stats', []))
            ->filter(fn ($item) => is_array($item) && !empty($item['number']) && !empty($item['label']))
            ->reject(fn ($item) => \Illuminate\Support\Str::contains(
                \Illuminate\Support\Str::lower((string) $item['label']),
                ['listener', 'audience', 'reach', 'follower']
            ));

        $primaryCtaUrl = trim((string) data_get($aboutContent, 'cta_primary_url', ''));
        $secondaryCtaUrl = trim((string) data_get($aboutContent, 'cta_secondary_url', ''));
        $hasPrimaryCta = $isUsableUrl($primaryCtaUrl) && trim((string) data_get($aboutContent, 'cta_primary_text', '')) !== '';
        $hasSecondaryCta = $isUsableUrl($secondaryCtaUrl) && trim((string) data_get($aboutContent, 'cta_secondary_text', '')) !== '';
    @endphp

    <section class="relative isolate overflow-hidden bg-[#07182b] text-white">
        <div
            class="absolute inset-0 -z-10"
            style="background-image: radial-gradient(circle at 85% 18%, rgba(243, 106, 33, .22), transparent 32%), radial-gradient(circle at 8% 92%, rgba(45, 87, 125, .38), transparent 34%);"
        ></div>
        <div class="mx-auto grid max-w-[1440px] gap-10 px-4 py-14 sm:px-6 sm:py-16 lg:grid-cols-[minmax(0,1.1fr)_minmax(320px,0.9fr)] lg:items-end lg:gap-16 lg:px-8 lg:py-20">
            <div class="max-w-4xl">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-orange-300">About the station</p>
                <h1 class="mt-4 text-4xl font-black leading-[1.02] tracking-[-0.045em] sm:text-5xl lg:text-6xl">
                    {{ data_get($aboutContent, 'header_title', 'About Glow 99.1 FM') }}
                </h1>
                @if(trim((string) data_get($aboutContent, 'header_subtitle', '')) !== '')
                    <p class="mt-6 max-w-3xl text-base leading-7 text-slate-300 sm:text-lg">
                        {{ data_get($aboutContent, 'header_subtitle') }}
                    </p>
                @endif
            </div>

            <div class="border-l border-white/15 pl-6 sm:pl-8">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-orange-300">Broadcasting from</p>
                <p class="mt-2 text-xl font-black text-white">Ijapo Estate, Akure</p>
                <p class="mt-1 text-sm text-slate-400">Ondo State, Nigeria · 99.1 FM</p>
            </div>
        </div>
    </section>

    <section class="border-b border-slate-200 bg-white py-7">
        <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8">
            <x-ad-slot placement="about" />
        </div>
    </section>

    <section class="bg-white py-16 sm:py-20" aria-labelledby="our-story-heading">
        <div class="mx-auto grid max-w-[1440px] gap-10 px-4 sm:px-6 lg:grid-cols-[minmax(0,1.35fr)_minmax(280px,0.65fr)] lg:items-start lg:gap-16 lg:px-8">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Our story</p>
                <h2 id="our-story-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                    {{ data_get($aboutContent, 'story_title', 'Glow 99.1 FM Akure') }}
                </h2>

                @if($storyParagraphs->isNotEmpty())
                    <div class="mt-6 space-y-5 text-base leading-7 text-slate-600 [&_a]:font-bold [&_a]:text-[#d94e12] [&_blockquote]:border-l-4 [&_blockquote]:border-orange-400 [&_blockquote]:pl-5 [&_li]:mb-2 [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:leading-7 [&_ul]:list-disc [&_ul]:pl-6">
                        @foreach($storyParagraphs as $paragraph)
                            <div>{!! $sanitizeRichText($paragraph) !!}</div>
                        @endforeach
                    </div>
                @endif

                @if($storyBadges->isNotEmpty())
                    <div class="mt-8 flex flex-wrap gap-2">
                        @foreach($storyBadges as $badge)
                            <span class="inline-flex items-center gap-2 rounded-md border border-slate-200 bg-[#f7f4ee] px-3 py-2 text-xs font-extrabold uppercase tracking-[0.11em] text-[#173b5f]">
                                <i class="fas fa-check text-[10px] text-[#e95516]" aria-hidden="true"></i>
                                {{ $badge }}
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>

            <aside class="overflow-hidden rounded-xl border border-slate-200 bg-[#f7f4ee]">
                <div class="flex aspect-[4/3] items-center justify-center bg-[#07182b] p-10">
                    <img src="{{ asset('glowfm logo.jpeg') }}" alt="Glow 99.1 FM" width="256" height="256"
                        loading="lazy" decoding="async" class="max-h-52 w-auto rounded-lg object-contain">
                </div>
                <div class="grid grid-cols-2 divide-x divide-slate-200">
                    <div class="p-5">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-500">Frequency</p>
                        <p class="mt-1 text-xl font-black text-[#07182b]">99.1 FM</p>
                    </div>
                    <div class="p-5">
                        <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-500">Home</p>
                        <p class="mt-1 text-xl font-black text-[#07182b]">Akure</p>
                    </div>
                </div>
            </aside>
        </div>
    </section>

    @if(trim((string) data_get($aboutContent, 'mission_body', '')) !== '' || trim((string) data_get($aboutContent, 'vision_body', '')) !== '')
        <section class="bg-[#f7f4ee] py-16 sm:py-20" aria-label="Mission and vision">
            <div class="mx-auto grid max-w-[1440px] gap-5 px-4 sm:px-6 md:grid-cols-2 lg:px-8">
                @if(trim((string) data_get($aboutContent, 'mission_body', '')) !== '')
                    <article class="rounded-xl border border-slate-200 bg-white p-7 sm:p-9">
                        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-orange-50 text-[#e95516]">
                            <i class="fas fa-bullseye" aria-hidden="true"></i>
                        </span>
                        <h2 class="mt-6 text-2xl font-black tracking-[-0.025em] text-[#07182b]">
                            {{ data_get($aboutContent, 'mission_title', 'Our Mission') }}
                        </h2>
                        <div class="mt-4 text-sm leading-7 text-slate-600 [&_a]:font-bold [&_a]:text-[#d94e12] [&_li]:mb-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_ul]:list-disc [&_ul]:pl-5">
                            {!! $sanitizeRichText(data_get($aboutContent, 'mission_body', '')) !!}
                        </div>
                    </article>
                @endif

                @if(trim((string) data_get($aboutContent, 'vision_body', '')) !== '')
                    <article class="rounded-xl bg-[#07182b] p-7 text-white sm:p-9">
                        <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-white/10 text-orange-300">
                            <i class="fas fa-eye" aria-hidden="true"></i>
                        </span>
                        <h2 class="mt-6 text-2xl font-black tracking-[-0.025em]">
                            {{ data_get($aboutContent, 'vision_title', 'Our Vision') }}
                        </h2>
                        <div class="mt-4 text-sm leading-7 text-slate-300 [&_a]:font-bold [&_a]:text-orange-300 [&_li]:mb-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_ul]:list-disc [&_ul]:pl-5">
                            {!! $sanitizeRichText(data_get($aboutContent, 'vision_body', '')) !!}
                        </div>
                    </article>
                @endif
            </div>
        </section>
    @endif

    @if($values->isNotEmpty())
        <section class="bg-white py-16 sm:py-20" aria-labelledby="values-heading">
            <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl">
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">What guides us</p>
                    <h2 id="values-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                        {{ data_get($aboutContent, 'values_title', 'Our Core Values') }}
                    </h2>
                    @if(trim((string) data_get($aboutContent, 'values_subtitle', '')) !== '')
                        <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">{{ data_get($aboutContent, 'values_subtitle') }}</p>
                    @endif
                </div>

                <div class="mt-9 grid border-l border-t border-slate-200 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($values as $value)
                        <article class="border-b border-r border-slate-200 p-6 sm:p-7">
                            <i class="{{ $value['icon'] ?? 'fas fa-circle' }} text-xl text-[#e95516]" aria-hidden="true"></i>
                            <h3 class="mt-5 text-xl font-black text-[#07182b]">{{ $value['title'] }}</h3>
                            @if(trim((string) ($value['description'] ?? '')) !== '')
                                <div class="mt-3 text-sm leading-6 text-slate-600 [&_a]:font-bold [&_a]:text-[#d94e12] [&_li]:mb-1 [&_ul]:list-disc [&_ul]:pl-5">
                                    {!! $sanitizeRichText($value['description']) !!}
                                </div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($milestones->isNotEmpty())
        <section class="border-y border-slate-200 bg-[#f7f4ee] py-16 sm:py-20" aria-labelledby="journey-heading">
            <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl">
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Timeline</p>
                    <h2 id="journey-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                        {{ data_get($aboutContent, 'milestones_title', 'Our Journey') }}
                    </h2>
                    @if(trim((string) data_get($aboutContent, 'milestones_subtitle', '')) !== '')
                        <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">{{ data_get($aboutContent, 'milestones_subtitle') }}</p>
                    @endif
                </div>

                <div class="mt-10 divide-y divide-slate-200 border-y border-slate-200">
                    @foreach($milestones as $milestone)
                        <article class="grid gap-3 py-6 sm:grid-cols-[120px_minmax(0,1fr)] sm:gap-8">
                            <p class="text-2xl font-black text-[#e95516]">{{ $milestone['year'] ?? '' }}</p>
                            <div>
                                <h3 class="text-xl font-black text-[#07182b]">{{ $milestone['title'] }}</h3>
                                @if(trim((string) ($milestone['description'] ?? '')) !== '')
                                    <div class="mt-2 text-sm leading-6 text-slate-600 [&_a]:font-bold [&_a]:text-[#d94e12]">
                                        {!! $sanitizeRichText($milestone['description']) !!}
                                    </div>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($team->isNotEmpty())
        <section class="bg-white py-16 sm:py-20" x-data="{ activeMember: null }" @keydown.escape.window="activeMember = null" aria-labelledby="leadership-heading">
            <div class="mx-auto max-w-[1440px] px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl">
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Leadership</p>
                    <h2 id="leadership-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                        {{ data_get($aboutContent, 'team_title', 'Meet Our Leadership') }}
                    </h2>
                    @if(trim((string) data_get($aboutContent, 'team_subtitle', '')) !== '')
                        <p class="mt-3 text-sm leading-6 text-slate-600 sm:text-base">{{ data_get($aboutContent, 'team_subtitle') }}</p>
                    @endif
                </div>

                <div class="mt-9 grid gap-x-5 gap-y-9 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach($team as $member)
                        @php
                            $memberBio = $member['bio'] ?? '';
                            $memberBioPreview = \Illuminate\Support\Str::limit(trim(strip_tags($memberBio)), 150);
                            $memberSocials = [];
                            $rawSocials = data_get($member, 'socials', null);

                            if (is_array($rawSocials)) {
                                foreach ($rawSocials as $social) {
                                    if (!is_array($social)) {
                                        continue;
                                    }

                                    $url = trim((string) ($social['url'] ?? ''));
                                    if (\Illuminate\Support\Str::startsWith($url, 'mailto:')) {
                                        $url = 'mailto:' . trim(\Illuminate\Support\Str::after($url, 'mailto:'));
                                    }
                                    if (!$isUsableUrl($url)) {
                                        continue;
                                    }

                                    $memberSocials[] = [
                                        'name' => $social['name'] ?? 'Profile link',
                                        'icon' => $social['icon'] ?? 'fas fa-link',
                                        'url' => $url,
                                        'external' => $isExternalUrl($url),
                                    ];
                                }
                            }

                            if (!$memberSocials && !array_key_exists('socials', $member)) {
                                $legacySocial = data_get($member, 'social', []);
                                if (is_array($legacySocial)) {
                                    $legacyMap = [
                                        'linkedin' => ['name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'mailto' => false],
                                        'twitter' => ['name' => 'Twitter', 'icon' => 'fab fa-twitter', 'mailto' => false],
                                        'email' => ['name' => 'Email', 'icon' => 'fas fa-envelope', 'mailto' => true],
                                    ];

                                    foreach ($legacyMap as $key => $meta) {
                                        $url = trim((string) ($legacySocial[$key] ?? ''));
                                        if ($meta['mailto'] && $url !== '' && !\Illuminate\Support\Str::startsWith($url, 'mailto:')) {
                                            $url = 'mailto:' . $url;
                                        }
                                        if (!$isUsableUrl($url)) {
                                            continue;
                                        }

                                        $memberSocials[] = [
                                            'name' => $meta['name'],
                                            'icon' => $meta['icon'],
                                            'url' => $url,
                                            'external' => $isExternalUrl($url),
                                        ];
                                    }
                                }
                            }

                            $memberModalPayload = $member;
                            unset($memberModalPayload['bio']);
                            $memberModalPayload['image'] = \App\Support\PublicImage::url($member['image'] ?? null);
                            $memberModalPayload['bio_html'] = $sanitizeRichText($memberBio);
                            $memberModalPayload['socials'] = $memberSocials;
                        @endphp

                        <article class="group">
                            <button
                                type="button"
                                class="relative block aspect-[4/5] w-full overflow-hidden rounded-xl bg-slate-100 text-left"
                                @click="activeMember = @js($memberModalPayload)"
                                aria-label="Read profile for {{ $member['name'] }}"
                            >
                                <x-initials-image
                                    :src="$member['image'] ?? null"
                                    :title="$member['name']"
                                    imgClass="h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                    fallbackClass="bg-[#173b5f]"
                                    textClass="text-4xl font-black text-white"
                                />
                                <span class="absolute inset-0 bg-gradient-to-t from-[#07182b]/85 via-transparent to-transparent"></span>
                                <span class="absolute bottom-4 left-4 right-4 text-xs font-bold uppercase tracking-[0.12em] text-orange-300">
                                    View profile
                                </span>
                            </button>
                            <button type="button" class="mt-4 text-left text-xl font-black text-[#07182b] transition hover:text-[#d94e12]" @click="activeMember = @js($memberModalPayload)">
                                {{ $member['name'] }}
                            </button>
                            @if(!empty($member['position']))
                                <p class="mt-1 text-sm font-bold text-[#e95516]">{{ $member['position'] }}</p>
                            @endif
                            @if($memberBioPreview !== '')
                                <p class="mt-2 text-sm leading-6 text-slate-600">{{ $memberBioPreview }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>

            <template x-teleport="body">
                <div x-cloak x-show="activeMember" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" role="dialog" aria-modal="true">
                    <div class="absolute inset-0 bg-[#07182b]/85 backdrop-blur-sm" @click="activeMember = null"></div>
                    <div class="relative flex max-h-[88vh] w-full max-w-3xl flex-col overflow-hidden rounded-xl bg-white shadow-2xl">
                        <button
                            type="button"
                            class="absolute right-4 top-4 z-10 flex h-10 w-10 items-center justify-center rounded-lg bg-white text-[#07182b] shadow-lg transition hover:bg-slate-100"
                            @click="activeMember = null"
                            aria-label="Close profile"
                        >
                            <i class="fas fa-times" aria-hidden="true"></i>
                        </button>
                        <div class="grid min-h-0 flex-1 md:grid-cols-[0.85fr_1.15fr]">
                            <div class="relative min-h-64 bg-[#07182b]">
                                <img
                                    :src="activeMember && activeMember.image ? activeMember.image : '{{ asset('glowfm logo.jpeg') }}'"
                                    :alt="activeMember ? activeMember.name : 'Team member'"
                                    class="absolute inset-0 h-full w-full object-cover"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-[#07182b]/70 to-transparent"></div>
                            </div>
                            <div class="min-h-0 overflow-y-auto p-7 sm:p-9">
                                <p class="text-[11px] font-extrabold uppercase tracking-[0.18em] text-[#e95516]">Leadership</p>
                                <h3 class="mt-2 pr-10 text-3xl font-black tracking-[-0.03em] text-[#07182b]" x-text="activeMember ? activeMember.name : ''"></h3>
                                <p class="mt-1 font-bold text-[#e95516]" x-text="activeMember ? activeMember.position : ''"></p>
                                <div class="mt-6 text-sm leading-7 text-slate-600 [&_a]:font-bold [&_a]:text-[#d94e12] [&_li]:mb-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_ul]:list-disc [&_ul]:pl-5" x-html="activeMember ? activeMember.bio_html : ''"></div>
                                <div class="mt-7 flex items-center gap-2" x-show="activeMember && activeMember.socials && activeMember.socials.length">
                                    <template x-for="(social, index) in (activeMember && activeMember.socials ? activeMember.socials : [])" :key="index">
                                        <a
                                            :href="social.url"
                                            :target="social.external ? '_blank' : null"
                                            :rel="social.external ? 'noopener noreferrer' : null"
                                            :aria-label="social.name"
                                            class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-[#173b5f] transition hover:border-[#e95516] hover:text-[#e95516]"
                                        >
                                            <i :class="social.icon || 'fas fa-link'" aria-hidden="true"></i>
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </section>
    @endif

    @if($achievements->isNotEmpty() || $partners->isNotEmpty() || $displayStats->isNotEmpty())
        <section class="border-y border-slate-200 bg-[#f7f4ee] py-16 sm:py-20" aria-labelledby="recognition-heading">
            <div class="mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-[#e95516]">Station record</p>
                <h2 id="recognition-heading" class="mt-2 text-3xl font-black tracking-[-0.035em] text-[#07182b] sm:text-4xl">
                    {{ data_get($aboutContent, 'achievements_title') ?: 'Recognition and growth' }}
                </h2>
                @if(trim((string) data_get($aboutContent, 'achievements_subtitle', '')) !== '')
                    <div class="mt-3 max-w-2xl text-sm leading-6 text-slate-600">
                        {!! $sanitizeRichText(data_get($aboutContent, 'achievements_subtitle', '')) !!}
                    </div>
                @endif

                @if($achievements->isNotEmpty())
                    <div class="mt-8 divide-y divide-slate-200 border-y border-slate-200 bg-white">
                        @foreach($achievements as $achievement)
                            <article class="grid gap-3 p-5 sm:grid-cols-[100px_minmax(0,1fr)] sm:items-center sm:gap-6 sm:p-6">
                                <p class="text-xl font-black text-[#e95516]">{{ $achievement['year'] ?? '' }}</p>
                                <div>
                                    <h3 class="text-lg font-black text-[#07182b]">{{ $achievement['award'] }}</h3>
                                    @if(!empty($achievement['organization']))
                                        <p class="mt-1 text-sm text-slate-600">{{ $achievement['organization'] }}</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                @if($displayStats->isNotEmpty())
                    <div class="mt-6 grid divide-y divide-slate-200 border-y border-slate-200 sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                        @foreach($displayStats as $stat)
                            <div class="py-5 sm:px-6 sm:first:pl-0">
                                <p class="text-3xl font-black text-[#07182b]">{{ $stat['number'] }}</p>
                                <p class="mt-1 text-xs font-bold uppercase tracking-[0.12em] text-slate-500">{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if($partners->isNotEmpty())
                    <div class="mt-10">
                        @if(trim((string) data_get($aboutContent, 'partners_title', '')) !== '')
                            <h3 class="text-lg font-black text-[#07182b]">{{ data_get($aboutContent, 'partners_title') }}</h3>
                        @endif
                        @if(trim((string) data_get($aboutContent, 'partners_subtitle', '')) !== '')
                            <p class="mt-1 text-sm text-slate-600">{{ data_get($aboutContent, 'partners_subtitle') }}</p>
                        @endif
                        <div class="mt-5 flex flex-wrap gap-3">
                            @foreach($partners as $partner)
                                <div class="flex min-h-20 min-w-40 items-center justify-center rounded-lg border border-slate-200 bg-white p-4">
                                    @if(!empty($partner['logo']))
                                        <img src="{{ $partner['logo'] }}" alt="{{ $partner['name'] }}" class="max-h-10 max-w-36 object-contain">
                                    @else
                                        <span class="text-sm font-black text-[#173b5f]">{{ $partner['name'] }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @if(
        trim((string) data_get($aboutContent, 'cta_title', '')) !== ''
        || trim((string) data_get($aboutContent, 'cta_body', '')) !== ''
        || $hasPrimaryCta
        || $hasSecondaryCta
    )
        <section class="bg-[#e95516] py-12 text-white sm:py-14">
            <div class="mx-auto grid max-w-[1200px] gap-7 px-4 sm:px-6 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center lg:gap-12 lg:px-8">
                <div>
                    @if(trim((string) data_get($aboutContent, 'cta_title', '')) !== '')
                        <h2 class="text-3xl font-black tracking-[-0.035em] sm:text-4xl">{{ data_get($aboutContent, 'cta_title') }}</h2>
                    @endif
                    @if(trim((string) data_get($aboutContent, 'cta_body', '')) !== '')
                        <div class="mt-3 max-w-2xl text-sm leading-6 text-orange-50 [&_a]:font-bold [&_a]:text-white">
                            {!! $sanitizeRichText(data_get($aboutContent, 'cta_body', '')) !!}
                        </div>
                    @endif
                </div>
                @if($hasPrimaryCta || $hasSecondaryCta)
                    <div class="flex flex-col gap-3 sm:flex-row">
                        @if($hasPrimaryCta)
                            <a
                                href="{{ $primaryCtaUrl }}"
                                @if($isExternalUrl($primaryCtaUrl)) target="_blank" rel="noopener noreferrer" @endif
                                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg bg-[#07182b] px-6 py-3 text-sm font-extrabold text-white transition hover:bg-[#102b48]"
                            >
                                {{ data_get($aboutContent, 'cta_primary_text') }}
                                <i class="fas fa-arrow-right text-xs" aria-hidden="true"></i>
                            </a>
                        @endif
                        @if($hasSecondaryCta)
                            <a
                                href="{{ $secondaryCtaUrl }}"
                                @if($isExternalUrl($secondaryCtaUrl)) target="_blank" rel="noopener noreferrer" @endif
                                class="inline-flex min-h-12 items-center justify-center gap-2 rounded-lg border border-white/35 bg-white/10 px-6 py-3 text-sm font-extrabold text-white transition hover:bg-white/15"
                            >
                                {{ data_get($aboutContent, 'cta_secondary_text') }}
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </section>
    @endif
</div>
