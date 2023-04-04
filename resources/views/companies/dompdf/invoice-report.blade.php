<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Download Invoice</title>

    <style>
        body {
          font-family: Verdana, Geneva, Tahoma, sans-serif;
          font-size: 10px;
          margin-left:1px;
          margin-right:1px;
        }
    </style>

</head>
<body>
<script type="text/php">
    if (isset($pdf)) {
        $size = 6;
        $color = array(0,0,0);
        if (class_exists('Font_Metrics')) {
          $font = Font_Metrics::get_font("helvetica");
          $font_bold = Font_Metrics::get_font("helvetica", "bold");
          $text_height = Font_Metrics::get_font_height($font, $size);
        } elseif (class_exists('Dompdf\\FontMetrics')) {
          $font = $fontMetrics->getFont("helvetica");
          $font_bold = $fontMetrics->getFont("helvetica", "bold");
          $text_height = $fontMetrics->getFontHeight($font, $size);
        }

        $foot = $pdf->open_object();

        $w = $pdf->get_width();
        $h = $pdf->get_height();

        // Draw a line along the bottom
        $y = $h - 2.5 * $text_height - 24;
        $pdf->line(16, $y, $w - 16, $y, array(0,0,0), 1);

        $y += $text_height;

        // Add the job number
        $text = "Lease: {{'IN'.$bill->period_from->format('mY').$bill->number.$company->id}}";
        $pdf->text(16, $y, $text, $font, $size, $color);

        $text = "Approved: {{$bill->created_at->format('d-F-Y h:i:s A')}}";
        if (class_exists('Font_Metrics')) {
          $width = Font_Metrics::get_text_width($text, $font, $size);
        } elseif (class_exists('Dompdf\\FontMetrics')) {
          $width = $fontMetrics->getTextWidth($text, $font, $size);
        }
        $pdf->text($w - 16 - $width, $y, $text, $font, $size, $color);

        $pdf->close_object();
        $pdf->add_object($foot, "all");


        if (strcmp("{{$bill->status}}", "Inactive")==0) {
            $watermark = $pdf->open_object();
            $pdf->text(110, $h - 240, "INACTIVE", $font_bold, 110, array(0.5, 0.5, 0.5), 0, 0, -52);
            $pdf->close_object();
            $pdf->add_object($watermark, "all");
        }


        $text = "Page {PAGE_NUM} of {PAGE_COUNT}";
        if (class_exists('Font_Metrics')) {
          $width = Font_Metrics::get_text_width($text, $font, $size);
        } elseif (class_exists('Dompdf\\FontMetrics')) {
          $width = $fontMetrics->getTextWidth($text, $font, $size);
        }
        $pdf->page_text($w / 2 - $width / 2, $y, $text, $font, $size, $color);
    }
</script>
    <div class="container-md">
        <table class="table">
            <tbody>
                <tr>
                    <td>
                        <ul class="list-unstyled">
                            @foreach ($tenants as $tenant)
                            <li class="text-muted">To: <span style="color:#5d9fc5 ;">{{$tenant->name}}</span></li>
                            @endforeach
                            <li class="text-muted">{{$tenant->addresses->first()->number}}, {{$tenant->addresses->first()->street}}, {{$tenant->addresses->first()->suite}}, {{$tenant->addresses->first()->city}}</li>
                            <li class="text-muted">{{$tenant->addresses->first()->region}}, {{$tenant->addresses->first()->country}}</li>
                            <li class="text-muted"><i class="fas fa-envelope"></i> {{$tenant->email}}</li>
                            @foreach ($tenant->contacts as $contact)
                                @if(strcmp($contact->type,'mobile')==0)
                                    <li class="text-muted"><i class="fas fa-phone"></i> {{$contact->description}} - {{ucfirst($contact->priority)}}</li>
                                @endif
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <img src="{{asset('storage/images/profile/companies/'.$company->logo)}}" class="rounded-sm" alt="...">
                    </td>
                    <td>
                        <p class="text-muted">Invoice</p>
                        <ul class="list-unstyled">
                            <li class="text-muted"><i class="fas fa-circle" style="color:#3c8dbc ;"></i> <span
                            class="fw-bold">ID:</span>#{{$bill->number}}-{{$bill->created_at->format('Y')}}</li>
                            <li class="text-muted"><i class="fas fa-circle" style="color:#3c8dbc ;"></i> <span
                            class="fw-bold">Period: </span>{{$bill->period_from->format('Y-m')}} / {{$bill->period_to->format('Y-m')}}</li>
                            <li class="text-muted"><i class="fas fa-circle" style="color:#3c8dbc ;"></i> <span
                            class="fw-bold">Due date: </span>{{$bill->payment_due_date->format('Y-m-d')}}</li>
                            <li class="text-muted"><i class="fas fa-circle" style="color:#3c8dbc ;"></i> <span
                            class="me-1 fw-bold">Status:</span><span class="badge bg-warning text-black fw-bold">
                            {{ucfirst($bill->status)}}</span></li>
                        </ul>
                    </td>

                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
            </tbody>
        </table>
    </div>

        <div class="row">
            <div class="col-xs-8">

            </div>
            <div class="col-xs-4">

            </div>
        </div>

        <div class="row my-2 mx-1 justify-content-center">
            <table class="table table-sm">
                <thead style="background-color:#3c8dbc ;" class="text-white">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Description</th>
                    <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bill_lines as $key=>$line)
                    <tr>
                        <th scope="row">{{$key+1}}</th>
                        <td>{{$line['name']}}</td>
                        <td>${{number_format($line['amount'], 2)}}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
        <div class="row">
            <div class="col-xl-9">
                <p class="ms-3">Add additional notes and payment information</p>
            </div>
            <div class="col-xl-3">
              <ul class="list-unstyled">
                <li class="text-muted ms-3"><span class="text-black me-4"></span></li>
                <li class="text-muted ms-3 mt-2"><span class="text-black me-4"></span></li>
              </ul>
              <p class="text-black float-start"><span class="text-black me-3"> Total Amount</span><span
                  style="font-size: 25px;">${{$bill->total_amount}}</span></p>
            </div>
        </div>
</body>
</html>



