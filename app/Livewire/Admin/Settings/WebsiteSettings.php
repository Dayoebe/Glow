<?php

namespace App\Livewire\Admin\Settings;

use App\Models\Setting;
use App\Support\CloudinaryUploader;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class WebsiteSettings extends Component
{
    use WithFileUploads;

    public $home = [];
    public $about = [];
    public $contact = [];
    public $teamImageUploads = [];
    public $partnerLogoUploads = [];

    public function mount()
    {
        $defaults = $this->defaultContent();
        $settings = Setting::get('website', []);
        $data = array_replace_recursive($defaults, $settings);

        $this->home = $data['home'];
        $this->about = $data['about'];
        $this->contact = $data['contact'];

        $this->normalizeAboutTeamSocials();
    }

    public function save()
    {
        $this->applyImageUploads();
        $this->persistSettings(true);
    }

    public function addAboutStoryParagraph()
    {
        $this->about['story_paragraphs'][] = '';
    }

    public function removeAboutStoryParagraph($index)
    {
        $items = $this->about['story_paragraphs'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->about['story_paragraphs'] = array_values($items);
    }

    public function addAboutBadge()
    {
        $this->about['story_badges'][] = '';
    }

    public function removeAboutBadge($index)
    {
        $items = $this->about['story_badges'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->about['story_badges'] = array_values($items);
    }

    public function addAboutValue()
    {
        $this->about['values'][] = [
            'icon' => 'fas fa-star',
            'title' => 'New Value',
            'description' => '',
            'color' => 'emerald',
        ];
    }

    public function removeAboutValue($index)
    {
        $items = $this->about['values'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->about['values'] = array_values($items);
    }

    public function addAboutMilestone()
    {
        $this->about['milestones'][] = [
            'year' => date('Y'),
            'title' => 'New Milestone',
            'description' => '',
        ];
    }

    public function removeAboutMilestone($index)
    {
        $items = $this->about['milestones'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->about['milestones'] = array_values($items);
    }

    public function addAboutTeamMember()
    {
        $this->about['team'][] = [
            'name' => 'New Member',
            'position' => '',
            'bio' => '',
            'image' => '',
            'socials' => [],
        ];
    }

    public function addAboutTeamSocial($memberIndex)
    {
        if (!isset($this->about['team'][$memberIndex])) {
            return;
        }

        $this->about['team'][$memberIndex]['socials'][] = [
            'name' => '',
            'icon' => 'fas fa-link',
            'url' => '',
        ];
    }

    public function removeAboutTeamSocial($memberIndex, $socialIndex)
    {
        if (!isset($this->about['team'][$memberIndex]['socials'][$socialIndex])) {
            return;
        }

        $socials = $this->about['team'][$memberIndex]['socials'];
        unset($socials[$socialIndex]);
        $this->about['team'][$memberIndex]['socials'] = array_values($socials);
    }

    public function removeAboutTeamMember($index)
    {
        $items = $this->about['team'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->about['team'] = array_values($items);
        $this->teamImageUploads = $this->shiftUploadIndexes($this->teamImageUploads, (int) $index);
    }

    public function addAboutAchievement()
    {
        $this->about['achievements'][] = [
            'year' => date('Y'),
            'award' => '',
            'organization' => '',
            'icon' => 'fas fa-award',
        ];
    }

    public function removeAboutAchievement($index)
    {
        $items = $this->about['achievements'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->about['achievements'] = array_values($items);
        $this->persistSettings();
    }

    public function addAboutPartner()
    {
        $this->about['partners'][] = [
            'name' => 'New Partner',
            'logo' => '',
        ];
    }

    public function removeAboutPartner($index)
    {
        $items = $this->about['partners'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->about['partners'] = array_values($items);
        $this->partnerLogoUploads = $this->shiftUploadIndexes($this->partnerLogoUploads, (int) $index);
    }

    public function clearTeamImageUpload($index): void
    {
        unset($this->teamImageUploads[$index]);
    }

    public function clearPartnerLogoUpload($index): void
    {
        unset($this->partnerLogoUploads[$index]);
    }

    public function addAboutStat()
    {
        $this->about['stats'][] = [
            'number' => '',
            'label' => '',
        ];
    }

    public function removeAboutStat($index)
    {
        $items = $this->about['stats'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->about['stats'] = array_values($items);
    }

    public function addContactDepartment()
    {
        $this->contact['departments'][] = [
            'name' => 'New Department',
            'icon' => 'fas fa-info-circle',
            'email' => '',
            'phone' => '',
            'description' => '',
            'color' => 'emerald',
        ];
    }

    public function removeContactDepartment($index)
    {
        $items = $this->contact['departments'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->contact['departments'] = array_values($items);
    }

    public function addContactFaq()
    {
        $this->contact['faqs'][] = [
            'question' => '',
            'answer' => '',
        ];
    }

    public function removeContactFaq($index)
    {
        $items = $this->contact['faqs'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->contact['faqs'] = array_values($items);
    }

    public function addContactSocial()
    {
        $this->contact['socials'][] = [
            'name' => '',
            'icon' => 'fab fa-facebook-f',
            'url' => '',
            'handle' => '',
            'color' => 'blue',
        ];
    }

    public function removeContactSocial($index)
    {
        $items = $this->contact['socials'] ?? [];
        if (!isset($items[$index])) {
            return;
        }
        unset($items[$index]);
        $this->contact['socials'] = array_values($items);
    }

    public function render()
    {
        return view('livewire.admin.settings.website-settings')
            ->layout('layouts.admin', ['header' => 'Website Settings']);
    }

    private function defaultContent(): array
    {
        return [
            'home' => [
                'hero_badge' => '',
                'hero_title' => '',
                'hero_highlight' => '',
                'hero_subtitle' => '',
                'primary_cta_text' => '',
                'primary_cta_url' => '',
                'secondary_cta_text' => '',
                'secondary_cta_url' => '',
                'now_playing_label' => '',
                'now_playing_title' => '',
                'now_playing_time' => '',
            ],
            'about' => [
                'header_title' => '',
                'header_subtitle' => '',
                'story_title' => '',
                'story_paragraphs' => [],
                'story_badges' => [],
                'mission_title' => '',
                'mission_body' => '',
                'vision_title' => '',
                'vision_body' => '',
                'values_title' => '',
                'values_subtitle' => '',
                'values' => [],
                'milestones_title' => '',
                'milestones_subtitle' => '',
                'milestones' => [],
                'team_title' => '',
                'team_subtitle' => '',
                'team' => [],
                'achievements_title' => '',
                'achievements_subtitle' => '',
                'achievements' => [],
                'partners_title' => '',
                'partners_subtitle' => '',
                'partners' => [],
                'stats_title' => '',
                'stats_subtitle' => '',
                'stats' => [],
                'cta_title' => '',
                'cta_body' => '',
                'cta_primary_text' => '',
                'cta_primary_url' => '',
                'cta_secondary_text' => '',
                'cta_secondary_url' => '',
            ],
            'contact' => [
                'header_title' => '',
                'header_subtitle' => '',
                'contact_info' => [
                    'address' => '',
                    'phone' => '',
                    'email' => '',
                    'hours' => [
                        'weekdays' => '',
                        'saturday' => '',
                        'sunday' => '',
                    ],
                    'map_embed' => '',
                ],
                'departments' => [],
                'faqs' => [],
                'socials' => [],
            ],
        ];
    }

    private function persistSettings(bool $flash = false): void
    {
        Setting::set('website', [
            'home' => $this->home,
            'about' => $this->about,
            'contact' => $this->contact,
        ], 'website');

        if ($flash) {
            session()->flash('success', 'Website settings updated successfully.');
        }
    }

    private function applyImageUploads(): void
    {
        $this->validate([
            'teamImageUploads.*' => 'nullable|image|max:5120',
            'partnerLogoUploads.*' => 'nullable|image|max:5120',
        ]);

        foreach ($this->teamImageUploads as $index => $file) {
            if (!$file || !isset($this->about['team'][$index])) {
                continue;
            }

            $path = CloudinaryUploader::uploadImage($file, 'website/team');
            if ($path) {
                $this->about['team'][$index]['image'] = $path;
            }
        }

        foreach ($this->partnerLogoUploads as $index => $file) {
            if (!$file || !isset($this->about['partners'][$index])) {
                continue;
            }

            $path = CloudinaryUploader::uploadImage($file, 'website/partners');
            if ($path) {
                $this->about['partners'][$index]['logo'] = $path;
            }
        }
    }

    private function shiftUploadIndexes(array $uploads, int $index): array
    {
        if (!$uploads) {
            return $uploads;
        }

        $shifted = [];
        foreach ($uploads as $key => $file) {
            $numericKey = is_int($key) ? $key : (ctype_digit((string) $key) ? (int) $key : null);
            if ($numericKey === null) {
                $shifted[$key] = $file;
                continue;
            }

            if ($numericKey === $index) {
                continue;
            }

            $newKey = $numericKey > $index ? $numericKey - 1 : $numericKey;
            $shifted[$newKey] = $file;
        }

        ksort($shifted);

        return $shifted;
    }

    private function normalizeAboutTeamSocials(): void
    {
        if (empty($this->about['team']) || !is_array($this->about['team'])) {
            return;
        }

        foreach ($this->about['team'] as $index => $member) {
            if (!is_array($member)) {
                continue;
            }

            $existingSocials = $member['socials'] ?? null;
            if (is_array($existingSocials)) {
                $this->about['team'][$index]['socials'] = array_values($existingSocials);
                continue;
            }

            $legacy = data_get($member, 'social', []);
            if (!is_array($legacy)) {
                $legacy = [];
            }

            $legacyMap = [
                'linkedin' => ['name' => 'LinkedIn', 'icon' => 'fab fa-linkedin-in', 'mailto' => false],
                'twitter' => ['name' => 'Twitter', 'icon' => 'fab fa-twitter', 'mailto' => false],
                'email' => ['name' => 'Email', 'icon' => 'fas fa-envelope', 'mailto' => true],
            ];

            $socials = [];
            foreach ($legacyMap as $key => $meta) {
                $value = $legacy[$key] ?? '';
                if (!is_string($value)) {
                    continue;
                }

                $value = trim($value);
                if ($value === '' || $value === '#') {
                    continue;
                }

                if ($meta['mailto'] && !Str::startsWith($value, 'mailto:')) {
                    $value = 'mailto:' . $value;
                }

                $socials[] = [
                    'name' => $meta['name'],
                    'icon' => $meta['icon'],
                    'url' => $value,
                ];
            }

            $this->about['team'][$index]['socials'] = $socials;
        }
    }
}
