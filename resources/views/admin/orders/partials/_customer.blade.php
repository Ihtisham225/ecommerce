<div 
    class="bg-white p-6 rounded-lg shadow-sm border border-gray-200"
    x-data="customerManager({{ $order->id }})"
    x-init="init()"
    @click.outside="showDropdown = false"
>

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900">Customer Information</h3>
                <p class="text-sm text-gray-500">Search for an existing customer or create a new one</p>
            </div>
        </div>

        <button
            @click="showCreateModal = true"
            class="flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg shadow-sm hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Customer
        </button>
    </div>

    <!-- SEARCH SECTION -->
    <div class="mb-4">
        <label for="customer-search" class="block text-sm font-medium text-gray-700 mb-1">Search Customers</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <input 
                id="customer-search"
                type="text"
                x-model="search"
                @input.debounce.300ms="searchCustomers"
                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                placeholder="Search by name, email, or phone..."
                @focus="showDropdown = true"
                :disabled="loading"
            >
            
            <!-- Loading indicator -->
            <div x-show="loading" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <svg class="animate-spin h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        </div>

        <!-- DROPDOWN -->
        <div class="relative">
            <div 
                x-show="showDropdown && (filtered.length || search.length >= 1)"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-1"
                class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto z-20"
                style="display: none;"
            >
                <template x-if="filtered.length">
                    <template x-for="cust in filtered" :key="cust.id">
                        <div
                            class="px-4 py-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0 transition-colors"
                            @click="selectCustomer(cust)"
                        >
                            <div class="font-medium text-gray-900" x-text="cust.first_name"></div>
                            <div class="flex justify-between text-sm text-gray-600 mt-1">
                                <span x-text="cust.email || 'No email'"></span>
                                <span x-text="cust.phone || 'No phone'"></span>
                            </div>
                        </div>
                    </template>
                </template>
                
                <div x-show="search.length >= 1 && !filtered.length && !loading" class="px-4 py-3 text-center text-gray-500">
                    No customers found. 
                    <button 
                        @click="showCreateModal = true" 
                        class="text-indigo-600 hover:text-indigo-800 font-medium ml-1"
                    >
                        Create a new customer?
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- SELECTED CUSTOMER -->
    <template x-if="selected">
        <div class="mt-4 p-4 bg-indigo-50 rounded-lg border border-indigo-100 transition-all">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="font-semibold text-gray-900" x-text="selected.first_name + ' ' + selected.last_name"></span>
                    </div>
                    <div class="text-sm text-gray-700 flex flex-col sm:flex-row sm:gap-4">
                        <span class="flex items-center gap-1" x-show="selected.email">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <span x-text="selected.email"></span>
                        </span>
                        <span class="flex items-center gap-1 mt-1 sm:mt-0" x-show="selected.phone">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span x-text="selected.phone"></span>
                        </span>
                    </div>
                </div>
                <button 
                    class="text-red-600 hover:text-red-800 p-1 rounded transition-colors"
                    @click="removeCustomer()"
                    title="Remove customer"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        </div>
    </template>

    <!-- CREATE CUSTOMER MODAL -->
    <div 
        x-show="showCreateModal"
        x-cloak
        class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    >
        <div 
            class="bg-white rounded-xl shadow-xl w-full max-w-md"
            @click.outside="showCreateModal = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Create New Customer</h2>
                <p class="text-sm text-gray-600 mt-1">Add a new customer to your database</p>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input 
                                type="text" 
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                x-model="form.first_name"
                                placeholder="John"
                            >
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input 
                                type="text" 
                                class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                                x-model="form.last_name"
                                placeholder="Doe"
                            >
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input 
                            type="text" 
                            class="w-full rounded-lg border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" 
                            x-model="form.phone"
                            placeholder="(123) 456-7890"
                        >
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button 
                        @click="showCreateModal = false" 
                        class="px-4 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="createCustomer" 
                        class="px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 flex items-center gap-2"
                        :disabled="creating"
                    >
                        <svg x-show="creating" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="creating ? 'Creating...' : 'Create Customer'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    <div 
        x-show="message.text"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        class="mt-4 p-3 rounded-lg"
        :class="{
            'bg-green-50 text-green-800 border border-green-200': message.type === 'success',
            'bg-red-50 text-red-800 border border-red-200': message.type === 'error'
        }"
    >
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <template x-if="message.type === 'success'">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </template>
                <template x-if="message.type === 'error'">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </template>
            </svg>
            <span x-text="message.text"></span>
        </div>
    </div>

