<div
    x-cloak
    x-show="showReturnModal"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
    x-transition>
    <div class="bg-white dark:bg-stone-800 p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Confirm Return</h2>
        <p class="text-gray-700 dark:text-gray-300">do want to return this inventory to the owner?</p>

        <div class="mt-6 flex justify-end space-x-2">
            <x-secondary-button x-on:click="showReturnModal = false">Cancel</x-secondary-button>

            <form
                :action="`{{ url('admin/return') }}/${returnId}`"
                method="POST"
                x-ref="returnForm">
                @csrf
                @method('PUT')
                <x-green-button type="submit">
                    Yes, Return
                </x-green-button>
            </form>
        </div>
    </div>
</div>