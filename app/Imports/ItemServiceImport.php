<?php

namespace App\Imports;

use App\Models\ItemService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemServiceImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ItemService([
            'po_service_id' => $row['po_service_id'],
            'item_code'     => $row['item_code'],
            'item_desc'     => $row['item_desc'],
            'qty'           => $row['qty'],
            'uom'           => $row['uom'],
            'unit_price'    => $row['unit_price'],
            'created_by'    => auth()->user()->username,
        ]);
    }
}
