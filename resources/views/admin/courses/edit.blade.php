<x-app-layout>
    <div class="container mx-auto py-10">
        <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Card Header -->
            <div class="bg-indigo-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white">{{ __('Edit Course') }}</h1>
            </div>
            
            <!-- Card Body -->
            <div class="p-6">
                <form action="{{ route('admin.courses.update', $course) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    @include('admin.courses.partials.form', ['course' => $course])
                    
                    <!-- Form Actions -->
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-colors">
                            {{ __('Update Course') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
