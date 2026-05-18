<x-app-layout>
    <div class="link text-gray-700 dark:text-gray-200 flex justify-between mt-2 px-4 my-6 font-bold">
        <div class="flex underline underline-offset-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
            <a href="{{ route('admin.manage-accounts') }}">Manage Accounts</a>
        </div>

        <div class="flex underline underline-offset-4">
            <a href="{{ route('admin.office') }}">Manage Cost Center</a>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </div>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('admin.register') }}" class="dark:bg-stone-800 mx-6 bg-gray-200 rounded-lg mt-2 " id="registerForm">
        <h2 class="py-4 px-6 bg-green-600 rounded-t-lg text-xl font-bold text-white">Register Cost Center Head
            <p class="text-sm font-semibold">all fields are required</p>
        </h2>
        
        @csrf
        <div class="lg:p-8 md:p-10 mx-10">

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" class="dark:text-gray-300" />
                <x-text-input placeholder="Ex. Juan P. Dela Cruz" id="name" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" class="dark:text-gray-300" />
                <x-text-input placeholder="Ex. jpdelacruz@transco.ph" id="email" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" class="dark:text-gray-300" />
                <x-text-input placeholder="Password" id="password" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="dark:text-gray-300" />
                <x-text-input placeholder="Confirm Password" id="password_confirmation" class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="office_id" :value="__('Cost Center')" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Select Status</x-input-label>
                <select name="office_id" id="office_id" class="block w-full p-2.5 border border-gray-300 rounded-lg shadow-sm focus:ring-gray-500 focus:border-gray-500 text-gray-700 dark:text-gray-300 dark:bg-stone-900 ">
                    <option value="" disabled selected hidden>-- Select Cost Center --</option>

                    @foreach($offices as $office)
                    <option value="{{ $office->id }}">{{ $office->department }}</option>
                    @endforeach
                </select>

                @error('office_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Already Registered -->
            <div class="flex flex-col sm:flex-row items-center justify-between mt-4">

                <!-- Checkbox to Show/Hide Password -->
                <div class="mt-2 flex items-center">
                    <input type="checkbox" id="show-password" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" onclick="togglePassword()">
                    <label for="show-password" class="ms-2 text-sm text-gray-600 dark:text-gray-400">Show Password</label>
                </div>
                <!-- Submit Button -->
                <x-green-button id="submitButton">
                    {{ __('create new cost center head') }}
                </x-green-button>
            </div>
        </div>
    </form>

</x-app-layout>

<script src="{{ asset('js/disabledbutton.js') }}"></script>
<script src="{{ asset('js/registerTogglePassword.js') }}"></script>