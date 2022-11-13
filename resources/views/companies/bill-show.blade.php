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
                <li class="breadcrumb-item active"> <a href="{{route('company.invoices', ['company' => $company])}}">Invoices</a></li>
                <li class="breadcrumb-item active">{{$bill->id}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
@php
    $heads = [
        '#',
        'Description',
        'Amount',
    ];
    $config = [
        'processing' => true,
        'serverSide' => true,
        'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.invoice.show', ['company'=>$company, 'bill'=>$bill])],
        'responsive'=> true,
        'order' => [[0,'asc']],
        'columns' => [['data'=>'DT_RowIndex', 'searchable'=>false, 'orderable' => false], ['data'=>'type'], ['data'=>'amount']],
        'dom'=>'<"row" <"col-sm-1 customButton"><"col-sm-5" B><"col-sm-6 d-flex justify-content-end" f> >
                <"row" <"col-12" tr> >
                <"row" <"col-sm-6 d-flex justify-content-start" i><"col-sm-6 d-flex justify-content-end" p> >',

    ]
@endphp
<div class="row">
    <div class="col-md-4">
        <x-adminlte-card title="Invoice" theme="lightblue" icon="fas fa-lg fa-file-invoice" header-class="text-uppercase rounded-bottom border-info" removable>
            <dl>
                <dt>Invoice</dt>
                <dd>{{$bill->number}}</dd>
                <dt>From - To</dt>
                <dd>
                    {{$bill->period_from->format('d M Y')}} - {{$bill->period_to->format('d M Y')}}
                </dd>
                <dt>Payment due:</dt>
                <dd>{{$bill->payment_due_date->format('d M Y')}}</dd>
                <dt>Total Amount</dt>
                <dd>{{$bill->total_amount}}</dd>
            </dl>
    </x-adminlte-card>
    </div>
    <div class="col-md-4">
        <x-adminlte-card title="Land lord" theme="lightblue" icon="fas fa-lg fa-user-secret" header-class="text-uppercase rounded-bottom border-info">
            <dl>
                <dt>Name</dt>
                <dd>{{$company->display_name}}</dd>
                <dt>Address</dt>
                <dd>
                    {{$company->addresses->first()->number}}, {{$company->addresses->first()->street}}, {{$company->addresses->first()->suite}}<br>
                    {{$company->addresses->first()->city}}, {{$company->addresses->first()->region}}, {{$company->addresses->first()->country}}
                </dd>
                <dt>Contact</dt>
                <dd>
                    @foreach ($company->contacts as $contact)
                        {{ucfirst($contact->type)}} : {{$contact->description}}<br>
                    @endforeach
                </dd>
            </dl>
        </x-adminlte-card>
    </div>
    <div class="col-md-4">
        <x-adminlte-card title="Tenant" theme="lightblue" icon="fas fa-lg fa-user-tie" header-class="text-uppercase rounded-bottom border-info">
            @foreach ($bill->checkAccounts as $checkaccount)
            <dl>
                <dt>Name</dt>
                <dd>{{$checkaccount->user->name}}</dd>
                <dt>Address</dt>
                <dd>
                    {{$checkaccount->user->addresses->first()->number}}, {{$checkaccount->user->addresses->first()->street}}, {{$checkaccount->user->addresses->first()->suite}}<br>
                    {{$checkaccount->user->addresses->first()->city}}, {{$checkaccount->user->addresses->first()->region}}, {{$checkaccount->user->addresses->first()->country}}
                </dd>
                <dt>Contact</dt>
                <dd>
                    Email : {{$checkaccount->user->email}} - System<br>
                    @foreach ($checkaccount->user->contacts as $contact)
                        {{ucfirst($contact->type)}} : {{$contact->description}} - {{ucfirst($contact->priority)}}<br>
                    @endforeach
                </dd>
            </dl>
            @endforeach
        </x-adminlte-card>
    </div>
    <div class="col-md-12">
        <x-adminlte-card theme="lightblue" theme-mode="outline">
            <x-adminlte-datatable id="InvoiceTable" :heads="$heads" :config="$config" with-buttons/>
        </x-adminlte-card>
    </div>
</div>
@stop

@section('js')
<script>
    $(function(){
        $(".customButton").html('@permission("bill-create")<div class="dt-buttons"><button type="button" class="btn btn-block btn-default addBill"><i class="fas fa-lg fa-plus-square text-primary"></i> New</button></div>@endpermission');
    })

    </script>
@stop
