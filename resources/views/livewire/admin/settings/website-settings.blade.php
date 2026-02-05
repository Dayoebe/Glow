<div>
    <div class="flex items-center justify-end mb-6">
        <button wire:click="save"
            class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
            Save Website Settings
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Home Page</h3>
        <p class="text-sm text-gray-500">Control the hero content shown on the homepage.</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Badge</label>
                <input type="text" wire:model="home.hero_badge"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                <input type="text" wire:model="home.hero_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Highlight</label>
                <input type="text" wire:model="home.hero_highlight"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                <input type="text" wire:model="home.hero_subtitle"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Primary CTA Text</label>
                <input type="text" wire:model="home.primary_cta_text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Primary CTA URL</label>
                <input type="text" wire:model="home.primary_cta_url"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Secondary CTA Text</label>
                <input type="text" wire:model="home.secondary_cta_text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Secondary CTA URL</label>
                <input type="text" wire:model="home.secondary_cta_url"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Now Playing Label</label>
                <input type="text" wire:model="home.now_playing_label"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Now Playing Title</label>
                <input type="text" wire:model="home.now_playing_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Now Playing Time</label>
                <input type="text" wire:model="home.now_playing_time"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900">About Page</h3>
        <p class="text-sm text-gray-500">Edit the about page sections and content blocks.</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Header Title</label>
                <input type="text" wire:model="about.header_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Header Subtitle</label>
                <input type="text" wire:model="about.header_subtitle"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Story Title</label>
                <input type="text" wire:model="about.story_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mission Title</label>
                <input type="text" wire:model="about.mission_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Mission Body</label>
                <textarea rows="3" wire:model="about.mission_body"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Vision Title</label>
                <input type="text" wire:model="about.vision_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Vision Body</label>
                <textarea rows="3" wire:model="about.vision_body"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Values Title</label>
                <input type="text" wire:model="about.values_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Values Subtitle</label>
                <input type="text" wire:model="about.values_subtitle"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Milestones Title</label>
                <input type="text" wire:model="about.milestones_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Milestones Subtitle</label>
                <input type="text" wire:model="about.milestones_subtitle"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Team Title</label>
                <input type="text" wire:model="about.team_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Team Subtitle</label>
                <input type="text" wire:model="about.team_subtitle"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Achievements Title</label>
                <input type="text" wire:model="about.achievements_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Achievements Subtitle</label>
                <input type="text" wire:model="about.achievements_subtitle"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Partners Title</label>
                <input type="text" wire:model="about.partners_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Partners Subtitle</label>
                <input type="text" wire:model="about.partners_subtitle"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stats Title</label>
                <input type="text" wire:model="about.stats_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stats Subtitle</label>
                <input type="text" wire:model="about.stats_subtitle"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">Story Paragraphs</h4>
            <div class="mt-4 space-y-4">
                @foreach($about['story_paragraphs'] as $index => $paragraph)
                    <div class="flex gap-3">
                        <textarea rows="3" wire:model="about.story_paragraphs.{{ $index }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                        <button type="button" wire:click="removeAboutStoryParagraph({{ $index }})"
                            class="h-10 px-3 bg-red-600 text-white rounded-lg">Remove</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addAboutStoryParagraph"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add Paragraph</button>
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">Story Badges</h4>
            <div class="mt-4 space-y-3">
                @foreach($about['story_badges'] as $index => $badge)
                    <div class="flex gap-3">
                        <input type="text" wire:model="about.story_badges.{{ $index }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        <button type="button" wire:click="removeAboutBadge({{ $index }})"
                            class="h-10 px-3 bg-red-600 text-white rounded-lg">Remove</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addAboutBadge"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add Badge</button>
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">Core Values</h4>
            <div class="mt-4 space-y-4">
                @foreach($about['values'] as $index => $value)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg">
                        <input type="text" wire:model="about.values.{{ $index }}.title"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Title">
                        <input type="text" wire:model="about.values.{{ $index }}.icon"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Icon class">
                        <input type="text" wire:model="about.values.{{ $index }}.color"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Color">
                        <textarea rows="2" wire:model="about.values.{{ $index }}.description"
                            class="md:col-span-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Description"></textarea>
                        <button type="button" wire:click="removeAboutValue({{ $index }})"
                            class="md:col-span-2 justify-self-end px-4 py-2 bg-red-600 text-white rounded-lg">Remove Value</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addAboutValue"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add Value</button>
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">Milestones</h4>
            <div class="mt-4 space-y-4">
                @foreach($about['milestones'] as $index => $milestone)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg">
                        <input type="text" wire:model="about.milestones.{{ $index }}.year"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Year">
                        <input type="text" wire:model="about.milestones.{{ $index }}.title"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Title">
                        <textarea rows="2" wire:model="about.milestones.{{ $index }}.description"
                            class="md:col-span-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Description"></textarea>
                        <button type="button" wire:click="removeAboutMilestone({{ $index }})"
                            class="md:col-span-2 justify-self-end px-4 py-2 bg-red-600 text-white rounded-lg">Remove Milestone</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addAboutMilestone"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add Milestone</button>
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">Team Members</h4>
            <div class="mt-4 space-y-4">
                @foreach($about['team'] as $index => $member)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg" wire:key="about-team-{{ $index }}">
                        <input type="text" wire:model="about.team.{{ $index }}.name"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Name">
                        <input type="text" wire:model="about.team.{{ $index }}.position"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Position">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image Upload</label>
                            <input type="file" wire:model="teamImageUploads.{{ $index }}" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @error("teamImageUploads.$index") <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                            @php
                                $teamImageUpload = $teamImageUploads[$index] ?? null;
                                $teamImageUploadPreviewable = false;
                                if ($teamImageUpload) {
                                    $teamImageExtension = strtolower($teamImageUpload->getClientOriginalExtension() ?: $teamImageUpload->extension());
                                    $teamImageUploadPreviewable = !in_array($teamImageExtension, ['avif'], true);
                                }
                            @endphp
                            @if ($teamImageUpload && $teamImageUploadPreviewable)
                                <div class="mt-2">
                                    <img src="{{ $teamImageUpload->temporaryUrl() }}" class="w-24 h-24 object-cover rounded-lg">
                                </div>
                                <p class="mt-1 text-xs text-emerald-600">Upload ready.</p>
                            @elseif ($teamImageUpload)
                                <p class="mt-2 text-xs text-gray-500">Preview not available for this file type.</p>
                            @endif

                            @if ($teamImageUpload)
                                <button type="button" wire:click="clearTeamImageUpload({{ $index }})"
                                    class="mt-2 inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                                    <i class="fas fa-times mr-1"></i>Remove selected image
                                </button>
                            @endif

                            @if (!empty($member['image']))
                                <div class="mt-3">
                                    <p class="text-xs text-gray-500">Current image:</p>
                                    <img src="{{ $member['image'] }}" class="mt-1 w-24 h-24 object-cover rounded-lg">
                                </div>
                            @endif
                            <p class="mt-2 text-xs text-gray-500">Upload overrides the URL on save.</p>
                        </div>
                        <input type="text" wire:model="about.team.{{ $index }}.image"
                            class="md:col-span-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Image URL (optional)">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bio (Rich Text)</label>
                            <div x-data="richTextEditor(@js($member['bio'] ?? ''))"
                                class="border border-gray-300 rounded-lg overflow-hidden">
                                <div class="flex flex-wrap items-center gap-2 px-3 py-2 bg-gray-50 border-b border-gray-200">
                                    <button type="button" class="px-2 py-1 text-sm font-semibold text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-100"
                                        @click="format('bold')"><strong>B</strong></button>
                                    <button type="button" class="px-2 py-1 text-sm italic text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-100"
                                        @click="format('italic')"><em>I</em></button>
                                    <button type="button" class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-100"
                                        @click="format('underline')"><span class="underline">U</span></button>
                                    <button type="button" class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-100"
                                        @click="formatBlock('p')">P</button>
                                    <button type="button" class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-100"
                                        @click="format('insertUnorderedList')"><i class="fas fa-list-ul"></i></button>
                                    <button type="button" class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-100"
                                        @click="format('insertOrderedList')"><i class="fas fa-list-ol"></i></button>
                                    <button type="button" class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-100"
                                        @click="createLink()"><i class="fas fa-link"></i></button>
                                    <button type="button" class="px-2 py-1 text-sm text-gray-700 bg-white border border-gray-200 rounded hover:bg-gray-100"
                                        @click="format('unlink')"><i class="fas fa-unlink"></i></button>
                                </div>
                                <div x-ref="editor" contenteditable="true" wire:ignore
                                    class="min-h-[120px] px-4 py-3 text-gray-800 focus:outline-none"
                                    @input="sync()"
                                    @blur="sync()">{!! $member['bio'] ?? '' !!}</div>
                                <textarea x-ref="model" class="hidden" wire:model.defer="about.team.{{ $index }}.bio"></textarea>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Use the toolbar for bold, italics, lists, and links. This will render on the About page.</p>
                        </div>
                        <div class="md:col-span-2">
                            <div class="flex items-center justify-between">
                                <h5 class="text-sm font-semibold text-gray-700">Social Links</h5>
                                <button type="button" wire:click="addAboutTeamSocial({{ $index }})"
                                    class="text-xs font-semibold text-emerald-700 hover:text-emerald-800">
                                    Add Social
                                </button>
                            </div>
                            <div class="mt-3 space-y-3">
                                @foreach((array) data_get($member, 'socials', []) as $socialIndex => $social)
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 p-3 border border-gray-200 rounded-lg bg-gray-50"
                                        wire:key="about-team-{{ $index }}-social-{{ $socialIndex }}">
                                        <input type="text" wire:model="about.team.{{ $index }}.socials.{{ $socialIndex }}.name"
                                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                            placeholder="Label (e.g., Instagram)">
                                        <input type="text" wire:model="about.team.{{ $index }}.socials.{{ $socialIndex }}.icon"
                                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                            placeholder="Icon class (e.g., fab fa-instagram)">
                                        <input type="text" wire:model="about.team.{{ $index }}.socials.{{ $socialIndex }}.url"
                                            class="md:col-span-3 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                            placeholder="URL or mailto:">
                                        <div class="md:col-span-3 flex justify-end">
                                            <button type="button" wire:click="removeAboutTeamSocial({{ $index }}, {{ $socialIndex }})"
                                                class="text-xs font-semibold text-red-600 hover:text-red-700">
                                                Remove Social
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                                @if(empty((array) data_get($member, 'socials', [])))
                                    <p class="text-xs text-gray-500">No social links added yet.</p>
                                @endif
                            </div>
                        </div>
                        <button type="button" wire:click="removeAboutTeamMember({{ $index }})"
                            class="md:col-span-2 justify-self-end px-4 py-2 bg-red-600 text-white rounded-lg">Remove Member</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addAboutTeamMember"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add Team Member</button>
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">Achievements</h4>
            <div class="mt-4 space-y-4">
                @foreach($about['achievements'] as $index => $achievement)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg">
                        <input type="text" wire:model="about.achievements.{{ $index }}.year"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Year">
                        <input type="text" wire:model="about.achievements.{{ $index }}.award"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Award">
                        <input type="text" wire:model="about.achievements.{{ $index }}.organization"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Organization">
                        <input type="text" wire:model="about.achievements.{{ $index }}.icon"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Icon class">
                        <button type="button" wire:click="removeAboutAchievement({{ $index }})"
                            class="md:col-span-2 justify-self-end px-4 py-2 bg-red-600 text-white rounded-lg">Remove Achievement</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addAboutAchievement"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add Achievement</button>
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">Partners</h4>
            <div class="mt-4 space-y-4">
                @foreach($about['partners'] as $index => $partner)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg">
                        <input type="text" wire:model="about.partners.{{ $index }}.name"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Name">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Logo Upload</label>
                            <input type="file" wire:model="partnerLogoUploads.{{ $index }}" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            @error("partnerLogoUploads.$index") <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                            @php
                                $partnerLogoUpload = $partnerLogoUploads[$index] ?? null;
                                $partnerLogoUploadPreviewable = false;
                                if ($partnerLogoUpload) {
                                    $partnerLogoExtension = strtolower($partnerLogoUpload->getClientOriginalExtension() ?: $partnerLogoUpload->extension());
                                    $partnerLogoUploadPreviewable = !in_array($partnerLogoExtension, ['avif'], true);
                                }
                            @endphp
                            @if ($partnerLogoUpload && $partnerLogoUploadPreviewable)
                                <div class="mt-2">
                                    <img src="{{ $partnerLogoUpload->temporaryUrl() }}" class="w-24 h-24 object-contain rounded-lg bg-gray-50">
                                </div>
                                <p class="mt-1 text-xs text-emerald-600">Upload ready.</p>
                            @elseif ($partnerLogoUpload)
                                <p class="mt-2 text-xs text-gray-500">Preview not available for this file type.</p>
                            @endif

                            @if ($partnerLogoUpload)
                                <button type="button" wire:click="clearPartnerLogoUpload({{ $index }})"
                                    class="mt-2 inline-flex items-center text-xs font-semibold text-gray-600 hover:text-gray-700">
                                    <i class="fas fa-times mr-1"></i>Remove selected logo
                                </button>
                            @endif

                            @if (!empty($partner['logo']))
                                <div class="mt-3">
                                    <p class="text-xs text-gray-500">Current logo:</p>
                                    <img src="{{ $partner['logo'] }}" class="mt-1 w-24 h-24 object-contain rounded-lg bg-gray-50">
                                </div>
                            @endif
                            <p class="mt-2 text-xs text-gray-500">Upload overrides the URL on save.</p>
                        </div>
                        <input type="text" wire:model="about.partners.{{ $index }}.logo"
                            class="md:col-span-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="Logo URL (optional)">
                        <button type="button" wire:click="removeAboutPartner({{ $index }})"
                            class="md:col-span-2 justify-self-end px-4 py-2 bg-red-600 text-white rounded-lg">Remove Partner</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addAboutPartner"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add Partner</button>
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">Stats</h4>
            <div class="mt-4 space-y-4">
                @foreach($about['stats'] as $index => $stat)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg">
                        <input type="text" wire:model="about.stats.{{ $index }}.number"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Number">
                        <input type="text" wire:model="about.stats.{{ $index }}.label"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Label">
                        <button type="button" wire:click="removeAboutStat({{ $index }})"
                            class="md:col-span-2 justify-self-end px-4 py-2 bg-red-600 text-white rounded-lg">Remove Stat</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addAboutStat"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add Stat</button>
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">CTA Title</label>
                <input type="text" wire:model="about.cta_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">CTA Body</label>
                <textarea rows="3" wire:model="about.cta_body"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">CTA Primary Text</label>
                <input type="text" wire:model="about.cta_primary_text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">CTA Primary URL</label>
                <input type="text" wire:model="about.cta_primary_url"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">CTA Secondary Text</label>
                <input type="text" wire:model="about.cta_secondary_text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">CTA Secondary URL</label>
                <input type="text" wire:model="about.cta_secondary_url"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Contact Page</h3>
        <p class="text-sm text-gray-500">Update the contact page hero, info, and sections.</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Header Title</label>
                <input type="text" wire:model="contact.header_title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Header Subtitle</label>
                <input type="text" wire:model="contact.header_subtitle"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <input type="text" wire:model="contact.contact_info.address"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="text" wire:model="contact.contact_info.phone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="text" wire:model="contact.contact_info.email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Map Embed URL</label>
                <input type="text" wire:model="contact.contact_info.map_embed"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Weekdays Hours</label>
                <input type="text" wire:model="contact.contact_info.hours.weekdays"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Saturday Hours</label>
                <input type="text" wire:model="contact.contact_info.hours.saturday"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sunday Hours</label>
                <input type="text" wire:model="contact.contact_info.hours.sunday"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">Departments</h4>
            <div class="mt-4 space-y-4">
                @foreach($contact['departments'] as $index => $dept)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg">
                        <input type="text" wire:model="contact.departments.{{ $index }}.name"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Name">
                        <input type="text" wire:model="contact.departments.{{ $index }}.icon"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Icon">
                        <input type="text" wire:model="contact.departments.{{ $index }}.email"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Email">
                        <input type="text" wire:model="contact.departments.{{ $index }}.phone"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Phone">
                        <input type="text" wire:model="contact.departments.{{ $index }}.color"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Color">
                        <textarea rows="2" wire:model="contact.departments.{{ $index }}.description"
                            class="md:col-span-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Description"></textarea>
                        <button type="button" wire:click="removeContactDepartment({{ $index }})"
                            class="md:col-span-2 justify-self-end px-4 py-2 bg-red-600 text-white rounded-lg">Remove Department</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addContactDepartment"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add Department</button>
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">FAQs</h4>
            <div class="mt-4 space-y-4">
                @foreach($contact['faqs'] as $index => $faq)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg">
                        <input type="text" wire:model="contact.faqs.{{ $index }}.question"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Question">
                        <textarea rows="2" wire:model="contact.faqs.{{ $index }}.answer"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Answer"></textarea>
                        <button type="button" wire:click="removeContactFaq({{ $index }})"
                            class="md:col-span-2 justify-self-end px-4 py-2 bg-red-600 text-white rounded-lg">Remove FAQ</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addContactFaq"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add FAQ</button>
            </div>
        </div>

        <div class="mt-8">
            <h4 class="text-md font-semibold text-gray-900">Social Links</h4>
            <div class="mt-4 space-y-4">
                @foreach($contact['socials'] as $index => $social)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 border border-gray-200 rounded-lg">
                        <input type="text" wire:model="contact.socials.{{ $index }}.name"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Name">
                        <input type="text" wire:model="contact.socials.{{ $index }}.icon"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Icon">
                        <input type="text" wire:model="contact.socials.{{ $index }}.url"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="URL">
                        <input type="text" wire:model="contact.socials.{{ $index }}.handle"
                            class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Handle">
                        <input type="text" wire:model="contact.socials.{{ $index }}.color"
                            class="md:col-span-2 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Color">
                        <button type="button" wire:click="removeContactSocial({{ $index }})"
                            class="md:col-span-2 justify-self-end px-4 py-2 bg-red-600 text-white rounded-lg">Remove Social</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addContactSocial"
                    class="px-4 py-2 bg-emerald-100 text-emerald-700 rounded-lg">Add Social</button>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-end">
        <button wire:click="save"
            class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
            Save Website Settings
        </button>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('richTextEditor', (initial = '') => ({
            content: typeof initial === 'string' ? initial : '',
            init() {
                this.$nextTick(() => {
                    const existing = this.$refs.editor.innerHTML || '';
                    const initialHtml = this.content || existing;
                    this.$refs.editor.innerHTML = initialHtml;
                    if (this.$refs.model) {
                        this.$refs.model.value = initialHtml;
                    }
                });
            },
            sync() {
                const html = this.$refs.editor.innerHTML;
                this.content = html;
                if (this.$refs.model) {
                    this.$refs.model.value = html;
                    this.$refs.model.dispatchEvent(new Event('input', { bubbles: true }));
                }
            },
            format(command) {
                this.$refs.editor.focus();
                document.execCommand(command, false, null);
                this.sync();
            },
            formatBlock(tag) {
                this.$refs.editor.focus();
                document.execCommand('formatBlock', false, tag);
                this.sync();
            },
            createLink() {
                const url = prompt('Enter URL');
                if (!url) return;
                this.$refs.editor.focus();
                document.execCommand('createLink', false, url);
                this.sync();
            },
        }));
    });
</script>
