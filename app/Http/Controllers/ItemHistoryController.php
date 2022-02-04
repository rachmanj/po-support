<?php

namespace App\Http\Controllers;

use App\Imports\ItemHistoryImport;
use App\Exports\ItemHistoryExport;
use App\Models\ItemHistory;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemHistoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // $items = ItemHistory::select('*');
            $items = ItemHistory::select('*')->whereNotNull('item_code');

            return datatables()->of($items)
                ->addIndexColumn()
                ->editColumn('purchase_date', function($items) {
                    return date('d-m-Y', strtotime($items->purchase_date));
                })
                ->editColumn('purchase_price', function ($items) {
                    return number_format($items->purchase_price, 0);
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('item_code'))) {
                        $instance->where(function($w) use($request){
                            $item_code = $request->get('item_code');
                            $w->orWhere('item_code', 'LIKE','%'.$item_code.'%');
                        });
                    }
                    if (!empty($request->get('item_desc'))) {
                        $instance->where(function($w) use($request){
                            $item_desc = $request->get('item_desc');
                            $w->orWhere('item_desc', 'LIKE','%'.$item_desc.'%');
                        });
                    }
                    if (!empty($request->get('cons_remarks1'))) {
                        $instance->where(function($w) use($request){
                            $cons_remarks1 = $request->get('cons_remarks1');
                            $w->orWhere('cons_remarks1', 'LIKE','%'.$cons_remarks1.'%');
                        });
                    }
                    if (!empty($request->get('cons_remarks2'))) {
                        $instance->where(function($w) use($request){
                            $cons_remarks2 = $request->get('cons_remarks2');
                            $w->orWhere('cons_remarks2', 'LIKE','%'.$cons_remarks2.'%');
                        });
                    }
                    if (!empty($request->get('vendor_code'))) {
                        $instance->where(function($w) use($request){
                            $vendor_code = $request->get('vendor_code');
                            $w->orWhere('vendor_code', 'LIKE','%'.$vendor_code.'%');
                        });
                    }
                    if (!empty($request->get('vendor_name'))) {
                        $instance->where(function($w) use($request){
                            $vendor_name = $request->get('vendor_name');
                            $w->orWhere('vendor_name', 'LIKE','%'.$vendor_name.'%');
                        });
                    }
                }, true)
                ->toJson();
            } 
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
