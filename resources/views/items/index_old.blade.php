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
        <a href="{{ route('items.export_excel') }}" class="btn btn-success btn-sm"><i class="fas fa-download"></i> Export to Excel</a>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="itemhistory" class="table table-bordered table-striped">
          <thead>
            <tr>
              <td></td>
              <td>
                <input type="text" class="search form-control" placeholder="ItemCode">
              </td>
              <td>
                <input type="text" class="search form-control" placeholder="Desc">
              </td>
              <td>
                <input type="text" class="search form-control" placeholder="Rem1">
              </td>
              <td>
                <input type="text" class="search form-control" placeholder="Rem2">
              </td>
              <td>
                <input type="text" class="search form-control" placeholder="VCode">
              </td>
              <td>
                <input type="text" class="search form-control" placeholder="VName">
              </td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
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
</div> -->

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
                  <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
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
    let dtOverrideGlobals = {
      processing: true,
      serverSide: true,
      retrieve: true,
      aaSorting: [],
      ajax: '{{ route('items.index_data') }}',
      columns: [
        {data: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'item_code'},
        {data: 'item_desc'},
        {data: 'cons_remarks1'},
        {data: 'cons_remarks2'},
        {data: 'vendor_code'},
        {data: 'vendor_name'},
        {data: 'purchase_date'},
        {data: 'price_currency'},
        {data: 'purchase_price'},
        // {data: 'action', orderable: false, searchable: false},
      ],
        orderCellsTop: true,
        order: [[ 1, 'asc' ]],
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

    $('.datatable thead').on('input', '.search', function () {
        let strict = $(this).attr('strict') || false
        let value = strict && this.value ? "^" + this.value + "$" : this.value
        table
          .column($(this).parent().index())
          .search(value, strict)
          .draw()
      });
  });
</script> 
@endsection
