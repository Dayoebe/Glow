<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public $stats = [];
    public $recentActivities = [];
    public $topSongs = [];
    public $upcomingShows = [];

    public function mount()
    {
        // Initialize dashboard data (these will be replaced with real data later)
        $this->stats = [
            [
                'title' => 'Current Listeners',
                'value' => '2,847',
                'change' => '+12.5%',
                'trend' => 'up',
                'icon' => 'fas fa-users',
                'color' => 'emerald'
            ],
            [
                'title' => 'Today\'s Requests',
                'value' => '156',
                'change' => '+8.2%',
                'trend' => 'up',
                'icon' => 'fas fa-headphones',
                'color' => 'blue'
            ],
            [
                'title' => 'Active Shows',
                'value' => '24',
                'change' => '0%',
                'trend' => 'neutral',
                'icon' => 'fas fa-microphone',
                'color' => 'amber'
            ],
            [
                'title' => 'Songs Played',
                'value' => '342',
                'change' => '+5.4%',
                'trend' => 'up',
                'icon' => 'fas fa-music',
                'color' => 'pink'
            ],
        ];

        $this->recentActivities = [
            [
                'type' => 'song',
                'title' => 'New song added to playlist',
                'description' => '"Blinding Lights" by The Weeknd',
                'time' => '2 minutes ago',
                'icon' => 'fas fa-compact-disc',
                'color' => 'emerald'
            ],
            [
                'type' => 'request',
                'title' => 'Song request received',
                'description' => 'Listener requested "Levitating"',
                'time' => '5 minutes ago',
                'icon' => 'fas fa-music',
                'color' => 'blue'
            ],
            [
                'type' => 'message',
                'title' => 'New listener message',
                'description' => 'John: "Love the morning show!"',
                'time' => '12 minutes ago',
                'icon' => 'fas fa-comment',
                'color' => 'violet'
            ],
            [
                'type' => 'show',
                'title' => 'Show started',
                'description' => 'MC Olumiko - Morning Vibes',
                'time' => '1 hour ago',
                'icon' => 'fas fa-broadcast-tower',
                'color' => 'amber'
            ],
        ];

        $this->topSongs = [
            ['title' => 'Blinding Lights', 'artist' => 'The Weeknd', 'plays' => 45],
            ['title' => 'Levitating', 'artist' => 'Dua Lipa', 'plays' => 38],
            ['title' => 'Save Your Tears', 'artist' => 'The Weeknd', 'plays' => 35],
            ['title' => 'Peaches', 'artist' => 'Justin Bieber', 'plays' => 32],
            ['title' => 'Good 4 U', 'artist' => 'Olivia Rodrigo', 'plays' => 29],
        ];

        $this->upcomingShows = [
            [
                'title' => 'Afternoon Drive',
                'host' => 'DJ Sarah',
                'time' => '2:00 PM - 6:00 PM',
                'status' => 'upcoming'
            ],
            [
                'title' => 'Evening Classics',
                'host' => 'DJ Robert',
                'time' => '6:00 PM - 10:00 PM',
                'status' => 'upcoming'
            ],
            [
                'title' => 'Night Grooves',
                'host' => 'DJ Alex',
                'time' => '10:00 PM - 2:00 AM',
                'status' => 'upcoming'
            ],
        ];
    }

    public function render()
    {
        return view('livewire.dashboard')->layout('layouts.admin', [
            'header' => 'Dashboard Overview'
        ]);
    }
}