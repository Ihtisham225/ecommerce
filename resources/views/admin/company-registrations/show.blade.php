<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Company Registration Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-2xl overflow-hidden">

                <div class="px-6 py-5 bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
                    <h3 class="text-2xl font-bold">{{ $companyRegistration->company_name }}</h3>
                    <p class="text-blue-100 mt-2">
                        {{ __('Registered for:') }} {{ $companyRegistration->courseSchedule->course->title ?? 'N/A' }}
                    </p>
                    <p class="text-blue-100 mt-2">
                        {{ __('Schedule:') }} {{ $companyRegistration->courseSchedule->formatted_date ?? 'N/A' }}
                    </p>
                    <p class="text-blue-100 mt-2">
                        {{ __('Location:') }} {{ $companyRegistration->courseSchedule->venue ?? 'N/A' }}
                    </p>
                    <p class="text-blue-100 mt-2">
                        {{ __('Contact:') }} {{ $companyRegistration->full_name }} ({{ $companyRegistration->email }})
                    </p>
                    <p class="text-blue-100 mt-2">{{ __('Status:') }}</p>

                    @if(auth()->user()->hasRole('admin'))
                        <form action="{{ route('admin.company-registrations.update', $companyRegistration) }}" method="POST" class="mt-1 w-48">
                            @csrf
                            @method('PUT')
                            <select name="status" onchange="this.form.submit()"
                                class="w-full px-3 py-2 text-gray-800 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                                <option value="pending" {{ $companyRegistration->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $companyRegistration->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ $companyRegistration->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    @else
                        <span class="inline-block px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100">
                            {{ ucfirst($companyRegistration->status) }}
                        </span>
                    @endif
                </div>

                <div class="px-6 py-6">
                    <h4 class="text-lg font-semibold mb-2">{{ __('Company Information') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 dark:text-gray-200">
                        <p><strong>Website:</strong> {{ $companyRegistration->website ?? 'N/A' }}</p>
                        <p><strong>Nature of Business:</strong> {{ $companyRegistration->nature_of_business ?? 'N/A' }}</p>
                        <p><strong>Country:</strong> {{ $companyRegistration->country ?? 'N/A' }}</p>
                        <p><strong>Postal Address:</strong> {{ $companyRegistration->postal_address ?? 'N/A' }}</p>
                    </div>

                    <h4 class="text-lg font-semibold mt-6 mb-2">{{ __('Contact Person') }}</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-gray-700 dark:text-gray-200">
                        <p><strong>Salutation:</strong> {{ $companyRegistration->salutation ?? 'N/A' }}</p>
                        <p><strong>Full Name:</strong> {{ $companyRegistration->full_name ?? 'N/A' }}</p>
                        <p><strong>Job Title:</strong> {{ $companyRegistration->job_title ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $companyRegistration->email ?? 'N/A' }}</p>
                        <p><strong>Telephone:</strong> {{ $companyRegistration->telephone ?? 'N/A' }}</p>
                        <p><strong>Mobile:</strong> {{ $companyRegistration->mobile ?? 'N/A' }}</p>
                    </div>

                    <h4 class="text-lg font-semibold mt-6 mb-2">{{ __('Participants') }}</h4>
                    @forelse($companyRegistration->participants as $p)
                        <p class="pl-2 border-l-4 border-indigo-500 mb-2">
                            {{ $p->full_name }} — {{ $p->participant_number }}
                        </p>
                    @empty
                        <p>No participants added.</p>
                    @endforelse

                    <div class="mt-6">
                        <a href="{{ route('admin.company-registrations.index') }}"
                           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg inline-flex items-center">
                            ← {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
