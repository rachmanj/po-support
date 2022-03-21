@extends('templates.main')

@section('title_page')
    Users
@endsection

@section('breadcrumb_title')
    users
@endsection

@section('content')
<div class="row">
  <div class="col-12">

    <div class="card">
      <div class="card-header">
        @if (Session::has('success'))
          <div class="alert alert-success">
            {{ Session::get('success') }}
          </div>
        @endif
        @if (Session::has('error'))
          <div class="alert alert-danger">
            {{ Session::get('error') }}
          </div>
        @endif
        <button href="#" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modal-input"><i class="fas fa-plus"></i> User</button>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <table id="users_table" class="table table-bordered table-striped">
          <thead>
          <tr>
            <th>No</th>
            <th>Name</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created at</th>
            <th></th>
          </tr>
          </thead>
        </table>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->

<div class="modal fade" id="modal-input">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"> New User</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('users.store') }}" method="POST">
        @csrf
      <div class="modal-body">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" autofocus>
          </div>
          <div class="form-group">
            <label for="username">Username</label>
            <input type="text" name="username" class="form-control">
          </div>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control">
          </div>
          <div class="form-group">
            <label>Project Code</label>
            <select name="project_code" class="form-control">
              <option value="">-- Select Project --</option>
              @foreach ($projects as $project)
                <option value="{{ $project }}">{{ $project }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name='password' class="form-control">
          </div>
          <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name='password_confirmation' class="form-control">
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
    $("#users_table").DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('users.data') }}',
      columns: [
        {data: 'DT_RowIndex', orderable: false, searchable: false},
        {data: 'name'},
        {data: 'username'},
        {data: 'email'},
        {data: 'created_at'},
        {data: 'action', orderable: false, searchable: false},
      ],
      fixedHeader: true,
    })
  });
</script>
@endsection