<div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">About Content</h3>
                <p class="text-sm text-gray-500">Edit the short intro that appears on the public Vettas page.</p>
            </div>
            <a href="{{ route('vettas.index') }}" target="_blank"
                class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-up-right-from-square mr-2"></i>
                View Page
            </a>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Eyebrow</label>
                <input type="text" wire:model="about.eyebrow"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('about.eyebrow') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" wire:model="about.title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('about.title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Short About Text</label>
                <textarea rows="5" wire:model="about.summary"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="Write a short, clear introduction for the apartment or location."></textarea>
                <p class="mt-2 text-xs text-gray-500">Recommended: 2 to 4 short sentences.</p>
                @error('about.summary') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">Highlights</h4>
                    <p class="text-xs text-gray-500">Short points that describe the Vettas experience.</p>
                </div>
                <button type="button" wire:click="addHighlight"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Add Highlight
                </button>
            </div>

            <div class="mt-4 space-y-3">
                @foreach($about['highlights'] ?? [] as $index => $highlight)
                    <div class="flex items-start gap-3">
                        <input type="text" wire:model="about.highlights.{{ $index }}"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            placeholder="e.g. Fully furnished apartment spaces">
                        <button type="button" wire:click="removeHighlight({{ $index }})"
                            class="px-3 py-2 border border-red-200 text-red-600 rounded-lg hover:bg-red-50">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    @error('about.highlights.' . $index) <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                @endforeach
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900">Contact Details</h3>
        <p class="text-sm text-gray-500">These details appear on the public Vettas page for enquiries and bookings.</p>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section Title</label>
                <input type="text" wire:model="contact.title"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('contact.title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                <input type="text" wire:model="contact.phone"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="+234 800 000 0000">
                @error('contact.phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">WhatsApp Number</label>
                <input type="text" wire:model="contact.whatsapp"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="+234 800 000 0000">
                @error('contact.whatsapp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" wire:model="contact.email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="bookings@example.com">
                @error('contact.email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Intro Text</label>
                <textarea rows="3" wire:model="contact.intro"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="Tell visitors how to reach you for bookings or enquiries."></textarea>
                @error('contact.intro') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                <input type="text" wire:model="contact.address"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="Enter the apartment address or location note">
                @error('contact.address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Available Hours</label>
                <input type="text" wire:model="contact.hours"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="Mon - Sun, 8:00 AM - 8:00 PM">
                @error('contact.hours') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Website Link</label>
                <input type="text" wire:model="contact.website"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="https://example.com">
                @error('contact.website') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Instagram</label>
                <input type="text" wire:model="contact.instagram"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="@vettasapartment or https://instagram.com/...">
                @error('contact.instagram') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Booking Note</label>
                <input type="text" wire:model="contact.booking_note"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"
                    placeholder="Optional short note for guests">
                @error('contact.booking_note') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="button" wire:click="save"
                class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg">
                Save Vettas Settings
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="fixed bottom-4 right-4 z-50 bg-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg flash-auto-dismiss">
            {{ session('success') }}
        </div>
    @endif
</div>
