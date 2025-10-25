<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Country Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl">
                
                <!-- Header -->
                <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-700 text-white flex items-center justify-between">
                    <h3 class="text-2xl font-bold">{{ $country->name }}</h3>
                    @if($country->countryFlag)
                        <img src="{{ asset('storage/' . $country->countryFlag->file_path) }}" 
                             alt="{{ $country->name }}"
                             class="w-16 h-12 object-contain border-2 border-white shadow">
                    @endif
                </div>
                
                <!-- Content -->
                <div class="px-6 py-6 space-y-6">
                    <!-- English Content -->
                    <div id="show-english-content" class="tab-panel">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Name') }}</p>
                                <p class="text-lg font-semibold">{{ $country->name ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Country Code') }}</p>
                                <p class="text-lg font-semibold">{{ $country->code ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Currency') }}</p>
                                <p class="text-lg font-semibold">{{ $country->currency ?? '-' }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Currency Code') }}</p>
                                <p class="text-lg font-semibold">{{ $country->currency_code ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $country->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                            {{ $country->is_active ? __('Active') : __('Inactive') }}
                        </span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between pt-4 border-t border-gray-200">
                        <a href="{{ route('admin.countries.index') }}" 
                           class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            ← {{ __('Back to Countries') }}
                        </a>
                        <a href="{{ route('admin.countries.edit', $country) }}" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Edit') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching functionality
            const tabs = document.querySelectorAll('.tab-button');
            const panels = document.querySelectorAll('.tab-panel');
            
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.getAttribute('data-tab');
                    
                    // Update tab styles
                    tabs.forEach(t => {
                        t.classList.remove('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                        t.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    });
                    
                    this.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    this.classList.add('border-indigo-500', 'text-indigo-600', 'dark:text-indigo-400');
                    
                    // Show/hide panels
                    panels.forEach(panel => {
                        panel.classList.add('hidden');
                    });
                    
                    document.getElementById(targetTab + '-content').classList.remove('hidden');
                });
            });
        });
    </script>
    @endpush
</x-app-layout>