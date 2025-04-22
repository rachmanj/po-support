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
                    <h3 class="card-title">New PO Service</h3>
                    <a href="{{ route('po_service.index') }}" class="btn btn-sm btn-primary float-right"><i
                            class="fas fa-undo"></i> Back</a>
                </div>

                <form action="{{ route('po_service.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="po_no">PO No</label>
                            <input type="text" name="po_no" value="{{ old('po_no') }}"
                                class="form-control @error('po_no') is-invalid @enderror">
                            @error('po_no')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="date">PO Date</label>
                            <input type="date" name="date" value="{{ old('date') }}"
                                class="form-control @error('date') is-invalid @enderror">
                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="employee_id">Project</label>
                            <select name="project_code" id="project_code"
                                class="form-control select2bs4 @error('project_code') is-invalid @enderror">
                                <option value="">-- select project --</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project }}"
                                        {{ old('project_code') == $project ? 'selected' : '' }}>{{ $project }}</option>
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
                            <select name="vendor_code" id="vendor_code"
                                class="form-control select2bs4 @error('vendor_code') is-invalid @enderror">
                                <option value="">-- select vendor --</option>
                                @foreach ($vendors as $vendor)
                                    <option value="{{ $vendor->code }}"
                                        {{ old('vendor_code') == $vendor->code ? 'selected' : '' }}>{{ $vendor->name }} -
                                        {{ $vendor->code }}
                                    </option>
                                @endforeach
                            </select>
                            @error('vendor_code')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" name="is_vat" value="1"
                                    id="is_vat" {{ old('is_vat') == 1 ? 'checked' : '' }}>
                                <label for="is_vat" class="custom-control-label">is VAT</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="remarks">Remarks</label>
                            <textarea name="remarks" id="remarks" cols="30" rows="2" class="form-control">{{ old('remarks') }}</textarea>
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
        $(function() {
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        })
    </script>
@endsection
