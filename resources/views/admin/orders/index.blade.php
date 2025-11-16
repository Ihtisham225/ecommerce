<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orders') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Single Order Delete Confirmation Modal -->
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
                                    Delete Order
                                </h3>
                                <div class="mt-3">
                                    <p class="text-gray-600 dark:text-gray-300 text-lg font-medium" id="orderNumber"></p>
                                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">
                                        This action cannot be undone. All order data, items, and payment information will be permanently removed.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="button" id="confirmDelete" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Order
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
                                    Delete Multiple Orders
                                </h3>
                                <div class="mt-3">
                                    <p class="text-gray-600 dark:text-gray-300 text-lg font-medium" id="bulkOrderCount"></p>
                                    <p class="text-gray-500 dark:text-gray-400 mt-2 text-sm">
                                        This action cannot be undone. All selected orders, their items, and payment information will be permanently removed.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3">
                            <button type="button" id="confirmBulkDelete" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200 shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Orders
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
                    <a href="{{ route('admin.orders.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('Create Order') }}
                    </a>

                    <!-- Import Orders Button -->
                    <button id="openImportModal" 
                        class="inline-flex items-center px-4 py-2.5 bg-green-600 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        {{ __('Import Orders') }}
                    </button>

                    <div id="bulk-actions" class="hidden items-center gap-3 bg-gray-50 dark:bg-gray-700 px-4 py-3 rounded-lg border">
                        <span id="selected-count" class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
                        <div class="flex items-center gap-2">
                            <select id="bulk-action-select" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                <option value="">{{ __('Choose Action') }}</option>
                                <option value="confirm">{{ __('Confirm') }}</option>
                                <option value="process">{{ __('Process') }}</option>
                                <option value="ship">{{ __('Ship') }}</option>
                                <option value="deliver">{{ __('Deliver') }}</option>
                                <option value="cancel">{{ __('Cancel') }}</option>
                                <option value="mark_paid">{{ __('Mark as Paid') }}</option>
                                <option value="mark_refunded">{{ __('Mark as Refunded') }}</option>
                                <option value="delete">{{ __('Delete') }}</option>
                            </select>
                            <button id="apply-bulk" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                {{ __('Apply') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('Manage your orders') }}
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <select id="status-filter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ __('All Status') }}</option>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="confirmed">{{ __('Confirmed') }}</option>
                            <option value="processing">{{ __('Processing') }}</option>
                            <option value="shipped">{{ __('Shipped') }}</option>
                            <option value="delivered">{{ __('Delivered') }}</option>
                            <option value="cancelled">{{ __('Cancelled') }}</option>
                            <option value="refunded">{{ __('Refunded') }}</option>
                        </select>

                        <select id="payment-status-filter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ __('All Payment Status') }}</option>
                            <option value="pending">{{ __('Pending') }}</option>
                            <option value="paid">{{ __('Paid') }}</option>
                            <option value="failed">{{ __('Failed') }}</option>
                            <option value="refunded">{{ __('Refunded') }}</option>
                            <option value="partially_refunded">{{ __('Partially Refunded') }}</option>
                        </select>

                        <select id="source-filter" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">{{ __('All Sources') }}</option>
                            <option value="online">{{ __('Online') }}</option>
                            <option value="in_store">{{ __('In Store') }}</option>
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
                    <table id="orders-table" class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 w-12 text-left">
                                    <input id="select-all" type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600" />
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">SNo.</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Order Number</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Payment</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Source</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
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

    <!-- Import Orders Modal -->
    <div id="importModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="import-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="relative inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-gray-200 dark:border-gray-700">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-white/20 rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-white" id="import-modal-title">
                                    Import Orders
                                </h3>
                                <p class="text-sm text-blue-100">Upload WooCommerce CSV file</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <form id="importForm" enctype="multipart/form-data" class="bg-white dark:bg-gray-800 px-6 py-6">
                    <!-- File Upload Area -->
                    <div class="mb-6">
                        <label class="block mb-3 font-semibold text-gray-700 dark:text-gray-200 text-sm uppercase tracking-wide">
                            Select CSV File
                        </label>
                        
                        <div class="relative group">
                            <input type="file" name="file" accept=".csv" required 
                                class="hidden" id="fileInput">
                            
                            <label for="fileInput" 
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 group-hover:border-blue-400 group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20">
                                
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <p class="mb-1 text-sm text-gray-500 dark:text-gray-400 group-hover:text-blue-600 transition-colors">
                                        <span class="font-semibold">Click to upload</span> or drag and drop
                                    </p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500 group-hover:text-blue-500 transition-colors">
                                        CSV files only (.csv)
                                    </p>
                                </div>
                            </label>
                        </div>

                        <!-- Selected File Display -->
                        <div id="selectedFile" class="hidden mt-3 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-sm font-medium text-blue-800 dark:text-blue-300" id="fileName"></span>
                                </div>
                                <button type="button" onclick="clearFileSelection()" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Help Text -->
                        <div class="mt-3 flex items-start space-x-2 text-xs text-gray-500 dark:text-gray-400">
                            <svg class="w-4 h-4 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p>Make sure your CSV file follows the WooCommerce order export format for best results.</p>
                        </div>
                    </div>

                    <!-- Progress Bar (Hidden by default) -->
                    <div id="importProgress" class="hidden mb-6">
                        <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-2">
                            <span>Importing...</span>
                            <span id="progressPercent">0%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                </form>

                <!-- Footer Actions -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-3 border-t border-gray-200 dark:border-gray-600">
                    <button type="submit" id="startImport" 
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none disabled:shadow-none">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4M4 4l8 8 8-8"/>
                        </svg>
                        Start Import
                    </button>
                    <button type="button" id="cancelImport" 
                        class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-600 hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                        Cancel
                    </button>
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
            #orders-table_wrapper {
                @apply overflow-hidden;
            }

            #orders-table {
                @apply min-w-full table-auto;
            }

            /* Ensure table cells don't cause overflow */
            #orders-table th,
            #orders-table td {
                @apply whitespace-nowrap overflow-hidden text-ellipsis;
            }

            /* Adjust column widths for better fit */
            #orders-table th:nth-child(1),
            #orders-table td:nth-child(1) {
                @apply w-12;
            }

            #orders-table th:nth-child(2),
            #orders-table td:nth-child(2) {
                @apply w-16;
            }

            #orders-table th:nth-child(3),
            #orders-table td:nth-child(3) {
                @apply w-32;
            }

            #orders-table th:nth-child(4),
            #orders-table td:nth-child(4) {
                @apply min-w-[200px] max-w-[300px];
            }

            #orders-table th:nth-child(5),
            #orders-table td:nth-child(5),
            #orders-table th:nth-child(6),
            #orders-table td:nth-child(6),
            #orders-table th:nth-child(7),
            #orders-table td:nth-child(7) {
                @apply w-32;
            }

            #orders-table th:nth-child(8),
            #orders-table td:nth-child(8) {
                @apply w-24;
            }

            #orders-table th:nth-child(9),
            #orders-table td:nth-child(9) {
                @apply w-40;
            }

            #orders-table th:nth-child(10),
            #orders-table td:nth-child(10) {
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
            let table = $('#orders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.orders.index') }}",
                    data: function (d) {
                        d.status = $('#status-filter').val();
                        d.payment_status = $('#payment-status-filter').val();
                        d.source = $('#source-filter').val();
                        d.date_range = $('#date-filter').val();
                        d.search = $('.dataTables_filter input').val();
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
                        data: 'order_number',
                        name: 'order_number',
                        className: 'px-4 py-3 text-sm font-medium text-gray-900 dark:text-white'
                    },
                    {
                        data: 'customer',
                        name: 'customer',
                        className: 'px-4 py-3 text-sm text-gray-900 dark:text-white'
                    },
                    { 
                        data: 'status', 
                        orderable: false, 
                        searchable: false,
                        className: 'px-4 py-3' 
                    },
                    { 
                        data: 'payment_status', 
                        orderable: false, 
                        searchable: false,
                        className: 'px-4 py-3' 
                    },
                    { 
                        data: 'source', 
                        orderable: false, 
                        searchable: false,
                        className: 'px-4 py-3' 
                    },
                    { 
                        data: 'total', 
                        orderable: false, 
                        searchable: false,
                        className: 'px-4 py-3 text-sm font-medium text-gray-900 dark:text-white' 
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
            let currentOrderNumber = '';

            function showDeleteModal(orderId, orderNumber) {
                currentDeleteId = orderId;
                currentOrderNumber = orderNumber;
                
                $('#orderNumber').text(orderNumber);
                $('#deleteModal').removeClass('hidden');
                $('body').addClass('overflow-hidden');
            }

            function hideDeleteModal() {
                $('#deleteModal').addClass('hidden');
                $('body').removeClass('overflow-hidden');
                currentDeleteId = null;
                currentOrderNumber = '';
            }

            // Bulk Delete Modal functionality
            let currentBulkDeleteIds = [];

            function showBulkDeleteModal(ids, count) {
                currentBulkDeleteIds = ids;
                
                $('#bulkOrderCount').text(`You are about to delete ${count} order${count !== 1 ? 's' : ''}.`);
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
                    url: `/admin/orders/${currentDeleteId}`,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: () => { 
                        table.ajax.reload(null, false); 
                        showToast('Order deleted successfully.'); 
                        hideDeleteModal();
                    },
                    error: () => {
                        showToast('Failed to delete order.', 'error');
                        hideDeleteModal();
                    }
                });
            });

            $('#confirmBulkDelete').on('click', function() {
                if (!currentBulkDeleteIds.length) return;

                $.post(`{{ route('admin.orders.bulk') }}`, {
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
                $('#status-filter').val('');
                $('#payment-status-filter').val('');
                $('#source-filter').val('');
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
            $('#orders-table').on('change', '.row-check', toggleBulkBar);

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
                    showToast('Please select an action and at least one order.', 'error');
                    return;
                }

                if (action === 'delete') {
                    showBulkDeleteModal(ids, ids.length);
                } else {
                    // For other actions, proceed directly
                    $.post(`{{ route('admin.orders.bulk') }}`, {
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
            $('#orders-table').on('click', '.delete-btn', function (e) {
                e.stopPropagation();
                const id = $(this).data('id');
                const orderNumber = $(this).data('number') || 'this order';
                showDeleteModal(id, orderNumber);
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
                $('select[name="orders-table_length"]').addClass('w-24 sm:w-32 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-sm');

                // Style the search input
                $('.dataTables_filter input').addClass('w-40 sm:w-64 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm');
            });
        });

        // ✅ Import Orders Modal Logic with Chunk Processing
        $(document).ready(function () {
            const importModal = $('#importModal');
            const importForm = $('#importForm');
            const startBtn = document.getElementById('startImport');
            const importProgress = document.getElementById('importProgress');
            const progressBar = document.getElementById('progressBar');
            const progressPercent = document.getElementById('progressPercent');
            const selectedFileDiv = document.getElementById('selectedFile');
            const fileInput = document.getElementById('fileInput');

            // Configuration
            const uploadUrl = "{{ route('admin.orders.import.upload') }}";
            const processUrl = "{{ route('admin.orders.import.processChunk') }}";
            const chunkSize = 1; // number of rows per AJAX call
            const maxRetries = 3;

            let path = null;
            let total = 0;
            let offset = 0;

            // Modal event handlers
            $('#openImportModal').on('click', () => {
                importModal.removeClass('hidden');
                $('body').addClass('overflow-hidden');
            });

            $('#cancelImport').on('click', closeImportModal);
            $('#importModal .fixed.inset-0').on('click', function (e) {
                if (e.target === this) closeImportModal();
            });

            function closeImportModal() {
                importModal.addClass('hidden');
                $('body').removeClass('overflow-hidden');
                importForm.trigger('reset');
                resetImportUI();
            }

            // File input handling
            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    document.getElementById('fileName').textContent = file.name;
                    selectedFileDiv.classList.remove('hidden');
                    startBtn.disabled = false;
                }
            });

            // Clear file selection
            window.clearFileSelection = function clearFileSelection() {
                fileInput.value = '';
                selectedFileDiv.classList.add('hidden');
                startBtn.disabled = true;
            };

            // Start import button click handler
            startBtn.addEventListener('click', function (e) {
                e.preventDefault();
                uploadAndStart();
            });

            // Updated uploadAndStart function with better error handling
            async function uploadAndStart() {
                const file = fileInput.files[0];
                if (!file) {
                    showToast('Please choose a CSV file to import.', 'error');
                    return;
                }

                // Disable UI
                startBtn.disabled = true;
                startBtn.innerHTML = `
                    <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828m12.728 0l-2.828-2.828M7.464 6.464L4.636 3.636"/>
                    </svg>
                    Uploading...
                `;

                const fd = new FormData();
                fd.append('file', file);

                try {
                    // Step 1: Upload file and get total rows
                    const resp = await fetch(uploadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: fd
                    });

                    const data = await resp.json();
                    if (!resp.ok) {
                        throw new Error(data.message || `Upload failed with status ${resp.status}`);
                    }

                    path = data.path;
                    total = parseInt(data.total, 10) || 0;
                    offset = 0;

                    if (total === 0) {
                        throw new Error('No valid rows found in CSV file');
                    }

                    // Show progress UI
                    importProgress.classList.remove('hidden');
                    startBtn.innerHTML = `
                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828m12.728 0l-2.828-2.828M7.464 6.464L4.636 3.636"/>
                        </svg>
                        Importing... 0%
                    `;

                    updateProgress(0);

                    // Step 2: Process chunks sequentially
                    await processChunksSequential();

                    // Ensure 100% progress is shown
                    updateProgress(100);

                    showToast(`Import complete — Processed ${total} order rows.`, 'success');
                    
                    // Refresh orders table
                    try { 
                        $('#orders-table').DataTable().ajax.reload(); 
                    } catch (e) {
                        console.log('DataTable reload error:', e);
                    }
                    
                    // Close modal and reset after a brief delay to show completion
                    setTimeout(() => {
                        closeImportModal();
                    }, 1000);
                    
                } catch (err) {
                    console.error('Import error:', err);
                    showToast(err.message || 'Import failed', 'error');
                    resetImportUI();
                }
            }

            // Updated processChunksSequential with better error handling
            async function processChunksSequential() {
                let chunkNumber = 0;
                
                while (offset < total) {
                    console.log(`🔄 Processing chunk ${chunkNumber}: offset=${offset}, limit=${chunkSize}, total=${total}`);
                    
                    let success = false;
                    let attempts = 0;
                    
                    while (!success && attempts < maxRetries) {
                        attempts++;
                        try {
                            console.log(`📤 Sending request: offset=${offset}, limit=${chunkSize}`);
                            const chunkResp = await processChunk(offset, chunkSize);
                            console.log(`📥 Received response:`, chunkResp);
                            
                            if (!chunkResp.ok) {
                                throw new Error(chunkResp.message || 'Chunk processing failed');
                            }
                            
                            // Always move to the next chunk, even if we processed 0 records due to duplicates
                            offset += chunkSize;
                            chunkNumber++;
                            
                            // Update progress
                            let percent = Math.min(100, Math.round((offset / total) * 100));
                            updateProgress(percent);
                            
                            console.log(`✅ Chunk ${chunkNumber} completed: new offset=${offset}, progress=${percent}%`);
                            
                            success = true;
                            await sleep(300);
                            
                        } catch (err) {
                            console.warn(`❌ Chunk ${chunkNumber} failed (attempt ${attempts})`, err);
                            if (attempts >= maxRetries) {
                                throw new Error(`Import failed after ${maxRetries} attempts: ${err.message}`);
                            }
                            await sleep(1000 * attempts);
                        }
                    }
                }
                
                console.log('🎉 Import completed!');
            }

            // Ensure processChunk handles response properly
            async function processChunk(offsetParam, limitParam) {
                const fd = new FormData();
                fd.append('path', path);
                fd.append('offset', offsetParam);
                fd.append('limit', limitParam);

                const resp = await fetch(processUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: fd
                });

                const data = await resp.json();
                
                // Return both the response status and data
                return {
                    ok: resp.ok,
                    ...data
                };
            }

            function updateProgress(percent) {
                progressBar.style.width = percent + '%';
                progressPercent.textContent = percent + '%';
                
                const currentBtn = document.getElementById('startImport');
                if (!currentBtn) return;
                
                if (percent === 100) {
                    // Import complete - show active button that closes modal and refreshes page
                    currentBtn.innerHTML = `
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Done - Close
                    `;
                    currentBtn.disabled = false; // Enable the button
                    currentBtn.classList.remove('from-blue-500', 'to-indigo-500');
                    currentBtn.classList.add('from-blue-600', 'to-indigo-600', 'hover:from-blue-700', 'hover:to-indigo-700', 'hover:shadow-xl', 'hover:-translate-y-0.5');
                    
                    // Update click handler for the "Done" button
                    currentBtn.onclick = function(e) {
                        e.preventDefault();
                        location.reload(); // Refresh the page
                    };
                } else if (percent > 0 && percent < 100) {
                    // Import in progress
                    currentBtn.innerHTML = `
                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v4m0 12v4m8-10h-4M6 12H2m15.364-7.364l-2.828 2.828M7.464 17.536l-2.828 2.828m12.728 0l-2.828-2.828M7.464 6.464L4.636 3.636"/>
                        </svg>
                        Importing... ${percent}%
                    `;
                    currentBtn.disabled = true;
                    // Reset click handler to default during import
                    currentBtn.onclick = function(e) {
                        e.preventDefault();
                        uploadAndStart();
                    };
                } else {
                    // 0% - Initial state
                    currentBtn.innerHTML = `
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4M4 4l8 8 8-8"/>
                        </svg>
                        Start Import
                    `;
                    currentBtn.disabled = false;
                    // Reset click handler to default
                    currentBtn.onclick = function(e) {
                        e.preventDefault();
                        uploadAndStart();
                    };
                }
            }

            function sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            function resetImportUI() {
                const startBtn = document.getElementById('startImport');
                
                startBtn.disabled = false;
                startBtn.innerHTML = `
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v16h16V4M4 4l8 8 8-8"></path>
                    </svg>
                    Start Import
                `;
                
                // Reset button styles
                startBtn.classList.remove('from-blue-500', 'to-indigo-500');
                startBtn.classList.add('from-blue-600', 'to-indigo-600', 'hover:from-blue-700', 'hover:to-indigo-700', 'hover:shadow-xl', 'hover:-translate-y-0.5');
                
                importProgress.classList.add('hidden');
                updateProgress(0);
                fileInput.value = '';
                selectedFileDiv.classList.add('hidden');
                
                // Reset variables
                path = null;
                total = 0;
                offset = 0;
            }

            // Initialize with disabled import button
            startBtn.disabled = true;
        });
        </script>
    @endpush
</x-app-layout>