</div>

<script>
    function customerManager(orderId) {
        return {
            search: "",
            filtered: [],
            showDropdown: false,
            selected: @json($order->customer ?? null),
            loading: false,
            creating: false,
            
            form: {
                first_name: "",
                last_name: "",
                email: "",
                phone: "",
            },
            
            message: {
                text: "",
                type: "success"
            },
            
            showCreateModal: false,
            
            init() {
                // Close dropdown when clicking outside
                document.addEventListener('click', (e) => {
                    if (!this.$el.contains(e.target)) {
                        this.showDropdown = false;
                    }
                });
            },
            
            showMessage(text, type = "success") {
                this.message.text = text;
                this.message.type = type;
                
                // Auto-hide message after 5 seconds
                setTimeout(() => {
                    this.message.text = "";
                }, 5000);
            },
            
            searchCustomers() {
                if (this.search.length < 1) {
                    this.filtered = [];
                    this.showDropdown = false;
                    return;
                }
                
                this.loading = true;
                
                fetch(`/admin/customers/search?q=${this.search}`)
                    .then(res => res.json())
                    .then(data => {
                        this.filtered = data.customers || [];
                        this.loading = false;
                    })
                    .catch(err => {
                        console.error("Search error:", err);
                        this.loading = false;
                        this.showMessage("Failed to search customers", "error");
                    });
            },
            
            selectCustomer(cust) {
                this.selected = cust;
                this.showDropdown = false;
                this.search = "";
                
                // Dispatch event to parent form with customer data
                this.$dispatch('customer-selected', { 
                    customer_id: cust.id,
                    customer: cust
                });
                
                fetch(`/admin/orders/${orderId}/customer/select`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ customer_id: cust.id })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.showMessage("Customer successfully assigned to order");
                    } else {
                        this.showMessage("Failed to assign customer", "error");
                    }
                })
                .catch(err => {
                    console.error("Select customer error:", err);
                    this.showMessage("Failed to assign customer", "error");
                });
            },
            
            removeCustomer() {
                this.selected = null;
                
                // Dispatch event to parent form to clear customer_id
                this.$dispatch('customer-removed');
                
                fetch(`/admin/orders/${orderId}/customer/remove`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        this.showMessage("Customer removed from order");
                    } else {
                        this.showMessage("Failed to remove customer", "error");
                    }
                })
                .catch(err => {
                    console.error("Remove customer error:", err);
                    this.showMessage("Failed to remove customer", "error");
                });
            },
            
            createCustomer() {
                if (!this.form.first_name || !this.form.last_name) {
                    this.showMessage("First and last name are required", "error");
                    return;
                }
                
                this.creating = true;
                
                fetch(`/admin/customers`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.form)
                })
                .then(res => res.json())
                .then(data => {
                    this.creating = false;
                    
                    if (data.success) {
                        this.selected = data.customer;
                        this.showCreateModal = false;
                        this.form = { first_name: "", last_name: "", email: "", phone: "" };
                        
                        // Dispatch event to parent form with new customer data
                        this.$dispatch('customer-selected', { 
                            customer_id: data.customer.id,
                            customer: data.customer
                        });
                        
                        fetch(`/admin/orders/${orderId}/customer/select`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ customer_id: data.customer.id })
                        });
                        
                        this.showMessage("Customer created successfully");
                    } else {
                        this.showMessage(data.message || "Failed to create customer", "error");
                    }
                })
                .catch(err => {
                    console.error("Create customer error:", err);
                    this.creating = false;
                    this.showMessage("Failed to create customer", "error");
                });
            }
        }
    }
</script>