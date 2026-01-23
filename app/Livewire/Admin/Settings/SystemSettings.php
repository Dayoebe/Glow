<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use Livewire\Component;

class SystemSettings extends Component
{
    public $maintenance_mode = false;
    public $maintenance_message = '';
    public $support_email = '';
    public $analytics_id = '';
    public $timezone = '';

    public function mount()
    {
        $defaults = [
            'maintenance_mode' => false,
            'maintenance_message' => 'We are performing scheduled maintenance. Please check back shortly.',
            'support_email' => 'support@glowfm.com',
            'analytics_id' => '',
            'timezone' => config('app.timezone', 'UTC'),
        ];

        $settings = Setting::get('system', []);
        $data = array_replace_recursive($defaults, $settings);

        $this->maintenance_mode = (bool) $data['maintenance_mode'];
        $this->maintenance_message = $data['maintenance_message'];
        $this->support_email = $data['support_email'];
        $this->analytics_id = $data['analytics_id'];
        $this->timezone = $data['timezone'];
    }

    public function save()
    {
        Setting::set('system', [
            'maintenance_mode' => $this->maintenance_mode,
            'maintenance_message' => $this->maintenance_message,
            'support_email' => $this->support_email,
            'analytics_id' => $this->analytics_id,
            'timezone' => $this->timezone,
        ], 'system');

        session()->flash('success', 'System settings updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.settings.system-settings')
            ->layout('layouts.admin', ['header' => 'System Settings']);
    }
}
