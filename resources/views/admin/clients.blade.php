@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('title', 'Clients')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>
                Clients
                <small>list</small>
            </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Admin</a></li>
                <li class="breadcrumb-item active">Clients</li>
            </ol>
        </div>
    </div>
</div><!-- /.container-fluid -->
@stop

@section('content')
@php
  $heads = [
    'Logo',
    'Name',
    'B. Number',
    'Admin',
    'Managers',
    'Janitors',
    'Tenants',
    ['label'=>'Actions', 'width'=>16],
  ];

  $config = [
    'processing' => true,
    'serverSide' => true,
    'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('admin.clients.index')],
    'responsive'=> true,
    'order' => [[1,'asc']],
    'columns' => [['data'=>'logo', 'searchable'=>false, 'orderable' => false], ['data'=>'display_name'], ['data'=>'bn'], ['data'=>'adminCount'], ['data'=>'managerCount'], ['data'=>'janitorCount'], ['data'=>'tenantCount'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]],
  ]
@endphp
    <div class="row">
        <div class="col-md-12">
            <x-adminlte-card title="Clients" theme="lightblue" icon="fas fa-lg fa-users" removable collapsible>
                <x-adminlte-datatable id="clientsTable" :heads="$heads" :config="$config" />
            </x-adminlte-card>
        </div>
    </div>
@stop


@section('js')
    <script> //console.log('Hi clients!'); </script>
@stop
