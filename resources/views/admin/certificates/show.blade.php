<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"> 
            {{ __('Certificate Details') }} 
        </h2> 
    </x-slot> 
    <div class="py-6"> 
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> 
            <!-- Card --> 
             <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl"> 
                <!-- Header --> 
                 <div class="px-6 py-5 bg-gradient-to-r from-purple-600 to-indigo-700 text-white flex items-center justify-between"> 
                    <h3 class="text-2xl font-bold"> {{ $certificate->title ?? __('Untitled') }} </h3> 
                </div> 
                <!-- File Preview --> 
                 @if($certificate->certificateFile) 
                 <div class="px-6 pb-6"> 
                    
                 <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg shadow-inner"> 
                    @php $ext = pathinfo($certificate->certificateFile->file_path, PATHINFO_EXTENSION); 
                    @endphp @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp'])) 
                    <!-- Image Preview --> 
                     <img src="{{ asset('storage/' . $certificate->certificateFile->file_path) }}" alt="{{ $certificate->certificateFile->name }}" class="max-h-[500px] w-auto mx-auto rounded-lg shadow"> 
                     @elseif(strtolower($ext) === 'pdf') 
                     <!-- PDF Preview --> 
                    <iframe src="{{ asset('storage/' . $certificate->certificateFile->file_path) }}" class="w-full h-[600px] rounded-lg border" frameborder="0"></iframe> 
                    @else 
                    <!-- Fallback: download --> 
                     <p class="text-gray-600 dark:text-gray-300"> {{ __('Preview not available for this file type.') }} </p> 
                        <a href="{{ asset('storage/' . $certificate->certificateFile->file_path) }}" target="_blank" class="mt-2 inline-block px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg"> 
                            {{ __('Open File') }} 
                        </a>
                        @endif 
                    </div> 
                </div> 
                @endif 
                <!-- Content --> 
                <div class="px-6 py-6 space-y-6"> 
                    <!-- Basic info --> 
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6"> 
                    <div> 
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('User') }}</p> 
                        <p class="text-lg font-semibold"> {{ $certificate->recipient_name ?? '-' }} </p> 
                    </div> 
                    <div> 
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Course') }}</p> 
                        <p class="text-lg font-semibold"> {{ $certificate->course->title ?? '-' }} </p> 
                    </div> 
                    <div> 
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Issued At') }}</p> 
                        <p class="text-lg font-semibold"> {{ $certificate->issued_at ? $certificate->issued_at->format('d M, Y') : '-' }} </p> 
                    </div> 
                    
                    <div> <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Status') }}</p> 
                    <span class="px-3 py-1 text-sm rounded-full {{ $certificate->is_active ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700' }}"> {{ $certificate->is_active ? __('Active') : __('Inactive') }} </span> 
                </div> 
            </div> 
            <!-- File Section --> 
             <div> 
                <h4 class="text-lg font-semibold mb-2 text-gray-700 dark:text-gray-300">{{ __('Certificate File') }}</h4> 
                @if($certificate->certificateFile) 
                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 p-3 rounded-lg"> 
                    <div class="flex items-center"> 
                        <svg class="h-10 w-10 text-blue-600 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
                            <!-- Circle (certificate seal) --> 
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.5 2A8.5 8.5 0 1112 3.5 8.5 8.5 0 0120.5 12z" /> 
                            <!-- Ribbon --> 
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20v-4m0 0l-2 2m2-2l2 2" /> </svg> 
                             <span class="text-gray-700 dark:text-gray-300">{{ $certificate->certificateFile->name }}</span> </div> <div class="space-x-2"> 
                                <!-- View --> 
                                <a href="{{ asset('storage/' . $certificate->certificateFile->file_path) }}" target="_blank" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg"> {{ __('View') }} </a> 
                                <!-- Download --> <a href="{{ asset('storage/' . $certificate->certificateFile->file_path) }}" download class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg"> {{ __('Download') }} </a> 
                                </div> 
                            </div> 
                            @else 
                            <p class="text-gray-500 dark:text-gray-400">{{ __('No file uploaded') }}</p> 
                            @endif </div> 
                            <!-- Actions --> 
                            <div class="flex justify-between mt-6"> 
                                <a href="{{ route('admin.certificates.index') }}" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg flex items-center"> ← {{ __('Back to Certificates') }} </a> 
                                @role('admin') 
                                <a href="{{ route('admin.certificates.edit', $certificate) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center"> ✎ {{ __('Edit Certificate') }} </a> 
                                @endrole 
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
        </x-app-layout>