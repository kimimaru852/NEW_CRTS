<div>
    <div class="mt-4">
        <div class="mx-6">
            <div class="bg-zinc-200 dark:bg-stone-800 shadow overflow-hidden sm:rounded-lg">
                <table id="inventory-table" class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Cost Center ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Cost Center Name(Double click to edit)</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Delete</th>
                        </tr>
                    </thead>
                    <tbody class="bg-zinc-200 dark:bg-stone-900">
                        @foreach ($offices as $office)
                        <tr class="border-b border-gray-300 dark:border-stone-700 hover:bg-gray-100 dark:hover:bg-stone-800">
                            <td class="py-3 px-6 text-left text-gray-700 dark:text-gray-200"><span>CCID</span>{{ $office->id }}</td>

                            <!-- display department name and double click to edit -->
                            <td class="py-3 px-6 text-left text-gray-700 dark:text-gray-200"
                                x-data="{ editing: false, department: '{{ $office->department }}', original: '{{ $office->department }}' }"
                                x-on:dblclick="editing = true; $nextTick(() => $refs.input.focus())"
                                @click.outside="editing = false">
                                <!-- Display -->
                                <span x-show="!editing" x-text="department"></span>

                                <!-- Edit input -->
                                <input x-show="editing"
                                    x-ref="input"
                                    type="text"
                                    x-model="department"
                                    class="w-full bg-white dark:bg-stone-700 text-gray-800 dark:text-gray-100 border border-gray-300 dark:border-stone-600 rounded px-2 py-1"
                                    @keydown.enter.prevent="
                fetch('{{ route('department.update', $office->id) }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ department })
                })
                .then(res => {
                    if (!res.ok) throw new Error('Failed to save');
                    editing = false;
                    original = department;
                })
                .catch(err => {
                    alert('Error: ' + err.message);
                    department = original;
                    editing = false;
                });
           "
                                    @keydown.escape="editing = false; department = original"
                                    @blur="editing = false; department = original">
                            </td>
                            <!-- delete button -->
                            <td>
                                <x-danger-button type="submit" x-data=""
                                    x-on:click.prevent="$dispatch('open-modal', { name: 'delete-office', officeId: {{ $office->id }} })">
                                    Delete
                                </x-danger-button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

@include('admin.office.delete-office-modal')