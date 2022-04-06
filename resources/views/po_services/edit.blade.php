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
            <h3 class="card-title">Edit PO Service</h3>
            <a href="{{ route('po_service.index') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-undo"></i> Back</a>
          </div>
        
        <form action="{{ route('po_service.update', $po->id) }}" method="POST">
          @csrf @method('PUT')
          <div class="card-body">
            <div class="form-group">
              <label for="po_no">PO No</label>
              <input type="text" name="po_no" class="form-control @error('po_no') is-invalid @enderror" value="{{ old('po_no', $po->po_no) }}">
              @error('po_no')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <label for="date">PO Date</label>
              <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', $po->date) }}">
              @error('date')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
  
            <div class="form-group">
              <label for="employee_id">Project</label>
              <select name="project_code" id="project_code" class="form-control select2bs4 @error('project_code') is-invalid @enderror">
                <option value="">-- select project --</option>
                @foreach ($projects as $project)
                    <option value="{{ $project }}" {{ $po->project_code === $project ? 'selected' : '' }}>{{ $project }}</option>
                @endforeach
              </select>
              @error('project_code')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>
  
            <div class="form-group">
              <label for="vendor_code">Vendor Code</label>
              <select name="vendor_code" id="vendor_code" class="form-control select2bs4 @error('vendor_code') is-invalid @enderror">
                <option value="">-- select vendor --</option>
                @foreach ($vendors as $vendor)
                    <option value="{{ $vendor->vendor_code }}" {{ $po->vendor_code === $vendor->vendor_code ? 'selected' : '' }}>{{ $vendor->vendor_name }}</option>
                @endforeach
              </select>
              @error('vendor_code')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
            </div>

            <div class="form-group">
              <label>VAT</label>
              <div class="form-check">
                <input name="is_vat" value="1" class="form-check-input" type="radio" {{ $po->is_vat == true ? 'checked' : '' }} id="yes">
                <label for="yes" class="form-check-label">Yes</label>
              </div>
              <div class="form-check">
                <input name="is_vat" value="0" class="form-check-input" type="radio" id="no" {{ $po->is_vat == false ? 'checked' : '' }}>
                <label for="no" class="form-check-label">No</label>
              </div>
            </div>

            <div class="form-group">
              <label for="remark">Remarks</label>
              <textarea name="remarks" id="remarks" cols="30" rows="2" class="form-control">{{ old('remarks', $po->remarks) }}</textarea>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fas fa-save"></i> Save</button>
          </div>
        </form>
        
        </div>
      </div>
    </div>
@endsection

@section('styles')
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>

<script>
  $(function () {
    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
  }) 
</script>
@endsection