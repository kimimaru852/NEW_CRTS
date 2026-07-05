<?php

namespace App\Services\API\Admin\GrdsList;

use App\Models\GrdsLists;
use Yajra\DataTables\Facades\DataTables;

class DisplayGRDSList
{
    public function display()
    {
        $query = GrdsLists::query();

        return DataTables::of($query)

            ->addColumn('action', function ($row) {
                $data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                return "
                            <button class='bg-green-500 text-white px-2 py-1 rounded edit-btn' data-row='{$data}'>
                                Edit
                            </button>

                            <button class='bg-red-500 text-white px-2 py-1 rounded delete-btn' data-id='{$row->id}' data-description='{$row->description}'>
                                Delete
                            </button>
                        ";
            })->rawColumns(['action'])
            ->make(true);
    }
}
