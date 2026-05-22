<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use App\Support\Seo;
use Livewire\Component;

class StationSettings extends Component
{
    public $name = '';
    public $frequency = '';
    public $tagline = '';
    public $phone = '';
    public $email = '';
    public $address = '';
    public $stream_url = '';
    public $logo_url = '';
    public $favicon_url = '';

    public $socials = [
        'facebook' => '',
        'twitter' => '',
        'instagram' => '',
        'youtube' => '',
        'tiktok' => '',
        'linkedin' => '',
    ];

    public function mount()
    {
        $station = Seo::station();
        $defaults = [
            'name' => $station['name'],
            'frequency' => $station['frequency'],
            'tagline' => $station['tagline'],
            'phone' => $station['phone'],
            'email' => $station['email'],
            'address' => $station['address'],
            'stream_url' => $station['stream_url'],
            'logo_url' => '',
            'favicon_url' => '',
            'socials' => $this->socials,
        ];

        $settings = Setting::get('station', []);
        $data = array_replace_recursive($defaults, $settings);

        $this->name = $data['name'];
        $this->frequency = $data['frequency'];
        $this->tagline = $data['tagline'];
        $this->phone = $data['phone'];
        $this->email = $data['email'];
        $this->address = $data['address'];
        $this->stream_url = $data['stream_url'];
        $this->logo_url = $data['logo_url'];
        $this->favicon_url = $data['favicon_url'];
        $this->socials = $data['socials'] ?? $this->socials;
    }

    public function save()
    {
        Setting::set('station', [
            'name' => $this->name,
            'frequency' => $this->frequency,
            'tagline' => $this->tagline,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'stream_url' => $this->stream_url,
            'logo_url' => $this->logo_url,
            'favicon_url' => $this->favicon_url,
            'socials' => $this->socials,
        ], 'station');

        session()->flash('success', 'Station settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings.station-settings')
            ->layout('layouts.admin', ['header' => 'Station Settings']);
    }
}
