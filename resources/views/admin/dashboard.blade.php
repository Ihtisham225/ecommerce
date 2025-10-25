<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @php
                $stats = [];
                if(auth()->user()->hasRole('admin')){
                    $stats = [
                        ['label'=>'Blogs','count'=>\App\Models\Blog::count(),'icon'=>'M4 6h16M4 12h16M4 18h16','color'=>'bg-blue-500'],
                        ['label'=>'Courses','count'=>\App\Models\Course::count(),'icon'=>'M12 14l9-5-9-5-9 5 9 5z','color'=>'bg-green-500'],
                        ['label'=>'Users','count'=>\App\Models\User::count(),'icon'=>'M17 20h5v-2a4 4 0 00-5-4M9 20H4v-2a4 4 0 015-4','color'=>'bg-purple-500'],
                        ['label'=>'Instructors','count'=>\App\Models\Instructor::count(),'icon'=>'M12 14l9-5-9-5-9 5 9 5z','color'=>'bg-yellow-500'],
                        ['label'=>'Sponsors','count'=>\App\Models\Sponsor::count(),'icon'=>'M14 10h4.764a2 2 0 011.789 2.894L19 18H5l-1.553-5.106A2 2 0 015.236 10H10','color'=>'bg-pink-500'],
                        ['label'=>'Registrations','count'=>\App\Models\CourseRegistration::count(),'icon'=>'M5 13l4 4L19 7','color'=>'bg-indigo-500'],
                        ['label'=>'Evaluations','count'=>\App\Models\CourseEvaluation::count(),'icon'=>'M5 13l4 4L19 7','color'=>'bg-red-500'],
                        ['label'=>'Countries','count'=>\App\Models\Country::count(),'icon'=>'M12 2a10 10 0 100 20 10 10 0 000-20z','color'=>'bg-teal-500'],
                        ['label'=>'Blog Comments','count'=>\App\Models\BlogComment::count(),'icon'=>'M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8s-9-3.582-9-8','color'=>'bg-orange-500'],
                    ];
                } elseif(auth()->user()->hasRole('customer')) {
                    $stats = [
                        ['label'=>'My Registrations','count'=>\App\Models\CourseRegistration::where('user_id', auth()->id())->count(),'icon'=>'M5 13l4 4L19 7','color'=>'bg-indigo-500'],
                        ['label'=>'My Evaluations','count'=>\App\Models\CourseEvaluation::where('user_id', auth()->id())->count(),'icon'=>'M5 13l4 4L19 7','color'=>'bg-red-500'],
                        ['label'=>'My Contact Inquiries','count'=>\App\Models\ContactInquiry::where('email', auth()->user()->email)->count(),'icon'=>'M16 12H8m0 0l4-4m-4 4l4 4','color'=>'bg-green-500'],
                        ['label'=>'My Certificates','count'=>\App\Models\Certificate::where('user_id', auth()->id())->count(),'icon'=>'M9 12l2 2l4-4','color'=>'bg-blue-500'],
                        ['label'=>'My Comments','count'=>\App\Models\BlogComment::where('user_id', auth()->id())->count(),'icon'=>'M9 12l2 2l4-4','color'=>'bg-blue-500'],
                    ];
                }

                // Prepare chart data for registrations
                if(auth()->user()->hasRole('admin')){
                    $registrationDates = \App\Models\CourseRegistration::selectRaw('DATE(created_at) as date')
                        ->groupBy('date')->orderBy('date')->pluck('date');
                    $registrationCounts = \App\Models\CourseRegistration::selectRaw('COUNT(*) as count, DATE(created_at) as date')
                        ->groupBy('date')->orderBy('date')->pluck('count');
                } else {
                    $registrationDates = \App\Models\CourseRegistration::where('user_id', auth()->id())
                        ->selectRaw('DATE(created_at) as date')->groupBy('date')->orderBy('date')->pluck('date');
                    $registrationCounts = \App\Models\CourseRegistration::where('user_id', auth()->id())
                        ->selectRaw('COUNT(*) as count, DATE(created_at) as date')->groupBy('date')->orderBy('date')->pluck('count');
                }

                // Recent registrations
                $recentRegistrations = auth()->user()->hasRole('admin') 
                    ? \App\Models\CourseRegistration::latest()->take(5)->get()
                    : \App\Models\CourseRegistration::where('user_id', auth()->id())->latest()->take(5)->get();
            @endphp

            @foreach($stats as $stat)
                <div class="flex items-center p-6 bg-white dark:bg-gray-800 shadow rounded-lg transform hover:scale-105 transition">
                    <div class="p-4 rounded-full text-white {{ $stat['color'] }} text-2xl mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}" />
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $stat['count'] }}</div>
                        <div class="text-gray-500 dark:text-gray-400">{{ $stat['label'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Charts --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            {{-- Line Chart: Registrations Over Time --}}
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-gray-800 dark:text-gray-200 font-semibold mb-4">Course Registrations Over Time</h3>
                <canvas id="registrationsChart" class="w-full h-64"></canvas>
            </div>

            {{-- Pie Chart: Users by Role (admin only) --}}
            @if(auth()->user()->hasRole('admin'))
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-gray-800 dark:text-gray-200 font-semibold mb-4">Users by Role</h3>
                <canvas id="usersRoleChart" class="w-full h-64"></canvas>
            </div>
            @endif
        </div>

        {{-- Recent Registrations Table --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-gray-800 dark:text-gray-200 font-semibold mb-4">Recent Course Registrations</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentRegistrations as $registration)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $registration->user->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $registration->course->title ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ ucfirst($registration->status) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-100">{{ $registration->created_at->format('M d, Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Charts.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Registrations over time
        const registrationsCtx = document.getElementById('registrationsChart').getContext('2d');
        const registrationsChart = new Chart(registrationsCtx, {
            type: 'line',
            data: {
                labels: @json($registrationDates),
                datasets: [{
                    label: 'Registrations',
                    data: @json($registrationCounts),
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 2,
                    tension: 0.3
                }]
            },
            options: { responsive: true, plugins: { legend: { display: true } } }
        });

        @if(auth()->user()->hasRole('admin'))
        // Users by role
        const usersRoleCtx = document.getElementById('usersRoleChart').getContext('2d');
        const usersRoleChart = new Chart(usersRoleCtx, {
            type: 'pie',
            data: {
                labels: @json(\Spatie\Permission\Models\Role::pluck('name')),
                datasets: [{
                    label: 'Users',
                    data: @json(\Spatie\Permission\Models\Role::withCount('users')->pluck('users_count')),
                    backgroundColor: ['#3B82F6','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6']
                }]
            },
            options: { responsive: true }
        });
        @endif
    </script>

</x-app-layout>
