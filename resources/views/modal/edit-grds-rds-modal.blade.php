<div
    x-data="{
        show: false,
        form: {},
        isPermanent() {
            return this.form.document_status === 'Permanent';
        }
    }"
    x-on:open-edit-modal.window="show = true; form = $event.detail"
    x-show="show"
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="display:none;">

    <div class="bg-white dark:bg-stone-800 p-6 rounded w-[500px]">

        <h2 class="text-xl font-bold mb-4">
            Update GRDS/RDS List
        </h2>

        <form method="POST" :action="`/admin/grds/update/${form.id}`">
            @csrf
            @method('PUT')

            <!-- Description -->
            <div class="mb-3">
                <label>Description</label>
                <input
                    type="text"
                    name="description"
                    x-model="form.description"
                    class="w-full border p-2">
            </div>

            <!-- GRDS/RDS No -->
            <div class="mb-3">
                <label>GRDS/RDS No</label>
                <input
                    type="text"
                    name="grds_rds_no"
                    x-model="form.grds_rds_no"
                    class="w-full border p-2">
            </div>

            <!-- Document Status Dropdown -->
            <div class="mb-3">
                <label>Document Status</label>

                <select
                    name="document_status"
                    x-model="form.document_status"
                    @change="
                            if(form.document_status === 'Permanent') {
                                form.retention_period = '';
                            }
                        "
                    class="w-full border p-2 dark:bg-stone-800 text-stone-800 dark:text-white">

                    <option value="" disabled>
                        -- Select Status --
                    </option>

                    <option value="Permanent">
                        Permanent
                    </option>

                    <option value="Temporary">
                        Temporary
                    </option>
                </select>
            </div>

            <!-- Retention Period -->
            <div class="mb-3">
                <label>Retention Period</label>

                <input
                    type="text"
                    name="retention_period"
                    x-model="form.retention_period"
                    :disabled="isPermanent()"
                    :class="{ 'bg-gray-200 cursor-not-allowed': isPermanent() }"
                    class="w-full border p-2">
            </div>

            <div class="flex justify-end gap-2">
                <button
                    type="button"
                    @click="show = false"
                    class="px-3 py-1 bg-gray-400">
                    Cancel
                </button>

                <x-green-button type="submit">
                    Save
                </x-green-button>
            </div>

        </form>
    </div>
</div>