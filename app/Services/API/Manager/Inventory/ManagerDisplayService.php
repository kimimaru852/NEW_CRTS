<?php

namespace App\Services\API\Manager\Inventory;

use App\Models\Inventory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ManagerDisplayService
{
    public function display($request)
    {
        if ($request->ajax()) {
            $data = Inventory::with('user', 'items')
                ->whereHas('user', function ($query) {
                    $query->where('managerId', Auth::id());
                });

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

                // Prepared By Column
                ->addColumn('prepared_by', function ($row) {
                    return $row->user->name ?? 'N/A';
                })
                ->filterColumn('prepared_by', function ($query, $keyword) {
                    $query->whereHas('user', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })

                // Turn-Over Date Column (using earliest created_at from items)
                ->editColumn('created_at', function ($row) {
                    $earliestDate = $row->items->min('created_at');
                    return $earliestDate ? Carbon::parse($earliestDate)->format('Y-m-d') : '—';
                })
                ->filterColumn('created_at', function ($query, $keyword) {
                    $query->whereHas('items', function ($q) use ($keyword) {
                        $q->whereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') like ?", ["%{$keyword}%"]);
                    });
                })


                ->editColumn('disposed_date', function ($row) {
                    return $row->disposed_date ? Carbon::parse($row->disposed_date)->format('Y-m-d') : '';
                })

                // Action Buttons
                ->addColumn('action', function ($row) {
                    $inventoryJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

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
                ';
                })

                // Mark "action" column as raw HTML
                ->rawColumns(['action'])

                ->make(true);
        }

        return null;
    }
}
