@props([
    'type' => 'success',  // success | error
    'title' => null,
    'message' => null,
])

@php
    $styles = [
        'success' => 'border-green-500 bg-green-50 text-green-900',
        'error'   => 'border-red-500 bg-red-50 text-red-900',
    ];

    $icons = [
        'success' => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />',
        'error'   => '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />',
    ];
@endphp

<div class="fixed inset-x-0 top-16 z-50 flex justify-center pointer-events-none auto-dismiss">
    <div class="max-w-7xl w-full px-4 sm:px-6 lg:px-8 pointer-events-auto">
        <div class="relative flex items-start p-5 rounded-lg shadow-lg border-l-4 {{ $styles[$type] }} transition-all duration-300 hover:shadow-xl">

            <!-- Icon -->
            <div class="flex-shrink-0 mt-1">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                    {!! $icons[$type] !!}
                </svg>
            </div>

            <!-- Content -->
            <div class="ml-4 flex-1">
                <p class="font-bold text-lg capitalize">{{ $title ?? ucfirst($type) }}</p>
                @if(is_array($message))
                    <ul class="mt-1 text-base list-disc list-inside">
                        @foreach ($message as $msg)
                            <li>{{ $msg }}</li>
                        @endforeach
                    </ul>
                @else
                    <p class="mt-1 text-base">{{ $message }}</p>
                @endif
            </div>

            <!-- Close Button -->
            <button onclick="this.closest('div').remove()" class="ml-4 text-gray-500 hover:text-gray-700 transition-colors duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</div>
