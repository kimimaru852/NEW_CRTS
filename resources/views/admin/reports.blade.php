<x-app-layout>
    <div class="link text-gray-700 dark:text-gray-200 flex justify-start mt-2 px-4 font-bold">
        <div class="flex underline underline-offset-4">

            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>

            <a href="{{ route('admin.office') }}">Manage Cost Center</a>
        </div>
    </div>
    <header class="flex justify-between items-center mx-2 mt-4">
        <h2 class="w-full py-6 px-4 bg-stone-600 text-gray-50 font-bold text-xl rounded-t-lg">Summary Reports</h2>
    </header>
    <div>
        <div class="py-4 sm:px-6 bg-white dark:bg-stone-800 shadow shadow-stone-500/50 text-gray-900 dark:text-gray-100 mx-2 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
                <div class="card shadow-md rounded-md border-l-4 border-green-500 bg-stone-200 dark:bg-stone-700">
                    <div class="flex w-full justify-between text-lg px-8 pt-8">
                        <p>
                            <strong class="text-green-800 dark:text-green-100">Registered User:</strong>
                        </p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 p-2 rounded-full bg-green-100 text-green-800">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <div class="flex justify-center content-center w-full h-full pb-8">
                        <strong class="text-2xl">{{ $users }}</strong>
                    </div>
                </div>

                <div class="card shadow-md rounded-md border-l-4 border-orange-500 bg-stone-200 dark:bg-stone-700">
                    <div class="flex w-full justify-between text-lg px-8 pt-8">
                        <p>
                            <strong class="text-green-800 dark:text-green-100">Disposed Boxes</strong>
                        </p>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-12 p-2 rounded-full bg-green-100 text-green-800">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                        </svg>
                    </div>
                    <div class="flex justify-center content-center w-full h-full pb-8">
                        <strong class="text-2xl">{{ $inventories}}</strong>
                    </div>

                </div>

                <div class="card shadow-md rounded-md border-l-4 border-blue-500 bg-stone-200 dark:bg-stone-700">
                    <div class="flex w-full justify-between text-lg px-8 pt-8">
                        <p>
                            <strong class="text-green-800 dark:text-green-100">Registered Cost Center:</strong>
                        </p>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-12 p-2 rounded-full bg-green-100 text-green-800">
                            <path fill-rule="evenodd" d="M3 2.25a.75.75 0 0 0 0 1.5v16.5h-.75a.75.75 0 0 0 0 1.5H15v-18a.75.75 0 0 0 0-1.5H3ZM6.75 19.5v-2.25a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 1-.75.75h-3a.75.75 0 0 1-.75-.75ZM6 6.75A.75.75 0 0 1 6.75 6h.75a.75.75 0 0 1 0 1.5h-.75A.75.75 0 0 1 6 6.75ZM6.75 9a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75ZM6 12.75a.75.75 0 0 1 .75-.75h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 1-.75-.75ZM10.5 6a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75Zm-.75 3.75A.75.75 0 0 1 10.5 9h.75a.75.75 0 0 1 0 1.5h-.75a.75.75 0 0 1-.75-.75ZM10.5 12a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75ZM16.5 6.75v15h5.25a.75.75 0 0 0 0-1.5H21v-12a.75.75 0 0 0 0-1.5h-4.5Zm1.5 4.5a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Zm.75 2.25a.75.75 0 0 0-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 0 0 .75-.75v-.008a.75.75 0 0 0-.75-.75h-.008ZM18 17.25a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75h-.008a.75.75 0 0 1-.75-.75v-.008Z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex justify-center content-center w-full h-full pb-8">
                        <strong class="text-2xl">{{ $office }}</strong>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mx-2 mt-4 ">
            <h2 class="w-full py-6 px-4 bg-stone-600 text-gray-50 font-bold text-xl rounded-t-lg">List of Disposed Boxes</h2>
        </div>
        <div class="mx-2">
            <div class="bg-zinc-200 dark:bg-stone-800 shadow overflow-hidden">
                <div>
                    <div class="bg-white dark:bg-stone-800 p-4 shadow overflow-hidden sm:rounded-l">
                        <table id="inventory-table" class="display nowrap dt-responsive text-center min-w-full divide-y divide-gray-200 dark:divide-gray-700 drop-shadow-md shadow-stone-500" style="width:100%">
                            <thead class="bg-gray-50 dark:bg-gray-200">
                                <tr>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">box no.</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Prepared by</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Cost Center Head</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Cost Center</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">turn-over date</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">status</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Date of Disposal</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Location Code</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

                <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
                <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>


                <script>
                    $(function() {
                        $('#inventory-table').DataTable({
                            processing: true,
                            serverSide: true,
                            responsive: true,
                            ajax: "{{ route('admin.reports') }}",
                            columns: [{
                                    data: 'id',
                                    name: 'id'
                                },
                                {
                                    data: 'prepared_by',
                                    name: 'prepared_by'
                                },
                                {
                                    data: 'manager_approval',
                                    name: 'manager_approval'
                                },
                                {
                                    data: 'office_name',
                                    name: 'office.department'
                                },
                                {
                                    data: 'created_at',
                                    name: 'created_at'
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
                                        } else if (value === 'recieved by admin') {
                                            td.classList.add("bg-blue-200", "text-blue-800");
                                        } else if (value === 'disposed') {
                                            td.classList.add("bg-red-200", "text-red-800");
                                        } else if (value === 'for inventory') {
                                            td.classList.add("bg-purple-200", "text-purple-800");
                                        }
                                    }
                                },
                                {
                                    data: 'disposed_date',
                                    name: 'disposed_date'
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
    @include('modal.view-arch-inventory-modal')
    @include('modal.delete-inventory-modal')
</x-app-layout>