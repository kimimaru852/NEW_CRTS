<x-app-layout>
    <div class="link text-gray-700 dark:text-gray-200 flex justify-between mt-2 px-4 font-bold">
        <div class="flex underline underline-offset-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <a href="{{ route('admin.registerForm') }}">Add Cost Center Head</a>
        </div>

        <div class="flex underline underline-offset-4">
            <a href="{{ route('admin.reports') }}">Reports</a>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </div>
    </div>

    <!-- Office Department -->
    <form method="POST" action="{{ route('admin.storeOffice') }}" class="dark:bg-stone-800 bg-gray-200 rounded-lg mx-6 mt-2" id="registerForm">
        <h2 class="py-4 px-6 bg-green-600 rounded-t-lg text-xl font-bold text-white">Add Cost Center</h2>
        @csrf
        <div class="p-10">
            <!-- office name -->
            <div>
                <x-input-label for="office_dept" :value="__('Cost Center')" class="dark:text-gray-300 text-md" />
                <x-text-input placeholder="Ex. General Services Division" id="department" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="text" name="department" :value="old('department    ')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>


            <!-- Already Registered -->
            <div class="flex flex-col sm:flex-row items-center justify-between mt-4">
                <!-- Submit Button -->
                <x-green-button id="officeBtn">
                    {{ __('register new Cost Center') }}
                </x-green-button>
            </div>
        </div>
    </form>

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

    @include('admin.office.department')
</x-app-layout>