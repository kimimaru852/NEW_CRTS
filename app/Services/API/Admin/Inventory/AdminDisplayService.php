<?php

namespace App\Services\API\Admin\Inventory;

use App\Models\Inventory;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class AdminDisplayService
{
    public function display($request)
    {
        if ($request->ajax()) {
            $data = Inventory::with('items')->whereNotNull('manager_approval');

            return DataTables::of($data)

                ->addIndexColumn()
                ->filter(function ($query) use ($request) {

                    if ($keyword = $request->get('search')['value']) {

                        $query->where(function ($q) use ($keyword) {

                            $q->where('office_origin', 'like', "%{$keyword}%")
                                ->orWhere('prepared_by', 'like', "%{$keyword}%")
                                ->orWhere('manager_approval', 'like', "%{$keyword}%")
                                ->orWhereHas('items', function ($item) use ($keyword) {
                                    $item->where('document_status', 'like', "%{$keyword}%")
                                        ->orWhere('description', 'like', "%{$keyword}%")
                                        ->orWhere('unit_code', 'like', "%{$keyword}%")
                                        ->orWhere('doc_date', 'like', "%{$keyword}%")
                                        ->orWhere('disposal_date', 'like', "%{$keyword}%")
                                        ->orWhere('rds_no', 'like', "%{$keyword}%");
                                });
                        });
                    }
                })
                ->addColumn('action', function ($row) {
                    $inventoryJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                    // Check disable conditions
                    $isDisabled = is_null($row->verified_date)
                        || strtolower($row->disposal_status) === 'returned';

                    $disposeClass = $isDisabled
                        ? 'disabled opacity-50 cursor-not-allowed'
                        : 'hover:bg-red-600';

                    $disposeAttr = $isDisabled ? 'disabled' : '';

                    return '
                        <button 
                            x-data 
                            x-on:click="$dispatch(\'open-modal\', { inventory: ' . $inventoryJson . ' })"
                            class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded hover:bg-blue-700 transition"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                            View
                        </button>

                        <button 
                            x-data 
                            x-on:click="$dispatch(\'edit-modal\', { inventory: ' . $inventoryJson . ' })"
                            class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 transition"
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                        </svg>
                            Update Form
                        </button>

                        <button 
                            x-data 
                            x-on:click="$dispatch(\'confirm-dispose\', { id: ' . $row->id . ', nap_authority_no: \'' . e($row->nap_authority_no) . '\' })"
                            class="inline-flex items-center px-3 py-1 bg-red-500 text-white text-xs font-semibold rounded transition ' . $disposeClass . '"
                            ' . $disposeAttr . '
                        >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                        </svg>
                            Dispose
                        </button>

                        <a href="' . route('print-pdf', $row->id) . '" target="_blank" class="ml-3">
                            <button class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                </svg>
                                .PDF
                            </button>
                        </a>
                        <a href="' . route('export.excel', $row->id) . '" target="_blank" class="ml-3">
                            <button class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs font-semibold rounded hover:bg-green-700 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                                </svg>

                                .xlsx
                            </button>
                        </a>
                    ';
                })->editColumn('created_at', function ($row) {
                    return $row->created_at ? Carbon::parse($row->created_at)->format('Y-m-d') : '';
                })->rawColumns(['action', 'disposal_status'])
                ->make(true);
        }

        return null;
    }
}
