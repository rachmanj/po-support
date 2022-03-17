<a href="{{ route('po_service.edit', $model->id) }}" class="btn btn-xs btn-warning">edit</a>
<a href="{{ route('po_service.add_items', $model->id) }}" class="btn btn-xs btn-primary">items</a>
<a href="{{ route('po_service.print_pdf', $model->id) }}" class="btn btn-xs btn-success">print</a>