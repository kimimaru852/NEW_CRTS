<div
    x-data="{ showModal: false, deleteUrl: '' }"
    x-show="showModal"
    style="display: none"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow-xl w-96">
        <h2 class="text-lg font-bold mb-4">Confirm Deletion</h2>
        <p class="mb-6">Are you sure you want to delete this item? This action cannot be undone.</p>
        <div class="flex justify-end space-x-4">
            <button
                @click="showModal = false"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 rounded hover:bg-gray-400">
                Cancel
            </button>
            <form :action="deleteUrl" method="POST">
                @csrf
                @method('DELETE')
                <x-danger-button type="submit">
                    {{ __('Delete') }}
                </x-danger-button>
            </form>
        </div>
    </div>
</div>