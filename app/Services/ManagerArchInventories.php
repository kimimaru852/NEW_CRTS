<?php

namespace App\Services;

use App\Models\ArchiveInventories;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class ManagerArchInventories
{
    /**
     * Get all countries.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */

    public function display()
    {
        $query = ArchiveInventories::with('user', 'items')
            ->whereHas('user', function ($query) {
                $query->where('managerId', Auth::id());
            })
            ->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('prepared_by', fn($row) => ucfirst($row->prepared_by))
            ->addColumn('manager_approval', fn($row) => ucfirst($row->manager_approval)) // cost center head
            ->addColumn('office_name', function ($row) {
                return $row->office ? $row->office->department : 'N/A';
            })
            ->addColumn('created_at', function ($row) {
                $item = $row->items->first();
                return $item && $item->created_at
                    ? Carbon::parse($item->created_at)->format('Y-m-d')
                    : 'N/A';
            })
            
            ->addColumn('disposal_status', function ($row) {
                return $row->disposal_status ?? 'N/A';
            })
            ->editColumn('disposed_date', function ($row) {
                return $row->disposed_date ? Carbon::parse($row->disposed_date)->format('Y-m-d') : '';
            })
            ->addColumn('action', function ($row) {
                $downloadUrl = route('print-arch-pdf', $row->id);
                $inventoryJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                return '
                    <div class="flex items-center gap-4">
                        <!-- View Button -->
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

                        <!-- Download Button -->
                        <a href="' . $downloadUrl . '" target="_blank" 
                            class="inline-flex items-center px-3 py-1 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 transition">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                            </svg>
                            PDF
                        </a>
                    </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    public function count()
    {
        return ArchiveInventories::whereHas('user', function ($query) {
            $query->where('managerId', Auth::id());
        })->count();
    }
}
