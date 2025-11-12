<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Top bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('Add Product') }}
                    </a>

                    <div id="bulk-actions" class="hidden items-center gap-3 bg-gray-50 dark:bg-gray-700 px-4 py-3 rounded-lg border">
                        <span id="selected-count" class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
                        <div class="flex items-center gap-2">
                            <select id="bulk-action-select" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">{{ __('Choose Action') }}</option>
                                <option value="publish">{{ __('Publish') }}</option>
                                <option value="unpublish">{{ __('Unpublish') }}</option>
                                <option value="feature">{{ __('Feature') }}</option>
                                <option value="unfeature">{{ __('Unfeature') }}</option>
                                <option value="delete">{{ __('Delete') }}</option>
                            </select>
                            <button id="apply-bulk" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                {{ __('Apply') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Manage your product catalog') }}
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <select id="status-filter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ __('All') }}</option>
                            <option value="active">{{ __('Published') }}</option>
                            <option value="draft">{{ __('Draft') }}</option>
                        </select>

                        <select id="featured-filter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ __('All') }}</option>
                            <option value="1">{{ __('Featured') }}</option>
                            <option value="0">{{ __('Not Featured') }}</option>
                        </select>

                        <select id="date-filter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ __('All Time') }}</option>
                            <option value="today">{{ __('Today') }}</option>
                            <option value="yesterday">{{ __('Yesterday') }}</option>
                            <option value="week">{{ __('This Week') }}</option>
                            <option value="month">{{ __('This Month') }}</option>
                            <option value="year">{{ __('This Year') }}</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <button id="reset-filters" class="px-3 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 border border-gray-300 dark:border-gray-600 rounded-md transition duration-150 ease-in-out">
                            {{ __('Reset') }}
                        </button>
                        <button id="apply-filters" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium transition duration-150 ease-in-out">
                            {{ __('Apply Filters') }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="w-full">
                    <table id="products-table" class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 w-12 text-left">
                                    <input id="select-all" type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SNo.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Featured</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
        <style>
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                @apply px-4 py-3 text-sm text-gray-700 dark:text-gray-300;
            }
            
            .dataTables_wrapper .dataTables_filter input {
                @apply w-40 sm:w-64 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm;
            }
            
            div.dataTables_wrapper div.dataTables_length select {
                @apply w-24 sm:w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm;
            }
            
            .dataTables_wrapper .dataTables_paginate .paginate_button {
                @apply px-3 py-1 mx-1 text-sm text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600;
            }
            
            .dataTables_wrapper .dataTables_paginate .paginate_button.current {
                @apply bg-indigo-600 text-white border-indigo-600;
            }

            /* Remove horizontal scroll and ensure table fits container */
            #products-table_wrapper {
                @apply overflow-hidden;
            }

            #products-table {
                @apply min-w-full table-auto;
            }

            /* Ensure table cells don't cause overflow */
            #products-table th,
            #products-table td {
                @apply whitespace-nowrap overflow-hidden text-ellipsis;
            }

            /* Adjust column widths for better fit */
            #products-table th:nth-child(1),
            #products-table td:nth-child(1) {
                @apply w-12;
            }

            #products-table th:nth-child(2),
            #products-table td:nth-child(2) {
                @apply w-16;
            }

            #products-table th:nth-child(3),
            #products-table td:nth-child(3) {
                @apply min-w-[200px] max-w-[300px];
            }

            #products-table th:nth-child(4),
            #products-table td:nth-child(4),
            #products-table th:nth-child(5),
            #products-table td:nth-child(5) {
                @apply w-32;
            }

            #products-table th:nth-child(6),
            #products-table td:nth-child(6) {
                @apply w-40;
            }

            #products-table th:nth-child(7),
            #products-table td:nth-child(7) {
                @apply w-48;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

        <script>
        $(function () {
            let table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.products.index') }}",
                    data: function (d) {
                        d.status = $('#status-filter').val();
                        d.featured = $('#featured-filter').val();
                        d.date_range = $('#date-filter').val();
                    }
                },
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    }
                },
                dom: '<"flex flex-col lg:flex-row lg:items-center lg:justify-between p-4"<"mb-4 lg:mb-0"l><"mb-4 lg:mb-0"f>><"w-full"t><"flex flex-col lg:flex-row lg:items-center lg:justify-between p-4"<"mb-4 lg:mb-0"i><"mb-4 lg:mb-0"p>>',
                columns: [
                    { 
                        data: 'id', 
                        render: id => `<input type="checkbox" class="row-check w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" value="${id}">`, 
                        orderable: false,
                        className: 'px-4 py-3'
                    },
                    {
                        data: null,
                        render: function (data, type, row, meta) {
                            return meta.row + 1; // simple incremental number starting from 1
                        },
                        className: 'px-4 py-3 text-sm font-medium text-gray-900 dark:text-white',
                        orderable: false
                    },
                    { 
                        data: 'title', 
                        name: 'title', 
                        render: data => `<div class="font-medium text-gray-900 dark:text-white truncate" title="${escapeHtml(data)}">${escapeHtml(data)}</div>`,
                        className: 'px-4 py-3' 
                    },
                    { 
                        data: 'status', 
                        orderable: false, 
                        searchable: false,
                        className: 'px-4 py-3' 
                    },
                    { 
                        data: 'featured', 
                        orderable: false, 
                        searchable: false,
                        className: 'px-4 py-3' 
                    },
                    { 
                        data: 'created_at', 
                        name: 'created_at',
                        className: 'px-4 py-3 text-sm text-gray-600 dark:text-gray-400' 
                    },
                    { 
                        data: 'actions', 
                        orderable: false, 
                        searchable: false, 
                        className: 'px-4 py-3 text-center' 
                    }
                ],
                order: [[1, 'desc']],
                drawCallback: syncSelectAllCheckbox,
                responsive: false,
                autoWidth: false,
                scrollX: false,
                pagingType: 'simple_numbers'
            });

            function escapeHtml(text) {
                if (!text) return '';
                return text.replace(/[&<>"'`=\/]/g, s => ({
                    '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'
                })[s]);
            }

            // ✅ Filter functionality
            $('#apply-filters').on('click', function() {
                table.ajax.reload();
            });

            $('#reset-filters').on('click', function() {
                $('#status-filter').val('');
                $('#featured-filter').val('');
                $('#date-filter').val('');
                table.ajax.reload();
            });

            // Apply filters on enter key in search
            $('.dataTables_filter input').unbind().bind('keyup', function(e) {
                if(e.keyCode === 13) {
                    table.search(this.value).draw();
                }
            });

            // ✅ Toggle active/featured instantly
            $('#products-table').on('click', '.toggle-status, .toggle-feature', function (e) {
                e.stopPropagation();
                const id = $(this).data('id');
                const type = $(this).data('type');

                $.post(`/admin/products/${id}/toggle`, {
                    type, _token: "{{ csrf_token() }}"
                }).done(() => {
                    table.ajax.reload(null, false);
                    showToast('Status updated successfully.');
                });
            });

            // ✅ Select all / bulk actions
            $('#select-all').on('change', function() {
                $('.row-check').prop('checked', $(this).is(':checked'));
                toggleBulkBar();
            });
            $('#products-table').on('change', '.row-check', toggleBulkBar);

            function syncSelectAllCheckbox() {
                const total = $('.row-check').length;
                const checked = $('.row-check:checked').length;
                $('#select-all').prop('checked', total && total === checked);
            }
            function toggleBulkBar() {
                const count = $('.row-check:checked').length;
                $('#bulk-actions').toggleClass('hidden', !count);
                $('#selected-count').text(`${count} item${count !== 1 ? 's' : ''} selected`);
            }

            // ✅ Apply bulk actions
            $('#apply-bulk').click(() => {
                const action = $('#bulk-action-select').val();
                const ids = $('.row-check:checked').map((i, e) => e.value).get();
                if (!action || !ids.length) {
                    showToast('Please select an action and at least one product.', 'error');
                    return;
                }

                if (action === 'delete' && !confirm(`Are you sure you want to delete ${ids.length} product${ids.length !== 1 ? 's' : ''}?`)) {
                    return;
                }

                $.post(`{{ route('admin.products.bulk') }}`, {
                    action, ids, _token: "{{ csrf_token() }}"
                }).done(res => {
                    if (res.success) {
                        showToast(res.message);
                        table.ajax.reload();
                        toggleBulkBar();
                        $('#bulk-action-select').val('');
                    } else {
                        showToast(res.message, 'error');
                    }
                }).fail(() => {
                    showToast('An error occurred while processing your request.', 'error');
                });
            });

            // ✅ Delete button
            $('#products-table').on('click', '.delete-btn', function (e) {
                e.stopPropagation();
                const id = $(this).data('id');
                if (!confirm('Are you sure you want to delete this product?')) return;
                
                $.ajax({
                    url: `/admin/products/${id}`,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: () => { 
                        table.ajax.reload(null, false); 
                        showToast('Product deleted successfully.'); 
                    },
                    error: () => showToast('Failed to delete product.', 'error')
                });
            });

            function showToast(msg, type = 'success') {
                const bgColor = type === 'error' ? 'bg-red-600' : 'bg-green-600';
                const t = $(`<div class="fixed bottom-6 right-6 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ease-in-out translate-x-full">${msg}</div>`);
                $('body').append(t);
                
                // Animate in
                setTimeout(() => t.removeClass('translate-x-full'), 10);
                
                // Animate out
                setTimeout(() => {
                    t.addClass('translate-x-full');
                    setTimeout(() => t.remove(), 300);
                }, 4000);
            }

            // Handle window resize to ensure table stays within bounds
            $(window).on('resize', function() {
                table.columns.adjust();
            });

            $(document).ready(function() {
                // Style the length select
                $('select[name="products-table_length"]').addClass('w-24 sm:w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm');

                // Style the search input
                $('.dataTables_filter input').addClass('w-40 sm:w-64 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm');
            });
        });
        </script>
    @endpush
</x-app-layout>