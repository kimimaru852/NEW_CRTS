<div>
    <div class="mt-4">
        <div class="mx-6">
            <div class="bg-white dark:bg-stone-800 p-4 shadow overflow-hidden sm:rounded-l">
                <table id="lists-table" class="display nowrap dt-responsive text-center min-w-full divide-y divide-gray-200 dark:divide-gray-700 drop-shadow-md shadow-stone-500" style="width:100%">
                    <thead class="bg-gray-50 dark:bg-gray-200">
                        <tr>
                            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">Description</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">grds/rds no</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">retention period</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">document status</th>
                            <th class="text-center px-6 py-3 text-xs font-medium text-gray-500 dark:text-green-900 uppercase tracking-wider">action</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const table = $('#lists-table').DataTable({
                        responsive: true,
                        processing: true,
                        serverSide: true,
                        ajax: '{{ route("admin.grdslists") }}',

                        columns: [{
                                data: 'description',
                                name: 'description'
                            },
                            {
                                data: 'grds_rds_no',
                                name: 'grds_rds_no'
                            },
                            {
                                data: 'retention_period',
                                name: 'retention_period'
                            },
                            {
                                data: 'document_status',
                                name: 'document_status'
                            },
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false
                            }
                        ]
                    });

                    // OPEN DELETE MODAL
                    $(document).on('click', '.delete-btn', function() {

                        deleteId = $(this).data('id');
                        const description = $(this).data('description');

                        $('#deleteItemName').text(description);

                        $('#deleteModal')
                            .removeClass('hidden')
                            .addClass('flex');
                    });

                    // CANCEL DELETE
                    $('#cancelDelete').click(function() {
                        $('#deleteModal')
                            .removeClass('flex')
                            .addClass('hidden');
                    });

                    // CONFIRM DELETE
                    $('#confirmDelete').click(function() {

                        if (!deleteId) return;

                        $.ajax({
                            url: `/admin/grds-rds/${deleteId}`,
                            type: 'DELETE',

                            data: {
                                _token: '{{ csrf_token() }}'
                            },

                            success: function(response) {

                                $('#deleteModal')
                                    .removeClass('flex')
                                    .addClass('hidden');

                                table.ajax.reload(null, false);

                                //alert(response.message);
                            },

                            error: function() {
                                alert('Failed to delete record.');
                            }
                        });
                    });

                    // For Alpine or external reload
                    document.querySelector('#lists-table').addEventListener('reload-datatable', () => {
                        table.ajax.reload();
                    });
                });
            </script>
        </div>

        <!-- Delete Modal -->
        <div id="deleteModal"
            class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

            <div class="bg-white p-6 rounded-lg shadow-lg w-96">
                <h2 class="text-lg font-bold text-red-600">
                    Confirm Deletion
                </h2>

                <p class="mt-2 text-gray-700">
                    Are you sure you want to delete
                    <span id="deleteItemName" class="font-semibold"></span>?
                </p>

                <div class="flex justify-end gap-2 mt-4">
                    <button id="cancelDelete"
                        class="px-4 py-2 bg-gray-400 text-white rounded">
                        Cancel
                    </button>

                    <button id="confirmDelete"
                        class="px-4 py-2 bg-red-600 text-white rounded">
                        Delete
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

@include('modal.edit-grds-rds-modal')
<script src="{{ asset('js/grdsrdslistopenmodal.js') }}"></script>