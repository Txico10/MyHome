@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Moment', true)
@section('plugins.Datepicker', true)
@section('plugins.Inputmask', true)
@section('title', 'New Employee')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">New Employee</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.employees', ['company' => $company])}}">Employees</a></li>
                <li class="breadcrumb-item active">New Employee</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            {{-- Empty space --}}
        </div>
        <div class="col-md-6">
            <x-adminlte-card theme="lightblue" theme-mode="outline">
                <livewire:employees-create-form :company="$company"/>
            </x-adminlte-card>

        </div>
        <div class="col-md-3">
            {{-- Empty Space --}}
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css">
@stop

@section('js')
    <script> //console.log('Hi!'); </script>
@stop
