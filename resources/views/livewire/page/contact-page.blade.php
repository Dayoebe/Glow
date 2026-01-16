<div>
    <!-- Page Header -->
    <section class="relative bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-800 text-white py-20 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        </div>
        
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-5xl md:text-6xl font-bold mb-6">{{ $contactContent['header_title'] }}</h1>
                <p class="text-xl md:text-2xl text-emerald-100 leading-relaxed">
                    {{ $contactContent['header_subtitle'] }}
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Info Cards -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-20">
                <!-- Phone -->
                <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-phone text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Call Us</h3>
                    <p class="text-gray-600 mb-4">Weekdays: {{ $contactContent['contact_info']['hours']['weekdays'] }}</p>
                    <a href="tel:{{ $contactContent['contact_info']['phone'] }}" class="text-xl font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                        {{ $contactContent['contact_info']['phone'] }}
                    </a>
                </div>

                <!-- Email -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-envelope text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Email Us</h3>
                    <p class="text-gray-600 mb-4">We'll respond within 24 hours</p>
                    <a href="mailto:{{ $contactContent['contact_info']['email'] }}" class="text-xl font-semibold text-blue-600 hover:text-blue-700 transition-colors break-all">
                        {{ $contactContent['contact_info']['email'] }}
                    </a>
                </div>

                <!-- Location -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg">
                        <i class="fas fa-map-marker-alt text-3xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Visit Us</h3>
                    <p class="text-gray-600 mb-4">Come see our studio</p>
                    <p class="text-purple-600 font-semibold">
                        {{ $contactContent['contact_info']['address'] }}
                    </p>
                </div>
            </div>

            <!-- Main Contact Form and Info -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Contact Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-200">
                        <h2 class="text-3xl font-bold text-gray-900 mb-6">Send Us a Message</h2>

                        <!-- Success Message -->
                        @if($successMessage)
                            <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-600 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-emerald-600 text-2xl mr-3"></i>
                                    <p class="text-emerald-800 font-medium">{{ $successMessage }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Error Message -->
                        @if($errorMessage)
                            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-600 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-exclamation-circle text-red-600 text-2xl mr-3"></i>
                                    <p class="text-red-800 font-medium">{{ $errorMessage }}</p>
                                </div>
                            </div>
                        @endif

                        <form wire:submit.prevent="submitForm" class="space-y-6">
                            <!-- Inquiry Type -->
                            <div>
                                <label for="inquiry_type" class="block text-sm font-semibold text-gray-700 mb-2">
                                    What is your inquiry about? <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    wire:model="inquiry_type" 
                                    id="inquiry_type"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-emerald-500 transition-colors"
                                >
                                    <option value="general">General Inquiry</option>
                                    <option value="advertising">Advertising</option>
                                    <option value="programming">Programming/Shows</option>
                                    <option value="technical">Technical Support</option>
                                    <option value="events">Events</option>
                                    <option value="careers">Careers</option>
                                    <option value="feedback">Feedback</option>
                                </select>
                                @error('inquiry_type') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Name and Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Your Name <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="name" 
                                        id="name"
                                        placeholder="John Doe"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-emerald-500 transition-colors @error('name') border-red-500 @enderror"
                                    >
                                    @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Your Email <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="email" 
                                        wire:model="email" 
                                        id="email"
                                        placeholder="john@example.com"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-emerald-500 transition-colors @error('email') border-red-500 @enderror"
                                    >
                                    @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Phone and Subject -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Phone Number (Optional)
                                    </label>
                                    <input 
                                        type="tel" 
                                        wire:model="phone" 
                                        id="phone"
                                        placeholder="+1 (234) 567-890"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-emerald-500 transition-colors"
                                    >
                                    @error('phone') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2">
                                        Subject <span class="text-red-500">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        wire:model="subject" 
                                        id="subject"
                                        placeholder="Brief subject of your message"
                                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-emerald-500 transition-colors @error('subject') border-red-500 @enderror"
                                    >
                                    @error('subject') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Your Message <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    wire:model="message" 
                                    id="message"
                                    rows="6"
                                    placeholder="Tell us more about your inquiry..."
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-emerald-500 transition-colors resize-none @error('message') border-red-500 @enderror"
                                ></textarea>
                                @error('message') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button 
                                    type="submit"
                                    class="w-full px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-2"
                                >
                                    <i class="fas fa-paper-plane"></i>
                                    <span>Send Message</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quick Contact Info -->
                <div class="space-y-6">
                    <!-- Office Hours -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clock text-2xl text-emerald-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Office Hours</h3>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600 font-medium">Weekdays</span>
                                <span class="text-gray-900 font-semibold">{{ $contactContent['contact_info']['hours']['weekdays'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <span class="text-gray-600 font-medium">Saturday</span>
                                <span class="text-gray-900 font-semibold">{{ $contactContent['contact_info']['hours']['saturday'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600 font-medium">Sunday</span>
                                <span class="text-gray-900 font-semibold">{{ $contactContent['contact_info']['hours']['sunday'] }}</span>
                            </div>
                        </div>
                        <p class="mt-4 text-sm text-gray-600 bg-emerald-50 p-3 rounded-lg">
                            <i class="fas fa-broadcast-tower text-emerald-600 mr-2"></i>
                            We broadcast 24/7, but office hours are as listed above.
                        </p>
                    </div>

                    <!-- Social Media -->
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-200">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-share-alt text-2xl text-blue-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Follow Us</h3>
                        </div>
                        <p class="text-gray-600 mb-4">Connect with us on social media for updates and behind-the-scenes content!</p>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($contactContent['socials'] as $social)
                                <a 
                                    href="{{ $social['url'] }}" 
                                    class="flex flex-col items-center justify-center p-4 bg-gray-50 hover:bg-{{ $social['color'] }}-50 rounded-xl transition-all duration-300 group"
                                >
                                    <i class="{{ $social['icon'] }} text-2xl text-gray-600 group-hover:text-{{ $social['color'] }}-600 mb-2"></i>
                                    <span class="text-xs font-medium text-gray-700">{{ $social['name'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-xl p-6 text-white">
                        <div class="flex items-center space-x-3 mb-3">
                            <i class="fas fa-phone-volume text-3xl"></i>
                            <h3 class="text-xl font-bold">Request Line</h3>
                        </div>
                        <p class="mb-3 text-red-100">Call to request a song or give a shout-out on air!</p>
                        <a href="tel:{{ $contactContent['contact_info']['phone'] }}" class="block text-center py-3 bg-white text-red-600 font-bold rounded-xl hover:bg-red-50 transition-colors">
                            {{ $contactContent['contact_info']['phone'] }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Departments Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Contact by Department</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Reach out to the right team for faster assistance
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($contactContent['departments'] as $dept)
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 border-b-4 border-{{ $dept['color'] }}-500">
                        <div class="flex items-start space-x-4 mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-{{ $dept['color'] }}-100 rounded-xl flex items-center justify-center">
                                    <i class="{{ $dept['icon'] }} text-2xl text-{{ $dept['color'] }}-600"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $dept['name'] }}</h3>
                                <p class="text-sm text-gray-600">{{ $dept['description'] }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 pt-4 border-t border-gray-200">
                            <a href="mailto:{{ $dept['email'] }}" class="flex items-center space-x-2 text-gray-600 hover:text-{{ $dept['color'] }}-600 transition-colors">
                                <i class="fas fa-envelope text-sm"></i>
                                <span class="text-sm">{{ $dept['email'] }}</span>
                            </a>
                            <a href="tel:{{ $dept['phone'] }}" class="flex items-center space-x-2 text-gray-600 hover:text-{{ $dept['color'] }}-600 transition-colors">
                                <i class="fas fa-phone text-sm"></i>
                                <span class="text-sm">{{ $dept['phone'] }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Find Us</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Visit our studio and see where the magic happens
                </p>
            </div>

            <div class="rounded-2xl overflow-hidden shadow-2xl">
                <iframe 
                    src="{{ $contactContent['contact_info']['map_embed'] }}" 
                    width="100%" 
                    height="500" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade"
                    class="w-full"
                ></iframe>
            </div>

            <div class="mt-8 text-center">
                <a 
                    href="https://maps.google.com" 
                    target="_blank"
                    class="inline-flex items-center space-x-2 px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-300"
                >
                    <i class="fas fa-directions"></i>
                    <span>Get Directions</span>
                </a>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Quick answers to common questions
                </p>
            </div>

            <div class="max-w-4xl mx-auto space-y-4">
                @foreach($contactContent['faqs'] as $index => $faq)
                    <div x-data="{ open: false }" class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <button 
                            @click="open = !open"
                            class="w-full flex items-center justify-between p-6 text-left hover:bg-gray-50 transition-colors"
                        >
                            <span class="text-lg font-semibold text-gray-900 pr-4">{{ $faq['question'] }}</span>
                            <i class="fas fa-chevron-down text-emerald-600 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div 
                            x-show="open" 
                            x-collapse
                            class="px-6 pb-6"
                        >
                            <p class="text-gray-600 leading-relaxed">{{ $faq['answer'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <p class="text-gray-600 mb-4">Didn't find what you're looking for?</p>
                <a href="#contact-form" class="inline-flex items-center space-x-2 text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">
                    <span>Send us a message</span>
                    <i class="fas fa-arrow-up"></i>
                </a>
            </div>
        </div>
    </section>
</div>
