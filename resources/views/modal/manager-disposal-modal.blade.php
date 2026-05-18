<div
    x-cloak
    x-show="showDeleteModal"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
    x-transition>
    <div class="bg-white dark:bg-stone-800 p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-4">Confirm Reject</h2>
        <p class="text-gray-700 dark:text-gray-300">Are you sure you want to delete this inventory?</p>

        <div class="mt-6 flex justify-end space-x-2">
            <x-secondary-button x-on:click="showDeleteModal = false">Cancel</x-secondary-button>

            <form
                :action="`{{ url('manager/inventory') }}/${deleteId}`"
                method="POST"
                x-ref="deleteForm">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">
                    Yes, Reject
                </x-danger-button>
            </form>
        </div>
    </div>
</div>