<?php

namespace App\Http\Controllers;

use App\Models\ItemHistory;
use App\Models\ItemService;
use App\Models\PoService;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PoServiceController extends Controller
{
    /**
     * Available project codes
     */
    private const PROJECTS = ['000H', '001H', '017C', '021C', '022C', '023C', '025C', 'APS'];
    
    /**
     * Maximum number of times a PO can be printed
     */
    private const MAX_PRINT_COUNT = 3;

    /**
     * Display the PO services index page
     */
    public function index()
    {
        return view('po_services.index');
    }

    /**
     * Show the form for creating a new PO service
     */
    public function create()
    {
        $vendors = $this->getVendorsList();

        return view('po_services.create', [
            'projects' => self::PROJECTS,
            'vendors' => $vendors
        ]);
    }

    /**
     * Store a newly created PO service
     */
    public function store(Request $request)
    {
        $validated = $this->validatePoService($request);

        PoService::create($validated);

        return redirect()->route('po_service.index')
            ->with('success', 'PO Service has been added');
    }

    /**
     * Show the form for editing the specified PO service
     */
    public function edit($id)
    {
        $po = PoService::findOrFail($id);
        $vendors = $this->getVendorsList();

        return view('po_services.edit', [
            'po' => $po,
            'projects' => self::PROJECTS,
            'vendors' => $vendors
        ]);
    }

    /**
     * Update the specified PO service
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validatePoService($request, $id);

        $po = PoService::findOrFail($id);
        $po->update($validated);

        return redirect()->route('po_service.index')
            ->with('success', 'PO Service has been updated');
    }

    /**
     * Show the add items form for a PO service
     */
    public function add_items($id)
    {   
        $po = PoService::findOrFail($id);
        $vendor = Supplier::where('code', $po->vendor_code)->first() ?? (object)['name' => 'n/a'];
        
        $item_services = ItemService::where('po_service_id', $id)
            ->selectRaw('id, qty * unit_price as amount')
            ->get();

        return view('po_services.add_items', compact('po', 'vendor', 'item_services'));
    }

    /**
     * Preview the PO service
     */
    public function preview($id)
    {
        $po = PoService::findOrFail($id);
        $vendor = Supplier::where('code', $po->vendor_code)->first();
        $item_services = $this->getItemServices($id);

        return view('po_services.preview', compact('po', 'vendor', 'item_services'));
    }

    /**
     * Print the PO service as PDF
     */
    public function print_pdf($id)
    {
        $po = PoService::findOrFail($id);
        $vendor = ItemHistory::where('vendor_code', $po->vendor_code)->first();
        $item_services = $this->getItemServices($id);
        
        if ($po->print_count < self::MAX_PRINT_COUNT) {
            $po->increment('print_count');
            
            return view('po_services.print_pdf', compact('po', 'vendor', 'item_services'));
        }

        return redirect()->route('po_service.add_items', $id)
            ->with('error', 'PO Service has been printed ' . self::MAX_PRINT_COUNT . ' times');
    }

    /**
     * Delete the PO service and its related items
     */
    public function destroy($id)
    {
        DB::transaction(function() use ($id) {
            PoService::where('id', $id)->delete();
            ItemService::where('po_service_id', $id)->delete();
        });

        return redirect()->route('po_service.index')
            ->with('success', 'PO Service has been deleted');
    }

    /**
     * Get data for datatables
     */
    public function data()
    {
        $list = PoService::orderBy('date', 'desc')->get();

        return datatables()->of($list)
            ->editColumn('date', function($po) {
                return $po->date ? date('d-M-Y', strtotime($po->date)) : '';
            })
            ->editColumn('is_vat', function($po) {
                return $po->is_vat ? '<i class="fas fa-check"></i>' : '';
            })
            ->addColumn('vendor', function($po) {
                $supplier = Supplier::where('code', $po->vendor_code)->first();
                return $supplier ? $supplier->name : 'n/a';
            })
            ->addColumn('action', 'po_services.action')
            ->rawColumns(['action', 'is_vat'])
            ->addIndexColumn()
            ->toJson();
    }

    /**
     * Validate PO service data
     */
    private function validatePoService(Request $request, $id = null)
    {
        return $request->validate([
            'po_no' => [
                'required', 
                'min:5', 
                'max:15', 
                $id ? Rule::unique('po_services')->ignore($id) : Rule::unique('po_services')
            ],
            'date' => 'required|date',
            'vendor_code' => 'required|string',
            'project_code' => 'required|string',
            'is_vat' => 'boolean',
            'remarks' => 'nullable|string',
        ]);
    }

    /**
     * Get list of vendors/suppliers
     */
    private function getVendorsList()
    {
        return Supplier::select('code', 'name')
            ->orderBy('code')
            ->distinct('code')
            ->get();
    }

    /**
     * Get item services for a PO
     */
    private function getItemServices($poId)
    {
        return ItemService::where('po_service_id', $poId)
            ->selectRaw('id, item_code, item_desc, qty, uom, unit_price, qty * unit_price as sub_total')
            ->get();
    }
}
