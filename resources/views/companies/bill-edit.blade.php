@extends('adminlte::page')
@section('plugins.Moment', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datepicker', true)
@section('plugins.Inputmask', true)

@section('title', 'Edit Bill')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Invoice #{{$bill->number}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.invoices', ['company' => $company])}}">Invoices</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.invoice.show', ['company' => $company, 'bill' => $bill])}}">{{$bill->number}}</a></li>
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
@livewire('bill-form', ['company'=>$company, 'bill'=>$bill])
@stop
