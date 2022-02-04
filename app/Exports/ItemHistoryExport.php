<?php

namespace App\Exports;

use App\Models\ItemHistory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemHistoryExport implements FromCollection, WithHeadings
{
    public function headings(): array
    {
        return [
            '#',
            'item_code',
            'item_desc',
            'cons_remarks1',
            'cons_remarks2',
            'vendor_code',
            'vendor_name',
            'purchase_date',
            'price_currency',
            'purchase_price',
        ];
    }

    public function collection()
    {
        return ItemHistory::whereNotNull('item_code')->get();
    }
}
