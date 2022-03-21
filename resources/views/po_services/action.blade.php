<a href="{{ route('po_service.edit', $model->id) }}" class="btn btn-xs btn-warning {{ $model->print_count > 2 ? 'disabled' : '' }}">edit</a>
@can('add_item')
  <a href="{{ route('po_service.add_items', $model->id) }}" class="btn btn-xs btn-primary">items</a>
@endcan
<a href="{{ route('po_service.print_pdf', $model->id) }}" class="btn btn-xs btn-success {{ $model->print_count > 2 ? 'disabled' : '' }}" target="_blank">print</a>
@can('edit_po')
  <button class="btn btn-xs btn-danger {{ $model->print_count > 2 ? 'disabled' : '' }}" data-toggle="modal" data-target="{{ $model->print_count > 2 ? '' : '#modal-delete' }}">delete</button>
@endcan

<!-- modal Delete -->
<div class="modal fade" id="modal-delete">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">WARNING !!!</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>This action will also DELETE items connected to this PO.</p>
        <p>Are You sure??</p>
      </div>
      <div class="modal-footer justify-content-between">
        <form action="{{ route('po_service.destroy', $model->id) }}" method="POST">
          @csrf @method('DELETE')
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-sm btn-danger">DELETE</button>
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal --> 
