@extends('adminlte::page')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('title', 'Employees')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Employees</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item active">Employees</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
@php
  $heads = [
    'Photo',
    'Name',
    'Role',
    'Email',
    'Phone',
    'Availability',
    'Status',
    'Actions'
  ];

  $config = [
    'processing' => true,
    'serverSide' => true,
    'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.employees', ['company'=>$company])],
    'responsive'=> true,
    'order' => [[1,'asc']],
    'columns' => [['data'=>'photo', 'searchable'=>false, 'orderable' => false], ['data'=>'name'], ['data'=>'role'], ['data'=>'email'], ['data'=>'contact'], ['data'=>'availability'], ['data'=>'active'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]],
    'dom'=>'<"row" <"col-sm-1 customButton"><"col-sm-5" B><"col-sm-6 d-flex justify-content-end" f> >
                <"row" <"col-12" tr> >
                <"row" <"col-sm-6 d-flex justify-content-start" i><"col-sm-6 d-flex justify-content-end" p> >',
  ]
@endphp
<div class="row">
    <div class="col-md-12">
        <x-adminlte-card title="Employees" theme="lightblue" icon="fas fa-lg fa-users" removable collapsible>
            <x-adminlte-datatable id="EmployeesTable" :heads="$heads" :config="$config" with-buttons/>
        </x-adminlte-card>
    </div>
</div>
@stop

@section('js')
    <script>
        $(function(){
            $(".customButton").html('@permission("employee-create")<div class="dt-buttons"><button type="button" class="btn btn-block btn-default addEmployee"><i class="fas fa-lg fa-plus-square text-primary"></i> New</button></div>@endpermission');

            $(".addEmployee").on("click", function(event){
                window.location.href= "{{route('company.employees.create', [$company->slug])}}";
            })
        })
    </script>
@stop
