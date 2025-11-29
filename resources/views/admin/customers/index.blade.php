<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customers') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Single Customer Delete Confirmation Modal -->
            <div id="deleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    <!-- This element is to trick the browser into centering the modal contents. -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <!-- Modal panel -->
                    <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white dark:bg-gray-800 px-6 py-6">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="mt-4 text-center">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">
                                    Delete Customer
                                </h3>
                                <div class="mt-3">
                                    <p class="text-gray-600 dark:text-gray-300 text-lg font-medium" id="customerName"></p>
                                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">
                                        This action cannot be undone. All customer data, orders, and addresses will be permanently removed.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="button" id="confirmDelete" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Customer
                            </button>
                            <button type="button" id="cancelDelete" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Delete Confirmation Modal -->
            <div id="bulkDeleteModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="bulk-modal-title" role="dialog" aria-modal="true">
                <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
                    <!-- Background overlay -->
                    <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                    <!-- This element is to trick the browser into centering the modal contents. -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <!-- Modal panel -->
                    <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white dark:bg-gray-800 px-6 py-6">
                            <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="mt-4 text-center">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="bulk-modal-title">
                                    Delete Multiple Customers
                                </h3>
                                <div class="mt-3">
                                    <p class="text-gray-600 dark:text-gray-300 text-lg font-medium" id="bulkCustomerCount"></p>
                                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">
                                        This action cannot be undone. All selected customers, their orders, and addresses will be permanently removed.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="button" id="confirmBulkDelete" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Customers
                            </button>
                            <button type="button" id="cancelBulkDelete" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                    <a href="{{ route('admin.customers.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('Create Customer') }}
                    </a>

                    <div id="bulk-actions" class="hidden items-center gap-3 bg-gray-50 dark:bg-gray-700 px-4 py-3 rounded-lg border">
                        <span id="selected-count" class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
                        <div class="flex items-center gap-2">
                            <select id="bulk-action-select" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">{{ __('Choose Action') }}</option>
                                <option value="activate">{{ __('Activate') }}</option>
                                <option value="deactivate">{{ __('Deactivate') }}</option>
                                <option value="delete">{{ __('Delete') }}</option>
                            </select>
                            <button id="apply-bulk" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                {{ __('Apply') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Manage your customers') }}
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <select id="type-filter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ __('All Types') }}</option>
                            <option value="registered">{{ __('Registered') }}</option>
                            <option value="guest">{{ __('Guest') }}</option>
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
                    <table id="customers-table" class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 w-12 text-left">
                                    <input id="select-all" type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SNo.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Phone</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Orders</th>
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
            #customers-table_wrapper {
                @apply overflow-hidden;
            }

            #customers-table {
                @apply min-w-full table-auto;
            }

            /* Ensure table cells don't cause overflow */
            #customers-table th,
            #customers-table td {
                @apply whitespace-nowrap overflow-hidden text-ellipsis;
            }

            /* Adjust column widths for better fit */
            #customers-table th:nth-child(1),
            #customers-table td:nth-child(1) {
                @apply w-12;
            }

            #customers-table th:nth-child(2),
            #customers-table td:nth-child(2) {
                @apply w-16;
            }

            #customers-table th:nth-child(3),
            #customers-table td:nth-child(3) {
                @apply min-w-[150px] max-w-[200px];
            }

            #customers-table th:nth-child(4),
            #customers-table td:nth-child(4) {
                @apply min-w-[200px] max-w-[250px];
            }

            #customers-table th:nth-child(5),
            #customers-table td:nth-child(5) {
                @apply w-32;
            }

            #customers-table th:nth-child(6),
            #customers-table td:nth-child(6),
            #customers-table th:nth-child(7),
            #customers-table td:nth-child(7) {
                @apply w-24;
            }

            #customers-table th:nth-child(8),
            #customers-table td:nth-child(8) {
                @apply w-32;
            }

            #customers-table th:nth-child(9),
            #customers-table td:nth-child(9) {
                @apply w-40;
            }

            #customers-table th:nth-child(10),
            #customers-table td:nth-child(10) {
                @apply w-48;
            }

            /* Modal animations */
            #deleteModal, #bulkDeleteModal {
                transition: opacity 0.3s ease;
            }

            #deleteModal:not(.hidden), #bulkDeleteModal:not(.hidden) {
                display: block !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

        <script>
        $(function () {
            let table = $('#customers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.customers.index') }}",
                    data: function (d) {
                        d.type       = $('#type-filter').val();
                        d.date_range = $('#date-filter').val();
                        d.search     = $('.dataTables_filter input').val();
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
                            return meta.row + 1;
                        },
                        className: 'px-4 py-3 text-sm font-medium text-gray-900 dark:text-white',
                        orderable: false
                    },
                    {
                        data: 'full_name',
                        name: 'full_name',
                        className: 'px-4 py-3 text-sm font-medium text-gray-900 dark:text-white'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'px-4 py-3 text-sm text-gray-900 dark:text-white'
                    },
                    { 
                        data: 'phone', 
                        name: 'phone',
                        className: 'px-4 py-3 text-sm text-gray-600 dark:text-gray-400' 
                    },
                    { 
                        data: 'guest', 
                        orderable: false, 
                        searchable: false,
                        className: 'px-4 py-3' 
                    },
                    { 
                        data: 'orders_count', 
                        orderable: false, 
                        searchable: false,
                        className: 'px-4 py-3 text-center' 
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

            // Single Delete Modal functionality
            let currentDeleteId = null;
            let currentCustomerName = '';

            function showDeleteModal(customerId, customerName) {
                currentDeleteId = customerId;
                currentCustomerName = customerName;
                
                $('#customerName').text(customerName);
                $('#deleteModal').removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function hideDeleteModal() {
                $('#deleteModal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
                currentDeleteId = null;
                currentCustomerName = '';
            }

            // Bulk Delete Modal functionality
            let currentBulkDeleteIds = [];

            function showBulkDeleteModal(ids, count) {
                currentBulkDeleteIds = ids;
                
                $('#bulkCustomerCount').text(`You are about to delete ${count} customer${count !== 1 ? 's' : ''}.`);
                $('#bulkDeleteModal').removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function hideBulkDeleteModal() {
                $('#bulkDeleteModal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
                currentBulkDeleteIds = [];
            }

            // Modal event handlers
            $('#cancelDelete').on('click', hideDeleteModal);
            $('#deleteModal .fixed.inset-0').on('click', function(e) {
                if (e.target === this) hideDeleteModal();
            });

            $('#cancelBulkDelete').on('click', hideBulkDeleteModal);
            $('#bulkDeleteModal .fixed.inset-0').on('click', function(e) {
                if (e.target === this) hideBulkDeleteModal();
            });

            $('#confirmDelete').on('click', function() {
                if (!currentDeleteId) return;

                $.ajax({
                    url: `/admin/customers/${currentDeleteId}`,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: () => { 
                        table.ajax.reload(null, false); 
                        showToast('Customer deleted successfully.'); 
                        hideDeleteModal();
                    },
                    error: () => {
                        showToast('Failed to delete customer.', 'error');
                        hideDeleteModal();
                    }
                });
            });

            $('#confirmBulkDelete').on('click', function() {
                if (!currentBulkDeleteIds.length) return;

                $.post(`{{ route('admin.customers.bulk') }}`, {
                    action: 'delete', 
                    ids: currentBulkDeleteIds, 
                    _token: "{{ csrf_token() }}"
                }).done(res => {
                    if (res.success) {
                        showToast(res.message);
                        table.ajax.reload();
                        toggleBulkBar();
                        $('#bulk-action-select').val('');
                        hideBulkDeleteModal();
                    } else {
                        showToast(res.message, 'error');
                        hideBulkDeleteModal();
                    }
                }).fail(() => {
                    showToast('An error occurred while processing your request.', 'error');
                    hideBulkDeleteModal();
                });
            });

            // Close modals on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    if (!$('#deleteModal').hasClass('hidden')) {
                        hideDeleteModal();
                    }
                    if (!$('#bulkDeleteModal').hasClass('hidden')) {
                        hideBulkDeleteModal();
                    }
                }
            });

            // ✅ Filter functionality
            $('#apply-filters').on('click', function() {
                table.ajax.reload();
            });

            $('#reset-filters').on('click', function() {
                $('#type-filter').val('');
                $('#date-filter').val('');
                table.ajax.reload();
            });

            // Apply filters on enter key in search
            $('.dataTables_filter input').unbind().bind('keyup', function(e) {
                if(e.keyCode === 13) {
                    table.search(this.value).draw();
                }
            });

            // ✅ Select all / bulk actions
            $('#select-all').on('change', function() {
                $('.row-check').prop('checked', $(this).is(':checked'));
                toggleBulkBar();
            });
            $('#customers-table').on('change', '.row-check', toggleBulkBar);

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
                    showToast('Please select an action and at least one customer.', 'error');
                    return;
                }

                if (action === 'delete') {
                    showBulkDeleteModal(ids, ids.length);
                } else {
                    // For other actions, proceed directly
                    $.post(`{{ route('admin.customers.bulk') }}`, {
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
                }
            });

            // ✅ Delete button - now opens modal
            $('#customers-table').on('click', '.delete-btn', function (e) {
                e.stopPropagation();
                const id = $(this).data('id');
                const customerName = $(this).data('name') || 'this customer';
                showDeleteModal(id, customerName);
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
                $('select[name="customers-table_length"]').addClass('w-24 sm:w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm');

                // Style the search input
                $('.dataTables_filter input').addClass('w-40 sm:w-64 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm');
            });
        });
        </script>
    @endpush
</x-app-layout>