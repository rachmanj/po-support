<?php

namespace App\Imports;

use App\Models\ItemHistory;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemHistoryImport implements ToModel, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function model(array $row)
    {
        return new ItemHistory([
            'item_code' => $row['item_code'],
            'item_desc' => $row['item_desc'],
            'cons_remarks1' => $row['cons_remarks1'],
            'cons_remarks2' => $row['cons_remarks2'],
            'vendor_code' => $row['vendor_code'],
            'vendor_name' => $row['vendor_name'],
            'purchase_date' => $this->convert_date($row['purchase_date']),
            'price_currency' => $row['price_currency'],
            'purchase_price' => $row['purchase_price'],
        ]);
    }

    public function convert_date($date)
    {
        $year = substr($date, 6, 4);
        $month = substr($date, 3, 2);
        $day = substr($date, 0, 2);
        $new_date = $year . '-' . $month . '-' . $day;

        return $new_date;
    }
}
