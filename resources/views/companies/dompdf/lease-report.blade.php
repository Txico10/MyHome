<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Download lease</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">

    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    <style>
        @-moz-document url-prefix() {
          fieldset { display: table-cell; }
        }

        body {
          font-family: Verdana, Geneva, Tahoma, sans-serif;
          font-size: 11px;
        }

        div.full { border:thin blue solid; margin:2pt;}

        #footer {
            position: fixed;
            left: 0;
            right: 0;
            color: #aaa;
            font-size: 0.9em;
        }

        .page-break {
            page-break-after: always;
        }

        table.signature_table {
          width: 80%;
          font-size: 0.7em;
          margin: 2em auto 2em auto;
        }

        table.signature_table tr td {
          padding-top: 1.5em;
          vertical-align: top;
          white-space: nowrap;
        }

        .table-borderless > tbody > tr > td,
        .table-borderless > tbody > tr > th,
        .table-borderless > tfoot > tr > td,
        .table-borderless > tfoot > tr > th,
        .table-borderless > thead > tr > td,
        .table-borderless > thead > tr > th {
            border: none;
        }

        .written_field {
          border-bottom: 1px solid black;
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
            $text = "Lease: {{'BL'.$lease->start_at->format('mY').$lease->id.$company->id}}";
            $pdf->text(16, $y, $text, $font, $size, $color);

            $text = "Approved: {{$lease->created_at->format('d-F-Y h:i:s A')}}";
            if (class_exists('Font_Metrics')) {
              $width = Font_Metrics::get_text_width($text, $font, $size);
            } elseif (class_exists('Dompdf\\FontMetrics')) {
              $width = $fontMetrics->getTextWidth($text, $font, $size);
            }
            $pdf->text($w - 16 - $width, $y, $text, $font, $size, $color);

            $pdf->close_object();
            $pdf->add_object($foot, "all");

            if (strcmp("{{$lease_status}}", "Inactive")==0) {
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

    <div class="container">
        <table class="table table-borderless">
            <tr>
                <td>
                    <div class="row">
                        <div class="col-xs-4">

                        </div>
                        <div class="col-xs-7" style="text-align: right">
                            <p class="text-primary" style="font-size: xx-large; font-weight:bolder">LEASE OF DWEELING</p>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td class="bg-primary" style="width: 15px; padding: 3px; text-align: center;">A</td>
                            <td style="width: 1px;">&nbsp;</td>
                            <td class="bg-primary" style="padding-left: 5px; width: 33.5%; ">Between the lessor</td>
                            <td class="bg-primary" style="padding-left: 5px;">And the lessee(s)</td>
                        </tr>
                    </tbody>
                </table>
            </tr>
            <tr>
                <td>
                    <div class="row">
                        <div class="col-xs-4" style="border-right:thin solid #0275d8;">
                            <strong class="text-primary">{{$company->display_name}}</strong><br>
                            {{$company->addresses->first()->number}} {{$company->addresses->first()->street}} <br>
                            @if(!empty($company->addresses->first()->suite))
                            {{$company->addresses->first()->suite}}
                            @endif
                            {{$company->addresses->first()->city}}, {{$company->addresses->first()->region}} <br>
                            {{$company->addresses->first()->country}} {{$company->addresses->first()->postcode}} <br>
                            <span><i class="fas fa-phone-alt fa-fw"></i></span> : <span>{{$company->contacts->where('type', 'phone')->first()?$company->contacts->where('type', 'phone')->first()->description:""}}</span><br>
                            <span><i class="fas fa-envelope fa-fw"></i></span> : <span>{{$company->contacts->where('type', 'email')->first()?$company->contacts->where('type', 'email')->first()->description:""}}</span><br>
                        </div>

                        @foreach ($lease->users as $user)
                        <div class="col-xs-3">
                            <strong class="text-primary">{{$user->name}}</strong><br>
                            {{$user->addresses->first()->number}} {{$user->addresses->first()->street}} <br>
                            @if(!empty($user->addresses->first()->suite))
                            {{$user->addresses->first()->suite}}
                            @endif
                            {{$user->addresses->first()->city}}, {{$user->addresses->first()->region}} <br>
                            {{$user->addresses->first()->country}} {{$user->addresses->first()->postcode}} <br>
                            <span><i class="fas fa-phone-alt fa-fw"></i></span> :
                            @if($user->contacts->where('type', 'mobile')->first())
                                {{$user->contacts->where('type', 'mobile')->first()->description}}
                            @else
                                @if($user->contacts->where('type', 'phone')->first())
                                    {{$user->contacts->where('type', 'phone')->first()->description}}
                                @else
                                    {{"N/A"}}
                                @endif
                            @endif

                            <br>
                            <span><i class="fas fa-envelope fa-fw"></i></span> : {{$user->email??"N/A"}}<br>
                        </div>
                        @endforeach
                    </div>
                </td>
            </tr>
            <tr>
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td class="bg-primary" style="width: 15px; padding: 3px; text-align: center">B</td>
                            <td style="width: 1px;">&nbsp;</td>
                            <td class="bg-primary" style="padding-left: 5px">Description and destination of leased dweeling, accessories and dependencies</td>
                        </tr>
                    </tbody>
                </table>
            </tr>
            <tr>
                <td>
                    <p>
                    <strong class="text-primary">Address:</strong> {{$lease->apartment->building->address->number}} {{$lease->apartment->building->address->street}} <strong class="text-primary">Appt:</strong> {{$lease->apartment->number}}
                    <strong class="text-primary">Municipality:</strong> {{$lease->apartment->building->address->city}}, {{$lease->apartment->building->address->region}} {{$lease->apartment->building->address->country}}
                    <strong class="text-primary">Postal Code:</strong> {{$lease->apartment->building->address->postcode}} <strong class="text-primary">Number of rooms:</strong> {{$lease->apartment->teamSettings->first()->display_name}}</p>
                    <p> The dwelling is leased for residencial purpose only
                    @if($lease->residential_purpose)
                        <strong class="text-primary">Yes</strong>
                    @else
                        <strong class="text-primary">No</strong> <br>
                        The dwelling is leased for combined purposes of housing and {{$lease->residential_purpose_description}} but more than one-tird of the total floor area will be used for that second purpose
                    @endif
                    </p>
                    <p>Dwelling is located in a unit under devided co-ownership <strong class="text-primary">{{$lease->co_ownership?"Yes":"No"}}</strong></p>
                    @php
                        $total_service=0;
                    @endphp
                    @if($lease->dependencies->count()>0)
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>Type</th>
                              <th>Number</th>
                              <th>Price</th>
                              <th>Description</th>
                            </tr>
                          </thead>
                        </thead>
                        <tbody>
                            @foreach ($lease->dependencies as $key=>$new_dependency)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$new_dependency->teamSettings->first()->display_name}}</td>
                                <td>{{$new_dependency->number}}</td>
                                <td>${{number_format($new_dependency->pivot->price, 2, '.', ',')}}</td>
                                <td>{{$new_dependency->pivot->description}}</td>
                            </tr>
                            @php
                                $total_service+=$new_dependency->pivot->price;
                            @endphp
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    <p>Furniture is leased and included in the rent <strong class="text-primary">{{$lease->furniture_included?"Yes":"No"}}</strong></p>
                    @if($lease->furniture_included)
                        <table class="table table-condensed">
                            <thead>
                              <tr>
                                <th style="width: 10px">#</th>
                                <th>Type</th>
                                <th>Manufacturer</th>
                                <th>Model</th>
                                <th>Price</th>
                                <th>Description</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($lease->accessories as $key=>$new_accessory)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$new_accessory->teamSettings->first()->display_name}}</td>
                                    <td>{{ucfirst($new_accessory->manufacturer)}}</td>
                                    <td>{{ucfirst($new_accessory->model)}}</td>
                                    <td>$ {{number_format($new_accessory->pivot->price, 2, '.', ',')}}</td>
                                    <td>{{$new_accessory->pivot->description}}</td>
                                </tr>
                                @php
                                    $total_service+=$new_accessory->pivot->price;
                                @endphp
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    <div style="border:thin solid; border-color:black; margin:2pt ;text-align: justify;">
                        <p style="padding: 1rem"><strong>The lessor and the lessee undertake, in acordance with their respective responsabilities, to comply with the regulations respecting the presence and proper working order of one or more smoke deterctors in the dweeling and the immovable.</strong></p>
                        <table class="signature_table">
                            <tr>
                              <td class="written_field" style="width: 15%; padding-left: 1em">&nbsp;</td>
                              <td>&nbsp;</td>
                              <td class="written_field" style="width: 15%">&nbsp;</td>
                              <td>&nbsp;</td>
                              @for($i = 0; $i < $lease->users->count(); $i++)
                                <td class="written_field" style="width: 15%">&nbsp;</td>
                                <td>&nbsp;</td>
                              @endfor
                              <td class="written_field" style="width: 15%">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="text-align: center; padding-top: 0em">initials of lessor</td>
                                <td>&nbsp;</td>
                                <td style="text-align: center; padding-top: 0em;">Date</td>
                                <td>&nbsp;</td>
                                @for($i = 0; $i < $lease->users->count(); $i++)
                                <td style="text-align: center; padding-top: 0em">initials of lessee</td>
                                <td>&nbsp;</td>
                                @endfor
                                <td style="text-align: center; padding-top: 0em;">Date</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td class="bg-primary" style="width: 15px; padding: 3px; text-align: center">C</td>
                            <td style="width: 1px;">&nbsp;</td>
                            <td class="bg-primary" style="padding-left: 5px">Term of lease</td>
                        </tr>
                    </tbody>
                </table>
            </tr>
            <tr>
                <td>
                    <p><strong class="text-primary">{{Str::upper($lease->term)}} TERM LEASE</strong> <br>
                        The term of lease is
                        @if(strcmp($lease->term, 'fixed')==0)
                            <u>{{$lease->end_at->diffForHumans($lease->start_at, ['parts' => 3])}} lease starts</u> <br>
                            From <u>{{$lease->start_at->format('d / m / Y')}}</u> to <u>{{$lease->end_at->format('d / m / Y')}}</u>
                        @else
                            indeterminate <br>
                            Beginning on <u>{{$lease->start_at->format('d / m / Y')}}</u>
                        @endif
                    </p>
                </td>
            </tr>
            <tr>
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td class="bg-primary" style="width: 15px; padding: 3px; text-align: center">D</td>
                            <td style="width: 1px;">&nbsp;</td>
                            <td class="bg-primary" style="padding-left: 5px">Rent</td>
                        </tr>
                    </tbody>
                </table>
            </tr>
            <tr>
                <td>
                    <p>
                        The rent is <u>${{number_format($lease->rent_amount, 2, '.', ',')}}</u><br>
                        The total cost of services: <u>${{number_format($total_service, 2, '.', ',')}}</u><br>
                        The total rent is: <u>${{number_format($lease->rent_amount+$total_service, 2, '.', ',')}}</u><br>
                    </p>
                    <span class="text-primary"><strong>Date of payment</strong></span>
                    <ul>
                        <li>First paiment period: {{$lease->first_payment_at->format('d / m / Y')}} <br></li>
                        <li>The rent will be payed on the 1st day of the <u>{{$lease->rent_recurrence}}</u></li>
                    </ul>
                    <span class="text-primary"><strong>Method of payment</strong></span><br>
                    The rent is payable in accordance with the following method of payment:
                    <ul>
                        <li>
                            @foreach ($lease->teamSettings->where('type', 'method_payment') as $payment_method)
                                {{$payment_method->display_name}},
                            @endforeach
                        </li>
                    </ul>
                    <p>The lessee agreement to give the lessor postdated cheques for the term of the lease <strong>{{$lease->postdated_cheques==true?"Yes":"No"}}</strong></p>
                </td>
            </tr>
            <tr>
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td class="bg-primary" style="width: 15px; padding: 3px; text-align: center">E</td>
                            <td style="width: 1px;">&nbsp;</td>
                            <td class="bg-primary" style="padding-left: 5px">Services and conditions</td>
                        </tr>
                    </tbody>
                </table>
            </tr>
            <tr>
                <td>
                    <strong class="text-primary">By-laws of immovable</strong><br>
                    <p>A copy of the by-laws of the imovable was given to the lessee <strong>before</strong> entering into the lease <br>
                    Given on: {{is_null($lease->by_law_given_on)?'N/A':$lease->by_law_given_on->format('d / m / Y')}}</p>
                    <span class="text-primary"><strong>Works and repaires</strong></span><br>
                    The works and repairs to be done by the lessor and the timetable for performing them are as follows:
                    <table>
                        <tr>
                            <td>
                                <ul>
                                    <li><strong>Before</strong> the delivery of the dwelling</li>
                                    <ul>
                                        <li>
                                        @foreach ($lease->teamSettings->where('type','service') as $service)
                                            @if(Str::startsWith($service->pivot->description, 'before') )
                                                {{$service->display_name}},
                                            @endif
                                        @endforeach
                                        </li>
                                    </ul>

                                </ul>
                            </td>
                            <td>
                                <ul>
                                    <li><strong>During</strong> the lease</li>
                                    <ul>
                                        <li>
                                        @foreach ($lease->teamSettings->where('type','service') as $service)
                                            @if(Str::startsWith($service->pivot->description, 'during') )
                                                {{$service->display_name}},
                                            @endif
                                        @endforeach
                                        </li>
                                    </ul>
                                </ul>
                            </td>
                        </tr>
                    </table>
                    <p><strong class="text-primary">Janitorial Services</strong><br>
                    For any problem, please contact the janitor at the following contacts: <br>
                    <strong>Name:</strong> {{$janitor->name}} <strong>Mobile:</strong>{{$janitor->contacts->where('type', 'mobile')->first()?$janitor->contacts->where('type', 'mobile')->first()->description:""}} <strong>Email:</strong>{{$janitor->contacts->where('type', 'email')->first()?$janitor->contacts->where('type', 'email')->first()->description:""}}</p>

                </td>
            </tr>
            <tr>
                <td>
                    <span class="text-primary"><strong>Services taxes and consumption costs</strong></span>
                <div class="row">
                    <div class="col-xs-5">
                        @if($lease->teamSettings->where('type','consumption_cost')->count()>0)
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Will be born by</th>
                                    <th>Lessor</th>
                                    <th>Lessee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lease->teamSettings->where('type','consumption_cost') as $consumption)
                                    <tr>
                                        @if (strcmp($consumption->display_name, 'Heating of dwelling')==0 && !empty($lease->apartment->teamSettings->where('type', 'heating_of_dweeling')->first()))
                                            <td>
                                                {{$consumption->display_name.' ('.$lease->apartment->teamSettings->where('type', 'heating_of_dweeling')->first()->display_name.')'}}
                                            </td>
                                        @else
                                            <td>{{$consumption->display_name}}</td>
                                        @endif
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if (strcmp('lessor', $consumption->pivot->description)==0)
                                                <span class="text-primary"><i class="fas fa-check"></i></span>
                                            @endif
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if(strcmp('lessee', $consumption->pivot->description)==0)
                                                <span class="text-primary"><i class="fas fa-check"></i></span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                    <div class="col-xs-6">
                        @if($lease->teamSettings->where('type','snow_removal')->count()>0)
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Snow and ice removal</th>
                                    <th>Lessor</th>
                                    <th>Lessee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($lease->teamSettings->where('type','snow_removal') as $cost)
                                    <tr>
                                        <td>{{$cost->display_name}}</td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if (strcmp('lessor', $cost->pivot->description)==0)
                                                <span class="text-primary"><i class="fas fa-check"></i></span>
                                            @endif
                                        </td>
                                        <td style="text-align: center; vertical-align: middle;">
                                            @if(strcmp('lessee', $cost->pivot->description)==0)
                                                <span class="text-primary"><i class="fas fa-check"></i></span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <p>
                        <strong class="text-primary">CONDITIONS</strong><br>
                        The lessee has the rigth of access to the land: <strong class="text-primary">{{$lease->land_access?"Yes":"No"}}</strong><br>
                        @if(!$lease->land_access)
                            <strong class="text-primary">Specification:</strong> {{$lease->land_access_description}} <br>
                        @endif </p>
                        <p>The lessee has the rigth to keep one or more animals: <strong class="text-primary">{{$lease->animals?"Yes":"No"}}</strong> <br>
                        @if($lease->animals)
                            <strong>Specifications:</strong> {{$lease->animals_description}} <br>
                        @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <p><strong class="text-primary">OTHER SERVICES, CONDITIONS AND RESTRICTIONS</strong><br>
                        {{$lease->others??'N/A'}}</p>
                    </div>
                </div>
                </td>
            </tr>
            @if(!is_null($lease->apartment->building->ready_for_habitation) && $lease->apartment->building->ready_for_habitation->greaterThan('today-5years'))
                <tr>
                    <table style="width: 100%;">
                        <tbody>
                            <tr>
                                <td class="bg-primary" style="width: 15px; padding: 3px; text-align: center">F</td>
                                <td style="width: 1px;">&nbsp;</td>
                                <td class="bg-primary" style="padding-left: 5px">Restrictions on the right to have the rent fixed and the lease modified</td>
                            </tr>
                        </tbody>
                    </table>
                </tr>
                <tr>
                    <td>
                        <p><strong>The lessor and the lessee may not apply to the Regie du logement for the fixing of the rent or for the modification of another condition of the lease</strong></p>
                        <p>The immovable become ready for habitation on {{$lease->apartment->building->ready_for_habitation->format('d/ m/ Y')}}</p>
                    </td>
                </tr>
            @endif
            <tr>
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td class="bg-primary" style="width: 15px; padding: 3px; text-align: center">F</td>
                            <td style="width: 1px;">&nbsp;</td>
                            <td class="bg-primary" style="padding-left: 5px">Signatures</td>
                        </tr>
                    </tbody>
                </table>
            </tr>
            <tr>
                <table class="signature_table">
                    @for($i = 0; $i < $lease->users->count(); $i++)
                    <tr>
                        <td class="written_field" style="width: 33%; padding-left: 1em">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="written_field" style="width: 13%">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="written_field" style="width: 33%">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="written_field" style="width: 13%">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding-top: 0em">Signature of the lessor</td>
                        <td style="padding-top: 0em">&nbsp;</td>
                        <td style="text-align: center; padding-top: 0em;">Date</td>
                        <td style="padding-top: 0em">&nbsp;</td>
                        <td style="text-align: center; padding-top: 0em;">Signature of lessee</td>
                        <td style="padding-top: 0em">&nbsp;</td>
                        <td style="text-align: center; padding-top: 0em;">Date</td>
                    </tr>
                    @endfor
                </table>
            </tr>
        </table>
    </div>
</body>
</html>
