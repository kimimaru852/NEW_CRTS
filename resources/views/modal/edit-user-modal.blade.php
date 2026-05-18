<div
    x-data="{
        show: false,
        userId: null,
        userName: '',
        userEmail: '',
        init() {
            window.addEventListener('open-modal', event => {
                if (event.detail.name === 'edit-user') {
                    this.userId = event.detail.userId;
                    this.userName = event.detail.userName;
                    this.userEmail = event.detail.userEmail;
                    this.show = true;
                }
            });
        }
    }"
    x-init="init()"
    x-show="show"
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
    style="display: none;">

    <!-- Overlay (Backdrop) -->
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

    <!-- Modal Content -->
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
            <form :action="'{{ url('/admin/users') }}/' + userId" method="POST">

                @csrf
                @method('PUT')

                <div class="flex justify-center text-sm mt-2 pb-4 text-gray-800 dark:text-gray-200">
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                            <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                            <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                        </svg>
                    </span>
                    <h3 class="text-lg">
                        Edit details of <span x-text="userName"></span>
                    </h3>
                </div>
                <!-- name -->
                <div class="mt-6">
                    <x-input-label for="name" :value="__('Name')" class="dark:text-gray-300" />
                    <x-text-input name="name" x-model="userName" type="text" class="mt-1 w-full" required />
                </div>
                <!-- email -->
                <div class="mt-6">
                    <x-input-label for="email" :value="__('Email')" class="dark:text-gray-300" />
                    <x-text-input name="email" x-model="userEmail" type="email" class="mt-1 w-full" required />
                </div>
                <!-- password -->
                <div class="mt-6">
                    <x-input-label for="password" :value="__('Password')" class="dark:text-gray-300" />
                    <x-text-input name="password" type="password" placeholder="Leave blank to keep current password" class="mt-1 w-full" />
                </div>
                
                <div class="mt-6 gap-4 flex justify-end">
                    <x-secondary-button x-on:click="show = false">
                        Cancel
                    </x-secondary-button>

                    <x-green-button type="submit">
                        Save Changes
                    </x-green-button>
                </div>

            </form>
        </div>

    </div>
</div>
