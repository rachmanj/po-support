@extends('templates.main')

@section('title_page')
  Items History
@endsection

@section('breadcrumb_title')
  items
@endsection

@section('content')
  <div class="row">
    <div class="col-12">

      <div class="card">
        <div class="card-header">

          @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible">
              {{ session('success') }}
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
          @endif

          @if (session()->has('danger'))
            <div class="alert alert-danger alert-dismissible">
              {{ session('danger') }}
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            </div>
          @endif

          <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#importExcel">
            <i class="fa fa-upload"></i> Upload Excel
          </button>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
          Filter:
          <div class="row">
            <div class="col-3">
              <input type="text" name="item_code" id="item_code" class="form-control" placeholder="ItemCode"
                value="{{ request('item_code') }}">
            </div>
            <div class="col-3">
              <input type="text" name="item_desc" id="item_desc" class="form-control" placeholder="Description"
                value="{{ request('item_desc') }}">
            </div>
            <div class="col-3">
              <input type="text" name="cons_remarks1" id="cons_remarks1" class="form-control" placeholder="Remarks 1"
                value="{{ request('cons_remarks1') }}">
            </div>
            <div class="col-3">
              <input type="text" name="cons_remarks2" id="cons_remarks2" class="form-control" placeholder="Remarks 2"
                value="{{ request('cons_remarks2') }}">
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-3">
              <input type="text" name="vendor_code" id="vendor_code" class="form-control" placeholder="Vendor Code"
                value="{{ request('vendor_code') }}">
            </div>
            <div class="col-3">
              <input type="text" name="vendor_name" id="vendor_name" class="form-control" placeholder="Vendor Name"
                value="{{ request('vendor_name') }}">
            </div>
          </div>
          <br>
          <table id="itemhistory" class="table table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th>No</th>
                <th>ItemCode</th>
                <th>Desc</th>
                <th>Rem1</th>
                <th>Rem2</th>
                <th>VCode</th>
                <th>VName</th>
                <th>PurD</th>
                <th>Curr</th>
                <th>Price</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- Import Excel -->

  <div class="modal fade" id="importExcel">
    <div class="modal-dialog">
      <div class="modal-content animated rollIn">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fa fa-star"></i> PO With Eta Upload</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post" action="{{ route('items.import_excel') }}" enctype="multipart/form-data">
            {{ csrf_field() }}
            <label>Pilih file excel</label>
            <div class="form-group">
              <input type="file" name="file" required="required">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i>
                Close</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Import</button>
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
  <link rel="stylesheet"
    href="{{ asset('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
  <link rel="stylesheet" type="text/css" href="{{ asset('adminlte/plugins/datatables/css/datatables.min.css') }}" />
@endsection

@section('scripts')
  <!-- DataTables  & Plugins -->
  <script src="{{ asset('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('adminlte/plugins/datatables/datatables.min.js') }}"></script>

  {{-- <script>
    $(function() {
      let dtOverrideGlobals = {
        processing: true,
        serverSide: true,
        retrieve: true,
        aaSorting: [],
        ajax: '{{ route('items.index_data') }}',
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'item_code'
          },
          {
            data: 'item_desc'
          },
          {
            data: 'cons_remarks1'
          },
          {
            data: 'cons_remarks2'
          },
          {
            data: 'vendor_code'
          },
          {
            data: 'vendor_name'
          },
          {
            data: 'purchase_date'
          },
          {
            data: 'price_currency'
          },
          {
            data: 'purchase_price'
          },
          // {data: 'action', orderable: false, searchable: false},
        ],
        orderCellsTop: true,
        order: [
          [1, 'asc']
        ],
        pageLength: 10,
        // fixedHeader: true,
        // columnDefs: [
        //   {
        //     "targets": 9,
        //     "className": "text-right"
        //   }
        // ],
      }

      let table = $('#itemhistory').DataTable(dtOverrideGlobals);

      $('.datatable thead').on('input', '.search', function() {
        let strict = $(this).attr('strict') || false
        let value = strict && this.value ? "^" + this.value + "$" : this.value
        table
          .column($(this).parent().index())
          .search(value, strict)
          .draw()
      });
    });
  </script> --}}

  <script type="text/javascript">
    $(function() {
      var table = $('#itemhistory').DataTable({
        processing: true,
        serverSide: true,
        dom: 'lrtip',
        ajax: {
          url: "{{ route('items.index') }}",
          data: function(d) {
            d.item_code = $('#item_code').val(),
              d.item_desc = $('#item_desc').val(),
              d.cons_remarks1 = $('#cons_remarks1').val(),
              d.cons_remarks2 = $('#cons_remarks2').val(),
              d.vendor_code = $('#vendor_code').val(),
              d.vendor_name = $('#vendor_name').val()
          }
        },
        length: 10,
        columns: [{
            data: 'DT_RowIndex',
            orderable: false,
            searchable: false
          },
          {
            data: 'item_code'
          },
          {
            data: 'item_desc'
          },
          {
            data: 'cons_remarks1'
          },
          {
            data: 'cons_remarks2'
          },
          {
            data: 'vendor_code'
          },
          {
            data: 'vendor_name'
          },
          {
            data: 'purchase_date'
          },
          {
            data: 'price_currency'
          },
          {
            data: 'purchase_price'
          }
          // {data: 'action', orderable: false, searchable: false},
        ]
      });

      $('#item_code, #item_desc, #cons_remarks1, #cons_remarks2, #vendor_code, #vendor_name').keyup(function() {
        table.draw();
      });
    });
  </script>
@endsection
