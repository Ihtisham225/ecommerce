<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Search Results') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Results -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 space-y-6">
                @forelse($results as $modelName => $items)
                    @if(count($items))
                        <div>
                            <h3 class="text-lg font-semibold mb-2">{{ __(ucfirst(str_replace('_', ' ', $modelName))) }}</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full border">
                                    <thead class="bg-gray-100 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 border">{{ __('ID') }}</th>
                                            <th class="px-4 py-2 border">{{ __('Name / Title') }}</th>
                                            <th class="px-4 py-2 border">{{ __('Description / Info') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            @php
                                                $item = is_array($item) ? (object) $item : $item;
                                                $description = $item->description ?? $item->email ?? '-';

                                                // Determine the show route dynamically
                                                $routeMap = [
                                                    'courses' => 'admin.courses.show',
                                                    'blogs' => 'admin.blogs.show',
                                                    'instructors' => 'admin.instructors.show',
                                                    'users' => 'admin.users.show',
                                                    'blog_categories' => 'admin.blog-categories.show',
                                                    'course_categories' => 'admin.course-categories.show',
                                                    'countries' => 'admin.countries.show',
                                                    'sponsors' => 'admin.sponsors.show',
                                                    'certificates' => 'admin.certificates.show',
                                                    'documents' => 'admin.documents.show',
                                                    'gallery' => 'admin.gallery.show',
                                                ];

                                                $showRoute = $routeMap[$modelName] ?? null;
                                            @endphp
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                <td class="px-4 py-2 border">{{ $item->id ?? '-' }}</td>
                                                <td class="px-4 py-2 border">
                                                    @if($showRoute)
                                                        <a href="{{ route($showRoute, $item->id) }}"
                                                           class="text-indigo-600 hover:underline">
                                                            {{ $item->title ?? $item->name ?? '-' }}
                                                        </a>
                                                    @else
                                                        {{ $item->title ?? $item->name ?? '-' }}
                                                    @endif
                                                </td>
                                                <td class="px-4 py-2 border">{{ $description }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                @empty
                    <p class="text-center text-gray-500">{{ __('No results found.') }}</p>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
