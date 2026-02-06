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

    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Plays</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ number_format($stats['total_plays']) }}</p>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-headphones text-indigo-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Listen time: {{ $stats['listen_hours'] }} hrs</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Raw Plays</p>
                    <p class="text-2xl font-bold text-slate-700">{{ number_format($stats['raw_plays']) }}</p>
                </div>
                <div class="w-12 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-layer-group text-slate-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">All-time play starts</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Unique Sessions</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['unique_listeners']) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Plays with tracked sessions</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Average Completion</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['avg_completion'] }}%</p>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-emerald-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Average completion rate</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Downloads</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($stats['total_downloads']) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-download text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-4">Offline downloads tracked</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Episodes</h3>
                <p class="text-sm text-gray-500">Most played episodes in {{ strtolower($rangeLabel) }}</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($topEpisodes as $episode)
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center space-x-4">
                            @if($episode->cover_image)
                                <img src="{{ $episode->cover_image }}" alt="{{ $episode->title }}" class="w-14 h-14 rounded-lg object-cover">
                            @else
                                <div class="w-14 h-14 rounded-lg bg-purple-100 flex items-center justify-center">
                                    <i class="fas fa-microphone text-purple-600"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $episode->title }}</p>
                                <p class="text-xs text-gray-500">Avg completion: {{ round($episode->avg_completion ?? 0, 1) }}%</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-indigo-600">{{ number_format($episode->plays) }}</p>
                            <p class="text-xs text-gray-500">plays</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-sm text-gray-500">No play data yet for this range.</div>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Shows</h3>
                <p class="text-sm text-gray-500">Most played shows in {{ strtolower($rangeLabel) }}</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($topShows as $show)
                    <div class="flex items-center justify-between p-6">
                        <div class="flex items-center space-x-3">
                            @if($show->cover_image)
                                <img src="{{ $show->cover_image }}" alt="{{ $show->title }}" class="w-12 h-12 rounded-lg object-cover">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center">
                                    <i class="fas fa-podcast text-indigo-600"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $show->title }}</p>
                                <p class="text-xs text-gray-500">{{ $show->episode_count }} episodes</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold text-indigo-600">{{ number_format($show->plays) }}</p>
                            <p class="text-xs text-gray-500">plays</p>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-sm text-gray-500">No show analytics yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Device Mix</h3>
                <p class="text-sm text-gray-500">Plays by device type</p>
            </div>
            <div class="p-6 space-y-4">
                @forelse($deviceBreakdown as $row)
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-700">{{ $row->device_type ?: 'Unknown' }}</div>
                        <div class="text-sm font-semibold text-gray-900">{{ number_format($row->total) }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No device data available.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Platform Mix</h3>
                <p class="text-sm text-gray-500">Plays by platform</p>
            </div>
            <div class="p-6 space-y-4">
                @forelse($platformBreakdown as $row)
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-medium text-gray-700">{{ $row->platform ?: 'Unknown' }}</div>
                        <div class="text-sm font-semibold text-gray-900">{{ number_format($row->total) }}</div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No platform data available.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Plays</h3>
                <p class="text-sm text-gray-500">Latest 10 plays</p>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentPlays as $play)
                    <div class="p-4">
                        <p class="text-sm font-semibold text-gray-900">{{ $play->episode_title }}</p>
                        <p class="text-xs text-gray-500">{{ $play->show_title }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500 mt-2">
                            <span>{{ \Illuminate\Support\Carbon::parse($play->started_at)->format('M d, Y g:i A') }}</span>
                            <span>{{ round($play->completion_rate ?? 0, 1) }}% completion</span>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-sm text-gray-500">No recent play activity yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
