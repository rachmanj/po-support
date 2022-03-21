<?php

namespace App\Http\Controllers;

use App\Imports\ItemServiceImport;
use App\Models\ItemService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ItemServiceController extends Controller
{
    public function store(Request $request, $po_id)
    {
        $this->validate($request, [
            'item_code' => 'required',
            'item_desc' => 'required',
            'qty' => 'required',
            'uom' => 'required',
            'unit_price' => 'required',
        ]);

        ItemService::create([
            'po_service_id' => $po_id,
            'item_code'     => $request->item_code,
            'item_desc'     => $request->item_desc,
            'qty'           => $request->qty,
            'uom'           => $request->uom,
            'unit_price'    => $request->unit_price
        ]);

        return redirect()->route('po_service.add_items', $po_id)->with('success', 'Item has been added');
    }

    public function edit($id)
    {
        $item = ItemService::find($id);
        $po_id = $item->po_service_id;

        return view('item_service.edit', compact('item', 'po_id'));
    }

    public function update(Request $request, $item_id)
    {
        $item = ItemService::find($item_id);
        $po_id = $item->po_service_id;

        $this->validate($request, [
            'item_code' => 'required',
            'item_desc' => 'required',
            'qty' => 'required',
            'uom' => 'required',
            'unit_price' => 'required',
        ]);

        $item->update([
            'item_code'     => $request->item_code,
            'item_desc'     => $request->item_desc,
            'qty'           => $request->qty,
            'uom'           => $request->uom,
            'unit_price'    => $request->unit_price
        ]);

        return redirect()->route('po_service.add_items', $po_id)->with('success', 'Item has been updated');
    }

    public function destroy($item_id)
    {
        $item = ItemService::find($item_id);
        $po_id = $item->po_service_id;

        $item->delete();

        return redirect()->route('po_service.add_items', $po_id)->with('success', 'Item has been deleted');
    }

    public function data($po_id)
    {
        $items = ItemService::where('po_service_id', $po_id)->get();

        return datatables()->of($items)
            ->editColumn('unit_price', function ($items) {
                return number_format($items->unit_price, 0);
            })
            ->addColumn('item_amount', function ($items) {
                return number_format($items->qty * $items->unit_price, 2);
            })
            ->addIndexColumn()
            ->addColumn('action', 'item_service.action')
            ->rawColumns(['action'])
            ->toJson();
    }

    public function import_item(Request $request, $po_id)
    {
        // validasi
        $this->validate($request, [
            'file_upload' => 'required|mimes:xls,xlsx'
        ]);

        // menangkap file excel
        $file = $request->file('file_upload');

        // membuat nama file unik
        $nama_file = rand().$file->getClientOriginalName();

        // upload ke folder file_upload
        $file->move('file_upload', $nama_file);

        // import data
        Excel::import(new ItemServiceImport, public_path('/file_upload/'.$nama_file));

        //update flag
        $temp_flag = 'TEMP' . auth()->user()->id;

        ItemService::where('flag', $temp_flag)->update([
            'po_service_id' => $po_id,
            'flag' => null
        ]);

        // alihkan halaman kembali
        return redirect()->route('po_service.add_items', $po_id)->with('success', 'Data Excel Berhasil Diimport!');
    }
}
