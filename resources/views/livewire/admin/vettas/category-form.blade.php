<div>
    @include('livewire.admin.vettas._nav')
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $isEditing ? 'Edit Category' : 'Create Category' }}
                </h3>
                <p class="text-sm text-gray-500">Organize Vettas photos into clean public gallery sections.</p>
            </div>
            <a href="{{ route('admin.vettas.categories') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                <i class="fas fa-arrow-left mr-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                <input type="text" wire:model.live="name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                <input type="text" wire:model="slug"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea wire:model="description" rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div><label class="block text-sm font-medium text-gray-700 mb-2">Page Eyebrow</label><input wire:model="eyebrow" class="w-full rounded-lg border-gray-300" placeholder="e.g. Rest and recharge"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-2">Page Headline</label><input wire:model="headline" class="w-full rounded-lg border-gray-300" placeholder="A clear visitor-focused headline"></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-2">SEO Title</label><input wire:model="seo_title" class="w-full rounded-lg border-gray-300" placeholder="Category at Vettas Apartment in Akure"><p class="mt-1 text-xs text-gray-500">Used in search results and social previews.</p></div>
            <div><label class="block text-sm font-medium text-gray-700 mb-2">Search Description</label><textarea wire:model="meta_description" rows="3" class="w-full rounded-lg border-gray-300" placeholder="Describe exactly what a visitor will find on this page."></textarea></div>

            <section class="lg:col-span-2 rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between"><div><h4 class="font-semibold text-gray-900">Page Highlights</h4><p class="text-xs text-gray-500">Specific benefits or features shown on the category page.</p></div><button type="button" wire:click="addHighlight" class="rounded-lg bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">Add highlight</button></div>
                <div class="mt-4 space-y-3">@foreach($highlights as $index => $highlight)<div class="flex gap-2"><input wire:model="highlights.{{ $index }}" class="flex-1 rounded-lg border-gray-300"><button type="button" wire:click="removeHighlight({{ $index }})" class="rounded-lg border border-red-200 px-3 text-red-600"><i class="fas fa-trash"></i></button></div>@endforeach</div>
            </section>

            <section class="lg:col-span-2 rounded-xl border border-gray-200 p-5">
                <div class="flex items-center justify-between"><div><h4 class="font-semibold text-gray-900">Frequently Asked Questions</h4><p class="text-xs text-gray-500">Visible answers that also help search engines and AI understand the page.</p></div><button type="button" wire:click="addFaq" class="rounded-lg bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700">Add FAQ</button></div>
                <div class="mt-4 space-y-4">@foreach($faqs as $index => $faq)<div class="rounded-lg bg-gray-50 p-4"><div class="flex gap-2"><input wire:model="faqs.{{ $index }}.question" class="flex-1 rounded-lg border-gray-300" placeholder="Question"><button type="button" wire:click="removeFaq({{ $index }})" class="rounded-lg border border-red-200 px-3 text-red-600"><i class="fas fa-trash"></i></button></div><textarea wire:model="faqs.{{ $index }}.answer" rows="3" class="mt-3 w-full rounded-lg border-gray-300" placeholder="Clear factual answer"></textarea></div>@endforeach</div>
            </section>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input type="number" min="0" wire:model="sort_order"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                @error('sort_order') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center space-x-2 pt-8">
                <input type="checkbox" wire:model="is_active" id="vettas_category_active" class="rounded border-gray-300">
                <label for="vettas_category_active" class="text-sm text-gray-700">Active</label>
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-3">
            <a href="{{ route('admin.vettas.categories') }}"
                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                Cancel
            </a>
            <button wire:click="save"
                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                {{ $isEditing ? 'Update Category' : 'Create Category' }}
            </button>
        </div>
    </div>
</div>
