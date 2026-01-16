<div>
    <!-- Page Header -->
    <section
        class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0"
                style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');">
            </div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">{{ $aboutContent['header_title'] }}</h1>
                <p class="text-xl md:text-2xl text-emerald-100 leading-relaxed">
                    {{ $aboutContent['header_subtitle'] }}
                </p>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ $aboutContent['story_title'] }}</h2>
                    <div class="space-y-6 text-lg text-gray-700 leading-relaxed">
                        @foreach($aboutContent['story_paragraphs'] as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                    <div class="mt-8 flex flex-wrap gap-4">
                        @foreach($aboutContent['story_badges'] as $badge)
                            <div class="flex items-center space-x-3 bg-emerald-50 px-6 py-3 rounded-full">
                                <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                                <span class="font-semibold text-gray-900">{{ $badge }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="relative">
                    <div class="relative z-10">
                        <img src="https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&h=900&fit=crop"
                            alt="Radio Studio" class="rounded-2xl shadow-2xl">
                    </div>
                    <div class="absolute -bottom-6 -right-6 w-72 h-72 bg-emerald-100 rounded-2xl -z-10"></div>
                    <div class="absolute -top-6 -left-6 w-48 h-48 bg-emerald-600 rounded-full -z-10 opacity-20"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Vision Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Mission -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-emerald-600">
                    <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-bullseye text-3xl text-emerald-600"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ $aboutContent['mission_title'] }}</h3>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        {{ $aboutContent['mission_body'] }}
                    </p>
                </div>

                <!-- Vision -->
                <div class="bg-white rounded-2xl shadow-lg p-8 border-t-4 border-blue-600">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-eye text-3xl text-blue-600"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-4">{{ $aboutContent['vision_title'] }}</h3>
                    <p class="text-lg text-gray-700 leading-relaxed">
                        {{ $aboutContent['vision_body'] }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Core Values Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $aboutContent['values_title'] }}</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    {{ $aboutContent['values_subtitle'] }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($aboutContent['values'] as $value)
                    <div
                        class="bg-white rounded-2xl shadow-lg p-8 hover:shadow-xl transition-shadow duration-300 border-b-4 border-{{ $value['color'] }}-500">
                        <div
                            class="w-16 h-16 bg-{{ $value['color'] }}-100 rounded-2xl flex items-center justify-center mb-6">
                            <i class="{{ $value['icon'] }} text-3xl text-{{ $value['color'] }}-600"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ $value['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $value['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Timeline/Milestones Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $aboutContent['milestones_title'] }}</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    {{ $aboutContent['milestones_subtitle'] }}
                </p>
            </div>

            <div class="max-w-5xl mx-auto">
                @foreach($aboutContent['milestones'] as $index => $milestone)
                    <div class="relative pl-8 pb-12 border-l-4 border-emerald-600 {{ $loop->last ? 'pb-0' : '' }}">
                        <!-- Timeline Dot -->
                        <div
                            class="absolute -left-3 top-0 w-6 h-6 bg-emerald-600 rounded-full border-4 border-white shadow-lg">
                        </div>

                        <!-- Content -->
                        <div class="bg-white rounded-2xl shadow-lg p-8 ml-8 hover:shadow-xl transition-shadow duration-300">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                                <h3 class="text-3xl font-bold text-emerald-600 mb-2 md:mb-0">{{ $milestone['year'] }}</h3>
                                <span
                                    class="inline-block px-4 py-2 bg-emerald-100 text-emerald-700 font-semibold rounded-full text-sm">
                                    Milestone {{ $index + 1 }}
                                </span>
                            </div>
                            <h4 class="text-2xl font-bold text-gray-900 mb-3">{{ $milestone['title'] }}</h4>
                            <p class="text-gray-600 leading-relaxed">{{ $milestone['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Leadership Team Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $aboutContent['team_title'] }}</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    {{ $aboutContent['team_subtitle'] }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($aboutContent['team'] as $member)
                    <div
                        class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                        <div class="relative h-80 overflow-hidden">
                            <img src="{{ $member['image'] }}" alt="{{ $member['name'] }}"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="text-2xl font-bold text-white mb-1">{{ $member['name'] }}</h3>
                                <p class="text-emerald-300 font-semibold">{{ $member['position'] }}</p>
                            </div>
                        </div>
                        <div class="p-6">
                            <p class="text-gray-600 mb-4 leading-relaxed">{{ $member['bio'] }}</p>
                            <div class="flex items-center space-x-3">
                                <a href="{{ $member['social']['linkedin'] }}"
                                    class="w-10 h-10 bg-gray-100 hover:bg-emerald-600 text-gray-600 hover:text-white rounded-lg flex items-center justify-center transition-colors duration-300">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="{{ $member['social']['twitter'] }}"
                                    class="w-10 h-10 bg-gray-100 hover:bg-emerald-600 text-gray-600 hover:text-white rounded-lg flex items-center justify-center transition-colors duration-300">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="mailto:{{ $member['social']['email'] }}"
                                    class="w-10 h-10 bg-gray-100 hover:bg-emerald-600 text-gray-600 hover:text-white rounded-lg flex items-center justify-center transition-colors duration-300">
                                    <i class="fas fa-envelope"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section class="py-20 bg-emerald-600 text-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold mb-4">{{ $aboutContent['achievements_title'] }}</h2>
                <p class="text-xl text-emerald-100 max-w-2xl mx-auto">
                    {{ $aboutContent['achievements_subtitle'] }}
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($aboutContent['achievements'] as $achievement)
                    <div
                        class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center">
                                    <i class="{{ $achievement['icon'] }} text-3xl text-yellow-300"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-semibold text-emerald-200 mb-1">{{ $achievement['year'] }}</div>
                                <h3 class="text-xl font-bold mb-2">{{ $achievement['award'] }}</h3>
                                <p class="text-emerald-100 text-sm">{{ $achievement['organization'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Partners Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">{{ $aboutContent['partners_title'] }}</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    {{ $aboutContent['partners_subtitle'] }}
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
                @foreach($aboutContent['partners'] as $partner)
                    <div
                        class="bg-white rounded-xl shadow-md p-6 flex items-center justify-center hover:shadow-xl transition-shadow duration-300 group">
                        <img src="{{ $partner['logo'] }}" alt="{{ $partner['name'] }}"
                            class="w-full h-auto opacity-60 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <div
                    class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 rounded-3xl shadow-2xl p-12 text-white">
                    <div class="text-center mb-12">
                        <h2 class="text-4xl font-bold mb-4">{{ $aboutContent['stats_title'] }}</h2>
                        <p class="text-xl text-emerald-100">{{ $aboutContent['stats_subtitle'] }}</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        @foreach($aboutContent['stats'] as $stat)
                            <div class="text-center">
                                <div class="text-5xl font-bold mb-2">{{ $stat['number'] }}</div>
                                <div class="text-emerald-100">{{ $stat['label'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">{{ $aboutContent['cta_title'] }}</h2>
                <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                    {{ $aboutContent['cta_body'] }}
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="{{ $aboutContent['cta_primary_url'] }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-envelope"></i>
                        <span>{{ $aboutContent['cta_primary_text'] }}</span>
                    </a>
                    <a href="{{ $aboutContent['cta_secondary_url'] }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-8 py-4 bg-white hover:bg-gray-50 text-emerald-600 font-semibold rounded-full shadow-lg hover:shadow-xl border-2 border-emerald-600 transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-play-circle"></i>
                        <span>{{ $aboutContent['cta_secondary_text'] }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
