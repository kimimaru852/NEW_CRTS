<?php

namespace App\Services;

use App\Models\ArchiveInventories;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

class AdminArchInventories
{

    /**
     * Get all database.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function display()
    {
        $query = ArchiveInventories::with('items', 'office');

        return DataTables::of($query)
            ->addIndexColumn()

            // Prepared By
            ->addColumn('prepared_by', fn($row) => ucfirst($row->prepared_by))
            ->filterColumn('prepared_by', function ($query, $keyword) {
                $query->where('prepared_by', 'LIKE', "%{$keyword}%");
            })

            // Cost Center Head
            ->addColumn('manager_approval', fn($row) => ucfirst($row->manager_approval))
            ->filterColumn('manager_approval', function ($query, $keyword) {
                $query->where('manager_approval', 'LIKE', "%{$keyword}%");
            })

            // Office
            ->addColumn('office_name', function ($row) {
                return $row->office ? $row->office->department : 'N/A';
            })
            ->filterColumn('office_name', function ($query, $keyword) {
                $query->whereHas('office', function ($q) use ($keyword) {
                    $q->where('department', 'LIKE', "%{$keyword}%");
                });
            })

            // Turn-over date (from items)
            ->addColumn('created_at', function ($row) {
                $item = $row->items->first();
                return $item && $item->created_at
                    ? Carbon::parse($item->created_at)->format('Y-m-d')
                    : 'N/A';
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereHas('items', function ($q) use ($keyword) {
                    $q->whereDate('created_at', 'LIKE', "%{$keyword}%");
                });
            })

            // Status (make searchable)
            ->addColumn('disposal_status', function ($row) {
                return $row->disposal_status ?? 'N/A';
            })
            ->filterColumn('disposal_status', function ($query, $keyword) {
                $query->where('disposal_status', 'LIKE', "%{$keyword}%");
            })

            // Disposed Date
            ->editColumn('disposed_date', function ($row) {
                return $row->disposed_date ? Carbon::parse($row->disposed_date)->format('Y-m-d') : '';
            })

            // Action buttons
            ->addColumn('action', function ($row) {
                $deleteUrl = route('archInventory.destroy', $row->id);
                $downloadUrl = route('print-arch-pdf', $row->id);
                $downloadExcel = route('export.arch-excel', $row->id);
                $inventoryJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                return '
                <div class="flex items-center gap-4">
                    <button 
                        x-data 
                        x-on:click=\'$dispatch("open-modal", { archInventory: ' . $inventoryJson . ' })\' 
                        class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        View
                    </button>

                    

                    <a href="' . $downloadUrl . '" target="_blank" 
                        class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                            </svg>
                            .PDF
                    </a>

                    <a href="' . $downloadExcel . '" target="_blank" 
                        class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                            </svg>
                        .XLSX
                    </a>
                </div>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    
    // Delete Button Removed
    // <form action="' . $deleteUrl . '" method="POST" onsubmit="return confirm(\'Are you sure?\');" class="inline">
    //                     <input type="hidden" name="_token" value="' . csrf_token() . '">
    //                     <input type="hidden" name="_method" value="DELETE">
    //                     <button type="submit"
    //                         class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 transition">
    //                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
    //                             <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
    //                         </svg>
    //                         Delete
    //                     </button>
    //                 </form>
}
