<div>
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        @foreach($stats as $stat)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-{{ $stat['color'] }}-100">
                        <i class="{{ $stat['icon'] }} text-{{ $stat['color'] }}-600 text-xl"></i>
                    </div>
                    @if($stat['trend'] === 'up')
                        <span class="flex items-center text-sm font-medium text-emerald-600">
                            <i class="fas fa-arrow-up mr-1"></i>
                            {{ $stat['change'] }}
                        </span>
                    @elseif($stat['trend'] === 'down')
                        <span class="flex items-center text-sm font-medium text-red-600">
                            <i class="fas fa-arrow-down mr-1"></i>
                            {{ $stat['change'] }}
                        </span>
                    @else
                        <span class="flex items-center text-sm font-medium text-gray-500">
                            {{ $stat['change'] }}
                        </span>
                    @endif
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-1">{{ $stat['value'] }}</h3>
                <p class="text-sm text-gray-600">{{ $stat['title'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        
        <!-- Now Playing Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Now Playing</h3>
                <span class="px-3 py-1 {{ $nowPlaying['is_live'] ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-600' }} text-xs font-semibold rounded-full flex items-center">
                    <span class="w-2 h-2 {{ $nowPlaying['is_live'] ? 'bg-red-500 animate-pulse' : 'bg-gray-400' }} rounded-full mr-2"></span>
                    {{ $nowPlaying['is_live'] ? 'LIVE' : 'OFFLINE' }}
                </span>
            </div>
            
            <div class="flex flex-col lg:flex-row gap-6">
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg shadow-lg flex items-center justify-center">
                        <i class="fas fa-broadcast-tower text-white text-4xl"></i>
                    </div>
                    <p class="text-xs text-gray-500 mt-3">{{ $nowPlaying['status'] }}</p>
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $nowPlaying['title'] }}</h4>
                    <p class="text-lg text-gray-600 mb-3">{{ $nowPlaying['artist'] }}</p>

                    <div class="mb-4">
                        @if(!empty($nowPlaying['stream_url']))
                            <audio controls class="w-full">
                                <source src="{{ $nowPlaying['stream_url'] }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        @else
                            <div class="text-sm text-gray-500 bg-gray-50 border border-dashed border-gray-200 rounded-lg p-3">
                                Stream URL not set.
                            </div>
                        @endif
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-emerald-600 h-2 rounded-full" style="width: {{ $nowPlayingProgress }}%"></div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @forelse($showTimeline as $show)
                            <div class="p-3 rounded-lg border {{ $show['is_current'] ? 'border-emerald-300 bg-emerald-50' : 'border-gray-200 bg-white' }}">
                                <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500">{{ $show['label'] }}</p>
                                <p class="text-sm font-semibold text-gray-900 mt-1">{{ $show['title'] }}</p>
                                <p class="text-xs text-gray-600">{{ $show['host'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $show['time'] }}
                                    <span class="ml-1 text-[10px] font-semibold">WAT</span>
                                </p>
                            </div>
                        @empty
                            <div class="col-span-full text-sm text-gray-500">No show timeline available.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Show Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Current Show</h3>
            
            <div class="text-center mb-6">
                <div class="w-24 h-24 mx-auto mb-4 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full shadow-lg flex items-center justify-center">
                    <i class="fas fa-microphone-alt text-white text-3xl"></i>
                </div>
                <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $currentShow['title'] ?? 'Unknown' }}</h4>
                <p class="text-sm text-gray-600 mb-2">{{ $currentShow['host'] ?? 'Unknown' }}</p>
                <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                    <i class="fas fa-clock mr-1"></i>
                    {{ $currentShow['time'] ?? 'Unknown' }}
                    <span class="ml-2 text-[10px] font-semibold">WAT</span>
                </span>
            </div>

            <div class="space-y-2 border-t border-gray-200 pt-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Status</span>
                    <span class="font-semibold text-gray-900">{{ $currentShow['status'] ?? 'Unknown' }}</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Schedule</span>
                    <span class="font-semibold text-gray-900">{{ $currentDay ?: now()->format('l') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                <a href="{{ route('admin.comms.analytics') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                    View All
                </a>
            </div>

            <div class="space-y-4">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-colors duration-150">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center">
                                <i class="{{ $activity['icon'] }} text-{{ $activity['color'] }}-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $activity['title'] }}</p>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $activity['time'] }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent activity yet.</p>
                @endforelse
            </div>
        </div>

        <div class="space-y-6">
            <!-- Top News -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Top News</h3>
                    <a href="{{ route('admin.news.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                        View All
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($topItems as $index => $item)
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0 w-8 text-center">
                                <span class="text-lg font-bold text-gray-400">{{ $index + 1 }}</span>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg shadow-sm flex items-center justify-center">
                                    <i class="fas fa-newspaper text-white"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item['title'] }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $item['subtitle'] }}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="text-sm font-semibold text-emerald-600">{{ $item['metric'] }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No news data available.</p>
                    @endforelse
                </div>

                <a href="{{ route('admin.news.index') }}" class="w-full mt-6 py-2 px-4 bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 rounded-lg transition-colors duration-150 inline-flex items-center justify-center">
                    Manage News
                </a>
            </div>

            <!-- Today's Schedule -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Today's Schedule</h3>
                    <a href="{{ route('admin.shows.schedule') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                        View Schedule
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse($todaySchedule as $slot)
                        <div class="flex items-start justify-between gap-4 p-3 bg-gray-50 rounded-lg">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $slot['title'] ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $slot['host'] ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $slot['time'] ?? 'Unknown' }}
                                </p>
                            </div>
                            <span class="text-[11px] font-semibold px-2 py-1 rounded-full {{ ($slot['status'] ?? '') === 'On Air' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                {{ $slot['status'] ?? 'Unknown' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No programs scheduled for today.</p>
                    @endforelse
                </div>
            </div>

            <!-- Show Performance -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900">Show Performance</h3>
                    <a href="{{ route('admin.shows.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                        View Shows
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($recentReviews as $review)
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-semibold text-gray-900">{{ $review['show'] }}</p>
                                <div class="flex items-center space-x-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $review['rating'] >= $i ? 'text-emerald-500' : 'text-gray-300' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">{{ $review['user'] }} • {{ $review['time'] }}</p>
                            @if($review['review'])
                                <p class="text-sm text-gray-700 mt-2 line-clamp-2">{{ $review['review'] }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No reviews yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Shows -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Shows</h3>
            <a href="{{ route('admin.shows.schedule') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                View Schedule
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse($showTimeline as $show)
                <div class="p-4 border {{ $show['is_current'] ? 'border-emerald-300 bg-emerald-50' : 'border-gray-200' }} rounded-lg hover:border-emerald-300 hover:shadow-sm transition-all duration-150">
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-gray-500 mb-2">{{ $show['label'] }}</p>
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $show['title'] }}</h4>
                            <p class="text-xs text-gray-600">{{ $show['host'] }}</p>
                        </div>
                    </div>
                    <div class="flex items-center text-xs text-gray-600">
                        <i class="fas fa-clock mr-2"></i>
                        {{ $show['time'] }}
                        <span class="ml-1 text-[10px] font-semibold">WAT</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-sm text-gray-500">No show timeline available.</div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <a href="{{ route('admin.news.create') }}" class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-emerald-500 hover:shadow-md transition-all duration-150 group">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-emerald-600 transition-colors duration-150">
                    <i class="fas fa-plus text-emerald-600 text-xl group-hover:text-white"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Add News</span>
            </div>
        </a>

        <a href="{{ route('admin.shows.schedule') }}" class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:shadow-md transition-all duration-150 group">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-blue-600 transition-colors duration-150">
                    <i class="fas fa-calendar-plus text-blue-600 text-xl group-hover:text-white"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Schedule Show</span>
            </div>
        </a>

        <a href="{{ route('admin.events.create') }}" class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-amber-500 hover:shadow-md transition-all duration-150 group">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-amber-600 transition-colors duration-150">
                    <i class="fas fa-bullhorn text-amber-600 text-xl group-hover:text-white"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Add Event</span>
            </div>
        </a>

        <a href="{{ route('admin.comms.analytics') }}" class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-pink-500 hover:shadow-md transition-all duration-150 group">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-pink-600 transition-colors duration-150">
                    <i class="fas fa-chart-bar text-pink-600 text-xl group-hover:text-white"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Comms Analytics</span>
            </div>
        </a>
    </div>
</div>
