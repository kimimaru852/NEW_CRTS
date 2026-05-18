<div
    x-data="{
        show: false,
        inventory: { items: [] },

        init() {
            window.addEventListener('open-edit-modal', event => {
                Object.assign(this.inventory, JSON.parse(JSON.stringify(event.detail.inventory || {})));
                if (!Array.isArray(this.inventory.items)) {
                    this.inventory.items = [];
                }
                    
                this.inventory.items.forEach(item => {
                    if (item.doc_date) {
                        const d = new Date(item.doc_date);
                        item.doc_date = d.toISOString().split('T')[0]; // YYYY-MM-DD
                    }
                });

                this.show = true;
            });
        },

        addNewItem() {
            const maxItemNo = this.inventory.items.length
                ? Math.max(...this.inventory.items.map(i => parseInt(i.item_no) || 0))
                : 0;

            this.inventory.items.push({
                id: null,
                item_no: maxItemNo + 1,
                description: '',
                doc_date: '',
                quantity: '',
                unit_code: '',
                rds_no: '',
                document_status: 'Temporary',
                retention_period: null,
                inventory_id: this.inventory.id,
            });
        },

        removeItem(index) {
            if (confirm('Are you sure you want to delete this item?')) {
                this.inventory.items.splice(index, 1);
            }
        },

        save() {
            fetch(`/user/inventories/${this.inventory.id}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify(this.inventory)
            })
            .then(res => res.json())
            .then(data => {
                this.show = false;
                window.location.reload();
            })
            .catch(err => console.error(err));
        }
    }"
    x-init="init()"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 lg:mx-4 md:mx-4 sm:mx-2 xs:mx-0"
    style="display:none">
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-500 dark:bg-stone-900 opacity-75"></div>
    </div>

    <div x-show="show"
        class="uppercase px-3 mb-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-stone-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">

        <div class="flex justify-end mt-2">
            <button @click="show = false" class="text-red-300 hover:text-red-500">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        <div class="head-title text-center pt-4">
            <span class="flex justify-center">
                <img src="{{ asset('images/TranscoLogo.png') }}" class="w-[100px]" />
            </span>
            <h1 class="text-red-600 font-bold text-lg p-4 dark:text-red-400">National Transmission Corporation</h1>
            <p class="text-md">RECORDS TURN-OVER / INVENTORY EDIT FORM</p>
        </div>

        <!-- Editable Items Table -->
        <h3 class="mt-6 font-semibold">Box No. <span x-text="inventory.id"></span></h3>
        <table class="w-full text-sm border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Item No</th>
                    <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Description</th>
                    <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Doc Date</th>
                    <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Quantity</th>
                    <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Unit Code</th>
                    <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">GRDS/RDS No</th>
                    <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Document Status</th>
                    <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Retention Period</th>
                    <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Action</th>
                </tr>

            </thead>
            <tbody>
                <template x-if="Array.isArray(inventory.items)">
                    <template x-for="(item, index) in inventory.items" :key="index + '-' + (item.id ?? 'new')">
                        <tr>
                            <td class="whitespace-nowrap px-4 py-2 text-center border" x-text="item.item_no"></td>
                            <td><input type="text" x-model="item.description" class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white"></td>
                            <td><input type="date" x-model="item.doc_date" class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white"></td>
                            <td><input type="text" x-model="item.quantity" class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white"></td>
                            <td>
                                <select x-model="item.unit_code" class="form-select w-full border rounded dark:bg-stone-800 text-stone-800 dark:text-white">
                                    <option value="" disabled>-- Select Unit --</option>
                                    <option value="Folder">Folder</option>
                                    <option value="Molar">Molar</option>
                                    <option value="Binder">Binder</option>
                                </select>
                            </td>
                            <td><input type="number" x-model="item.rds_no" class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white"></td>
                            <td>
                                <select x-model="item.document_status" class="form-select w-full border rounded dark:bg-stone-800 text-stone-800 dark:text-white">
                                    <option value="Permanent">Permanent</option>
                                    <option value="Temporary">Temporary</option>
                                </select>
                            </td>
                            <td>
                                <input type="number"
                                    x-model="item.retention_period"
                                    x-effect="if (item.document_status === 'Permanent') item.retention_period = null"
                                    :disabled="item.document_status === 'Permanent'"
                                    placeholder="Years"
                                    class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white">
                            </td>
                            <td class="text-center">
                                <button type="button"
                                    class="text-red-600 hover:text-red-800 font-bold"
                                    @click="removeItem(index)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                </template>
            </tbody>
        </table>

        <div class="mt-4 flex justify-end">
            <button
                type="button"
                @click="addNewItem()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm font-semibold">
                Add Item
            </button>
        </div>

        <!-- Save Button -->
        <div class="mt-6 py-6 flex justify-end">
            <button @click="save" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Save Changes</button>
        </div>
    </div>
</div>