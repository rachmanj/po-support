<?php

namespace App\Http\Controllers;

use App\Models\ItemHistory;
use App\Models\ItemService;
use App\Models\PoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PoServiceController extends Controller
{
    public function index()
    {
        return view('po_services.index');
    }

    public function create()
    {
        $projects = ['000H','001H', '017C', '021C', '022C', '023C', 'APS'];

        $vendors = ItemHistory::select('vendor_code', 'vendor_name')
                    ->orderby('vendor_code')
                    ->distinct('vendor_code')
                    ->get();

        return view('po_services.create', compact('projects', 'vendors'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'po_no' => 'required|min:5|max:15|unique:po_services',
            'date' => 'required',
            'vendor_code' => 'required',
            'project_code' => 'required',
        ]);

        PoService::create([
            'po_no' => $request->po_no,
            'date' => $request->date,
            'vendor_code' => $request->vendor_code,
            'project_code' => $request->project_code,
            'is_vat' => $request->boolean('is_vat'),
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('po_service.index')->with('success', 'PO Service has been added');
    }

    public function edit($id)
    {
        $po = PoService::find($id);
        $projects = ['000H','001H', '017C', '021C', '022C', '023C', 'APS'];

        $vendors = ItemHistory::select('vendor_code', 'vendor_name')
                    ->orderby('vendor_code')
                    ->distinct('vendor_code')
                    ->get();

        return view('po_services.edit', compact('po', 'projects', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'po_no' => 'required|min:5|max:15|unique:po_services,po_no,'.$id,
            'date' => 'required',
            'vendor_code' => 'required',
            'project_code' => 'required',
        ]);

        $po = PoService::find($id);
        $po->update([
            'po_no' => $request->po_no,
            'date' => $request->date,
            'vendor_code' => $request->vendor_code,
            'project_code' => $request->project_code,
            'is_vat' => $request->boolean('is_vat'),
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('po_service.index')->with('success', 'PO Service has been updated');
    }

    public function add_items($id)
    {   
        $po = PoService::find($id);
        $vendor = ItemHistory::where('vendor_code', $po->vendor_code)->first();
        $item_services = DB::table('item_services')
            ->where('po_service_id', $id)
            ->selectRaw('id, qty * unit_price as amount')
            ->get();

        return view('po_services.add_items', compact('po', 'vendor', 'item_services'));
    }

    public function preview($id)
    {
        $po = PoService::find($id);
        $vendor = ItemHistory::where('vendor_code', $po->vendor_code)->first();
        $item_services = DB::table('item_services')
            ->where('po_service_id', $id)
            ->selectRaw('id, item_code, item_desc, qty, uom, unit_price, qty * unit_price as sub_total')
            ->get();

        return view('po_services.preview', compact('po', 'vendor', 'item_services'));
    }

    public function print_pdf($id)
    {
        $po = PoService::find($id);
        $vendor = ItemHistory::where('vendor_code', $po->vendor_code)->first();
        $item_services = DB::table('item_services')
            ->where('po_service_id', $id)
            ->selectRaw('id, item_code, item_desc, qty, uom, unit_price, qty * unit_price as sub_total')
            ->get();
        
        if ($po->print_count < 3) {
            $po->update([
                'print_count' => $po->print_count + 1,
            ]);
            return view('po_services.print_pdf', compact('po', 'vendor', 'item_services'));
        }

        return redirect()->route('po_service.add_items', $id)->with('error', 'PO Service has been printed 3 times');

    }

    public function destroy($id)
    {
        $po = PoService::find($id);

        $item_services = ItemService::where('po_service_id', $id)->get();

        foreach ($item_services as $item_service) {
            $item_service->update(['deleted_by' => auth()->user()->username]);
            $item_service->delete();
        }

        $po->update(['deleted_by' => auth()->user()->username]);
        $po->delete();

        return redirect()->route('po_service.index')->with('success', 'PO Service has been deleted');
    }

    public function data()
    {
        $list = PoService::all();

        return datatables()->of($list)
                ->editColumn('date', function($list) {
                    return $list->date ? date('d-M-Y', strtotime($list->date)) : '';
                })
                ->editColumn('is_vat', function($list) {
                    return $list->is_vat == 1 ? '<i class="fas fa-check"></i>' : '';
                })
                ->addColumn('vendor', function($list) {
                    return ItemHistory::where('vendor_code', $list->vendor_code)->first()->vendor_name;
                })
                ->addColumn('action', 'po_services.action')
                ->rawColumns(['action', 'is_vat'])
                ->addIndexColumn()
                ->toJson();
    }
}
