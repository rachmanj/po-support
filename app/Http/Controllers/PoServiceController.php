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
        $vendors = ItemHistory::select('vendor_code', 'vendor_name')->orderby('vendor_code')->distinct()->get();

        return view('po_services.create', compact('projects', 'vendors'));
    }

    public function store(Request $request)
    {
        // dump($request->is_vat);
        // dump($request->boolean('is_vat'));
        // die;

        $this->validate($request, [
            'po_no' => 'required',
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
        $vendors = ItemHistory::select('vendor_code', 'vendor_name')->orderby('vendor_code')->distinct()->get();

        return view('po_services.edit', compact('po', 'projects', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'po_no' => 'required',
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

    public function print_pdf($id)
    {
        $po = PoService::find($id);
        $vendor = ItemHistory::where('vendor_code', $po->vendor_code)->first();
        $item_services = DB::table('item_services')
            ->where('po_service_id', $id)
            ->selectRaw('id, item_code, item_desc, qty, uom, unit_price, qty * unit_price as sub_total')
            ->get();

        return view('po_services.print_pdf', compact('po', 'vendor', 'item_services'));
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
