@extends('templates.main')

@section('title_page')
  PO Service
@endsection

@section('breadcrumb_title')
  po service
@endsection

@section('content')
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            @if (Session::has('success'))
              <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('success') }}
              </div>
            @endif
            @if (Session::has('error'))
              <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {{ Session::get('error') }}
              </div>
            @endif
            <h3 class="card-title">Add Items</h3>
            <a href="{{ route('po_service.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-undo"></i> Back</a>
          </div>
          <div class="card-body">
            <dl class="row">
              <dt class="col-sm-4">Record ID / PO Service ID</dt>
              <dd class="col-sm-8">: <b>{{ $po->id }}</b></dd>
              <dt class="col-sm-4">PO No</dt>
              <dd class="col-sm-8">: {{ $po->po_no }}</dd>
              <dt class="col-sm-4">Date</dt>
              <dd class="col-sm-8">: {{ $po->date ? date('d-M-Y', strtotime($po->date)) : '-' }}</dd>
              <dt class="col-sm-4">Vendor</dt>
              <dd class="col-sm-8">: {{ $vendor->vendor_name }}</dd>
              <dt class="col-sm-4">Project</dt>
              <dd class="col-sm-8">: {{ $po->project_code }}</dd>
              <dt class="col-sm-4">Remarks</dt>
              <dd class="col-sm-8">: {{ $po->remarks }}</dd>
              <dt class="col-sm-4">Sub Total</dt>
              <dd class="col-sm-8">: IDR {{ $item_services ? number_format($item_services->sum('amount'), 2) : '-' }}</dd>
              <dt class="col-sm-4">VAT</dt>
              <dd class="col-sm-8">: IDR {{ $po->is_vat == 1 ? number_format($item_services->sum('amount') * 0.11, 2) : '-'  }}</dd>
              <dt class="col-sm-4">Total Amount</dt>
              <dd class="col-sm-8">: IDR <b>{{ $po->is_vat == 1 ? number_format($item_services->sum('amount') * 1.11, 2) : number_format($item_services->sum('amount'), 2)  }}</b></dd>
              <dt class="col-sm-4">Created by</dt>
              <dd class="col-sm-8">: {{ $po->created_by }}</dd>
            </dl>
          </div>
          <div class="card-header">
            
            <button class="btn btn-sm btn-primary {{ $po->print_count > 2 ? 'disabled' : '' }}" data-toggle="modal" data-target="#modal-input"><i class="fas fa-plus"></i> Item</button>
            <button class="btn btn-sm btn-success {{ $po->print_count > 2 ? 'disabled' : '' }}" data-toggle="modal" data-target="#modal-excel"><i class="fas fa-upload"></i> Upload Items</button>
            <a href="{{ route('po_service.preview', $po->id) }}" class="btn btn-sm btn-info" target="_blank"><i class="fas fa-print"></i> Preview</a>
            <a href="{{ route('po_service.print_pdf', $po->id) }}" class="btn btn-sm btn-warning {{ $po->print_count > 2 ? 'disabled' : '' }}" target="_blank"><i class="fas fa-print" ></i> Print ({{ $po->print_count }})</a>
            <form action="{{ route('item_service.delete_all', $po->id) }}" method="POST" id="delete-items">@csrf @method('DELETE')
            <button type="submit" form="delete-items" class="btn btn-sm btn-danger float-right" onclick="return confirm('Are You sure You want to DELETE ALL ITEMS in this PO?') "><i class="fas fa-trash"></i> Delete All Items</button>
          </form>
          </div>
          <div class="card-body">
            <table class="table table-striped table-bordered" id="table-items">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Item Code</th>
                  <th>Item Desc</th>
                  <th>Qty</th>
                  <th>Uom</th>
                  <th>Price</th>
                  <th>Total</th>
                  <th></th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>

    {{-- Modal input manual --}}
    <div class="modal fade" id="modal-input">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title"> New Item</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form action="{{ route('item_service.store', $po->id) }}" method="POST">
            @csrf
          <div class="modal-body">

              <div class="form-group">
                <label for="item_code">Item Code</label>
                <input type="text" name="item_code" class="form-control @error('item_code') is-invalid @enderror">
                @error('item_code')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>

              <div class="form-group">
                <label for="item_desc">Item Description</label>
                <input type="text" name="item_desc" class="form-control @error('item_desc') is-invalid @enderror">
                @error('item_desc')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>

              <div class="form-group">
                <label for="qty">Qty</label>
                <input type="text" name="qty" class="form-control @error('qty') is-invalid @enderror">
                @error('qty')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>
    
              <div class="form-group">
                <label for="uom">UOM</label>
                <input type="text" name="uom" class="form-control @error('uom') is-invalid @enderror">
                @error('uom')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>

              <div class="form-group">
                <label for="unit_price">Unit Price</label>
                <input type="text" name="unit_price" class="form-control @error('unit_price') is-invalid @enderror">
                @error('unit_price')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                @enderror
              </div>

          </div>
          <div class="modal-footer float-left">
            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal"> Close</button>
            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save</button>
          </div>
        </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

    <!--Modal upload Excel -->
    <div class="modal fade" id="modal-excel">
      <div class="modal-dialog">
        <div class="modal-content animated rollIn">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fa fa-star"></i> Upload Items Data</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form method="post" action="{{ route('item_service.import_item', $po->id) }}" enctype="multipart/form-data">
              @csrf
              <label>Pilih file excel</label>
              <div class="form-group">
                <input type="file" name="file_upload" required="required">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><i class="fa fa-times"></i>
                  Close</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Import</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

@endsection

@section('styles')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/datatables/css/datatables.min.css') }}"/>
@endsection

@section('scripts')
  <!-- DataTables  & Plugins -->
  <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables/datatables.min.js') }}"></script>

  <script>
    $(function () {
      $("#table-items").DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('item_service.data', $po->id) }}',
        columns: [
          {data: 'DT_RowIndex', orderable: false, searchable: false},
          {data: 'item_code'},
          {data: 'item_desc'},
          {data: 'qty'},
          {data: 'uom'},
          {data: 'unit_price'},
          {data: 'item_amount'},
          {data: 'action'},
        ],
        fixedHeader: true,
        columnDefs: [
              {
                "targets": [3, 5, 6],
                "className": "text-right"
              }
            ]
      })
    });
  </script>
@endsection

