@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Moment', true)
@section('plugins.Datepicker', true)
@section('plugins.Summernote', true)
@section('plugins.Inputmask', true)

@section('title', 'Employee Contract Edit')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Edit {{$employee->name}} Contract</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.employees', ['company' => $company])}}">Employees</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.employees.show', ['company' => $company, 'employee'=>$employee])}}">{{$employee->name}}</a></li>
                <li class="breadcrumb-item active">Edit Contract</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
<form action="{{route('company.employees.contract.update', ['company'=>$company, 'employee'=>$employee, 'contract'=>$contract])}}" method="post">
    <div class="row">
        <div class="col-md-3">
            <x-adminlte-card title="General conditions" theme="lightblue" icon="fas fa-lg fa-handshake" removable collapsible>
                @csrf
                @method('PATCH')
                <x-adminlte-select2 name="role_id" label="Role" label-class="text-lightblue" data-placeholder="Select an option...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-lightblue">
                            <i class="fas fa-fw fa-user-tag"></i>
                        </div>
                    </x-slot>

                    @foreach ($roles as $role)
                        @if($role->id == $contract->role_id)
                            <option value="{{$role->id}}" selected>{{$role->display_name}}</option>
                        @else
                            <option value="{{$role->id}}">{{$role->display_name}}</option>
                        @endif
                    @endforeach
                </x-adminlte-select2>
                <x-adminlte-select2 name="availability" label="Availability" label-class="text-lightblue" data-placeholder="Select an option...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-lightblue">
                            <i class="fas fa-fw fa-calendar-alt"></i>
                        </div>
                    </x-slot>
                        @if(strcmp("part-time", $contract->availability)==0)
                            <option value="part-time" selected>Part Time</option>
                            <option value="full-time">Full Time</option>
                        @else
                            <option value="part-time">Part Time</option>
                            <option value="full-time" selected>Full Time</option>
                        @endif
                </x-adminlte-select2>

                <x-adminlte-input name="min_week_time" label="Minimum week time" label-class="text-lightblue" value="{{$contract->min_week_time ?? null}}" placeholder="HH:MM:SS">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-lightblue">
                            <i class="fas fa-fw fa-clock"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                <x-adminlte-input name="max_week_time" label="Maximum week time" label-class="text-lightblue" value="{{$contract->max_week_time ?? null}}" placeholder="HH:MM:SS">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-lightblue">
                            <i class="fas fa-fw fa-clock"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </x-adminlte-card>
            <x-adminlte-card title="Benefits" theme="lightblue" icon="fas fa-lg fa-laptop-medical" removable collapsible>
                @php

                    $config = [
                        "placeholder" => "Select Benefits",
                        "allowClear" => true,
                    ];
                @endphp
                <x-adminlte-select2 id="benefits" name="benefits[]" label="Benefits" label-class="text-lightblue" :config="$config" multiple>
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-lightblue">
                            <i class="fas fa-user-injured"></i>
                        </div>
                    </x-slot>
                    @foreach($benefits as $key => $name)
                        @if ($contract->teamSettings->contains('id', $key))
                        <option value="{{$key}}" selected>{{$name}}</option>
                        @else
                        <option value="{{$key}}">{{$name}}</option>
                        @endif
                    @endforeach
                </x-adminlte-select2>
            </x-adminlte-card>
        </div>
        <div class="col-md-3">
            <x-adminlte-card title="Duration" theme="lightblue" icon="fas fa-lg fa-calendar" removable collapsible>
                @php
                    $config = ['format' => 'YYYY-MM-DD'];
                @endphp
                <x-adminlte-input-date name="start_at" :config="$config" value="{{$contract->start_at}}" label="Start date" label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-lightblue">
                            <i class="fas fa-fw fa-calendar-check"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
                <x-adminlte-input-date name="end_at" :config="$config" value="{{$contract->end_at ?? null}}" placeholder="Choose end date" label="End date" label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-lightblue">
                            <i class="fas fa-fw fa-calendar-times"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
            </x-adminlte-card>
            <x-adminlte-card title="Salary" theme="lightblue" icon="fas fa-lg fa-money-check-alt" removable collapsible>
                <x-adminlte-select2 name="salary_term" label="Salary term" label-class="text-lightblue" data-placeholder="Select the salary term">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-lightblue">
                            <i class="fas fa-fw fa-file-invoice-dollar"></i>
                        </div>
                    </x-slot>
                        @switch($contract->salary_term)
                            @case('hourly')
                                <option value="hourly" selected>Hourly</option>
                                <option value="monthly">Monthly</option>
                                <option value="annual">Annual</option>
                                @break
                            @case('monthly')
                                <option value="hourly">Hourly</option>
                                <option value="monthly" selected>Monthly</option>
                                <option value="annual">Annual</option>
                                @break
                            @case('annual')
                                <option value="hourly">Hourly</option>
                                <option value="monthly">Monthly</option>
                                <option value="annual" selected>Annual</option>
                                @break

                        @endswitch
                </x-adminlte-select2>
                <x-adminlte-input name="salary_amount" label="Salary" placeholder="Salary" type="number" min="0.00" step="0.01" value="{{$contract->salary_amount ?? null}}" label-class="text-lightblue">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-gradient-lightblue">
                            <i class="fas fa-fw fa-dollar-sign"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </x-adminlte-card>
        </div>
        <div class="col-md-6">
            @php
                $config = [
                    "height" => "500",
                    "toolbar" => [
                        // [groupName, [list of button]]
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['strikethrough', 'superscript', 'subscript']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview', 'help']],
                    ],
                ]
            @endphp
            <x-adminlte-card title="Agreement" theme="lightblue" icon="fas fa-lg fa-file-contract" removable collapsible>
                <x-adminlte-text-editor name="agreement" label="Edit agreement" label-class="text-lightblue" placeholder="Write some text..." :config="$config">
                    {!! $contract->agreement !!}
                </x-adminlte-text-editor>
            </x-adminlte-card>
        </div>
    </div>
    <button class="btn btn-danger" type="reset">Reset</button>
    <button class="btn btn-primary float-right" type="submit"><i class="fas fa-save"></i> Save</button>
</form>

@stop

@section('js')
    <script>
        $("#min_week_time").inputmask({
            mask: "99:99:99",
            //placeholder: "*",
            showMaskOnHover: true,
            showMaskOnFocus: false,
        })
        $("#max_week_time").inputmask({
            mask: "99:99:99",
            //placeholder: "*",
            showMaskOnHover: true,
            showMaskOnFocus: false,
        })
    </script>
@stop
