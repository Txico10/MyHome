@extends('adminlte::page')

@section('title', 'Employee Contract Details')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{$employee->name}} Contract</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            {{--
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.employees', ['company' => $company])}}">Employees</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.employees.show', ['company' => $company, 'employee'=>$employee])}}">{{$employee->name}}</a></li>
                <li class="breadcrumb-item active">Contract</li>
            </ol>
            --}}
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if(session('success'))
                <x-adminlte-alert theme="success" title="Success" dismissable>
                    {{session('success')}}
                </x-adminlte-alert>
            @endif
        </div>
    </div>
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
                            <dd class="col-sm-7 border-bottom">{{$contract->start_at==null?'N/A':$contract->start_at->format('d F Y')}}</dd>
                            <dt class="col-sm-5">End date</dt>
                            <dd class="col-sm-7 border-bottom">{{$contract->end_at==null?'N/A':$contract->end_at->format('d F Y')}}</dd>
                            <dt class="col-sm-5">Agreement status</dt>
                            <dd class="col-sm-7 border-bottom">{{ucfirst($contract->agreement_status)}}</dd>
                            <dt class="col-sm-5">Signature date</dt>
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
                                    @foreach ($contract->teamSettings->where('type','benefit') as $key=>$benefit)
                                        @if($key>0 && $key<$contract->teamSettings->where('type','benefit')->count())
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
                @if(strcmp($contract->agreement_status, "terminated")==0)
                <div class="col-sm-12">
                    <x-adminlte-card title="Contract termination" theme="lightblue" icon="fas fa-lg fa-user-times" removable collapsible>
                        <dl class="row">
                            <dt class="col-sm-4">Date</dt>
                            <dd class="col-sm-8">{{$contract->termination_at? $contract->termination_at->format('d F Y'):'N/A'}}</dd>
                            @if($contract->teamSettings->isNotEmpty())
                                @foreach ($contract->teamSettings->where('type','contract_termination') as $key=>$benefit)
                                <dt class="col-sm-4">Motive</dt>
                                <dd class="col-sm-8">
                                    {{$benefit->display_name}}
                                </dd>
                                <dt class="col-sm-4">Description</dt>
                                <dd class="col-sm-8">
                                    {{$benefit->pivot->description}}
                                </dd>
                                @endforeach
                            @else
                                N/A
                            @endif
                        </dl>
                    </x-adminlte-card>
                </div>
                @endif
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
