<?php

namespace App\Http\Controllers;

use App\Imports\ItemHistoryImport;
use App\Exports\ItemHistoryExport;
use App\Models\ItemHistory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemHistoryController extends Controller
{
    public function index()
    {
        return view('items.index');
    }

    public function index_data()
    {
        $items = ItemHistory::whereNotNull('item_code')->get();

        return datatables()->of($items)
            ->editColumn('purchase_date', function($items) {
                return date('d-m-Y', strtotime($items->purchase_date));
            })
            ->editColumn('purchase_price', function ($items) {
                return number_format($items->purchase_price, 0);
            })
            ->addIndexColumn()
            ->toJson();
    }

    public function import_excel(Request $request)
    {
        // validasi
        $this->validate($request, [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        // menangkap file excel
        $file = $request->file('file');

        // membuat nama file unik
        $nama_file = rand() . $file->getClientOriginalName();

        // upload ke folder file_siswa di dalam folder public
        $file->move('file_upload', $nama_file);

        // import data
        Excel::import(new ItemHistoryImport, public_path('/file_upload/' . $nama_file));

        return redirect()->route('items.index')->with('success', 'Data successfully imported');
    }

    public function truncate()
    {
        ItemHistory::truncate();

        return redirect()->route('items.index')->with($this->alertTruncated());
    }

    public function test()
    {
        $num = '33,663,133.127600';
        $test = (float)$num;
        
        return $test;
    }

    public function export_excel()
    {
        return Excel::download(new ItemHistoryExport(), 'item_history.xlsx');
    }
}
