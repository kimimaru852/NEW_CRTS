<div
    x-data="{
        show: false,
        inventory: {},
        
            disposalYearClass(date) {
        if (!date) return '';

        const now = new Date();
        const disposalDate = new Date(date);

        // Calculate year difference
        const diffYears = disposalDate.getFullYear() - now.getFullYear();

        if (diffYears >= 2) {
            return 'text-green-800 bg-green-300 font-extrabold rounded-full px-4';
        } else if (diffYears === 1) {
            return 'text-yellow-800 bg-yellow-300 font-extrabold rounded-full px-4';
        } else if (diffYears <= 0) {
            return 'text-red-800 bg-red-300 font-extrabold rounded-full px-4';
        }

        return '';
    },

        confirmDelete(id) {
            this.deleteId = id;
            this.showDeleteModal = true;
        },
        
    init() {
        window.addEventListener('open-modal', event => {
            this.inventory = event.detail.inventory;
            this.show = true;
        });
    }
}"
    x-init="init()"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50 lg:mx-4 md:mx-4 sm:mx-2 xs:mx-0"
    style="display: none;">
    <!-- Overlay -->
    <div
        x-show="show"
        class="fixed inset-0 transform transition-all"
        x-on:click="show = false"
        x-transition:enter="ease-out duration-300"
        x-transition:leave="ease-in duration-200">
        <div class="absolute inset-0 bg-gray-500 dark:bg-stone-900 opacity-75"></div>
    </div>

    <!-- Modal Content -->
    <div x-show="show"
        class="uppercase px-3 mb-6 text-gray-900 dark:text-gray-100 bg-white dark:bg-stone-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        @php
        $loggedInUser = Auth::user();
        @endphp
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
            <h1 class="text-red-600 font-bold lg:text-lg md:text-lg sm:text-sm xs:text-sm p-4 dark:text-red-400">National Transmission Corporation</h1>
            <p class="xs:text-sm">RECORDS TURN-OVER / INVENTORY LIST FORM</p>
            <p><strong>Cost Center Head:</strong> {{ $loggedInUser->name ?? 'N/A' }}</p>
        </div>

        <div class="text-sm w-full flex justify-center py-4">
            <div class="flex-1 xs:text-sm">
                <p><strong>Office origin:</strong> <span class="underline">{{ $loggedInUser->office?->department ?? 'N/A' }}</span></p>
                <h3><strong>turn-over date: </strong><span class="underline" x-text="new Date(inventory.created_at).toLocaleDateString('en-US') ?? ' '"></span></h3>
            </div>
            <div class="flex-1 xs:text-sm">
                <h3><strong>prepared by: </strong><span class="underline" x-text="inventory.prepared_by"></span></h3>
                <h3><strong>approved by: </strong><span x-text="inventory.manager_approval ?? ' '" class="px-2 rounded-full  underline"></span></h3>
            </div>
        </div>

        <div class="space-y-2 text-sm overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-200">
                    <tr>
                        <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">item no</th>
                        <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Description</th>
                        <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Doc Date</th>
                        <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Quantity</th>
                        <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">unit Code</th>
                        <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Document Status</th>
                        <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">GRDS/RDS No</th>
                        <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Retention period</th>
                        <th class="whitespace-nowrap text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Disposal date</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-stone-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="item in inventory.items" :key="item.id">
                        <tr>
                            <td class="whitespace-nowrap px-4 py-2 text-center" x-text="item.item_no"></td>
                            <td class="whitespace-nowrap px-4 py-2 text-center" x-text="item.description"></td>
                            <td class="whitespace-nowrap px-4 py-2 text-center" x-text="item.doc_date ? new Date(item.doc_date).toLocaleDateString('en-US') : '—'"></td>
                            <td class="whitespace-nowrap px-4 py-2 text-center" x-text="item.quantity"></td>
                            <td class="whitespace-nowrap px-4 py-2 text-center" x-text="item.unit_code"></td>
                            <td class="whitespace-nowrap px-4 py-2 text-center" x-text="item.document_status"></td>
                            <td class="whitespace-nowrap px-4 py-2 text-center" x-text="item.rds_no"></td>
                            <td class="whitespace-nowrap px-4 py-2 text-center" x-text="item.retention_period ?? '—'"></td>
                            <td
                                class="whitespace-nowrap px-4 py-2 text-center"
                                :class="disposalYearClass(item.disposal_date)"
                                x-text="item.disposal_date ? new Date(item.disposal_date).toLocaleDateString('en-US') : '—'">
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <div class="text-sm flex justify-center py-4">
            <div class="flex-1">
                <h3><strong>box no.:</strong> <span x-text="inventory.id"></span></h3>
                <h3><strong>Rack No.:</strong><span x-text="inventory.rack_no"></span></h3>
                <h3><strong>location code:</strong> <span x-text="inventory.loc_code"></span></h3>
            </div>
            <div class="flex-1">
                <h3><strong>received by:</strong><span x-text="inventory.received_by" :class="inventory.received_by" class="underline"></span></h3>
                <h3><strong>date:</strong><span 
                        class="underline"
                        x-text="inventory.received_date 
                                ? new Date(inventory.received_date).toLocaleDateString('en-US') 
                                : ' '">
                    </span></h3>
                <h3><strong>validated by:</strong><span x-text="inventory.verified_by" :class="inventory.verified_by" class="underline"></span></h3>
                <h3><strong>date:</strong>
                    <span 
                        class="underline"
                        x-text="inventory.verified_date 
                                ? new Date(inventory.verified_date).toLocaleDateString('en-US') 
                                : ' '">
                    </span>
                </h3>
            </div>
        </div>

        <div class="mt-6 text-right">
            <div class="p-8 flex justify-end">
                <div>
                    <div class="flex gap-4">
                        <!-- <x-danger-button
                            type="button"
                            x-on:click="confirmDelete(inventory.id)"
                            x-show="!inventory.verified_by && !inventory.manager_approval">
                            {{ __('Reject') }}
                        </x-danger-button> -->
                        <form :action="'{{ route('inventory.approve') }}'" method="POST" x-show="!inventory.manager_approval">
                            @csrf
                            <input type="hidden" name="id" :value="inventory.id">
                            <x-primary-button type="submit">
                                {{ __('Approve') }}
                            </x-primary-button>
                        </form>
                        <!-- If already approved, disable the button -->
                        <div>
                            <template x-if="inventory.manager_approval">
                                <p class="text-blue-500 dark:text-blue-200 flex align-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    {{ __('Approved') }}
                                </p>
                            </template>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@include('modal.manager-disposal-modal')
@if (session('success'))
<div x-data="{ show: true }" x-show="show"
    class="fixed top-5 right-5 bg-green-500 text-white p-4 rounded shadow-lg"
    x-init="setTimeout(() => show = false, 3000)" class="bg-green-500 text-white p-2 rounded my-4 text-center">
    {{ session('success') }}
</div>
@endif