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
                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full flex items-center">
                    <span class="w-2 h-2 bg-red-500 rounded-full mr-2 animate-pulse"></span>
                    LIVE
                </span>
            </div>
            
            <div class="flex items-center space-x-6">
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg shadow-lg flex items-center justify-center">
                        <i class="fas fa-play text-white text-4xl"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="text-xl font-bold text-gray-900 mb-1">Evening News</h4>
                    <p class="text-lg text-gray-600 mb-4">Nancy Chidinma</p>
                    
                    <!-- Progress Bar -->
                    <div class="mb-3">
                 
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-emerald-600 h-2 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <button class="flex items-center justify-center w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors duration-150">
                            <i class="fas fa-backward text-gray-700"></i>
                        </button>
                        <button class="flex items-center justify-center w-12 h-12 bg-emerald-600 hover:bg-emerald-700 rounded-full shadow-lg transition-colors duration-150">
                            <i class="fas fa-pause text-white"></i>
                        </button>
                        <button class="flex items-center justify-center w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors duration-150">
                            <i class="fas fa-forward text-gray-700"></i>
                        </button>
                        <button class="flex items-center justify-center w-10 h-10 bg-gray-100 hover:bg-gray-200 rounded-full transition-colors duration-150 ml-auto">
                            <i class="fas fa-ellipsis-v text-gray-700"></i>
                        </button>
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
                <h4 class="text-xl font-bold text-gray-900 mb-1">Morning Vibes</h4>
                <p class="text-sm text-gray-600 mb-2">with MC Olumiko</p>
                <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-semibold rounded-full">
                    <i class="fas fa-clock mr-1"></i>
                    6:00 AM - 10:00 AM
                </span>
            </div>

            <div class="space-y-2 border-t border-gray-200 pt-4">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Time Remaining</span>
                    <span class="font-semibold text-gray-900">2h 35m</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Songs Played</span>
                    <span class="font-semibold text-gray-900">28</span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600">Requests</span>
                    <span class="font-semibold text-gray-900">12</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                    View All
                </a>
            </div>

            <div class="space-y-4">
                @foreach($recentActivities as $activity)
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
                @endforeach
            </div>
        </div>

        <!-- Top Songs Today -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Top Songs Today</h3>
                <button class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
            </div>

            <div class="space-y-4">
                @foreach($topSongs as $index => $song)
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 w-8 text-center">
                            <span class="text-lg font-bold text-gray-400">{{ $index + 1 }}</span>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-lg shadow-sm flex items-center justify-center">
                                <i class="fas fa-music text-white"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $song['title'] }}</p>
                            <p class="text-xs text-gray-600 truncate">{{ $song['artist'] }}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="text-sm font-semibold text-emerald-600">{{ $song['plays'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <button class="w-full mt-6 py-2 px-4 bg-gray-50 hover:bg-gray-100 text-sm font-medium text-gray-700 rounded-lg transition-colors duration-150">
                View Full Chart
            </button>
        </div>
    </div>

    <!-- Upcoming Shows -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mt-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Upcoming Shows</h3>
            <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                View Schedule
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($upcomingShows as $show)
                <div class="p-4 border border-gray-200 rounded-lg hover:border-emerald-300 hover:shadow-sm transition-all duration-150">
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
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
        <button class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-emerald-500 hover:shadow-md transition-all duration-150 group">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-emerald-600 transition-colors duration-150">
                    <i class="fas fa-plus text-emerald-600 text-xl group-hover:text-white"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Add Song</span>
            </div>
        </button>

        <button class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-blue-500 hover:shadow-md transition-all duration-150 group">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-blue-600 transition-colors duration-150">
                    <i class="fas fa-calendar-plus text-blue-600 text-xl group-hover:text-white"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">Schedule Show</span>
            </div>
        </button>

        <button class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-amber-500 hover:shadow-md transition-all duration-150 group">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-amber-600 transition-colors duration-150">
                    <i class="fas fa-bullhorn text-amber-600 text-xl group-hover:text-white"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">New Announcement</span>
            </div>
        </button>

        <button class="p-4 bg-white border-2 border-gray-200 rounded-xl hover:border-pink-500 hover:shadow-md transition-all duration-150 group">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-pink-600 transition-colors duration-150">
                    <i class="fas fa-chart-bar text-pink-600 text-xl group-hover:text-white"></i>
                </div>
                <span class="text-sm font-medium text-gray-900">View Reports</span>
            </div>
        </button>
    </div>
</div>