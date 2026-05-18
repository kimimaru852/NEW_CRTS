<div
    x-data="{
        show: false,
        officeId: null,
        init() {
            window.addEventListener('open-modal', event => {
                if (event.detail.name === 'delete-office') {
                    this.officeId = event.detail.officeId;  // Set the userId from the event
                    this.show = true;  // Show the modal
                }
            });
        }
    }"
    x-init="init()"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;">
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

    <div
        x-show="show"
        class="mb-6 bg-white dark:bg-stone-800 rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:max-w-2xl sm:mx-auto"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        <div class="p-6">

           

            <form :action="'/admin/office/' + officeId" method="POST">
                @csrf
                @method('DELETE')
                <div class="mt-6">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Are you sure you want to delete this department?') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ __('Once this department is deleted, all of its resources including Users and Inventories listed under this department will be permanently deleted. Please enter your password to confirm you would like to permanently delete this department.') }}
                    </p>
                    <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                    <x-text-input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('Password') }}" />

                    <x-input-error :messages="$errors->officeDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-2 flex items-center">
                    <input type="checkbox" id="show-password" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" onclick="togglePassword()">
                    <label for="show-password" class="ms-2 text-sm text-gray-600 dark:text-gray-400">Show Password</label>
                </div>

                <div class="mt-6 gap-4 flex justify-end">
                    <x-secondary-button x-on:click="show = false">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-danger-button id="submitButton">
                        {{ __('Confirm Delete') }}
                    </x-danger-button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/registerTogglePassword.js') }}"></script>