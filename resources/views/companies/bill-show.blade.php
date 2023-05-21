@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('title', 'Bill')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Invoice</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.invoices', ['company' => $company])}}">Invoices</a></li>
                <li class="breadcrumb-item active">{{$bill->number}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="invoice p-3 mb-3">
                <div class="row">
                    <div class="col-12">
                        <div class="image">
                            <h4>
                            <img src="{{asset('storage/images/profile/companies/'.$company->logo)}}" width="55px" class="img-circle">
                            {{$company->display_name}}</h4>
                        </div>
                    </div>
                </div>
                <div class="row invoice-info">
                    <div class="col-sm-4 invoice-col">
                        From
                        <address>
                            <strong>{{$company->display_name}}</strong><br>
                            {{$company->addresses->first()->number}} {{$company->addresses->first()->street}} @if(!is_null($company->addresses->first()->suite)), {{$company->addresses->first()->suite}} @endif<br>
                            {{$company->addresses->first()->city}}, {{$company->addresses->first()->region}} {{$company->addresses->first()->postcode}}<br>

                            Phone: {{$company->contacts->where('type','phone')->first()->description}}<br>
                            Email: {{$company->contacts->where('type','email')->first()?$company->contacts->where('type','email')->first()->description:''}}
                        </address>
                    </div>
                    <div class="col-sm-4 invoice-col">
                        To
                        <address>
                            <strong>{{$tenant['name']}}</strong><br>
                            {{$tenant['address']->number}} {{$tenant['address']->street}} @if(!is_null($tenant['address']->suite)), App: {{$tenant['address']->suite}} @endif<br>
                            {{$tenant['address']->city}}, {{$tenant['address']->region}} {{$tenant['address']->postcode}}<br>
                            Phone: {{$tenant['phone']}}<br>
                            Email: {{$tenant['email']}}
                        </address>
                    </div>
                    <div class="col-sm-4 invoice-col">
                        <b>Invoice #{{$bill->number}}</b><br>
                        <br>
                        <b>Lease invoice</b> <br>
                        <b>Period:</b> {{$bill->period_from->format('Y M j')}} / {{$bill->period_to->format('Y M j')}} <br>
                        <b>Status:</b> {{strcmp($bill->status,'created')==0?'Not Payed':ucfirst($bill->status)}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                <th>Qty</th>
                                <th>Product</th>
                                <th>Reference #</th>
                                <th>Description</th>
                                <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bill_lines as $line)
                                    <tr>
                                        <td>1</td>
                                        <td>{{$line['name']}}</td>
                                        <td>{{$line['serial']}}</td>
                                        <td>{{is_null($line['description'])?'N/A':$line['description']}}</td>
                                        <td>${{number_format($line['amount'], 2)}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-6">
                        <p class="lead">Payment Methods:</p>
                        <img src="{{asset('storage/images/credit/visa.png')}}" alt="Visa">
                        <img src="{{asset('storage/images/credit/mastercard.png')}}" alt="Mastercard">
                        <img src="{{asset('storage/images/credit/american-express.png')}}" alt="American Express">
                        <img src="{{asset('storage/images/credit/paypal2.png')}}" alt="Paypal">
                        <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                            @if($bill->payments->isNotEmpty())
                            <b>Payment informations</b> <br>
                            Payment made by {{$bill->payments->last()->method}} <br>
                                @switch($bill->payments->last()->method)
                                    @case('cheque')
                                        Cheque number: {{$bill->payments->last()->method_number}}
                                        @break
                                    @case('cash')
                                        Payment made by cash
                                        @break
                                    @default
                                        {{$bill->payments->last()->transaction_id?$bill->payments->last()->transaction_id:'N/A'}}
                                @endswitch
                            @else
                                The payment can be made using one of the payment methods presented above.
                            @endif
                        </p>
                    </div>
                    <div class="col-6">
                        <p class="lead">
                            @if($bill->payments->isEmpty())
                            <b>Amount Due:</b> {{$bill->payment_due_date->format('Y M d')}}
                            @else
                            <b>Amount payed at:</b> {{$bill->payments->last()->payed_at->format('Y M d')}}
                            @endif</p>
                        <div class="table-responsive">
                            <table class="table">
                            <tbody><tr>
                            <th>Total:</th>
                            <td>${{number_format($bill->total_amount, 2)}}</td>
                            </tr>
                            </tbody></table>
                        </div>
                    </div>
                </div>

                <div class="row no-print">
                    <div class="col-12">
                        <a href="invoice-print.html" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                        @if($bill->payments->isEmpty())
                        <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit Payment</button>
                        @endif
                        <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;"><i class="fas fa-download"></i> Generate PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(function(){

    })

    </script>
@stop
