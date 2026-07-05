<x-app-layout>
    <div class="link text-gray-700 dark:text-gray-200 flex justify-between mt-2 px-4 my-6 font-bold">
        <div class="flex underline underline-offset-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <a href="{{ route('admin.index') }}">Records Turn Over </a>
        </div>

        <div class="flex underline underline-offset-4">
            <a href="{{ route('admin.manage-accounts') }}">Manage Accounts</a>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </div>
    </div>

    <form id="grds-list-form" method="POST" action="{{ route('admin.creategrdslist') }}" class="mx-6 mt-2 bg-white">
        <div>
            <h2 class="py-4 px-6 bg-gradient-to-r from-emerald-500/90 to-green-600/90 backdrop-blur rounded-t-lg text-xl font-bold text-white">Create GRDS/RDS lists
                <p class="text-sm font-semibold">all fields are required</p>
            </h2>


            @csrf
            <div class="lg:p-8 md:p-10 mx-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2">
                    <!-- description -->
                    <div>
                        <x-input-label for="description" :value="__('description')" class="dark:text-gray-300 capitalize" />
                        <x-text-input placeholder="Ex. Juan P. Dela Cruz" id="description" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="text" name="description" :value="old('description')" required autofocus autocomplete="description" />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <!-- RDS_No -->
                    <div>
                        <x-input-label :value="__('GRDS/RDS No')" />
                        <input type="number"
                            name="grds_rds_no"
                            placeholder="Ex. 1 (Kindly check on the latest TransCo RDS or NAP GRDS)"
                            class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-2" x-data="{ document_status: '', retention_period: '' }">
                    <!-- Retention Period -->
                    <div x-effect="if (document_status === 'Permanent') retention_period = ''">
                        <x-input-label :value="__('Retention Period (years)')" />
                        <input type="number"
                            name="retention_period"
                            x-model="retention_period"
                            placeholder="Ex. 1"
                            class="form-input w-full dark:bg-stone-800 text-stone-800 dark:text-white"
                            :disabled="document_status === 'Permanent'"
                            :class="document_status === 'Permanent' ? 'bg-gray-300 dark:bg-gray-600 cursor-not-allowed' : ''">
                    </div>

                    <!-- Status -->
                    <div>
                        <x-input-label :value="__('Document Status')" />
                        <select name="document_status" x-model="document_status" class="form-select w-full dark:bg-stone-800 text-stone-800 dark:text-white">
                            <option value="" disabled selected hidden>-- Select Status --</option>
                            <option value="Permanent">Permanent</option>
                            <option value="Temporary">Temporary</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col sm:flex-row items-center justify-end mt-4 ">
                    <x-green-button>
                        {{ __('Submit') }}
                    </x-green-button>
                </div>
            </div>
        </div>

    </form>

    <div class="flex justify-end mx-6 mt-4">
        <a href="{{ route('admin.print-grds-list') }}"
            target="_blank"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded shadow">
            Print PDF
        </a>
    </div>

    @if(session('error'))
    <div x-data="{ show: true }" x-show="show"
        class="fixed top-5 right-5 z-50 bg-red-500 text-white p-4 rounded shadow-lg"
        x-init="setTimeout(() => show = false, 3000)">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show"
        class="fixed top-5 right-5 z-50 bg-green-500 text-white p-4 rounded shadow-lg"
        x-init="setTimeout(() => show = false, 3000)">
        <p>{{ session('success') }}</p>
    </div>
    @endif
    @include('admin.grds-rds-lists.grds-rds-lists')

</x-app-layout>