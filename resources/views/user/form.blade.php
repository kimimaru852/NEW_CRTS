<x-app-layout>
    <div class="link text-gray-700 dark:text-gray-200 flex justify-between mt-2 px-4 font-bold">
        <div class="flex underline underline-offset-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <a href="{{ route('user.index') }}">RTO Inventory</a>
        </div>

        <div class="flex underline underline-offset-4">
            <a href="{{ route('user.reports') }}">Reports</a>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </div>
    </div>
    <form method="POST" action="{{ route('user.form') }}" class="bg-gray-200 dark:bg-stone-800 rounded-lg mx-4 md:mx-10 mt-6 shadow-lg">
        @csrf
        <div class="rounded-t-lg bg-green-500 px-6 py-5">
            <h2 class="text-2xl font-bold text-white"> RTO Inventory Form</h2>
            <p class="text-sm font-medium text-green-100">All fields are required.</p>
        </div>

        <div class="p-6 md:p-10" x-data="{ items: [{}] }">

            <template x-for="(item, index) in items" :key="index">
                <div class="border border-stone-300 dark:border-stone-600 p-6 rounded-lg mb-6 bg-white dark:bg-stone-700 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4"> Items #<span x-text="index + 1"></span></h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Description -->
                        <div>
                            <x-input-label :value="__('Document Description')" />
                            <input required type="text" :name="'items[' + index + '][description]'" x-model="item.description" placeholder="Ex. Inspection and Receiving Report" class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white">
                        </div>

                        <!-- Doc Date -->
                        <div>
                            <x-input-label :value="__('Doc Date')" />
                            <input required type="date" :name="'items[' + index + '][doc_date]'" x-model="item.doc_date" class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white">
                        </div>

                        <!-- unit_code -->
                        <div>
                            <x-input-label :value="__('Unit Code')" />
                            <select required
                                :name="'items[' + index + '][unit_code]'"
                                x-model="item.unit_code"
                                class="form-select w-full dark:bg-stone-800 text-stone-800 dark:text-white">
                                <option value="" disabled selected hidden>-- Select Unit --</option>
                                <option value="Folder">Folder</option>
                                <option value="Molar">Molar</option>
                                <option value="Binder">Binder</option>
                            </select>
                        </div>

                        <!-- quantity -->
                        <div>
                            <x-input-label :value="__('Quantity')" />
                            <input required type="text" :name="'items[' + index + '][quantity]'" x-model="item.quantity" placeholder="Ex. 1" class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white">
                        </div>

                        <!-- Retention Period -->
                        <div x-effect="if (item.document_status === 'Permanent') item.retention_period = ''">
                            <x-input-label :value="__('Retention Period (years)')" />
                            <input type="number"
                                :name="'items[' + index + '][retention_period]'"
                                x-model="item.retention_period"
                                placeholder="Ex. 1"
                                class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white"
                                :disabled="item.document_status === 'Permanent'"
                                :class="item.status === 'Permanent' ? 'bg-gray-300 dark:bg-gray-600 cursor-not-allowed' : ''">
                        </div>
                        <!-- RDS_No -->
                        <div>
                            <x-input-label :value="__('GRDS/RDS No')" />
                            <input type="number"
                                :name="'items[' + index + '][rds_no]'"
                                x-model="item.rds_no"
                                placeholder="Ex. 1 (Kindly check on the latest TransCo RDS or NAP GRDS)"
                                class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white">
                        </div>

                        <!-- Status -->
                        <div>
                            <x-input-label :value="__('Document Status')" />
                            <select :name="'items[' + index + '][document_status]'" x-model="item.document_status" class="form-select w-full dark:bg-stone-800 text-stone-800 dark:text-white">
                                <option value="" disabled selected hidden>-- Select Status --</option>
                                <option value="Permanent">Permanent</option>
                                <option value="Temporary">Temporary</option>
                            </select>
                        </div>
                    </div>

                    <!-- Remove Button -->
                    <div class="mt-4 text-right" x-show="items.length > 1">
                        <button type="button"
                            class="text-sm bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-500 transition"
                            @click="items.splice(index, 1)">
                            Remove Item
                        </button>
                    </div>
                </div>
            </template>

            <!-- Add Another Item Button -->
            <div class="flex justify-start mb-8">
                <x-primary-button type="button" @click="items.push({})">
                    Add Another Item
                </x-primary-button>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md font-semibold transition">
                    Submit Inventory
                </button>
            </div>
        </div>
    </form>


    <!-- Flash Messages -->
    @if(session('error'))
    <div x-data="{ show: true }" x-show="show"
        class="fixed top-5 right-5 bg-red-500 text-white p-4 rounded shadow-lg"
        x-init="setTimeout(() => show = false, 3000)">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show"
        class="fixed top-5 right-5 bg-green-500 text-white p-4 rounded shadow-lg"
        x-init="setTimeout(() => show = false, 3000)">
        <p>{{ session('success') }}</p>
    </div>
    @endif
</x-app-layout>