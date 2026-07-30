<?php

namespace App\Livewire\Page;

use App\Support\Seo;
use Livewire\Component;

class EditorialStandardsPage extends Component
{
    public function render()
    {
        $title = 'Editorial Standards and Corrections - Glow FM';
        $description = 'How Glow 99.1 FM approaches accuracy, sourcing, corrections, bylines, sponsored content, community contributions, and responsible use of AI.';
        $canonical = route('editorial.standards');

        return view('livewire.page.editorial-standards-page')
            ->layout('layouts.app', [
                'title' => $title,
                'meta_title' => $title,
                'meta_description' => $description,
                'canonical_url' => $canonical,
                'structured_data' => Seo::siteGraph([
                    'title' => $title,
                    'description' => $description,
                    'url' => $canonical,
                    'type' => 'AboutPage',
                    'breadcrumbs' => [
                        ['name' => 'Home', 'url' => route('home')],
                        ['name' => 'Editorial Standards', 'url' => $canonical],
                    ],
                ]),
            ]);
    }
}
