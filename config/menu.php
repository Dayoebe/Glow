<?php

return [
    [
        'group' => 'Main',
        'roles' => ['admin', 'staff'],
        'items' => [
            [
                'title' => 'Dashboard',
                'icon' => 'fas fa-home',
                'route' => 'dashboard',
                'active' => 'dashboard'
            ],
            [
                'title' => 'My Profile',
                'icon' => 'fas fa-user-circle',
                'route' => 'admin.profile',
                'active' => 'admin.profile'
            ],
            [
                'title' => 'Analytics',
                'icon' => 'fas fa-chart-line',
                'route' => 'admin.comms.analytics',
                'active' => 'admin.comms.analytics',
                'children' => [
                    [
                        'title' => 'Comms Analytics',
                        'route' => 'admin.comms.analytics',
                        'active' => 'admin.comms.analytics'
                    ],
                ]
            ],
        ]
    ],
    [
        'group' => 'Content Management',
        'roles' => ['admin', 'staff'],
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
                        'title' => 'Add Categories',
                        'route' => 'admin.news.categories',
                        'active' => 'admin.news.categories'
                    ],
                    [
                        'title' => 'News Analytics',
                        'route' => 'admin.news.analytics',
                        'active' => 'admin.news.analytics'
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
                        'title' => 'Analytics',
                        'route' => 'admin.blog.analytics',
                        'active' => 'admin.blog.analytics'
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
                        'route' => 'admin.podcasts.analytics',
                        'active' => 'admin.podcasts.analytics'
                    ],
                ]
            ],
            [
                'title' => 'Events',
                'icon' => 'fas fa-calendar-alt',
                'route' => 'admin.events.index',
                'active' => 'admin.events*',
                'children' => [
                    [
                        'title' => 'All Events',
                        'route' => 'admin.events.index',
                        'active' => 'admin.events.index'
                    ],
                    [
                        'title' => 'Create Event',
                        'route' => 'admin.events.create',
                        'active' => 'admin.events.create'
                    ],
                    [
                        'title' => 'Categories',
                        'route' => 'admin.events.categories',
                        'active' => 'admin.events.categories'
                    ],
                ]
            ],
        ]
    ],
    [
        'group' => 'Community',
        'roles' => ['admin', 'staff'],
        'items' => [
            [
                'title' => 'Listeners',
                'icon' => 'fas fa-users',
                'route' => 'admin.listeners.index',
                'active' => 'admin.listeners.*',
                'children' => [
                    [
                        'title' => 'All Listeners',
                        'route' => 'admin.listeners.index',
                        'active' => 'admin.listeners.index'
                    ],
                    [
                        'title' => 'Demographics',
                        'route' => 'admin.listeners.demographics',
                        'active' => 'admin.listeners.demographics'
                    ],
                    [
                        'title' => 'Feedback',
                        'route' => 'admin.listeners.feedback',
                        'active' => 'admin.listeners.feedback'
                    ],
                ]
            ],
            [
                'title' => 'Requests',
                'icon' => 'fas fa-headphones',
                'route' => 'admin.requests.songs',
                'active' => 'admin.requests.*',
                'children' => [
                    [
                        'title' => 'Song Requests',
                        'route' => 'admin.requests.songs',
                        'active' => 'admin.requests.songs'
                    ],
                    [
                        'title' => 'Dedications',
                        'route' => 'admin.requests.dedications',
                        'active' => 'admin.requests.dedications'
                    ],
                    [
                        'title' => 'Request Settings',
                        'route' => 'admin.requests.settings',
                        'active' => 'admin.requests.settings'
                    ],
                ]
            ],
            [
                'title' => 'Contests & Giveaways',
                'icon' => 'fas fa-gift',
                'route' => 'admin.contests.active',
                'active' => 'admin.contests.*',
                'children' => [
                    [
                        'title' => 'Active Contests',
                        'route' => 'admin.contests.active',
                        'active' => 'admin.contests.active'
                    ],
                    [
                        'title' => 'Past Contests',
                        'route' => 'admin.contests.past',
                        'active' => 'admin.contests.past'
                    ],
                    [
                        'title' => 'Winners',
                        'route' => 'admin.contests.winners',
                        'active' => 'admin.contests.winners'
                    ],
                ]
            ],
            [
                'title' => 'Messages',
                'icon' => 'fas fa-comments',
                'route' => 'admin.messages.inbox',
                'active' => 'admin.messages.*',
                'children' => [
                    [
                        'title' => 'Inbox',
                        'route' => 'admin.messages.inbox',
                        'active' => 'admin.messages.inbox'
                    ],
                    [
                        'title' => 'Text Line',
                        'route' => 'admin.messages.textline',
                        'active' => 'admin.messages.textline'
                    ],
                    [
                        'title' => 'Social Messages',
                        'route' => 'admin.messages.social',
                        'active' => 'admin.messages.social'
                    ],
                ]
            ],
        ]
    ],
    [
        'group' => 'Broadcasting',
        'roles' => ['admin'],
        'items' => [
            [
                'title' => 'Live Stream',
                'icon' => 'fas fa-broadcast-tower',
                'route' => 'admin.stream',
                'active' => 'admin.stream',
                'children' => [
                    [
                        'title' => 'Stream Status',
                        'route' => 'admin.stream',
                        'active' => 'admin.stream'
                    ],
                    [
                        'title' => 'Stream Settings',
                        'route' => 'admin.stream',
                        'active' => 'admin.stream'
                    ],
                ]
            ],
            [
                'title' => 'Shows & Programs',
                'icon' => 'fas fa-microphone',
                'route' => 'admin.shows.index',
                'active' => 'admin.shows*',
                'children' => [
                    [
                        'title' => 'All Shows',
                        'route' => 'admin.shows.index',
                        'active' => 'admin.shows.index'
                    ],
                    [
                        'title' => 'Schedule',
                        'route' => 'admin.shows.schedule',
                        'active' => 'admin.shows.schedule'
                    ],
                    [
                        'title' => 'OAPs',
                        'route' => 'admin.shows.oaps',
                        'active' => 'admin.shows.oaps'
                    ],
                    [
                        'title' => 'Segments',
                        'route' => 'admin.shows.segments',
                        'active' => 'admin.shows.segments'
                    ],
                    [
                        'title' => 'Reviews',
                        'route' => 'admin.shows.reviews',
                        'active' => 'admin.shows.reviews'
                    ],
                    [
                        'title' => 'Show Categories',
                        'route' => 'admin.shows.categories',
                        'active' => 'admin.shows.categories'
                    ],
                ]
            ],
        ]
    ],

    [
        'group' => 'Administrative Stuff',
        'roles' => ['admin'],
        'items' => [
            [
                'title' => 'Content Approvers',
                'icon' => 'fas fa-user-check',
                'route' => 'admin.approvals',
                'active' => 'admin.approvals'
            ],

            [
                'title' => 'Jingles & Ads',
                'icon' => 'fas fa-bullhorn',
                'route' => 'admin.ads.index',
                'active' => 'admin.ads.*',
                'children' => [
                    [
                        'title' => 'Station Jingles',
                        'route' => 'admin.ads.index',
                        'active' => 'admin.ads.index'
                    ],
                    [
                        'title' => 'Commercials',
                        'route' => 'admin.ads.index',
                        'active' => 'admin.ads.index'
                    ],
                    [
                        'title' => 'Ad Schedule',
                        'route' => 'admin.ads.index',
                        'active' => 'admin.ads.index'
                    ],
                ]
            ],
            [
                'title' => 'OAPs & Hosts',
                'icon' => 'fas fa-user-tie',
                'route' => 'admin.team.oaps',
                'active' => 'admin.team.oaps*',
                'children' => [
                    [
                        'title' => 'All OAPs',
                        'route' => 'admin.team.oaps',
                        'active' => 'admin.team.oaps'
                    ],
                    [
                        'title' => 'Add OAP',
                        'route' => 'admin.team.oaps.create',
                        'active' => 'admin.team.oaps.create'
                    ],
                    [
                        'title' => 'OAP Schedules',
                        'route' => 'admin.shows.schedule',
                        'active' => 'admin.shows.schedule'
                    ],
                ]
            ],
            [
                'title' => 'Staff',
                'icon' => 'fas fa-user-friends',
                'route' => 'admin.team.staff',
                'active' => 'admin.team.staff*',
                'children' => [
                    [
                        'title' => 'All Staff',
                        'route' => 'admin.team.staff',
                        'active' => 'admin.team.staff'
                    ],
                    [
                        'title' => 'Departments',
                        'route' => 'admin.team.departments',
                        'active' => 'admin.team.departments*'
                    ],
                    [
                        'title' => 'Roles & Permissions',
                        'route' => 'admin.team.roles',
                        'active' => 'admin.team.roles*'
                    ],
                ]
            ],
            [
                'title' => 'Users',
                'icon' => 'fas fa-user-shield',
                'route' => 'admin.users.index',
                'active' => 'admin.users*',
                'children' => [
                    [
                        'title' => 'All Users',
                        'route' => 'admin.users.index',
                        'active' => 'admin.users.index'
                    ],
                    [
                        'title' => 'Add User',
                        'route' => 'admin.users.create',
                        'active' => 'admin.users.create'
                    ],
                ]
            ],

            [
                'title' => 'Email Campaigns',
                'icon' => 'fas fa-envelope',
                'route' => 'admin.newsletter.subscribers',
                'active' => 'admin.newsletter.*',
                'children' => [
                    [
                        'title' => 'Subscribers',
                        'route' => 'admin.newsletter.subscribers',
                        'active' => 'admin.newsletter.subscribers'
                    ],

                ]
            ],
            [
                'title' => 'Station Settings',
                'icon' => 'fas fa-cog',
                'route' => 'admin.settings.station',
                'active' => 'admin.settings.station',
                'children' => [
                    [
                        'title' => 'General',
                        'route' => 'admin.settings.station',
                        'active' => 'admin.settings.station'
                    ],
                    [
                        'title' => 'Stream Configuration',
                        'route' => 'admin.settings.station',
                        'active' => 'admin.settings.station'
                    ],
                    [
                        'title' => 'Branding',
                        'route' => 'admin.settings.station',
                        'active' => 'admin.settings.station'
                    ],
                ]
            ],
            [
                'title' => 'Website',
                'icon' => 'fas fa-globe',
                'route' => 'admin.settings.website',
                'active' => 'admin.settings.website',
                'children' => [
                    [
                        'title' => 'Pages',
                        'route' => 'admin.settings.website',
                        'active' => 'admin.settings.website'
                    ],
                    [
                        'title' => 'Navigation',
                        'route' => 'admin.settings.website',
                        'active' => 'admin.settings.website'
                    ],
                    [
                        'title' => 'Theme Settings',
                        'route' => 'admin.settings.website',
                        'active' => 'admin.settings.website'
                    ],
                ]
            ],
            [
                'title' => 'System',
                'icon' => 'fas fa-server',
                'route' => 'admin.settings.system',
                'active' => 'admin.settings.system',
                'children' => [
                    [
                        'title' => 'System Info',
                        'route' => 'admin.settings.system',
                        'active' => 'admin.settings.system'
                    ],
                    [
                        'title' => 'Backups',
                        'route' => 'admin.settings.system',
                        'active' => 'admin.settings.system'
                    ],
                    [
                        'title' => 'Logs',
                        'route' => 'admin.settings.system',
                        'active' => 'admin.settings.system'
                    ],
                ]
            ],
        ]
    ],
];
