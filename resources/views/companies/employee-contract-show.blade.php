@extends('adminlte::page')

@section('title', 'Employee Contract Details')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{$employee->name}} Contract</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.employees', ['company' => $company])}}">Employees</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.employees.show', ['company' => $company, 'employee'=>$employee])}}">{{$employee->name}}</a></li>
                <li class="breadcrumb-item active">Contract</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="row">
                <div class="col-sm-12">
                    <x-adminlte-card title="Contract" theme="lightblue" icon="fas fa-lg fa-file-contract" removable collapsible>
                        <dl class="row">
                            <dt class="col-sm-5">Role</dt>
                            <dd class="col-sm-7 border-bottom">{{$role_name}}</dd>
                            <dt class="col-sm-5">Availability</dt>
                            <dd class="col-sm-7 border-bottom">{{ucfirst($contract->availability)}}</dd>
                            <dt class="col-sm-5">Min week time</dt>
                            <dd class="col-sm-7 border-bottom">{{$contract->min_week_time ? $contract->min_week_time : 'N/A'}}</dd>
                            <dt class="col-sm-5">Max week time</dt>
                            <dd class="col-sm-7 border-bottom">{{$contract->max_week_time ? $contract->max_week_time : 'N/A'}}</dd>
                            <dt class="col-sm-5">Start date</dt>
                            <dd class="col-sm-7 border-bottom">{{$contract->start_at==null?'':$contract->start_at->format('d F Y')}}</dd>
                            <dt class="col-sm-5">End date</dt>
                            <dd class="col-sm-7 border-bottom">{{$contract->end_at==null?'':$contract->end_at->format('d F Y')}}</dd>
                            <dt class="col-sm-5">Agreement status</dt>
                            <dd class="col-sm-7 border-bottom">{{ucfirst($contract->agreement_status)}}</dd>
                            <dt class="col-sm-5">Acceptance date</dt>
                            <dd class="col-sm-7">{{$contract->acceptance_at? $contract->acceptance_at->format('d F Y'):'N/A'}}</dd>
                        </dl>
                    </x-adminlte-card>
                </div>
                <div class="col-sm-12">
                    <x-adminlte-card title="Payment" theme="lightblue" icon="fas fa-lg fa-money-check-alt" removable collapsible>
                        <dl class="row">
                            <dt class="col-sm-4">Term</dt>
                            <dd class="col-sm-8">{{ucfirst($contract->salary_term)}}</dd>
                            <dt class="col-sm-4">Amount</dt>
                            <dd class="col-sm-8">${{number_format($contract->salary_amount, 2)}}</dd>
                            <dt class="col-sm-4">Benefits</dt>
                            <dd class="col-sm-8">
                                @if($contract->teamSettings->isNotEmpty())
                                    @foreach ($contract->teamSettings as $key=>$benefit)
                                        @if($key>0 && $key<$contract->teamSettings->count())
                                        ,
                                        @endif
                                        <span class="badge bg-success">{{$benefit->display_name}}</span>
                                    @endforeach
                                @else
                                    N/A
                                @endif
                            </dd>
                        </dl>
                    </x-adminlte-card>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <x-adminlte-card title="Agreement" theme="lightblue" icon="fas fa-lg fa-file-signature" removable collapsible>
                @if(empty($contract->agreement))
                    <strong>Agreement not available</strong>
                @else
                <div class="test" style="height:700px;">
                    {!! $contract->agreement !!}
                </div>
                @endif
            </x-adminlte-card>
        </div>
    </div>
@stop

@section('css')
    <style>
        .test {
            overflow: scroll;
        }
    </style>
@stop

@section('js')
    <script> //console.log('Hi!'); </script>
@stop
