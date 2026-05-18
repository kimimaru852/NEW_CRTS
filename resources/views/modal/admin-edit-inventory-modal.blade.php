<div
    x-data="{ show: false, inventory: {} }"
    x-on:edit-modal.window="show = true; inventory = $event.detail.inventory"
    x-show="show"
    class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center z-50">
    <div class="text-gray-900 dark:text-gray-100 bg-white dark:bg-stone-800 rounded-lg w-full max-w-md px-4 py-8">
        <h2 class="text-lg font-bold mb-4 uppercase">Rack No. & Loc Code Form</h2>

        <form
            x-on:submit.prevent="
                fetch(`/admin/inventories/${inventory.id}/update`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        rack_no: inventory.rack_no,
                        loc_code: inventory.loc_code
                    })
                }).then(() => {
                    show = false;
                    window.location.reload();
                })
            ">

            <label class="block mb-2">Rack No:</label>
            <input type="number" x-model="inventory.rack_no" placeholder="Ex. 123..." class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-60 mb-4">

            <!-- Location Code -->
            <label class="block mb-2">Location Code:</label>
            <select x-model="inventory.loc_code"
                class="block mt-1 w-full dark:bg-gray-700 dark:text-white dark:border-gray-600 mb-4 rounded">
                <option value="">-- Select Location --</option>
                <option value="TranCo Head Office">TransCo Head Office</option>
                <option value="TransCo Baesa Warehouse">TransCo Baesa Warehouse</option>
                <option value="TransCo Dormitory">TransCo Dormitory</option>
            </select>

            <div class="flex justify-end space-x-2">
                <x-secondary-button type="button" x-on:click="show = false" class="px-4 py-2">Cancel</x-secondary-button>
                <x-green-button type="submit" class="px-4 py-2 bg-green-600 text-white rounded">Save</x-green-button>
            </div>
        </form>
    </div>
</div>