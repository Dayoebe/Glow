<?php

return [
    [
        'group' => 'Main',
        'items' => [
            [
                'title' => 'Dashboard',
                'icon' => 'fas fa-home',
                'route' => 'dashboard',
                'active' => 'dashboard'
            ],
            [
                'title' => 'Analytics',
                'icon' => 'fas fa-chart-line',
                'route' => '#',
                'active' => 'analytics*',
                'children' => [
                    [
                        'title' => 'Listener Statistics',
                        'route' => '#',
                        'active' => 'analytics.listeners'
                    ],
                    [
                        'title' => 'Show Performance',
                        'route' => '#',
                        'active' => 'analytics.shows'
                    ],
                    [
                        'title' => 'Content Reports',
                        'route' => '#',
                        'active' => 'analytics.content'
                    ],
                ]
            ],
        ]
    ],
    [
        'group' => 'Content Management',
        'items' => [
            [
                'title' => 'News & Updates',
                'icon' => 'fas fa-newspaper',
                'route' => 'admin.news.index',
                'active' => 'admin.news*',
                'children' => [
                    [
                        'title' => 'All News',
                        'route' => 'admin.news.index',
                        'active' => 'admin.news.index'
                    ],
                    [
                        'title' => 'Add News',
                        'route' => 'admin.news.create',
                        'active' => 'admin.news.create'
                    ],
                    [
                        'title' => 'Categories',
                        'route' => 'admin.news.categories',
                        'active' => 'admin.news.categories'
                    ],
                ]
            ],
            [
                'title' => 'Blog',
                'icon' => 'fas fa-blog',
                'route' => 'admin.blog.index',
                'active' => 'admin.blog*',
                'children' => [
                    [
                        'title' => 'All Posts',
                        'route' => 'admin.blog.index',
                        'active' => 'admin.blog.index'
                    ],
                    [
                        'title' => 'Add Post',
                        'route' => 'admin.blog.create',
                        'active' => 'admin.blog.create'
                    ],
                    [
                        'title' => 'Categories',
                        'route' => 'admin.blog.categories',
                        'active' => 'admin.blog.categories'
                    ],
                ]
            ],
           [
                'title' => 'Podcasts',
                'icon' => 'fas fa-podcast',
                'route' => 'admin.podcasts.manage',
                'active' => 'admin.podcasts*',
                'children' => [
                    [
                        'title' => 'All Shows',
                        'route' => 'admin.podcasts.manage',
                        'active' => 'admin.podcasts.manage'
                    ],
                    [
                        'title' => 'Episodes',
                        'route' => 'admin.podcasts.manage',
                        'active' => 'admin.podcasts.episodes'
                    ],
                    [
                        'title' => 'Analytics',
                        'route' => '#',
                        'active' => 'admin.podcasts.analytics'
                    ],
                ]
            ],
            [
                'title' => 'Events',
                'icon' => 'fas fa-calendar-alt',
                'route' => '#',
                'active' => 'events*',
                'children' => [
                    [
                        'title' => 'All Events',
                        'route' => '#',
                        'active' => 'events.index'
                    ],
                    [
                        'title' => 'Create Event',
                        'route' => '#',
                        'active' => 'events.create'
                    ],
                    [
                        'title' => 'Event Calendar',
                        'route' => '#',
                        'active' => 'events.calendar'
                    ],
                ]
            ],
        ]
    ],
    [
        'group' => 'Broadcasting',
        'items' => [
            [
                'title' => 'Live Stream',
                'icon' => 'fas fa-broadcast-tower',
                'route' => '#',
                'active' => 'stream*',
                'children' => [
                    [
                        'title' => 'Stream Status',
                        'route' => '#',
                        'active' => 'stream.status'
                    ],
                    [
                        'title' => 'Stream Settings',
                        'route' => '#',
                        'active' => 'stream.settings'
                    ],
                    [
                        'title' => 'Backup Streams',
                        'route' => '#',
                        'active' => 'stream.backup'
                    ],
                ]
            ],
            [
                'title' => 'Shows & Programs',
                'icon' => 'fas fa-microphone',
                'route' => '#',
                'active' => 'shows*',
                'children' => [
                    [
                        'title' => 'All Shows',
                        'route' => '#',
                        'active' => 'shows.index'
                    ],
                    [
                        'title' => 'Schedule',
                        'route' => '#',
                        'active' => 'shows.schedule'
                    ],
                    [
                        'title' => 'Show Categories',
                        'route' => '#',
                        'active' => 'shows.categories'
                    ],
                ]
            ],
            [
                'title' => 'Playlists',
                'icon' => 'fas fa-list-music',
                'route' => '#',
                'active' => 'playlists*',
                'children' => [
                    [
                        'title' => 'All Playlists',
                        'route' => '#',
                        'active' => 'playlists.index'
                    ],
                    [
                        'title' => 'Create Playlist',
                        'route' => '#',
                        'active' => 'playlists.create'
                    ],
                    [
                        'title' => 'Auto DJ Settings',
                        'route' => '#',
                        'active' => 'playlists.autodj'
                    ],
                ]
            ],
            [
                'title' => 'Music Library',
                'icon' => 'fas fa-compact-disc',
                'route' => '#',
                'active' => 'music*',
                'children' => [
                    [
                        'title' => 'All Tracks',
                        'route' => '#',
                        'active' => 'music.index'
                    ],
                    [
                        'title' => 'Upload Music',
                        'route' => '#',
                        'active' => 'music.upload'
                    ],
                    [
                        'title' => 'Genres',
                        'route' => '#',
                        'active' => 'music.genres'
                    ],
                    [
                        'title' => 'Artists',
                        'route' => '#',
                        'active' => 'music.artists'
                    ],
                ]
            ],
            [
                'title' => 'Jingles & Ads',
                'icon' => 'fas fa-bullhorn',
                'route' => '#',
                'active' => 'jingles*',
                'children' => [
                    [
                        'title' => 'Station Jingles',
                        'route' => '#',
                        'active' => 'jingles.index'
                    ],
                    [
                        'title' => 'Commercials',
                        'route' => '#',
                        'active' => 'jingles.commercials'
                    ],
                    [
                        'title' => 'Ad Schedule',
                        'route' => '#',
                        'active' => 'jingles.schedule'
                    ],
                ]
            ],
        ]
    ],
    [
        'group' => 'Community',
        'items' => [
            [
                'title' => 'Listeners',
                'icon' => 'fas fa-users',
                'route' => '#',
                'active' => 'listeners*',
                'children' => [
                    [
                        'title' => 'All Listeners',
                        'route' => '#',
                        'active' => 'listeners.index'
                    ],
                    [
                        'title' => 'Demographics',
                        'route' => '#',
                        'active' => 'listeners.demographics'
                    ],
                    [
                        'title' => 'Feedback',
                        'route' => '#',
                        'active' => 'listeners.feedback'
                    ],
                ]
            ],
            [
                'title' => 'Requests',
                'icon' => 'fas fa-headphones',
                'route' => '#',
                'active' => 'requests*',
                'children' => [
                    [
                        'title' => 'Song Requests',
                        'route' => '#',
                        'active' => 'requests.songs'
                    ],
                    [
                        'title' => 'Dedications',
                        'route' => '#',
                        'active' => 'requests.dedications'
                    ],
                    [
                        'title' => 'Request Settings',
                        'route' => '#',
                        'active' => 'requests.settings'
                    ],
                ]
            ],
            [
                'title' => 'Contests & Giveaways',
                'icon' => 'fas fa-gift',
                'route' => '#',
                'active' => 'contests*',
                'children' => [
                    [
                        'title' => 'Active Contests',
                        'route' => '#',
                        'active' => 'contests.active'
                    ],
                    [
                        'title' => 'Past Contests',
                        'route' => '#',
                        'active' => 'contests.past'
                    ],
                    [
                        'title' => 'Winners',
                        'route' => '#',
                        'active' => 'contests.winners'
                    ],
                ]
            ],
            [
                'title' => 'Messages',
                'icon' => 'fas fa-comments',
                'route' => '#',
                'active' => 'messages*',
                'children' => [
                    [
                        'title' => 'Inbox',
                        'route' => '#',
                        'active' => 'messages.inbox'
                    ],
                    [
                        'title' => 'Text Line',
                        'route' => '#',
                        'active' => 'messages.textline'
                    ],
                    [
                        'title' => 'Social Messages',
                        'route' => '#',
                        'active' => 'messages.social'
                    ],
                ]
            ],
        ]
    ],
    [
        'group' => 'Team Management',
        'items' => [
            [
                'title' => 'DJs & Hosts',
                'icon' => 'fas fa-user-tie',
                'route' => '#',
                'active' => 'djs*',
                'children' => [
                    [
                        'title' => 'All DJs',
                        'route' => '#',
                        'active' => 'djs.index'
                    ],
                    [
                        'title' => 'Add DJ',
                        'route' => '#',
                        'active' => 'djs.create'
                    ],
                    [
                        'title' => 'DJ Schedules',
                        'route' => '#',
                        'active' => 'djs.schedules'
                    ],
                ]
            ],
            [
                'title' => 'Staff',
                'icon' => 'fas fa-user-friends',
                'route' => '#',
                'active' => 'staff*',
                'children' => [
                    [
                        'title' => 'All Staff',
                        'route' => '#',
                        'active' => 'staff.index'
                    ],
                    [
                        'title' => 'Departments',
                        'route' => '#',
                        'active' => 'staff.departments'
                    ],
                    [
                        'title' => 'Roles & Permissions',
                        'route' => '#',
                        'active' => 'staff.roles'
                    ],
                ]
            ],
        ]
    ],
    [
        'group' => 'Marketing',
        'items' => [
            [
                'title' => 'Advertisers',
                'icon' => 'fas fa-handshake',
                'route' => '#',
                'active' => 'advertisers*',
                'children' => [
                    [
                        'title' => 'All Advertisers',
                        'route' => '#',
                        'active' => 'advertisers.index'
                    ],
                    [
                        'title' => 'Campaigns',
                        'route' => '#',
                        'active' => 'advertisers.campaigns'
                    ],
                    [
                        'title' => 'Invoices',
                        'route' => '#',
                        'active' => 'advertisers.invoices'
                    ],
                ]
            ],
            [
                'title' => 'Social Media',
                'icon' => 'fas fa-share-alt',
                'route' => '#',
                'active' => 'social*',
                'children' => [
                    [
                        'title' => 'Posts',
                        'route' => '#',
                        'active' => 'social.posts'
                    ],
                    [
                        'title' => 'Schedule',
                        'route' => '#',
                        'active' => 'social.schedule'
                    ],
                    [
                        'title' => 'Connected Accounts',
                        'route' => '#',
                        'active' => 'social.accounts'
                    ],
                ]
            ],
            [
                'title' => 'Email Campaigns',
                'icon' => 'fas fa-envelope',
                'route' => '#',
                'active' => 'email*',
                'children' => [
                    [
                        'title' => 'Campaigns',
                        'route' => '#',
                        'active' => 'email.campaigns'
                    ],
                    [
                        'title' => 'Subscribers',
                        'route' => '#',
                        'active' => 'email.subscribers'
                    ],
                    [
                        'title' => 'Templates',
                        'route' => '#',
                        'active' => 'email.templates'
                    ],
                ]
            ],
        ]
    ],
    [
        'group' => 'Settings',
        'items' => [
            [
                'title' => 'Station Settings',
                'icon' => 'fas fa-cog',
                'route' => '#',
                'active' => 'settings*',
                'children' => [
                    [
                        'title' => 'General',
                        'route' => '#',
                        'active' => 'settings.general'
                    ],
                    [
                        'title' => 'Stream Configuration',
                        'route' => '#',
                        'active' => 'settings.stream'
                    ],
                    [
                        'title' => 'Branding',
                        'route' => '#',
                        'active' => 'settings.branding'
                    ],
                ]
            ],
            [
                'title' => 'Website',
                'icon' => 'fas fa-globe',
                'route' => '#',
                'active' => 'website*',
                'children' => [
                    [
                        'title' => 'Pages',
                        'route' => '#',
                        'active' => 'website.pages'
                    ],
                    [
                        'title' => 'Navigation',
                        'route' => '#',
                        'active' => 'website.navigation'
                    ],
                    [
                        'title' => 'Theme Settings',
                        'route' => '#',
                        'active' => 'website.theme'
                    ],
                ]
            ],
            [
                'title' => 'System',
                'icon' => 'fas fa-server',
                'route' => '#',
                'active' => 'system*',
                'children' => [
                    [
                        'title' => 'System Info',
                        'route' => '#',
                        'active' => 'system.info'
                    ],
                    [
                        'title' => 'Backups',
                        'route' => '#',
                        'active' => 'system.backups'
                    ],
                    [
                        'title' => 'Logs',
                        'route' => '#',
                        'active' => 'system.logs'
                    ],
                ]
            ],
        ]
    ],
];