<?php

namespace App\Livewire\Admin\Vettas;

use App\Models\Setting as SettingModel;
use App\Support\VettasPageSettings;
use Livewire\Component;

class Settings extends Component
{
    public array $about = [];
    public array $contact = [];

    public function mount(): void
    {
        $settings = SettingModel::get('vettas', []);
        $data = array_replace_recursive(VettasPageSettings::defaults(), $settings);

        $this->about = $data['about'];
        $this->contact = $data['contact'];
    }

    protected function rules(): array
    {
        return [
            'about.eyebrow' => 'nullable|string|max:80',
            'about.title' => 'nullable|string|max:150',
            'about.summary' => 'nullable|string|max:1000',
            'about.highlights' => 'array',
            'about.highlights.*' => 'nullable|string|max:120',
            'contact.title' => 'nullable|string|max:150',
            'contact.intro' => 'nullable|string|max:500',
            'contact.phone' => 'nullable|string|max:60',
            'contact.whatsapp' => 'nullable|string|max:60',
            'contact.email' => 'nullable|email|max:150',
            'contact.address' => 'nullable|string|max:255',
            'contact.hours' => 'nullable|string|max:150',
            'contact.booking_note' => 'nullable|string|max:500',
            'contact.instagram' => 'nullable|string|max:255',
            'contact.website' => 'nullable|string|max:255',
        ];
    }

    public function addHighlight(): void
    {
        $this->about['highlights'][] = '';
    }

    public function removeHighlight(int $index): void
    {
        $items = $this->about['highlights'] ?? [];

        if (!isset($items[$index])) {
            return;
        }

        unset($items[$index]);
        $this->about['highlights'] = array_values($items);
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'about' => [
                'eyebrow' => $this->normalizeString($this->about['eyebrow'] ?? ''),
                'title' => $this->normalizeString($this->about['title'] ?? ''),
                'summary' => $this->normalizeString($this->about['summary'] ?? ''),
                'highlights' => collect($this->about['highlights'] ?? [])
                    ->map(fn ($item) => $this->normalizeString($item))
                    ->filter()
                    ->values()
                    ->all(),
            ],
            'contact' => [
                'title' => $this->normalizeString($this->contact['title'] ?? ''),
                'intro' => $this->normalizeString($this->contact['intro'] ?? ''),
                'phone' => $this->normalizeString($this->contact['phone'] ?? ''),
                'whatsapp' => $this->normalizeString($this->contact['whatsapp'] ?? ''),
                'email' => $this->normalizeString($this->contact['email'] ?? ''),
                'address' => $this->normalizeString($this->contact['address'] ?? ''),
                'hours' => $this->normalizeString($this->contact['hours'] ?? ''),
                'booking_note' => $this->normalizeString($this->contact['booking_note'] ?? ''),
                'instagram' => $this->normalizeString($this->contact['instagram'] ?? ''),
                'website' => $this->normalizeString($this->contact['website'] ?? ''),
            ],
        ];

        SettingModel::set('vettas', $payload, 'pages');

        $this->about = $payload['about'];
        $this->contact = $payload['contact'];

        session()->flash('success', 'Vettas page settings updated successfully.');
    }

    private function normalizeString(mixed $value): string
    {
        return trim((string) $value);
    }

    public function render()
    {
        return view('livewire.admin.vettas.settings')
            ->layout('layouts.admin', ['header' => 'Vettas Settings']);
    }
}
