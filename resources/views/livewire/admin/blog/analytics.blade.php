<div>
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
                        class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
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
                        <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_posts']) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-blog text-purple-600 text-xl"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-4">Posts in {{ strtolower($rangeLabel) }}</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Published</p>
                        <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['published_posts']) }}
                        </p>
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
                        <p class="text-sm text-gray-600 mb-1">Avg Posts/Day</p>
                        <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['avg_per_day'], 1) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-indigo-600 text-xl"></i>
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
                    <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
                </div>
                <p class="text-xs text-gray-500">Calendar day total</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm text-gray-600">Posts This Week</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['posts_week']) }}</p>
                    </div>
                    <i class="fas fa-calendar-week text-purple-600 text-xl"></i>
                </div>
                <p class="text-xs text-gray-500">Week-to-date</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-sm text-gray-600">Posts This Month</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['posts_month']) }}</p>
                    </div>
                    <i class="fas fa-calendar text-purple-600 text-xl"></i>
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
                                <p class="text-lg font-semibold text-purple-600">{{ number_format($poster->total) }}</p>
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
                    <h3 class="text-lg font-semibold text-gray-900">Lowest Viewed Posts</h3>
                    <p class="text-sm text-gray-500">Least viewed in {{ strtolower($rangeLabel) }}</p>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($worstViewedPosts as $post)
                        <div class="flex items-center justify-between p-6">
                            <div class="flex items-center space-x-4">
                                @if($post->featured_image)
                                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                        class="w-14 h-14 rounded-lg object-cover">
                                @else
                                    <div class="w-14 h-14 rounded-lg bg-amber-100 flex items-center justify-center">
                                        <i class="fas fa-blog text-amber-600"></i>
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
                        <div class="p-6 text-sm text-gray-500">No view data yet for this range.</div>
                    @endforelse
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Views</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['total_views']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-eye text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Article views recorded</p>
        </div>

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
            <p class="text-xs text-gray-500 mt-4">Distinct visitor sessions</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Reactions</p>
                    <p class="text-2xl font-bold text-pink-600">{{ number_format($stats['total_reactions']) }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-heart text-pink-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Total emoji reactions</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Approved Comments</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ number_format($stats['total_comments']) }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comment-dots text-emerald-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Comments approved in range</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Posts</h3>
                <p class="text-sm text-gray-500">Most viewed posts in {{ strtolower($rangeLabel) }}</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($topPosts as $post)
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center space-x-4">
                            @if($post->featured_image)
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                    class="w-14 h-14 rounded-lg object-cover">
                            @else
                                <div class="w-14 h-14 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-blog text-purple-600"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $post->title }}</p>
                                <p class="text-xs text-gray-500">Slug: {{ $post->slug }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-purple-600">{{ number_format($post->views) }}</p>
                            <p class="text-xs text-gray-500">views</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-sm text-gray-500">No view data yet for this range.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Categories</h3>
                <p class="text-sm text-gray-500">Most viewed categories in {{ strtolower($rangeLabel) }}</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($topCategories as $category)
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-12 h-12 rounded-lg bg-{{ $category->color }}-100 flex items-center justify-center">
                                <i class="fas fa-layer-group text-{{ $category->color }}-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $category->name }}</p>
                                <p class="text-xs text-gray-500">Views from category</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-purple-600">{{ number_format($category->views) }}</p>
                            <p class="text-xs text-gray-500">views</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-sm text-gray-500">No category data yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Engagement Mix</h3>
                <p class="text-sm text-gray-500">Interactions by type</p>
            </div>
            <div class="p-6 space-y-4">
                @php
                    $typeMeta = [
                        'view' => ['label' => 'Views', 'icon' => 'fa-eye', 'color' => 'emerald'],
                        'reaction' => ['label' => 'Reactions', 'icon' => 'fa-heart', 'color' => 'pink'],
                        'share' => ['label' => 'Shares', 'icon' => 'fa-share', 'color' => 'purple'],
                        'bookmark' => ['label' => 'Bookmarks', 'icon' => 'fa-bookmark', 'color' => 'blue'],
                    ];
                @endphp
                @forelse($engagementMix as $row)
                    @php
                        $meta = $typeMeta[$row->type] ?? ['label' => ucfirst($row->type), 'icon' => 'fa-chart-bar', 'color' => 'gray'];
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i class="fas {{ $meta['icon'] }} text-{{ $meta['color'] }}-600"></i>
                            <span>{{ $meta['label'] }}</span>
                        </div>
                        <div class="text-sm font-semibold text-gray-900">{{ number_format($row->total) }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No engagement data available.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Reaction Breakdown</h3>
                <p class="text-sm text-gray-500">Top reaction types</p>
            </div>
            <div class="p-6 space-y-4">
                @php
                    $reactionMeta = [
                        'love' => ['label' => 'Love', 'icon' => 'fa-heart', 'color' => 'pink'],
                        'insightful' => ['label' => 'Insightful', 'icon' => 'fa-lightbulb', 'color' => 'amber'],
                        'fire' => ['label' => 'Fire', 'icon' => 'fa-fire', 'color' => 'red'],
                        'clap' => ['label' => 'Clap', 'icon' => 'fa-hands-clapping', 'color' => 'emerald'],
                    ];
                @endphp
                @forelse($reactionBreakdown as $row)
                    @php
                        $meta = $reactionMeta[$row->value] ?? ['label' => ucfirst($row->value ?? 'Other'), 'icon' => 'fa-face-smile', 'color' => 'gray'];
                    @endphp
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-sm font-medium text-gray-700">
                            <i class="fas {{ $meta['icon'] }} text-{{ $meta['color'] }}-600"></i>
                            <span>{{ $meta['label'] }}</span>
                        </div>
                        <div class="text-sm font-semibold text-gray-900">{{ number_format($row->total) }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No reactions recorded.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Comments</h3>
                <p class="text-sm text-gray-500">Latest 10 comments</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentComments as $comment)
                    <div class="p-4">
                        <p class="text-sm font-semibold text-gray-900">{{ $comment->post_title }}</p>
                        <p class="text-xs text-gray-500">{{ \Illuminate\Support\Str::limit($comment->comment, 80) }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                            <span>{{ \Illuminate\Support\Carbon::parse($comment->created_at)->format('M d, Y g:i A') }}</span>
                            <span class="{{ $comment->is_approved ? 'text-emerald-600' : 'text-amber-600' }}">
                                {{ $comment->is_approved ? 'Approved' : 'Pending' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-sm text-gray-500">No recent comments yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>