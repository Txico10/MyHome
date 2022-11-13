@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('title', 'Bills')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Bills</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item active">Invoices</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
@php
    $heads = [
        'Number',
        'Lease',
        'Tenant',
        'Month',
        'Period',
        'Year',
        'Total',
        'Payment until',
        'Status',
        'Actions'
    ];
    $config = [
        'processing' => true,
        'serverSide' => true,
        'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.invoices', ['company'=>$company])],
        'responsive'=> true,
        'order' => [[0,'asc']],
        'columns' => [['data'=>'number'],  ['data'=>'lease'], ['data'=>'tenant'],['data'=>'period'], ['data'=>'month'], ['data'=>'year'], ['data'=>'total_amount'], ['data'=>'payment_due_date'] , ['data'=>'status'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]],
        'dom'=>'<"row" <"col-sm-1 customButton"><"col-sm-5" B><"col-sm-6 d-flex justify-content-end" f> >
                <"row" <"col-12" tr> >
                <"row" <"col-sm-6 d-flex justify-content-start" i><"col-sm-6 d-flex justify-content-end" p> >',
    ]
@endphp
<div class="row">
    <div class="col-md-12">
        <x-adminlte-card title="Bills" theme="lightblue" icon="fas fa-lg fa-file-contract" removable collapsible>
            <x-adminlte-datatable id="billsTable" :heads="$heads" :config="$config" with-buttons/>
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
