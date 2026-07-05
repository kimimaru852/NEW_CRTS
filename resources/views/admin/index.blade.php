<x-app-layout>
    <div x-data="disposeHandler">
        @if(auth()->user()->hasRole('admin'))
        <div class="link text-gray-700 dark:text-gray-200 flex justify-between mt-2 px-4 font-bold">
            <div>
                <h1 class="text-xl font-bold text-green-400">Welcome, <span class="capitalize text-stone-800 dark:text-slate-50">{{Auth::user()->name}}</span></h1>
            </div>
            <div class="flex underline underline-offset-4">
                <a href="{{ route('admin.grdslists') }}">GRDS/RDS Lists</a>

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
        @endif

        <div class="mx-2 mt-5 px-4 py-6 bg-green-300 rounded-md border-4 border-green-300 border-l-green-800 shadow-sm shadow-green-800">
            <div>
                <p class="text-green-800 font-bold">Hello Admin this is all your pending for receiving and verification <span class="font-extrabold">RECORDS TURN-OVER</span> </p>
            </div>
        </div>

        <div class="text-gray-900 dark:text-gray-100 w-full py-6">
            <div class="text-xl mx-2 py-6 px-4 rounded-t-lg bg-gradient-to-b from-blue-600 to-indigo-800 text-gray-50 font-bold">Records Turn-Over Inventory Lists</div>
            <div>
                <div class="px-2">
                    <div class="bg-white dark:bg-stone-800 p-4 shadow overflow-hidden sm:rounded-l">
                        <table id="inventory-table" class="display nowrap dt-responsive text-center min-w-full divide-y divide-gray-200 dark:divide-gray-700 drop-shadow-md shadow-stone-500" style="width:100%">
                            <thead class="bg-gray-50 dark:bg-gray-200">
                                <tr>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">box no</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">cost center</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">prepared by</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Cost Center Head</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">NAP Authority No</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Turn-Over Date</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Status</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Location Code</th>
                                    <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const table = $('#inventory-table').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax: '{{ route("admin.index") }}',
                        columns: [{
                                data: 'id',
                                name: 'id'
                            },
                            {
                                data: 'office_origin',
                                name: 'office_origin'
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
                                data: 'nap_authority_no',
                                name: 'nap_authority_no'
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
                                name: 'loc_code',
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ],

                        initComplete: function() {
                            const $searchInput = $('div.dataTables_filter input');
                            $searchInput
                                .addClass('mb-4 px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-stone-800 text-gray-900 dark:text-gray-100')
                                .attr('placeholder', 'Search...');

                            const $select = $('div.dataTables_length select');
                            $select
                                .addClass('px-3 py-2 rounded border border-gray-300 dark:border-gray-600 bg-white dark:bg-stone-800 text-gray-900 dark:text-gray-100');
                            $select.find('option').each(function() {
                                this.style.color = document.documentElement.classList.contains('dark') ? '#f9fafb' : '#1f2937';
                                this.style.backgroundColor = document.documentElement.classList.contains('dark') ? '#1c1917' : '#ffffff';
                            });
                            $('div.dataTables_filter').append(`
                                <a href="{{ route('admin.exportExcel') }}"
                                class="ml-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                    Export xlsx
                                </a>
                            `);
                        }
                    });

                    // For Alpine or external reload
                    document.querySelector('#inventory-table').addEventListener('reload-datatable', () => {
                        table.ajax.reload();
                    });
                });
            </script>

            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('disposeHandler', () => ({
                        showDisposeModal: false,
                        inventoryToDispose: null,
                        disposedDate: '',

                        init() {
                            window.addEventListener('confirm-dispose', event => {
                                this.inventoryToDispose = event.detail.id;
                                this.disposedDate = '';
                                this.showDisposeModal = true;
                            });
                        },

                        disposeInventory() {
                            if (!this.disposedDate) return; // ensure date is chosen

                            fetch(`/admin/dashboard/dispose/${this.inventoryToDispose}`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        disposed_date: this.disposedDate
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        this.showDisposeModal = false;
                                        this.inventoryToDispose = null;
                                        this.disposedDate = '';
                                        document.querySelector('#inventory-table').dispatchEvent(new Event('reload-datatable'));
                                    }
                                });
                        }
                    }));
                });
            </script>

            <!-- DataTables CSS -->
            <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css" />

            <!-- DataTables Responsive JS -->
            <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

            @include('modal.admin-view-inventory-modal')
            @include('modal.admin-edit-inventory-modal')
            @include('modal.disposal-modal')

        </div>
        @if(session('error'))
        <div x-data="{ show: true }" x-show="show"
            class="fixed top-5 right-5 z-50 bg-red-500 text-white p-4 rounded shadow-lg"
            x-init="setTimeout(() => show = false, 3000)">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        @if(session('success'))
        <div x-data="{ show: true }" x-show="show"
            class="fixed top-5 right-5 z-50 bg-green-500 text-white p-4 rounded shadow-lg"
            x-init="setTimeout(() => show = false, 3000)">
            <p>{{ session('success') }}</p>
        </div>
        @endif
    </div>
</x-app-layout>