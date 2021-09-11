@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('title', 'Employee Show')

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
                <li class="breadcrumb-item"><a href="{{route('company.employees', ['company' => $company])}}">Employees</a></li>
                <li class="breadcrumb-item active">{{$employee->name}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Employee profile -->
        <div class="card card-widget widget-user-2">
            <div class="widget-user-header bg-gradient-lightblue" style="">
                <div class="widget-user-image">
                    <img class="img-circle elevation-2" src="{{!empty($employee->photo)? asset('storage/images/profile/users/'.$employee->photo) :'https://picsum.photos/id/1/100'}}" alt="User avatar:{{$employee->name}}">
                </div>
                <h3 class="widget-user-username mb-0">{{$employee->name}}</h3>
                <h5 class="widget-user-desc">{{$role_name}}</h5>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="p-0 col-12 mr-1 border-bottom">
                        <span class="nav-link">
                            <i class="fas fa-fw fa-venus-mars"></i>
                            Sex
                            <span class="float-right">
                                {{Str::ucfirst($employee->gender) }}
                            </span>
                        </span>
                    </div>
                    <div class="p-0 col-12 mr-1 border-bottom">
                        <span class="nav-link">
                            <i class="fas fa-fw fa-birthday-cake"></i>
                            Birthdate
                            <span class="float-right">
                                {{$employee->birthdate->format('d F Y')}}
                            </span>
                        </span>
                    </div>
                    <div class="p-0 col-12 mr-1 border-bottom">
                        <span class="nav-link">
                            <i class="fas fa-fw fa-user-check"></i>
                            Status
                            <span class="float-right">
                                {{$employee->active?"Active":"Inactive"}}
                            </span>
                        </span>
                    </div>
                    <div class="p-0 col-12 mr-1 border-bottom">
                        <span class="nav-link">
                            <i class="fas fa-fw fa-envelope"></i>
                            Email
                            <span class="float-right">
                                {{$employee->email}}
                            </span>
                        </span>
                    </div>
                    <div class="p-0 col-12 mr-1 mb-2">
                        <span class="nav-link">
                            <i class="fas fa-fw fa-hashtag"></i>SSN
                            <span class="float-right">{{$employee->ssn}}</span>
                        </span>
                    </div>
                    <button type="button" class="btn btn-primary btn-block">Edit profile </button>
                </div>
            </div>
        </div>
        <!--User contact-->
        <livewire:addresses :model="$employee" />
        <livewire:contacts :model="$employee" />
    </div>
    <div class="col-md-9">
        @php
            $heads = [
                '#',
                'Role',
                'Availability',
                'Start date',
                'End date',
                'Status',
                'Acceptance date',
                'Action'
            ];
            $config = [
                'processing' => true,
                'serverSide' => true,
                'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.employees.contract', ['company'=>$company, 'employee'=>$employee])],
                'responsive'=> true,
                'order' => [[0,'asc']],
                'columns' => [['data'=>'DT_RowIndex'], ['data'=>'role'], ['data'=>'availability'], ['data'=>'start_at'], ['data'=>'end_at'], ['data'=>'agreement_status'], ['data'=>'acceptance_at'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]]
            ]
        @endphp
        <x-adminlte-card title="Contracts" theme="lightblue" icon="fas fa-lg fa-file-contract" removable collapsible>
            <x-adminlte-datatable id="employeeContracts" :heads="$heads" :config="$config" />
        </x-adminlte-card>
    </div>
</div>
@stop

@section('js')
    <script> //console.log('Hi!'); </script>
@stop
