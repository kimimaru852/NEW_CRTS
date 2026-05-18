<x-app-layout>
    <div class="link text-gray-700 dark:text-gray-200 flex justify-between mt-2 px-4 font-bold">
        <div>
            <h1 class="text-xl font-bold text-green-400">Welcome, <span class="capitalize text-stone-800 dark:text-slate-50">{{Auth::user()->name}}</span></h1>
        </div>

        <div class="flex underline underline-offset-4">
            <a href="{{ route('form') }}">Form</a>

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
            </svg>
        </div>
    </div>

    <div class="text-gray-900 dark:text-gray-100 w-full py-6 ">
        <h3 class="text-gray-800 dark:text-green-200 py-4 px-4 font-bold text-lg">Total Records: {{ $inventories }}</h3>
        <div class="text-xl mx-2 py-6 px-4 rounded-t-lg bg-stone-600 text-gray-50 font-bold">Records Turn-Over Inventory List</div>
        <div>
            <div class="px-2">
                <div class="bg-zinc-200 dark:bg-stone-800 shadow overflow-hidden">
                    <!-- show table when desktop mode -->
                    <div>
                        <div class="bg-white dark:bg-stone-800 p-4 shadow overflow-hidden sm:rounded-l">
                            <table id="inventory-table" class="display nowrap dt-responsive text-center min-w-full divide-y divide-gray-200 dark:divide-gray-700 drop-shadow-md shadow-stone-500" style="width:100%">
                                <thead class="bg-gray-50 dark:bg-gray-200">
                                    <tr>
                                        <th class="text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">box no.</th>
                                        <th class="text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">cost center head</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Turn-Over Date</th>
                                        <th class="text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Approval Status(Head)</th>
                                        <th class="text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Status</th>
                                        <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Loc Code</th>
                                        <th class="text-center px-6 py-3 text-xs font-bold text-gray-500 dark:text-green-900 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                            </table>


                            <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
                            <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

                            <script>
                                $(function() {
                                    $('#inventory-table').DataTable({
                                        processing: true,
                                        serverSide: true,
                                        responsive: true,
                                        ajax: "{{ route('user.index') }}",
                                        columns: [{
                                                data: 'id',
                                                name: 'id'
                                            },
                                            {
                                                data: 'manager_approval',
                                                name: 'manager_approval'
                                            },
                                            {
                                                data: 'created_at',
                                                name: 'created_at'
                                            },
                                            {
                                                data: 'manager_approval',
                                                name: 'manager_approval',
                                                createdCell: function(td, cellData) {
                                                    td.classList.add("font-semibold", "text-center");
                                                    if (!cellData) {
                                                        td.textContent = "Pending...";
                                                        td.classList.add("bg-yellow-200", "text-yellow-800");
                                                    } else {
                                                        td.textContent = "Approved";
                                                        td.classList.add("bg-green-200", "text-green-800");
                                                    }
                                                }
                                            },
                                            {
                                                data: 'disposal_status',
                                                name: 'disposal_status',
                                                width: '200px',
                                                createdCell: function(td, cellData) {
                                                    if (!cellData) return;
                                                    let value = cellData.toLowerCase();
                                                    td.classList.add("font-semibold", "text-center");
                                                    if (value === 'verified by admin') {
                                                        td.classList.add("bg-green-200", "text-green-800");
                                                    } else if (value === 'received by admin') {
                                                        td.classList.add("bg-blue-200", "text-blue-800");
                                                    } else if (value === 'returned') {
                                                        td.classList.add("bg-red-200", "text-red-800");
                                                    } else if (value === 'for inventory') {
                                                        td.classList.add("bg-purple-200", "text-purple-800");
                                                    }
                                                }
                                            },
                                            {
                                                data: 'loc_code',
                                                name: 'loc_code'
                                            },
                                            {
                                                data: 'action',
                                                name: 'action',
                                                orderable: false,
                                                searchable: false
                                            },
                                        ],
                                        pagingType: "simple_numbers",
                                        language: {
                                            search: "_INPUT_",
                                            searchPlaceholder: "Search...",
                                            lengthMenu: "Show _MENU_ entries",

                                        },
                                        drawCallback: function() {
                                            $('#inventory-table_paginate').addClass('flex items-center gap-2 mt-4');
                                            $('#inventory-table_paginate a').addClass('px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600');
                                            $('#inventory-table_paginate .current').addClass('bg-green-600 text-white');
                                        }
                                    });
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>



    @if(session('error'))
    <div x-data="{ show: true }" x-show="show"
        class="fixed top-5 right-5 bg-red-500 text-white p-4 rounded shadow-lg"
        x-init="setTimeout(() => show = false, 3000)">
        <p>{{ session('error') }}</p>
    </div>
    @endif

    @if(session('success'))
    <div x-data="{ show: true }" x-show="show"
        class="fixed top-5 right-5 bg-green-500 text-white p-4 rounded shadow-lg"
        x-init="setTimeout(() => show = false, 3000)">
        <p>{{ session('success') }}</p>
    </div>
    @endif
    @include('modal.user-view-inventory-modal')
    @include('modal.user-edit-inventories-modal')
</x-app-layout>