@php
    $schedules = old('schedules', isset($schedules) ? $schedules : []);
@endphp
<div id="course-schedule"
     class="col-span-full w-full bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm space-y-6 border border-gray-200 dark:border-gray-700 mt-8">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ __('Course Schedule') }}</h3>

    <div id="schedule-wrapper" class="space-y-6">
        @if(count($schedules))
            @foreach($schedules as $index => $sch)
                <div class="schedule-item border-t border-gray-200 pt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg relative" data-index="{{ $index }}">
                    <button type="button"
                            class="remove-schedule absolute top-3 right-3 text-sm px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-600/20 text-red-600">
                        &times;
                    </button>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- English -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title (EN)') }}</label>
                            <input type="text" name="schedules[{{ $index }}][title_en]"
                                   value="{{ $sch['title']['en'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2">
                        </div>

                        <!-- Arabic -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title (AR)') }}</label>
                            <input type="text" name="schedules[{{ $index }}][title_ar]"
                                   value="{{ $sch['title']['ar'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2"
                                   dir="rtl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Venue (EN)') }}</label>
                            <input type="text" name="schedules[{{ $index }}][venue_en]"
                                   value="{{ $sch['venue']['en'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Venue (AR)') }}</label>
                            <input type="text" name="schedules[{{ $index }}][venue_ar]"
                                   value="{{ $sch['venue']['ar'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2"
                                   dir="rtl">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start Date') }}</label>
                            <input type="date" name="schedules[{{ $index }}][start_date]"
                                   value="{{ $sch['start_date'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End Date') }}</label>
                            <input type="date" name="schedules[{{ $index }}][end_date]"
                                   value="{{ $sch['end_date'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start Time') }}</label>
                            <input type="time" name="schedules[{{ $index }}][start_time]"
                                   value="{{ $sch['start_time'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End Time') }}</label>
                            <input type="time" name="schedules[{{ $index }}][end_time]"
                                   value="{{ $sch['end_time'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Days') }}</label>
                            <input type="text" name="schedules[{{ $index }}][days]"
                                   value="{{ $sch['days'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md p-2"
                                   placeholder="Sun - Thu">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost (KWD)') }}</label>
                            <input type="number" step="0.001" name="schedules[{{ $index }}][cost]"
                                   value="{{ $sch['cost'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md p-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Language') }}</label>
                            <input type="text" name="schedules[{{ $index }}][language]"
                                   value="{{ $sch['language'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md p-2" placeholder="English / Arabic">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Session') }}</label>
                            <input type="text" name="schedules[{{ $index }}][session]"
                                   value="{{ $sch['session'] ?? '' }}"
                                   class="form-input mt-1 w-full rounded-md p-2">
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Instructor') }}</label>
                            <select name="schedules[{{ $index }}][instructor_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                                <option value="">{{ __('Select Instructor') }}</option>
                                @foreach($instructors as $instructor)
                                    <option value="{{ $instructor->id }}"
                                        {{ (old("schedules.$index.instructor_id", $sch['instructor_id'] ?? '') == $instructor->id) ? 'selected' : '' }}>
                                        {{ $instructor->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Country') }}</label>
                            <select name="schedules[{{ $index }}][country_id]"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border"
                                    required>
                                <option value="">{{ __('Select Country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}"
                                        {{ old("schedules.$index.country_id", $sch['country_id'] ?? '') == $country->id ? 'selected' : '' }}>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nature') }}</label>
                            <select name="schedules[{{ $index }}][nature]"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                                <option value="online" {{ old("schedules.$index.nature", $sch['nature'] ?? '') == 'online' ? 'selected' : '' }}>
                                    {{ __('Online') }}
                                </option>
                                <option value="in_person" {{ old("schedules.$index.nature", $sch['nature'] ?? '') == 'in_person' ? 'selected' : '' }}>
                                    {{ __('In Person') }}
                                </option>
                                <option value="hybrid" {{ old("schedules.$index.nature", $sch['nature'] ?? '') == 'hybrid' ? 'selected' : '' }}>
                                    {{ __('Hybrid') }}
                                </option>
                            </select>
                        </div>

                        <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Type') }}</label>
                            <select name="schedules[{{ $index }}][type]"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                                <option value="course" {{ old("schedules.$index.type", $sch['type'] ?? '') == 'course' ? 'selected' : '' }}>
                                    {{ __('Course') }}
                                </option>
                                <option value="workshop" {{ old("schedules.$index.type", $sch['type'] ?? '') == 'workshop' ? 'selected' : '' }}>
                                    {{ __('Workshop') }}
                                </option>
                            </select>
                        </div>

                        <input type="hidden" name="schedules[{{ $index }}][id]" value="{{ $sch['id'] ?? '' }}">

                        <!-- Flyer Section -->
                        @include('admin.courses.sub-tabs.flyer', ['index' => $index, 'documents' => $documents, 'schedule' => $sch, 'flyer' => $sch['flyer'] ?? null])

                        <!-- Outline Section -->
                        @include('admin.courses.sub-tabs.outline', ['index' => $index, 'documents' => $documents, 'schedule' => $sch, 'outline' => $sch['outline'] ?? null])
                    </div>
                </div>
            @endforeach
        @else
            {{-- Empty schedule item --}}
            <div class="schedule-item border-t border-gray-200 pt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg relative" data-index="0">
                <button type="button"
                        class="remove-schedule absolute top-3 right-3 text-sm px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-600/20 text-red-600">
                    &times;
                </button>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title (EN)') }}</label>
                        <input type="text" name="schedules[0][title_en]" class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title (AR)') }}</label>
                        <input type="text" name="schedules[0][title_ar]" class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Venue (EN)') }}</label>
                        <input type="text" name="schedules[0][venue_en]" class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Venue (AR)') }}</label>
                        <input type="text" name="schedules[0][venue_ar]" class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2" dir="rtl">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start Date') }}</label>
                        <input type="date" name="schedules[0][start_date]" class="form-input mt-1 w-full rounded-md p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End Date') }}</label>
                        <input type="date" name="schedules[0][end_date]" class="form-input mt-1 w-full rounded-md p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start Time') }}</label>
                        <input type="time" name="schedules[0][start_time]" class="form-input mt-1 w-full rounded-md p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End Time') }}</label>
                        <input type="time" name="schedules[0][end_time]" class="form-input mt-1 w-full rounded-md p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Days') }}</label>
                        <input type="text" name="schedules[0][days]" class="form-input mt-1 w-full rounded-md p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost (KWD)') }}</label>
                        <input type="number" step="0.001" name="schedules[0][cost]" class="form-input mt-1 w-full rounded-md p-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Language') }}</label>
                        <input type="text" name="schedules[0][language]" class="form-input mt-1 w-full rounded-md p-2" placeholder="English / Arabic">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Session') }}</label>
                        <input type="text" name="schedules[0][session]" class="form-input mt-1 w-full rounded-md p-2">
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Instructor') }}</label>
                        <select name="schedules[0][instructor_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                            <option value="">{{ __('Select Instructor') }}</option>
                            @foreach($instructors as $instructor)
                                <option value="{{ $instructor->id }}" {{ old('instructor_id', $course->instructor_id ?? '') == $instructor->id ? 'selected' : '' }}>
                                    {{ $instructor->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Country') }}</label>
                        <select name="schedules[0][country_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                            <option value="">{{ __('Select Country') }}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}" {{ old('country_id', $course->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nature') }}</label>
                        <select name="schedules[0][nature]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                            <option value="online" {{ old('nature', $course->nature ?? '') == 'online' ? 'selected' : '' }}>{{ __('Online') }}</option>
                            <option value="in_person" {{ old('nature', $course->nature ?? '') == 'in_person' ? 'selected' : '' }}>{{ __('In Person') }}</option>
                            <option value="hybrid" {{ old('nature', $course->nature ?? '') == 'hybrid' ? 'selected' : '' }}>{{ __('Hybrid') }}</option>
                        </select>
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Type') }}</label>
                        <select name="schedules[0][type]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                            <option value="course" {{ old('type', $course->type ?? '') == 'course' ? 'selected' : '' }}>{{ __('Course') }}</option>
                            <option value="workshop" {{ old('type', $course->type ?? '') == 'workshop' ? 'selected' : '' }}>{{ __('Workshop') }}</option>
                        </select>
                    </div>

                    <!-- Flyer Section -->
                    @include('admin.courses.sub-tabs.flyer', ['index' => 0, 'documents' => $documents, 'schedule' => null])

                    <!-- Outline Section -->
                    @include('admin.courses.sub-tabs.outline', ['index' => 0, 'documents' => $documents, 'schedule' => null])
                </div>
            </div>
        @endif
    </div>

    <template id="schedule-template">
        <div class="schedule-item border-t border-gray-200 pt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg relative" data-index="__INDEX__">
            <button type="button"
                    class="remove-schedule absolute top-3 right-3 text-sm px-2 py-1 rounded hover:bg-red-50 dark:hover:bg-red-600/20 text-red-600">
                &times;
            </button>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Title English -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title (EN)') }}</label>
                    <input type="text" name="schedules[__INDEX__][title_en]"
                        class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2">
                </div>

                <!-- Title Arabic -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Title (AR)') }}</label>
                    <input type="text" name="schedules[__INDEX__][title_ar]"
                        class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2"
                        dir="rtl">
                </div>

                <!-- Venue English -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Venue (EN)') }}</label>
                    <input type="text" name="schedules[__INDEX__][venue_en]"
                        class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2">
                </div>

                <!-- Venue Arabic -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Venue (AR)') }}</label>
                    <input type="text" name="schedules[__INDEX__][venue_ar]"
                        class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2"
                        dir="rtl">
                </div>

                <!-- Start & End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start Date') }}</label>
                    <input type="date" name="schedules[__INDEX__][start_date]"
                        class="form-input mt-1 w-full rounded-md p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End Date') }}</label>
                    <input type="date" name="schedules[__INDEX__][end_date]"
                        class="form-input mt-1 w-full rounded-md p-2">
                </div>

                <!-- Start & End Time -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start Time') }}</label>
                    <input type="time" name="schedules[__INDEX__][start_time]"
                        class="form-input mt-1 w-full rounded-md p-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End Time') }}</label>
                    <input type="time" name="schedules[__INDEX__][end_time]"
                        class="form-input mt-1 w-full rounded-md p-2">
                </div>

                <!-- Days -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Days') }}</label>
                    <input type="text" name="schedules[__INDEX__][days]"
                        class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2">
                </div>

                <!-- Cost -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Cost (KWD)') }}</label>
                    <input type="number" step="0.001" name="schedules[__INDEX__][cost]"
                        class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2">
                </div>

                <!-- Language -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Language') }}</label>
                    <input type="text" name="schedules[__INDEX__][language]"
                        placeholder="English / Arabic"
                        class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2">
                </div>

                <!-- Session -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Session') }}</label>
                    <input type="text" name="schedules[__INDEX__][session]"
                        class="form-input mt-1 w-full rounded-md border-gray-300 dark:bg-gray-600 dark:text-white p-2">
                </div>

                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Instructor') }}</label>
                    <select name="schedules[__INDEX__][instructor_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                        <option value="">{{ __('Select Instructor') }}</option>
                        @foreach($instructors as $instructor)
                            <option value="{{ $instructor->id }}" {{ old('instructor_id') == $instructor->id ? 'selected' : '' }}>
                                {{ $instructor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Country') }}</label>
                    <select name="schedules[__INDEX__][country_id]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border" required>
                        <option value="">{{ __('Select Country') }}</option>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $course->country_id ?? '') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nature -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Nature') }}</label>
                    <select name="schedules[__INDEX__][nature]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                        <option value="online" {{ old('nature', $course->nature ?? '') == 'online' ? 'selected' : '' }}>{{ __('Online') }}</option>
                        <option value="in_person" {{ old('nature', $course->nature ?? '') == 'in_person' ? 'selected' : '' }}>{{ __('In Person') }}</option>
                        <option value="hybrid" {{ old('nature', $course->nature ?? '') == 'hybrid' ? 'selected' : '' }}>{{ __('Hybrid') }}</option>
                    </select>
                </div>

                <!-- Type -->
                <div class="bg-gray-50 p-4 rounded-lg dark:bg-gray-700">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Type') }}</label>
                    <select name="schedules[__INDEX__][type]" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white dark:border-gray-500 p-2 border">
                        <option value="course" {{ old('type', $course->type ?? '') == 'course' ? 'selected' : '' }}>{{ __('Course') }}</option>
                        <option value="workshop" {{ old('type', $course->type ?? '') == 'workshop' ? 'selected' : '' }}>{{ __('Workshop') }}</option>
                    </select>
                </div>

                <!-- Flyer Section -->
                @include('admin.courses.sub-tabs.flyer', ['index' => '__INDEX__', 'documents' => $documents, 'schedule' => null])

                <!-- Outline Section -->
                @include('admin.courses.sub-tabs.outline', ['index' => '__INDEX__', 'documents' => $documents, 'schedule' => null])
            </div>
        </div>
    </template>


    <!-- Button -->
    <div class="pt-4">
        <button type="button" id="add-schedule"
                class="w-full py-3 bg-[#1B5388] text-white text-center rounded-lg shadow-md hover:bg-[#163f66] font-medium transition">
            + {{ __('Add Another Schedule') }}
        </button>
    </div>
</div>
