<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Top bar -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.products.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        + {{ __('Add Product') }}
                    </a>

                    <!-- Bulk actions dropdown (hidden until rows selected) -->
                    <div id="bulk-actions" class="hidden items-center gap-2">
                        <span id="selected-count" class="text-sm text-gray-600"></span>

                        <select id="bulk-action-select" class="border rounded px-2 py-1 text-sm">
                            <option value="">{{ __('Bulk actions') }}</option>
                            <option value="delete">{{ __('Delete selected') }}</option>
                            <option value="publish">{{ __('Publish selected') }}</option>
                            <option value="unpublish">{{ __('Unpublish selected') }}</option>
                            <option value="feature">{{ __('Feature selected') }}</option>
                            <option value="unfeature">{{ __('Unfeature selected') }}</option>
                        </select>

                        <button id="apply-bulk" class="px-3 py-1 bg-indigo-600 text-white rounded text-sm">
                            {{ __('Apply') }}
                        </button>
                    </div>
                </div>

                <div class="text-sm text-gray-600">{{ __('Manage products: search, sort, bulk actions') }}</div>
            </div>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-4">
                <div class="overflow-x-auto">
                    <table id="products-table" class="min-w-full divide-y">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 py-2 w-10">
                                    <input id="select-all" type="checkbox" class="w-4 h-4 rounded" />
                                </th>
                                <th class="px-3 py-2 text-left">#</th>
                                <th class="px-3 py-2 text-left">{{ __('Product') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('SKU') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('Price') }}</th>
                                <th class="px-3 py-2 text-left">{{ __('Status') }}</th>
                                <th class="px-3 py-2 text-center">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css" />
    @endpush

    @push('scripts')
        <!-- DataTables -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>

        <script>
        $(function () {
            const table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.products.index') }}",
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: id => `<input type="checkbox" class="row-check" value="${id}">`
                    },
                    { data: 'id', name: 'id', width: '5%' },
                    {
                        data: 'title',
                        name: 'title',
                        render: function (data) {
                            return `<div class="font-medium text-gray-900">${escapeHtml(data)}</div>`;
                        }
                    },
                    { data: 'sku', name: 'sku' },
                    {
                        data: 'price',
                        name: 'price',
                        render: function (p) {
                            return p ? `<span class="text-green-600 font-semibold">$${p}</span>` : '-';
                        }
                    },
                    { data: 'status', name: 'status', orderable: false, searchable: false },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[1, 'desc']],
                drawCallback: function () {
                    // reset selects if needed
                    syncSelectAllCheckbox();
                }
            });

            function escapeHtml(text) {
                if (!text) return '';
                return text.replace(/[&<>"'`=\/]/g, function (s) {
                    return ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;','/':'&#x2F;','`':'&#x60;','=':'&#x3D;'})[s];
                });
            }

            // Handle select all
            $('#select-all').on('change', function() {
                const checked = $(this).is(':checked');
                $('.row-check').prop('checked', checked);
                toggleBulkBar();
            });

            // Row checkbox change
            $('#products-table').on('change', '.row-check', function () {
                toggleBulkBar();
                syncSelectAllCheckbox();
            });

            function syncSelectAllCheckbox() {
                const total = $('.row-check').length;
                const checked = $('.row-check:checked').length;
                $('#select-all').prop('checked', total > 0 && total === checked);
            }

            function toggleBulkBar() {
                const selectedCount = $('.row-check:checked').length;
                if (selectedCount > 0) {
                    $('#bulk-actions').removeClass('hidden');
                    $('#selected-count').text(`${selectedCount} item(s) selected`);
                } else {
                    $('#bulk-actions').addClass('hidden');
                    $('#bulk-action-select').val('');
                }
            }

            // Apply bulk action
            $('#apply-bulk').on('click', function () {
                const action = $('#bulk-action-select').val();
                const ids = $('.row-check:checked').map((i, el) => $(el).val()).get();
                if (!action) return alert('Please choose a bulk action.');
                if (!ids.length) return alert('Select at least one product.');

                // confirm for destructive actions
                const destructive = ['delete'].includes(action);
                if (destructive && !confirm('Are you sure? This action cannot be undone.')) return;

                $.ajax({
                    url: "{{ route('admin.products.bulk') }}",
                    method: 'POST',
                    data: {
                        action: action,
                        ids: ids,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        if (res.success) {
                            table.ajax.reload();
                            toggleBulkBar();
                            showToast(res.message || 'Bulk action completed.');
                        } else {
                            alert(res.message || 'Bulk action failed.');
                        }
                    },
                    error: function(xhr) {
                        const msg = xhr.responseJSON?.message || 'Bulk action failed.';
                        alert(msg);
                    }
                });
            });

            // Single delete (buttons inserted by actions column)
            $('#products-table').on('click', '.delete-btn', function () {
                const id = $(this).data('id');
                if (!confirm('Delete product?')) return;

                $.ajax({
                    url: `/admin/products/${id}`,
                    method: 'DELETE',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function() {
                        table.ajax.reload(null, false);
                        showToast('Product deleted.');
                    },
                    error: function() {
                        alert('Delete failed');
                    }
                });
            });

            // small toast
            function showToast(msg) {
                const t = $(`<div class="fixed bottom-6 right-6 bg-gray-900 text-white px-4 py-2 rounded shadow">${escapeHtml(msg)}</div>`);
                $('body').append(t);
                setTimeout(() => t.fadeOut(300, () => t.remove()), 3000);
            }
        });
        </script>
    @endpush
</x-app-layout>
