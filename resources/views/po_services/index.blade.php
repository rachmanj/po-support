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
            <a href="{{ route('po_service.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> New PO Service</a>
          </div>
          <div class="card-body">
            <table class="table table-striped table-bordered" id="po-services">
              <thead>
                <tr>
                  <th>#</th>
                  <th>PO No</th>
                  <th>Date</th>
                  <th>Vendor</th>
                  <th>Project</th>
                  <th>VAT</th>
                  <th>action</th>
                </tr>
              </thead>
            </table>
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
    $("#po-services").DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('po_service.data') }}',
      columns: [
        {data: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'po_no'},
        {data: 'date'},
        {data: 'vendor'},
        {data: 'project_code'},
        {data: 'is_vat'},
        {data: 'action', orderable: false, searchable: false},
      ],
      fixedHeader: true,
      columnDefs: [
              {
                "targets": [5],
                "className": "text-center"
              }
            ]
    })
  });
</script>
@endsection

