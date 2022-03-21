<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PO-Support | Print</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>
<body>
  <div class="wrapper">
    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-12">
          <h2 class="page-header">
            PT Arkananta Apta Pratista
            {{-- <h5 class="float-right">Date: 2/10/2014</h5> --}}
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          Lampiran PO
          <address>
            <strong>No. {{ $po->po_no }}</strong><br>
            Date: {{ date('d F Y', strtotime($po->date)) }}<br>
            Vendor: {{ $vendor->vendor_name }}<br>
            Project: {{ $po->project_code }}<br>
          </address>
        </div>
        
      </div>
      <!-- /.row -->
  
      <!-- Table row -->
      <div class="row">
        <div class="col-12 table-responsive">
          <table class="table table-striped">
            <thead>
            <tr>
              <th>#</th>
              <th>Item Code</th>
              <th>Description</th>
              <th class="text-right">Qty</th>
              <th>UoM</th>
              <th class="text-right">Price (IDR)</th>
              <th class="text-right">Subtotal (IDR)</th>
            </tr>
            </thead>
            <tbody>
            @if ($item_services)
                @foreach ($item_services as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->item_code }}</td>
                  <td>{{ $item->item_desc }}</td>
                  <td class="text-right">{{ $item->qty }}</td>
                  <td>{{ $item->uom }}</td>
                  <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                  <td class="text-right">{{ number_format($item->sub_total, 2) }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                  <td colspan="7" class="text-center">No Data Found</td>
                </tr>
            @endif
            </tbody>
          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
  
      <div class="row">
        <!-- accepted payments column -->
        <div class="col-6">  
          <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
            Remarks : <b>{{ $po->remarks }}</b>
          </p>
        </div>
        <!-- /.col -->
        <div class="col-6">
  
          <div class="table-responsive">
            <table class="table">
              <tr>
                <th style="width:50%">Subtotal:</th>
                <td>{{ number_format($item_services->sum('sub_total'), 2) }}</td>
              </tr>
                <tr>
                  <th>Tax (10 %)</th>
                  <td>{{ $po->is_vat == 1 ? number_format($item_services->sum('sub_total') * 0.1, 2) : '-' }}</td>
                </tr>
              <tr>
                <th>Total:</th>
                <td>{{ $po->is_vat == 1 ? number_format($item_services->sum('sub_total') + $item_services->sum('sub_total') * 0.1, 2) : number_format($item_services->sum('sub_total'), 2) }}</td>
              </tr>
            </table>
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- ./wrapper -->
  <!-- Page specific script -->
  {{-- <script>
    window.addEventListener("load", window.print());
  </script> --}}
  </body>
</html>