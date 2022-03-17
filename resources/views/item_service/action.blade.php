<form action="{{ route('item_service.destroy', $model->id) }}" method="POST">
  @csrf @method('DELETE')
  <a href="{{ route('item_service.edit', $model->id) }}" class="btn btn-xs btn-primary"><i class="fas fa-edit"></i></a>
  <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Are You sure You want to delete this records?') "><i class="fas fa-trash"></i></button>
</form>
