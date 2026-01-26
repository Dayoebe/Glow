<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="text-sm text-gray-600">Analytics range</p>
                <p class="text-lg font-semibold text-gray-900">{{ $rangeLabel }}</p>
            </div>
            <div class="w-full md:w-64">
                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Timeframe</label>
                <select wire:model.live="range"
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="7">Last 7 days</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="all">All time</option>
                </select>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Posts</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['total_posts']) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-newspaper text-emerald-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Posts in {{ strtolower($rangeLabel) }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Published</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['published_posts']) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Approved and live</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Pending Review</p>
                    <p class="text-2xl font-bold text-amber-600">{{ number_format($stats['pending_posts']) }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-amber-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Awaiting approval</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Views</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_views']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-eye text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Views in range</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Unique Readers</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['unique_readers']) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-indigo-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Distinct visitors</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Reactions</p>
                    <p class="text-2xl font-bold text-pink-600">{{ number_format($stats['total_reactions']) }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-face-smile text-pink-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">All reactions</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Shares</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_shares']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-share-nodes text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Total shares</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Avg Posts/Day</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['avg_per_day'], 1) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-emerald-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Across the range</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm text-gray-600">Posts Today</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['posts_today']) }}</p>
                </div>
                <i class="fas fa-calendar-day text-emerald-600 text-xl"></i>
            </div>
            <p class="text-xs text-gray-500">Calendar day total</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm text-gray-600">Posts This Week</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['posts_week']) }}</p>
                </div>
                <i class="fas fa-calendar-week text-emerald-600 text-xl"></i>
            </div>
            <p class="text-xs text-gray-500">Week-to-date</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <p class="text-sm text-gray-600">Posts This Month</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['posts_month']) }}</p>
                </div>
                <i class="fas fa-calendar text-emerald-600 text-xl"></i>
            </div>
            <p class="text-xs text-gray-500">Month-to-date</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Posters</h3>
                <p class="text-sm text-gray-500">Highest volume in {{ strtolower($rangeLabel) }}</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($topPosters as $poster)
                    <div class="flex items-center justify-between p-6">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $poster->name }}</p>
                            <p class="text-xs text-gray-500">{{ $poster->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-emerald-600">{{ number_format($poster->total) }}</p>
                            <p class="text-xs text-gray-500">posts</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-sm text-gray-500">No staff posts in this range.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lowest Posters</h3>
                <p class="text-sm text-gray-500">Lowest output in {{ strtolower($rangeLabel) }}</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($worstPosters as $poster)
                    <div class="flex items-center justify-between p-6">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $poster->name }}</p>
                            <p class="text-xs text-gray-500">{{ $poster->email }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-amber-600">{{ number_format($poster->total) }}</p>
                            <p class="text-xs text-gray-500">posts</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-sm text-gray-500">No staff posts in this range.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Viewed Posts</h3>
                <p class="text-sm text-gray-500">Most viewed in {{ strtolower($rangeLabel) }}</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($topViewed as $post)
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center space-x-3">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-emerald-100 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-emerald-600"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $post->title }}</p>
                                <p class="text-xs text-gray-500">Slug: {{ $post->slug }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-emerald-600">{{ number_format($post->views) }}</p>
                            <p class="text-xs text-gray-500">views</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-sm text-gray-500">No view data yet.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lowest Viewed Posts</h3>
                <p class="text-sm text-gray-500">Least viewed in {{ strtolower($rangeLabel) }}</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($worstViewed as $post)
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center space-x-3">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center">
                                    <i class="fas fa-newspaper text-amber-600"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $post->title }}</p>
                                <p class="text-xs text-gray-500">Slug: {{ $post->slug }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-amber-600">{{ number_format($post->views) }}</p>
                            <p class="text-xs text-gray-500">views</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-sm text-gray-500">No view data yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Daily Post Counts</h3>
            <p class="text-sm text-gray-500">Most recent calendar days</p>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($dailyCounts as $day)
                <div class="flex items-center justify-between p-6">
                    <div class="text-sm text-gray-700">
                        {{ \Carbon\Carbon::parse($day['day'])->format('M d, Y') }}
                    </div>
                    <div class="text-lg font-semibold text-gray-900">{{ number_format($day['total']) }}</div>
                </div>
            @empty
                <div class="p-6 text-sm text-gray-500">No posting data available.</div>
            @endforelse
        </div>
    </div>
</div>
