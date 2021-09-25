@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Building {{$building->display_name}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.buildings', ['company' => $company])}}">Buildings</a></li>
                <li class="breadcrumb-item active">{{$building->display_name}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <x-adminlte-card title="Purple Card" theme="purple" icon="fas fa-lg fa-fan" removable collapsible>
                A removable and collapsible card with purple theme...
            </x-adminlte-card>
        </div>
        <div class="col-md-9">
            <x-adminlte-card title="Purple Card" theme="purple" icon="fas fa-lg fa-fan" removable collapsible>
                A removable and collapsible card with purple theme...
            </x-adminlte-card>
        </div>
    </div>
@stop


@section('js')
    <script> //console.log('Hi!'); </script>
@stop
