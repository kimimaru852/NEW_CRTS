<div x-show="showDisposeModal"
    x-transition
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-stone-700 rounded-lg shadow-lg p-6 w-full max-w-md mx-auto">
        <h2 class="text-xl font-bold text-red-600 dark:text-red-400 mb-4">Confirm Disposal</h2>
        <p class="text-gray-700 dark:text-gray-200 mb-4">Please select a disposal date and confirm:</p>

        <!-- Date input -->
        <div class="mb-6">
            <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">
                Disposal Date
            </label>
            <input type="date" x-model="disposedDate"
                class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-stone-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>
        <div class="mb-4">
            <label class="block text-gray-700 dark:text-gray-200 font-semibold mb-2">
                NAP Authority No.
            </label>

            <input
                type="text"
                x-model="napAuthorityNo"
                placeholder="Ex. AV-2024-246"
                class="w-full px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-stone-800 text-gray-900 dark:text-gray-100">
        </div>

        <div class="flex justify-end space-x-4">
            <button @click="showDisposeModal = false"
                class="px-4 py-2 bg-gray-300 dark:bg-stone-600 text-gray-800 dark:text-white rounded hover:bg-gray-400 dark:hover:bg-stone-500">
                Cancel
            </button>
            <button @click="disposeInventory"
                :disabled="!disposedDate"
                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed">
                Yes, Dispose
            </button>
        </div>
    </div>
</div>