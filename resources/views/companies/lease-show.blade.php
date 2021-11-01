@extends('adminlte::page')

@section('title', 'Lease Show')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Lease Show</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.leases', ['company' => $company])}}">Leases</a></li>
                <li class="breadcrumb-item active">Lease</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
<div class="row">
    <div class="col-md-6">
        <x-adminlte-card title="Lessor" theme="lightblue" icon="fas fa-user-secret" removable collapsible>
            <dl>
                <dt>Name</dt>
                <dd>{{$company->display_name}}</dd>
                <dt>Address</dt>
                <dd>
                    {{$company->addresses->first()->number}}, {{$company->addresses->first()->street}}, {{$company->addresses->first()->suite}}<br>
                    {{$company->addresses->first()->city}}, {{$company->addresses->first()->region}}, {{$company->addresses->first()->country}}
                </dd>
                <dt>Contact</dt>
                <dd>
                    @foreach ($company->contacts as $contact)
                        {{ucfirst($contact->type)}} : {{$contact->description}}<br>
                    @endforeach
                </dd>
            </dl>
        </x-adminlte-card>
    </div>
    <div class="col-md-6">
        <x-adminlte-card title="Lessee" theme="lightblue" icon="fas fa-lg fa-user-tie" removable collapsible>
            @foreach ($lease->users as $user)
            <dl>
                <dt>Name</dt>
                <dd>{{$user->name}}</dd>
                <dt>Address</dt>
                <dd>
                    {{$user->addresses->first()->number}}, {{$user->addresses->first()->street}}, {{$user->addresses->first()->suite}}<br>
                    {{$user->addresses->first()->city}}, {{$user->addresses->first()->region}}, {{$user->addresses->first()->country}}
                </dd>
                <dt>Contact</dt>
                <dd>
                    Email : {{$user->email}} - System<br>
                    @foreach ($user->contacts as $contact)
                        {{ucfirst($contact->type)}} : {{$contact->description}} - {{ucfirst($contact->priority)}}<br>
                    @endforeach
                </dd>
            </dl>
            @endforeach
        </x-adminlte-card>
    </div>
    <div class="col-md-12">
        <x-adminlte-card title="Lease dweelling, accessories and dependencies" theme="lightblue" icon="fas fa-lg fa-file-contract" removable collapsible>
            <div class="row">
                <div class="col-md-6">
                    <x-adminlte-card title="Lease">
                        <dl class="row">
                            <dt class="col-sm-4">Term</dt>
                            <dd class="col-sm-8">{{ucfirst($lease->term)}}</dd>
                            <dt class="col-sm-4">Start date</dt>
                            <dd class="col-sm-8">
                                {{$lease->start_at->toDateString()}}
                            </dd>
                            @if(strcmp($lease->term, "fixed")==0)
                            <dt class="col-sm-4">End date</dt>
                            <dd class="col-sm-8">{{$lease->end_at->toDateString()}}</dd>
                            @endif
                            <dt class="col-sm-4">Residential purposes only</dt>
                            <dd class="col-sm-8">{{$lease->residential_purpose==true ? 'Yes': 'No'}}</dd>
                            @if($lease->residential_purpose==false)
                            <dt class="col-sm-4">Description</dt>
                            <dd class="col-sm-8">{{$lease->residential_purpose_description}}</dd>
                            @endif
                            <dt class="col-sm-4">Devided co-ownership</dt>
                            <dd class="col-sm-8">No</dd>
                            <dt class="col-sm-4">Rent subsidy program</dt>
                            <dd class="col-sm-8">{{$lease->subsidy_program==0?"No":"Yes"}}</dd>
                        </dl>
                    </x-adminlte-card>
                </div>
                <div class="col-md-6">
                    <x-adminlte-card title="Apartment">
                        <dl class="row">
                            <dt class="col-sm-4">Number</dt>
                            <dd class="col-sm-8">{{$lease->apartment->number}}</dd>
                            <dt class="col-sm-4">Type</dt>
                            <dd class="col-sm-8">
                                @foreach ($lease->apartment->teamSettings as $apt_type)
                                    {{$apt_type->display_name}}
                                @endforeach
                            </dd>
                            <dt class="col-sm-4">Building</dt>
                            <dd class="col-sm-8">{{ucfirst($lease->apartment->building->display_name)}}</dd>
                            <dt class="col-sm-4">Address</dt>
                            <dd class="col-sm-8">{{$lease->apartment->building->address->number}}, {{$lease->apartment->building->address->street}}</dd>
                            <dd class="col-sm-8 offset-sm-4">{{$lease->apartment->building->address->city}}, {{$lease->apartment->building->address->region}}, {{$lease->apartment->building->address->country}}</dd>
                        </dl>
                    </x-adminlte-card>
                </div>
                <div class="col-md-6">
                    <x-adminlte-card title="Dependencies">

                        <table class="table table-responsive text-nowrap">
                            <thead>
                              <tr>
                                <th style="width: 10px">#</th>
                                <th>Type</th>
                                <th>Number</th>
                                <th>Price</th>
                                <th>Description</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($lease->dependencies as $key=>$new_dependency)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$new_dependency->teamSettings->first()->display_name}}</td>
                                    <td>{{$new_dependency->number}}</td>
                                    <td>{{$new_dependency->pivot->price}}</td>
                                    <td>{{$new_dependency->pivot->description}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </x-adminlte-card>
                </div>
                @if($lease->furniture_included)
                <div class="col-md-6">
                    <x-adminlte-card title="Accessories">
                        <table class="table table-responsive text-nowrap">
                            <thead>
                              <tr>
                                <th style="width: 10px">#</th>
                                <th>Type</th>
                                <th>Manufacturer</th>
                                <th>Model</th>
                                <th>Price</th>
                                <th>Description</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($lease->accessories as $key=>$new_accessory)
                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td>{{$new_accessory->teamSettings->first()->display_name}}</td>
                                    <td>{{ucfirst($new_accessory->manufacturer)}}</td>
                                    <td>{{ucfirst($new_accessory->model)}}</td>
                                    <td>{{$new_accessory->pivot->price}}</td>
                                    <td>{{$new_accessory->pivot->description}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-adminlte-card>
                </div>
                @endif
                <div class="col-md-6">
                    <x-adminlte-card title="Rent">
                        <dl class="row">
                            <dt class="col-sm-4">Recurrence</dt>
                            <dd class="col-sm-8">The rent will be paid on the first day of the {{ucfirst($lease->rent_recurrence)}}</dd>
                            <dt class="col-sm-4">The rent</dt>
                            <dd class="col-sm-8">${{number_format($lease->rent_amount, 2)}}</dd>
                            <dt class="col-sm-4">Other costs</dt>
                            <dd class="col-sm-8">${{$total_sum}}</dd>
                            <dt class="col-sm-4">Total rent</dt>
                            <dd class="col-sm-8">${{number_format($lease->rent_amount+$total_sum, 2)}}</dd>
                        </dl>
                    </x-adminlte-card>
                </div>
                <div class="col-md-6">
                    <x-adminlte-card title="Payments">
                        <dl class="row">
                            <dt class="col-sm-4">First payment</dt>
                            <dd class="col-sm-8">{{$lease->first_payment_at->toDateString()}}</dd>
                            <dt class="col-sm-4">Payment Methods</dt>
                            <dd class="col-sm-8">
                                @foreach ($lease->teamSettings->where('type', 'method_payment') as $payment_method)
                                    {{$payment_method->display_name}},
                                @endforeach
                            </dd>
                            <dt class="col-sm-4">Agreement postdated cheques</dt>
                            <dd class="col-sm-8">{{$lease->postdated_cheques==true?"Yes":"No"}}</dd>
                        </dl>
                    </x-adminlte-card>
                </div>
            </div>
        </x-adminlte-card>
    </div>
    <div class="col-md-12">
        <x-adminlte-card title="Services and Conditions" theme="lightblue" icon="fas fa-lg fa-concierge-bell" removable collapsible>
            <div class="row">
                <div class="col-md-6">
                    <x-adminlte-card title="Law of the imavable and conditions">
                        <p>A copy of the by-laws of the imovable was given to the lessee</p>
                        <dl class="row">
                            <dt class="col-sm-4">By-laws given on</dt>
                            <dd class="col-sm-8">{{is_null($lease->by_law_given_on)?'N/A':$lease->by_law_given_on->toDateString()}}</dd>
                            <dt class="col-sm-4">Rigth of access to the land</dt>
                            <dd class="col-sm-8">{{$lease->land_access==true?'Yes':"No"}}</dd>
                            <dt class="col-sm-4">Rigth to have animals</dt>
                            <dd class="col-sm-8">{{$lease->animals==true?'Yes':"No"}}</dd>
                            @if (!is_null($lease->others))
                            <dt class="col-sm-4">Other conditions and restrictions</dt>
                            <dd class="col-sm-8">{{$lease->others}}</dd>
                            @endif
                        </dl>
                    </x-adminlte-card>
                </div>
                <div class="col-md-6">
                    <x-adminlte-card title="Work and repairs">
                        <p>The work and repairs to be done by the lessor</p>
                        <dl class="row">
                            <dt class="col-sm-4">Before</dt>
                            <dd class="col-sm-8">
                                @foreach ($lease->teamSettings->where('type', 'service') as $service_before)
                                    @if(strcmp('before the delivery of the dwelling', $service_before->pivot->description)==0)
                                        {{$service_before->display_name}},
                                    @endif
                                @endforeach
                            </dd>
                            <dt class="col-sm-4">After</dt>
                            <dd class="col-sm-8">
                                @foreach ($lease->teamSettings->where('type', 'service') as $service_before)
                                    @if(strcmp('during the lease', $service_before->pivot->description)==0)
                                        {{$service_before->display_name}},
                                    @endif
                                @endforeach
                            </dd>
                        </dl>
                    </x-adminlte-card>
                </div>
                <div class="col-md-6">
                    <x-adminlte-card title="Taxes and consumption costs">
                        <table class="table table-responsive text-nowrap">
                            <thead>
                              <tr>
                                <th>Name</th>
                                <th>Lessor</th>
                                <th>Lessee</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($lease->teamSettings->where('type', 'consumption_cost')->sortBy('name') as $key=>$new_consumption_cost)
                                <tr>
                                    <td>{{$new_consumption_cost->display_name}}</td>
                                    <td>
                                        @if (strcmp('lessor', $new_consumption_cost->pivot->description)==0)
                                        <span class="text-lightblue"><i class="fas fa-check-circle"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(strcmp('lessee', $new_consumption_cost->pivot->description)==0)
                                            <span class="text-lightblue"><i class="fas fa-check-circle"></i></span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </x-adminlte-card>
                </div>
                <div class="col-md-6">
                    <x-adminlte-card title="Snow removal">
                        <table class="table table-responsive text-nowrap">
                            <thead>
                              <tr>
                                <th>Name</th>
                                <th>Lessor</th>
                                <th>Lessee</th>
                              </tr>
                            </thead>
                            <tbody>
                                @foreach ($lease->teamSettings->where('type', 'service')->sortBy('name') as $new_snow_service)
                                @if(strcmp(substr($new_snow_service->name, 0, 4), 'snow')==0)
                                <tr>
                                    <td>{{$new_snow_service->display_name}}</td>
                                    <td>
                                        @if (strcmp('lessor', $new_snow_service->pivot->description)==0)
                                        <span class="text-lightblue"><i class="fas fa-check-circle"></i></span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(strcmp('lessee', $new_snow_service->pivot->description)==0)
                                            <span class="text-lightblue"><i class="fas fa-check-circle"></i></span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </x-adminlte-card>
                </div>
            </div>
        </x-adminlte-card>
    </div>
</div>
@stop

@section('js')
    <script>
    //console.log('Hi!');
    </script>
@stop
