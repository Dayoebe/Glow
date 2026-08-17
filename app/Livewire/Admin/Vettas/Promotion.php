<?php

namespace App\Livewire\Admin\Vettas;

use App\Models\Setting;
use App\Support\VettasPageSettings;
use Livewire\Component;

class Promotion extends Component
{
    public array $promotion = [];

    public function mount(): void
    {
        $this->promotion = array_replace_recursive(VettasPageSettings::defaults(), Setting::get('vettas', []))['promotion'];
    }

    public function save(): void
    {
        $validated = $this->validate([
            'promotion.headline' => 'required|string|max:160',
            'promotion.short_caption' => 'required|string|max:500',
            'promotion.long_caption' => 'required|string|max:1500',
            'promotion.hashtags' => 'nullable|string|max:500',
        ]);
        $settings = Setting::get('vettas', []);
        $settings['promotion'] = collect($validated['promotion'])->map(fn ($value) => trim($value))->all();
        Setting::set('vettas', $settings, 'pages');
        $this->promotion = $settings['promotion'];
        session()->flash('success', 'Promotion toolkit updated.');
    }

    public function render()
    {
        return view('livewire.admin.vettas.promotion')->layout('layouts.admin', ['header' => 'Vettas Promotion Toolkit']);
    }
}
