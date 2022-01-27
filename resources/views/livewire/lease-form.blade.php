<div>
    <x-adminlte-card theme="lightblue" theme-mode="outline">
    <div id="EmployeeCreateForm" class="bs-stepper linear">
        <div class="bs-stepper-header" role="tablist">
            <div class="step {{$currentStep==1? 'active': ''}}" data-target="#lessee-part">
                <button type="button" class="step-trigger" role="tab" id="lessee-part-trigger" aria-controls="lessee-part" aria-selected="{{$currentStep ==   1 ? 'true':'false' }}" {{$currentStep == 1 ? '':'disabled'}}>
                    <span class="bs-stepper-circle"><span class="fas fas fa-user"></span></span>
                    <span class="bs-stepper-label">Lessee</span>
                </button>
            </div>
            <div class="bs-stepper-line"></div>
            <div class="step {{$currentStep == 2 ? 'active':'' }}" data-target="#adress-part">
                <button type="button" class="step-trigger" role="tab" id="adress-part-trigger" aria-controls="adress-part" aria-selected="{{$currentStep == 2   ? 'true':'false'}}" {{$currentStep == 2 ? '':'disabled'}}>
                    <span class="bs-stepper-circle"><span class="fas fa-house-user"></span></span>
                    <span class="bs-stepper-label">Dwelling</span>
                </button>
            </div>
            <div class="bs-stepper-line"></div>
            <div class="step {{$currentStep == 3 ? 'active':'' }}" data-target="#lease-part">
                <button type="button" class="step-trigger" role="tab" id="lease-part-trigger" aria-controls="lease-part" aria-selected="{{$currentStep == 3 ?   'true':'false'}}" {{$currentStep == 3 ? '':'disabled'}}>
                    <span class="bs-stepper-circle"><span class="fas fa-file-contract"></span></span>
                    <span class="bs-stepper-label">Lease</span>
                </button>
            </div>
            <div class="bs-stepper-line"></div>
            <div class="step {{$currentStep == 4 ? 'active':'' }}" data-target="#thankyou-part">
                <button type="button" class="step-trigger" role="tab" id="thankyou-part-trigger" aria-controls="thankyou-part" aria-selected="{{$currentStep== 4 ? 'true':'false'}}" {{$currentStep == 4 ? '':'disabled'}}>
                    <span class="bs-stepper-circle"><span class="fas fa-save"></span></span>
                    <span class="bs-stepper-label">Save</span>
                </button>
            </div>
        </div>
        <div class="bs-stepper-content">
            <div id="employee-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 1 ? 'active dstepper-block':'dstepper-none'}}  "aria-labelledby="employee-part-trigger">
                <div class="row">
                    <div class="col-md-12">
                        <x-adminlte-card>
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                          <input type="checkbox" id="second_lessee" class="permission_box" {{$second_lessee==true?'checked':''}} data-selected="{{$second_lessee}}" wire:click="$toggle('second_lessee')">
                                          <label for="second_lessee" class="text-lightblue">
                                            Bail for two lessee
                                          </label>
                                        </div>
                                      </div>
                                </div>
                            </div>
                        </x-adminlte-card>
                    </div>
                </div>
                @foreach ($lessees as $indexL=>$lessee)
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-card title="Lessee #{{$indexL+1}}" theme="lightblue" theme-mode="outline">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-lightblue" for="lessee_name{{$indexL}}">Name</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user"></i></span>
                                            </div>
                                            <input type="text" name="lessee_name{{$indexL}}" id="lessee_name{{$indexL}}"  class="form-control @error('lessees.'.$indexL.'.name') is-invalid @enderror" wire:model.lazy="lessees.{{$indexL}}.name" placeholder="Enter Lessee name">
                                        </div>
                                        @error('lessees.'.$indexL.'.name')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- Birthdate --}}
                                    <div class="form-group">
                                        <label for="lessees.{{$indexL}}.birthdate" class="text-lightblue">Birthdate</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-fw fa-birthday-cake text-lightblue"></i>
                                                </div>
                                            </div>
                                            <input id="lessees.{{$indexL}}.birthdate" name="lessees.{{$indexL}}.birthdate" data-target="#lessees.{{$indexL}}.birthdate" data-toggle="datetimepicker"  class="form-control datetimepicker @error('lessees.'.$indexL.'.birthdate') is-invalid @enderror" wire:model="lessees.{{$indexL}}.birthdate" placeholder="Choose a date...">
                                            @error('lessees.'.$indexL.'.birthdate')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- Gender --}}
                                    <div class="form-group">
                                        <label class="text-lightblue" for="lessees.{{$indexL}}.gender">Gender</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-venus-mars"></i></span>
                                            </div>
                                            <select name="lessees.{{$indexL}}.gender" id="lessees.{{$indexL}}.gender" class="form-control select2" wire:model="lessees.{{$indexL}}.gender" data-placeholder="Enter lessee gender"  data-allow-clear="true" style="width: 80%">
                                                <option value=""></option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        @error('lessees.'.$indexL.'.gender')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- Email--}}
                                    <div class="form-group">
                                        <label for="lessee.{{$indexL}}.email" class="text-lightblue">Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-at"></i></span>
                                            </div>
                                            <input type="email" name="lessee.{{$indexL}}.email" id="lessee.{{$indexL}}.email" class="form-control @error('lessees.'.$indexL.'.email') is-invalid @enderror" wire:model.lazy="lessees.{{$indexL}}.email" placeholder="Enter lessee email">
                                        </div>
                                        @error('lessees.'.$indexL.'.email')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- Mobile--}}
                                    <div class="form-group">
                                        <label for="lessee.{{$indexL}}.mobile" class="text-lightblue">Mobile</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-mobile-alt"></i></span>
                                            </div>
                                            <input type="text" name="lessee.{{$indexL}}.mobile" id="lessee.{{$indexL}}.mobile" class="form-control @error('lessees.'.$indexL.'.mobile') is-invalid @enderror" wire:model.lazy="lessees.{{$indexL}}.mobile" placeholder="Enter lessee mobile">
                                        </div>
                                        @error('lessees.'.$indexL.'.mobile')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </x-adminlte-card>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-card title="Address for lessee # {{$indexL+1}}" theme="lightblue" theme-mode="outline">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-lightblue" for="addresses.{{$indexL}}.suite">Suite/Apartment</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-hashtag"></i></span>
                                            </div>
                                            <input type="text" name="addresses.{{$indexL}}.suite" id="addresses.{{$indexL}}.suite" wire:model.lazy="addresses.{{$indexL}}.suite" class="form-control @error('addresses.'.$indexL.'.suite') is-invalid @enderror" placeholder="Enter suite/apartment number">
                                        </div>
                                        @error('addresses.'.$indexL.'.suite')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addresses.{{$indexL}}.number" class="text-lightblue">Building number</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-building"></i></span>
                                            </div>
                                            <input type="text" name="addresses.{{$indexL}}.number" id="addresses.{{$indexL}}.number" class="form-control @error('addresses.'.$indexL.'.number') is-invalid @enderror" wire:model.lazy="addresses.{{$indexL}}.number" placeholder="Enter number">
                                        </div>
                                        @error('addresses.'.$indexL.'.number')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addresses.{{$indexL}}.street" class="text-lightblue">Street</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-road"></i></span>
                                            </div>
                                            <input type="text" name="addresses.{{$indexL}}.street" id="addresses.{{$indexL}}.street" class="form-control @error('addresses.'.$indexL.'.street') is-invalid @enderror" wire:model.lazy="addresses.{{$indexL}}.street" placeholder="Enter the street">
                                        </div>
                                        @error('addresses.'.$indexL.'.street')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addresses.{{$indexL}}.city" class="text-lightblue">City</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map-marked-alt"></i></span>
                                            </div>
                                            <select class="form-control select2" name="addresses.{{$indexL}}.city" id="addresses.{{$indexL}}.city" wire:model="addresses.{{$indexL}}.city" style="width: 80%" data-placeholder="Select city" data-allow-clear="true">
                                                <option value=""></option>
                                                @if(!empty($country_cities))
                                                    @foreach($country_cities as $key => $newCity)
                                                        <option value="{{$key}}">{{utf8_decode($newCity)}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('addresses.'.$indexL.'.city')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addresses.{{$indexL}}.region" class="text-lightblue">Region</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map-marked-alt"></i></span>
                                            </div>
                                            <input type="text" name="addresses.{{$indexL}}.region" id="addresses.{{$indexL}}.region" class="form-control @error('addresses.'.$indexL.'.region') is-invalid @enderror" wire:model="addresses.{{$indexL}}.region" placeholder="Enter the region" disabled>
                                        </div>
                                        @error('addresses.'.$indexL.'.region')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-lightblue" for="addresses.{{$indexL}}.country">Country</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map-marked-alt"></i></span>
                                            </div>
                                            <select class="form-control select2" name="addresses.{{$indexL}}.country" id="addresses.{{$indexL}}.country" wire:model="addresses.{{$indexL}}.country" data-placeholder="Enter your country" data-allow-clear="true" style="width: 80%">
                                                <option value=""></option>
                                                @foreach ($countries as $key=>$country)
                                                <option value="{{$key}}">{{$country}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('addresses.'.$indexL.'.country')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="addresses.{{$indexL}}.postcode" class="text-lightblue">Postal/Zip Code</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map"></i></span>
                                            </div>
                                            <input type="text" name="addresses.{{$indexL}}.postcode" id="addresses.{{$indexL}}.postcode" class="form-control @error('addresses.'.$indexL.'.postcode') is-invalid @enderror" wire:model.lazy="addresses.{{$indexL}}.postcode" placeholder="Enter the postal/zip code">
                                        </div>
                                        @error('addresses.'.$indexL.'.postcode')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </x-adminlte-card>
                    </div>
                </div>
                @endforeach
                <button class="btn btn-primary float-right" wire:click="myNextStep">Next <i class="fas fa-fw fa-chevron-right"></i></button>
            </div>
            <div id="adress-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 2 ? 'active dstepper-block':'dstepper-none'}}   "aria-labelledby="adress-part-trigger">
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-card title="Dweeling" theme="lightblue" theme-mode="outline">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-lightblue" for="building">Building</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-building"></i></span>
                                            </div>
                                            <select name="building" id="building" class="form-control select2" wire:model="building" data-placeholder="Select Building"  data-allow-clear="true" style="width: 80%">
                                                <option value=""></option>
                                                @foreach ($buildings as $key=>$value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('building')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-lightblue" for="apartment">Apartments</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-home"></i></span>
                                            </div>
                                            <select name="apartment" id="apartment" class="form-control select2" wire:model="apartment" data-placeholder="Select apartment"  data-allow-clear="true" style="width: 80%">
                                                <option value=""></option>
                                                @if(!empty($apartments))
                                                    @foreach ($apartments as $key=>$value)
                                                        <option value="{{$value->id}}">{{$value->number}} &rArr; {{$value->teamSettings->first()->display_name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @error('apartment')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                    <div class="form-group clearfix">
                                                        <div class="icheck-primary d-inline">
                                                          <input type="checkbox" id="family_residence" class="permission_box" {{$family_residence==true?'checked': ''}} data-selected="{{$family_residence}}" wire:model="family_residence">
                                                          <label for="family_residence" class="text-lightblue">
                                                            Used as family residence
                                                          </label>
                                                        </div>
                                                      </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            @if (!$family_residence)
                                                <div class="form-group">
                                                    <label class="text-lightblue" for="family_residence_description">Combined purposes of housing</label>
                                                    <input type="text" name="family_residence_description" id="family_residence_description" wire:model.lazy="family_residence_description" class="form-control form-control-border border-width-2 @error('family_residence_description') is-invalid @enderror" placeholder="Professional/Commercial activities">
                                                    @error('family_residence_description')
                                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                                    @enderror
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                  <input type="checkbox" id="dwelling_co_ownership" class="permission_box" {{$dwelling_co_ownership==true?'checked': ''}} data-selected="{{$dwelling_co_ownership}}" wire:model="dwelling_co_ownership">
                                                  <label for="dwelling_co_ownership" class="text-lightblue">
                                                    Dwelling is located in a unit under devided co-ownership
                                                  </label>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                  <input type="checkbox" id="furniture_included" class="permission_box" {{$furniture_included==true?'checked': ''}} data-selected="{{$furniture_included}}" wire:model="furniture_included">
                                                  <label for="furniture_included" class="text-lightblue">
                                                    Furniture is leased and included in the rent.
                                                  </label>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </x-adminlte-card>
                        {{-- --}}
                        <x-adminlte-card title="Parking and locker" theme="lightblue" theme-mode="outline">
                            @if(!empty($building))
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-outline-primary mx-0 my-2 shadow" wire:click.prevent="addDependency()"><i class="fas fa-lg fa-plus-square"></i> Add Parking/Locker</button>
                                    </div>
                                </div>
                            @endif
                            @foreach ($dependencies as $indexD=>$dependency)
                                <div class="row">
                                    <div class="col-md-3">
                                        {{-- dependency type --}}
                                        <div class="form-group">
                                            {{--<label class="text-lightblue" for="dependencies.{{$indexD}}.type">Gender</label>--}}
                                            <select id="dependencies.{{$indexD}}.type" class="form-control select2_dyn" name="dependencies.{{$indexD}}.type" wire:model="dependencies.{{$indexD}}.type" data-placeholder="Parking/locker"  data-allow-clear="true" style="width: 100%">
                                                <option value=""></option>
                                                @if (!empty($dependencies_company))
                                                    @foreach ( $dependencies_company as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            @error('dependencies.'.$indexD.'.type')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        {{-- dependency number --}}
                                        <div class="form-group">
                                            {{--<label class="text-lightblue" for="dependencies.{{$indexD}}.number">Number</label>--}}
                                            <select class="form-control select2_dyn" name="dependencies.{{$indexD}}.number" id="dependencies.{{$indexD}}.number" wire:model="dependencies.{{$indexD}}.number" data-placeholder="Number"  data-allow-clear="true" style="width: 100%">
                                                <option value=""></option>
                                                @if (!empty($dependencies_building[$indexD]))
                                                    @foreach ($dependencies_building[$indexD] as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                @endif

                                            </select>
                                            @error('dependencies.'.$indexD.'.number')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{-- <label class="text-lightblue" for="dependencies.{{$indexD}}.price">Price</label>--}}
                                            <input type="text" name="dependencies.{{$indexD}}.price" id="dependencies.{{$indexD}}.price" wire:model.lazy="dependencies.{{$indexD}}.price" class="form-control @error('dependencies.'.$indexD.'.price') is-invalid @enderror inputmask" data-inputmask="'alias': 'currency', 'prefix': '$ ', 'placeholder': '0'" placeholder="Price">
                                            @error('dependencies.'.$indexD.'.price')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            {{-- <label class="text-lightblue" for="dependencies.{{$indexD}}.price">Price</label>--}}
                                            <input type="text" name="dependencies.{{$indexD}}.description" id="dependencies.{{$indexD}}.description" wire:model.lazy="dependencies.{{$indexD}}.description" class="form-control @error('dependencies.'.$indexD.'.description') is-invalid @enderror" placeholder="Descrition">

                                            @error('dependencies.'.$indexD.'.description')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-outline-danger mx-1 shadow" wire:click.prevent="removeDependency({{$indexD}})"><i class="fas fa-lg fa-minus-square"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        </x-adminlte-card>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-card title="Appliances" theme="lightblue" theme-mode="outline">
                            @if(!empty($apartment) && $furniture_included)
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-outline-primary mx-0 my-2 shadow" wire:click.prevent="addAppliance()"><i class="fas fa-lg fa-plus-square"></i> Add appliance</button>
                                </div>
                            </div>
                            @endif
                            @foreach ($appliances as $indexA=>$appliance)
                                <div class="row">
                                    <div class="col-md-3">
                                        {{-- appliance type --}}
                                        <div class="form-group">
                                            {{--<label class="text-lightblue" for="dependencies.{{$indexD}}.type">Gender</label>--}}
                                            <select id="appliances.{{$indexA}}.type" class="form-control select2_dyn" name="appliances.{{$indexA}}.type" wire:model="appliances.{{$indexA}}.type" data-placeholder="Appliance"  data-allow-clear="true" style="width: 100%">
                                                <option value=""></option>
                                                @if (!empty($appliances_company))
                                                    @foreach ($appliances_company as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('appliances.'.$indexA.'.type')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{--<label class="text-lightblue" for="dependencies.{{$indexD}}.type">Gender</label>--}}
                                            <select id="appliances.{{$indexA}}.id" class="form-control select2_dyn" name="appliances.{{$indexA}}.id" wire:model="appliances.{{$indexA}}.id" data-placeholder="Model"  data-allow-clear="true" style="width: 100%">
                                                <option value=""></option>
                                                @if (!empty($appliances_list[$indexA]))
                                                    @foreach ($appliances_list[$indexA] as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('appliances.'.$indexA.'.id')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{-- <label class="text-lightblue" for="dependencies.{{$indexD}}.price">Price</label>--}}
                                            <input type="text" name="appliances.{{$indexA}}.price" id="appliances.{{$indexA}}.price" class="form-control @error('appliances.'.$indexA.'.price') is-invalid @enderror inputmask" wire:model.lazy="appliances.{{$indexA}}.price" data-inputmask="'alias': 'currency', 'prefix': '$ ', 'placeholder': '0'" placeholder="Price">

                                            @error('appliances.'.$indexA.'.price')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{-- <label class="text-lightblue" for="dependencies.{{$indexD}}.price">Price</label>--}}
                                            <input type="text" name="appliances.{{$indexA}}.description" id="appliances.{{$indexA}}.description" wire:model.lazy="appliances.{{$indexA}}.description" class="form-control @error('appliances.'.$indexA.'.description') is-invalid @enderror" placeholder="Descrition">

                                            @error('appliances.'.$indexA.'.description')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-outline-danger mx-1 shadow" wire:click.prevent="removeAppliance({{$indexA}})"><i class="fas fa-lg fa-minus-square"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        </x-adminlte-card>
                        <x-adminlte-card title="Furnitures" theme="lightblue" theme-mode="outline">
                            @if(!empty($apartment) && $furniture_included)
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-outline-primary mx-0 my-2 shadow" wire:click.prevent="addFurniture()"><i class="fas fa-lg fa-plus-square"></i> Add furniture</button>
                                    </div>
                                </div>
                            @endif
                            @foreach ($furnitures as $indexF=>$furniture)
                                <div class="row">
                                    <div class="col-md-3">
                                        {{-- accessories type --}}
                                        <div class="form-group">
                                            {{--<label class="text-lightblue" for="dependencies.{{$indexD}}.type">Gender</label>--}}
                                            <select id="furnitures.{{$indexF}}.type" class="form-control select2_dyn" name="furnitures.{{$indexF}}.type" wire:model="furnitures.{{$indexF}}.type" data-placeholder="Furniture"  data-allow-clear="true" style="width: 100%">
                                                <option value=""></option>
                                                @if (!empty($furnitures_company))
                                                    @foreach ($furnitures_company as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('furnitures.'.$indexF.'.type')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{--<label class="text-lightblue" for="dependencies.{{$indexD}}.type">Gender</label>--}}
                                            <select id="furnitures.{{$indexF}}.id" class="form-control select2_dyn" name="furnitures.{{$indexF}}.id" wire:model="furnitures.{{$indexF}}.id" data-placeholder="Model"  data-allow-clear="true" style="width: 100%">
                                                <option value=""></option>
                                                @if (!empty($furnitures_list[$indexF]))
                                                    @foreach ($furnitures_list[$indexF] as $key=>$value)
                                                        <option value="{{$key}}">{{$value}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @error('furnitures.'.$indexF.'.id')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            {{-- <label class="text-lightblue" for="dependencies.{{$indexD}}.price">Price</label>--}}
                                            <input type="text" name="furnitures.{{$indexF}}.price" id="furnitures.{{$indexF}}.price" wire:model="furnitures.{{$indexF}}.price" class="form-control @error('furnitures.'.$indexF.'.price') is-invalid @enderror inputmask" data-inputmask="'alias': 'currency', 'prefix': '$ ', 'placeholder': '0'" placeholder="Price">

                                            @error('furnitures.'.$indexF.'.price')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            {{-- <label class="text-lightblue" for="dependencies.{{$indexD}}.price">Price</label>--}}
                                            <input type="text" name="furnitures.{{$indexF}}.description" id="furnitures.{{$indexF}}.description" wire:model.lazy="furnitures.{{$indexF}}.description" class="form-control @error('furnitures.'.$indexF.'.description') is-invalid @enderror" placeholder="Descrition">

                                            @error('furnitures.'.$indexF.'.description')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-outline-danger mx-1 shadow" wire:click.prevent="removeFurniture({{$indexF}})"><i class="fas fa-lg fa-minus-square"></i></button>
                                    </div>
                                </div>
                            @endforeach
                        </x-adminlte-card>
                    </div>
                </div>
                <button class="btn btn-primary" type="button" wire:click="myPreviousStep"><i class="fas fa-fw fa-chevron-left"></i> Previous</button>
                <button class="btn btn-primary float-right" type="button" wire:click="myNextStep">Next <i class="fas fa-fw fa-chevron-right"></i></button>
            </div>
            <div id="lease-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 3 ? 'active dstepper-block':'dstepper-none'}}    "aria-labelledby="lease-part-trigger">
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-card title="Term of lease" theme="lightblue" theme-mode="outline">
                            <div class="row">
                                <div class="col-md-6">
                                    {{-- Term --}}
                                    <div class="form-group">
                                        <label class="text-lightblue" for="lease_term">Term</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-hourglass-half"></i></span>
                                            </div>
                                            <select name="lease_term" id="lease_term" class="form-control select2" wire:model="lease_term" data-placeholder="Enter lease term"  data-allow-clear="true" style="width: 80%">
                                                <option value=""></option>
                                                <option value="fixed">Fixed</option>
                                                <option value="indeterminate">Indeterminate</option>
                                            </select>
                                        </div>
                                        @error('lease_term')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- Start date --}}
                                    <div class="form-group">
                                        <label for="lease_start" class="text-lightblue">Beginning on</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-fw fa-calendar-check text-lightblue"></i>
                                                </div>
                                            </div>
                                            <input id="lease_start" name="lease_start" data-target="#lease_start" data-toggle="datetimepicker"  class="form-control datetimepicker @error('lease_start') is-invalid @enderror" wire:model="lease_start" placeholder="Choose a date...">
                                            @error('lease_start')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @if(!empty($lease_term)&& strcmp($lease_term, 'fixed')==0)
                                <div class="col-md-6">
                                    {{-- end date --}}
                                    <div class="form-group">
                                        <label for="lease_end" class="text-lightblue">To</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-fw fa-calendar-check text-lightblue"></i>
                                                </div>
                                            </div>
                                            <input id="lease_end" name="lease_end" data-target="#lease_end" data-toggle="datetimepicker"  class="form-control datetimepicker2 @error('lease_end') is-invalid @enderror" wire:model="lease_end" placeholder="Choose a date...">
                                            @error('lease_end')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </x-adminlte-card>
                        <x-adminlte-card title="Rent" theme="lightblue" theme-mode="outline">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="rent_amount" class="col-sm-4 col-form-label text-lightblue">The rent is</label>
                                        <div class="col-sm-4">
                                          <input type="text" name="rent_amount" id="rent_amount" wire:model.lazy="rent_amount" class="form-control form-control-border border-width-2 @error('rent_amount') is-invalid @enderror inputmaskFL" data-inputmask="'alias': 'currency', 'prefix': '$ ', 'rightAlign': 'true','placeholder': '0'" placeholder="Amount">
                                        </div>
                                        @error('rent_amount')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="total_cost_services" class="col-sm-4 col-form-label text-lightblue">The total cost of services is</label>
                                        <div class="col-sm-4">
                                          <input type="text" name="total_cost_services" id="total_cost_services" wire:model="total_cost_services" class="form-control form-control-border border-width-2 inputmaskFL" data-inputmask="'alias': 'currency', 'prefix': '$ ', 'rightAlign': 'true','placeholder': '0'" placeholder="total costs" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label for="total_rent" class="col-sm-4 col-form-label text-lightblue">The total rent is</label>
                                        <div class="col-sm-4">
                                          <input type="text" name="total_rent" id="total_rent" class="form-control form-control-border border-width-2 @error('total_rent') is-invalid @enderror inputmaskFL" data-inputmask="'alias': 'currency', 'prefix': '$ ', 'rightAlign': 'true','placeholder': '0'" placeholder="Total" value="{{$total_cost_services+$rent_amount}}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                  <input type="checkbox" id="subsidy_program" class="permission_box" {{$subsidy_program==true?'checked': ''}} data-selected="{{$subsidy_program}}" wire:model="subsidy_program">
                                                  <label for="subsidy_program" class="text-lightblue">
                                                    The lessee is beneficiary of a rent subsidy program
                                                  </label>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </x-adminlte-card>
                        <x-adminlte-card title="Payment" theme="lightblue" theme-mode="outline">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_payment_at" class="text-lightblue">First payment</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-calendar-day"></i></span>
                                            </div>
                                            <input id="first_payment_at" name="first_payment_at" data-target="first_payment_at" data-toggle="datetimepicker"  class="form-control datetimepicker @error('first_payment_at') is-invalid @enderror" wire:model="first_payment_at" placeholder="Choose a date...">
                                            @error('first_payment_at')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-lightblue" for="rent_recurrence">The rent will be payed on the 1st day of</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-redo"></i></span>
                                            </div>
                                            <select name="rent_recurrence" id="rent_recurrence" class="form-control select2" wire:model="rent_recurrence" data-placeholder="Othe payment periods "  data-allow-clear="true" style="width: 80%">
                                                <option value=""></option>
                                                <option value="week">Week</option>
                                                <option value="month">Month</option>
                                            </select>
                                        </div>
                                        @error('rent_recurrence')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-lightblue" for="payment_method">Payment method</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-money-check-alt"></i></span>
                                            </div>
                                            <select name="payment_method" id="payment_method" class="form-control select2m" multiple="multiple" wire:model="payment_method" data-placeholder="Select payment method"  data-allow-clear="true" style="width: 80%">
                                                <option value=""></option>
                                                @foreach($payment_methode_company as $key => $value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('payment_method')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                  <input type="checkbox" id="postdated_cheques" class="permission_box" {{$postdated_cheques==true?'checked': ''}} data-selected="{{$postdated_cheques}}" wire:model="postdated_cheques">
                                                  <label for="postdated_cheques" class="text-lightblue">
                                                    The lessee agrees to give the lessor postdated cheques for the term of the lease
                                                  </label>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </x-adminlte-card>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-card title="Services and conditions" theme="lightblue" theme-mode="outline">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-lightblue py-2">By-laws of immovable</h5>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                            <div class="form-group clearfix">
                                                <div class="icheck-primary d-inline">
                                                  <input type="checkbox" id="by_laws" class="permission_box" {{$by_laws==true?'checked': ''}} data-selected="{{$by_laws}}" wire:model="by_laws">
                                                  <label for="by_laws" class="text-lightblue">
                                                    A copy of the by-laws of the immovable was given to the lessee before entering into the lease.
                                                  </label>
                                                </div>
                                              </div>
                                        </div>
                                    </div>
                                </div>
                                @if($by_laws)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="by_laws_given_at" class="text-lightblue">Given on</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-calendar-day"></i></span>
                                                </div>
                                                <input id="by_laws_given_at" name="by_laws_given_at" data-target="by_laws_given_at" data-toggle="datetimepicker"  class="form-control datetimepicker2 @error('by_laws_given_at') is-invalid @enderror" wire:model="by_laws_given_at" placeholder="Choose a date...">
                                                @error('by_laws_given_at')
                                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-lightblue py-2">Work and repairs</h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-lightblue" for="payment_method">Before the delivery of the dwelling</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-tools"></i></span>
                                            </div>
                                            <select name="repairs_before" id="repairs_before" class="form-control select2m" multiple="multiple" wire:model="repairs_before" data-placeholder="Select service"  data-allow-clear="true" style="width: 80%">
                                                <option value=""></option>
                                                @foreach($service_company as $key => $value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('repairs_before')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="text-lightblue" for="payment_method">During the lease</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-tools"></i></span>
                                            </div>
                                            <select name="repair_during" id="repair_during" class="form-control select2m" multiple="multiple" wire:model="repair_during" data-placeholder="Select service"  data-allow-clear="true" style="width: 80%">
                                                <option value=""></option>
                                                @foreach($service_company as $key => $value)
                                                    <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('repair_during')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-lightblue py-2">Services, taxes and consumption costs</h5>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm text-lightblue no-border">
                                        <thead>
                                            <tr>
                                                <th>will be borne by:</th>
                                                <th>Lessor</th>
                                                <th>Lessee</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $count = 0;
                                            @endphp
                                            @foreach($consumption_costs as $key => $value)
                                            <tr>
                                                <td>
                                                    @if (strcmp($value, 'Heating of dwelling')==0 && !empty($apartment_heating->display_name))
                                                    {{$value.' ('.$apartment_heating->display_name.')'}}
                                                    @else
                                                    {{$value}}
                                                    @endif
                                                </td>
                                                <td style="text-align: center; vertical-align: middle;">
                                                    <div class="icheck-primary">
                                                        <input type="radio" id="cost_born_by.{{$count}}.lessor" name="{{$value.$key}}" value="{{$key}}" wire:model="cost_born_by.{{$count}}.lessor">
                                                        <label for="cost_born_by.{{$count}}.lessor">
                                                            @error('cost_born_by.'.$count.'.lessor')
                                                                E
                                                            @enderror
                                                        </label>
                                                    </div>
                                                </td>
                                                <td style="text-align: center; vertical-align: middle;">
                                                    <div class="icheck-primary">
                                                        <input type="radio" id="cost_born_by.{{$count}}.lessee" name="{{$value.$key}}" value="{{$key}}" wire:model="cost_born_by.{{$count}}.lessee">
                                                        <label for="cost_born_by.{{$count}}.lessee">
                                                            @error('cost_born_by.'.$count.'.lessee')
                                                                E
                                                            @enderror
                                                        </label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                $count++;
                                            @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm text-lightblue no-border">
                                        <thead>
                                            <tr>
                                                <th>Snow and ice removal:</th>
                                                <th>Lessor</th>
                                                <th>Lessee</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach($snow_removal as $key => $value)
                                            <tr>
                                                <td>{{$value}}</td>
                                                <td style="text-align: center; vertical-align: middle;">
                                                    <div class="icheck-primary">
                                                        <input type="radio" id="cost_born_by.{{$count}}.lessor" name="{{$value.$key}}" value="{{$key}}" wire:model="cost_born_by.{{$count}}.lessor">
                                                        <label for="cost_born_by.{{$count}}.lessor"></label>
                                                    </div>
                                                </td>
                                                <td style="text-align: center; vertical-align: middle;">
                                                    <div class="icheck-primary">
                                                        <input type="radio" id="cost_born_by.{{$count}}.lessee" name="{{$value.$key}}" value="{{$key}}" wire:model="cost_born_by.{{$count}}.lessee">
                                                        <label for="cost_born_by.{{$count}}.lessee"></label>
                                                    </div>
                                                </td>
                                            </tr>
                                            @php
                                                $count++;
                                            @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </x-adminlte-card>
                    </div>
                </div>
                <button class="btn btn-primary" type="button" wire:click="myPreviousStep"><i class="fas fa-fw fa-chevron-left"></i> Previous</button>
                <button class="btn btn-primary float-right" type="button" wire:click="myNextStep">Next <i class="fas fa-fw fa-chevron-right"></i></button>
            </div>
            <div id="thankyou-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 4 ? 'active dstepper-block':'dstepper-none'}} "aria-labelledby="thankyou-part-trigger">

                <button class="btn btn-primary" type="button" wire:click="myPreviousStep"><i class="fas fa-fw fa-chevron-left"></i> Previous</button>
                <button type="submit" class="btn btn-primary float-right" wire:click="submitForm"><i class="fas fa-fw fa-save"></i> Save</button>
            </div>
        </div>
    </div>
    </x-adminlte-card>
    <script>
        document.addEventListener('livewire:load', function () {
            $('.select2, .select2m').select2({
                width: 'resolve',
                theme: 'bootstrap4',
            });

            $(".datetimepicker").datetimepicker({
                format: 'YYYY-MM-DD',
            });



            $('.datetimepicker').on('hide.datetimepicker', function(event) {
                data = $(this).val() //moment(, 'DD/MM/YYYY').format('DD-MM-YYYY')
                field = this.getAttribute('id')
                //console.log(this.getAttribute('id'))
                //$(this).removeClass('is-invalid');
                @this.set(field, data)

            });

            $('.select2').on('select2:select', function (event) {
                //console.log("Hello")
                var field = this.getAttribute('id')
                var data = $(this).select2("val");
                //var data = $('#lessee_gender').select2("val");
                //console.log(data)
                @this.set(field, data);
            });

            $('.select2m').on('change', function (event) {
                //console.log("Hello")
                var field = this.getAttribute('id')
                var data = $(this).select2("val");
                //var data = $('#lessee_gender').select2("val");
                //console.log(data)
                @this.set(field, data);
            });

            //lease_term
            $('#lease_term').on('select2:select', function (event) {
                var data = $('#lease_term').select2("val");
                //console.log(data)
                @this.set('lease_term', data);
            });

            $('.inputmaskFL').inputmask({
                oncomplete: function(){
                    //$(this).removeClass('is-invalid');
                    //var field = this.getAttribute('id')
                    //let data = $(this).val()
                    //let amount = data.slice(2, data.length)
                    //amount = amount.replace(',','')
                    ////console.log(amount)
                    //@this.set(field, amount)
                },
                oncleared: function(){
                    var field = this.getAttribute('id')
                    @this.set(field, 0.00);
                    $(this).removeClass('is-invalid');
                },
            }).on("paste", function(){
                var field = this.getAttribute('id')
                let data = $(this).val()
                let amount = data.slice(2, data.length)
                amount = amount.replace(',','')
                @this.set(field, parseFloat(amount))
            });

            $('#rent_amount').on('change', function () {
                $(this).removeClass('is-invalid');
                var field = this.getAttribute('id')
                let data = $(this).val()
                let amount = data.slice(2, data.length)
                amount = amount.replace(',','')
                //console.log(amount)
                @this.set(field, parseFloat(amount))
            })
        })

        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (message, component) => {
                $('.select2,.select2_dyn, .select2m').select2({
                    width: 'resolve',
                    theme: 'bootstrap4',
                });
                $(".datetimepicker2").datetimepicker({
                    format: 'YYYY-MM-DD',
                });

                $('.datetimepicker2').on('hide.datetimepicker', function(event) {
                    data = $(this).val() //moment(, 'DD/MM/YYYY').format('DD-MM-YYYY')
                    field = this.getAttribute('id')
                    //console.log(this.getAttribute('id'))
                    //$(this).removeClass('is-invalid');
                    @this.set(field, data)
                });

                $('.select2_dyn').on('select2:select', function (event) {
                    //console.log("Hello")
                    var field = this.getAttribute('id')
                    var data = $(this).select2("val");
                    //var data = $('#lessee_gender').select2("val");
                    //console.log(data)
                    @this.set(field, data);
                });

                $('.inputmask').inputmask({
                    oncomplete: function(){
                        //$(this).removeClass('is-invalid');
                        //var field = this.getAttribute('id')
                        //let data = $(this).val()
                        //let amount = data.slice(2, data.length)
                        //amount = amount.replace(',','')
                        ////console.log(amount)
                        //@this.set(field, amount)
                    },
                    //onincomplete: function(){
                    //    if($(this).val()===''){
                    //        $(this).removeClass('is-invalid');
                    //    }else {
                    //        var field = this.getAttribute('id')
                    //        @this.set(field, '')
                    //        $(this).addClass('is-invalid');
                    //    }
                    //},
                    oncleared: function(){
                        var field = this.getAttribute('id')
                        @this.set(field, 0.00);
                        $(this).removeClass('is-invalid');
                    },

                }).on("paste", function(){
                    var field = this.getAttribute('id')
                    let data = $(this).val()
                    let amount = data.slice(2, data.length)
                    amount = amount.replace(',','')
                    @this.set(field, parseFloat(amount))
                });

                $('.inputmask').on('change', function () {
                    $(this).removeClass('is-invalid');
                    var field = this.getAttribute('id')
                    let data = $(this).val()
                    let amount = data.slice(2, data.length)
                    amount = amount.replace(',','')
                    //console.log(amount)
                    @this.set(field, parseFloat(amount))
                })

            })
        })
    </script>
</div>
