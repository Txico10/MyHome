<div>
    <div id="EmployeeCreateForm" class="bs-stepper linear">
        <div class="bs-stepper-header" role="tablist">
            <div class="step {{$currentStep==1? 'active': ''}}" data-target="#employee-part">
                <button type="button" class="step-trigger" role="tab" id="employee-part-trigger" aria-controls="employee-part" aria-selected="{{$currentStep ==   1 ? 'true':'false' }}" {{$currentStep == 1 ? '':'disabled'}}>
                    <span class="bs-stepper-circle"><span class="fas fas fa-user"></span></span>
                    <span class="bs-stepper-label">Employee</span>
                </button>
            </div>
            <div class="bs-stepper-line"></div>
            <div class="step {{$currentStep == 2 ? 'active':'' }}" data-target="#adress-part">
                <button type="button" class="step-trigger" role="tab" id="adress-part-trigger" aria-controls="adress-part" aria-selected="{{$currentStep == 2   ? 'true':'false'}}" {{$currentStep == 2 ? '':'disabled'}}>
                    <span class="bs-stepper-circle"><span class="fas fa-map-marked"></span></span>
                    <span class="bs-stepper-label">Address</span>
                </button>
            </div>
            <div class="bs-stepper-line"></div>
            <div class="step {{$currentStep == 3 ? 'active':'' }}" data-target="#contract-part">
                <button type="button" class="step-trigger" role="tab" id="contract-part-trigger" aria-controls="contract-part" aria-selected="{{$currentStep == 3 ?   'true':'false'}}" {{$currentStep == 3 ? '':'disabled'}}>
                    <span class="bs-stepper-circle"><span class="fas fa-file-contract"></span></span>
                    <span class="bs-stepper-label">Contract</span>
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
                        <div class="text-center pb-3">
                            @if(!empty($employee_photo))
                                <img class="profile-user-img img-fluid img-circle" src="{{$employee_photo->temporaryUrl()}}" alt="Employee profile picture">
                            @else
                                <img class="profile-user-img img-fluid img-circle" src="https://picsum.photos/128/128" alt="Employee profile picture">
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-file-image"></i></span>
                                </div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="employee_photo" wire:model="employee_photo">
                                    <label class="custom-file-label" for="employee_photo">Choose employee picture</label>
                                </div>
                            </div>
                            @error('employee_photo')
                                <span class="error invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Name --}}
                        <div class="form-group">
                            <label for="employee_name" class="text-lightblue">Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user"></i></span>
                                </div>
                                <input type="text" name="employee_name" id="employee_name" class="form-control @error('employee_name') is-invalid @enderror" wire:model.lazy="employee_name" placeholder="Employee name">
                            </div>
                            @error('employee_name')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Birthdate --}}
                        <div class="form-group">
                            <label for="employee_birthdate" class="text-lightblue">Birthdate</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-fw fa-birthday-cake text-lightblue"></i>
                                    </div>
                                </div>
                                <input id="employee_birthdate" name="employee_birthdate" data-target="#employee_birthdate" data-toggle="datetimepicker"  class="form-control datetimepicker @error('employee_birthdate') is-invalid @enderror" wire:model="employee_birthdate" placeholder="Choose a date...">
                                @error('employee_birthdate')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        {{-- Gender --}}
                        <div class="form-group">
                            <label class="text-lightblue" for="employee_gender">Gender</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-venus-mars"></i></span>
                                </div>
                                <select name="employee_gender" id="employee_gender" class="form-control" wire:model="employee_gender" data-placeholder="Enter employee gender" data-allow-clear="true" style="width: 85%">
                                    <option value=""></option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            @error('employee_gender')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- SSN--}}
                        <div class="form-group">
                            <label for="employee_ssn" class="text-lightblue">Social Security number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-hashtag"></i></span>
                                </div>
                                <input type="text" name="employee_ssn" id="employee_ssn" class="form-control @error('employee_ssn') is-invalid @enderror" placeholder="Social security number" wire:ignore>
                            </div>
                            @error('employee_ssn')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Mobile--}}
                        <div class="form-group">
                            <label for="employee_mobile" class="text-lightblue">Mobile</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-mobile-alt"></i></span>
                                </div>
                                <input type="text" name="employee_mobile" id="employee_mobile" class="form-control @error('contact_mobile') is-invalid @enderror" wire:model.lazy="contact_mobile" placeholder="Enter employee mobile">
                            </div>
                            @error('contact_mobile')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Email--}}
                        <div class="form-group">
                            <label for="employee_email" class="text-lightblue">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-at"></i></span>
                                </div>
                                <input type="email" name="employee_email" id="employee_email" class="form-control @error('employee_email') is-invalid @enderror" wire:model.lazy="employee_email" placeholder="Enter employee email">
                            </div>
                            @error('employee_email')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Password --}}
                        <div class="form-group">
                            <label for="employee_password" class="text-lightblue">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-key"></i></span>
                                </div>
                                <input type="password" name="employee_password" id="employee_password" class="form-control @error('employee_password') is-invalid @enderror" wire:model.lazy="employee_password" placeholder="Enter employee password">
                            </div>
                            @error('employee_password')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Password confirmation--}}
                        <div class="form-group">
                            <label for="employee_password_confirmation" class="text-lightblue">Password confirmation</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-key"></i></span>
                                </div>
                                <input type="password" name="employee_password_confirmation" id="employee_password_confirmation" class="form-control @error('employee_password_confirmation') is-invalid @enderror" wire:model.lazy="employee_password_confirmation" placeholder="Confirm password">
                            </div>
                            @error('employee_password_confirmation')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary float-right" wire:click="myNextStep">Next <i class="fas fa-fw fa-chevron-right"></i></button>
            </div>
            <div id="adress-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 2 ? 'active dstepper-block':'dstepper-none'}}   "aria-labelledby="adress-part-trigger">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-lightblue" for="address_type">Address type</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-address-book"></i></span>
                                </div>
                                <select name="address_type" id="address_type" class="form-control" wire:model="address_type" data-placeholder="Enter address type" data-allow-clear="true" style="width: 85%" disabled>
                                    <option value=""></option>
                                    <option value="primary">Primary</option>
                                    <option value="secondary">Secondary</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            @error('address_type')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-lightblue" for="address_suite">Suite/Apartment</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-hashtag"></i></span>
                                </div>
                                <input type="text" name="address_suite" id="address_suite" wire:model.lazy="address_suite" class="form-control @error('address_suite') is-invalid @enderror" placeholder="Enter suite/apartment number">
                            </div>
                            @error('address_suite')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address_number" class="text-lightblue">Building number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-building"></i></span>
                                </div>
                                <input type="text" name="address_number" id="address_number" class="form-control @error('address_number') is-invalid @enderror" wire:model.lazy="address_number" placeholder="Enter number">
                            </div>
                            @error('address_number')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address_street" class="text-lightblue">Street</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-road"></i></span>
                                </div>
                                <input type="text" name="address_street" id="address_street" class="form-control @error('address_street') is-invalid @enderror" wire:model.lazy="address_street" placeholder="Enter the street">
                            </div>
                            @error('address_street')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address_city" class="text-lightblue">City</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map-marked-alt"></i></span>
                                </div>
                                <select class="form-control" name="address_city" id="address_city" wire:model="address_city" style="width: 85%" data-placeholder="Select city" data-allow-clear="true">
                                    <option value=""></option>
                                    @if(!empty($country_cities))
                                        @foreach($country_cities as $key => $newCity)
                                            <option value="{{$key}}">{{utf8_decode($newCity)}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @error('address_city')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address_region" class="text-lightblue">Region</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map-marked-alt"></i></span>
                                </div>
                                <input type="text" name="address_region" id="address_region" class="form-control @error('address_region') is-invalid @enderror" wire:model="address_region" placeholder="Enter the region" disabled>
                            </div>
                            @error('address_region')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="text-lightblue" for="address_country">Country</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map-marked-alt"></i></span>
                                </div>
                                <select name="address_country" id="address_country" wire:model="address_country" data-placeholder="Enter your country" data-allow-clear="true" style="width: 85%">
                                    <option value=""></option>
                                    @foreach ($countries as $key=>$country)
                                    <option value="{{$key}}">{{$country}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('address_country')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address_postcode" class="text-lightblue">Postal/Zip Code</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map"></i></span>
                                </div>
                                <input type="text" name="address_postcode" id="address_postcode" class="form-control @error('address_postcode') is-invalid @enderror" wire:model.lazy="address_postcode" placeholder="Enter the postal/zip code">
                            </div>
                            @error('address_postcode')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="button" wire:click="myPreviousStep"><i class="fas fa-fw fa-chevron-left"></i> Previous</button>
                <button class="btn btn-primary float-right" type="button" wire:click="myNextStep">Next <i class="fas fa-fw fa-chevron-right"></i></button>
            </div>
            <div id="contract-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 3 ? 'active dstepper-block':'dstepper-none'}}    "aria-labelledby="contract-part-trigger">
                <div class="row">
                    <div class="col-md-6">
                        {{-- Role --}}
                        <div class="form-group">
                            <label for="contract_role" class="text-lightblue">Role</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user-tag"></i></span>
                                </div>
                                <select class="form-control" name="acontract_role" id="contract_role" wire:model="contract_role_id" style="width: 85%" data-placeholder="Select role" data-allow-clear="true">
                                    <option value=""></option>
                                    @if(!empty($roles))
                                        @foreach($roles as $role)
                                            <option value="{{$role->id}}">{{$role->display_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            @error('contract_role_id')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Availability --}}
                        <div class="form-group">
                            <label class="text-lightblue" for="contract_availability">Availability</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-address-book"></i></span>
                                </div>
                                <select name="contract_availability" id="contract_availability" class="form-control" wire:model="contract_availability" data-placeholder="Enter availability" data-allow-clear="true" style="width: 85%">
                                    <option value=""></option>
                                    <option value="full-time">Full time</option>
                                    <option value="partial-time">Partial time</option>
                                </select>
                            </div>
                            @error('contract_availability')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Min week time --}}
                        <div class="form-group">
                            <label for="contract_min_week_time" class="text-lightblue">Minimum week time</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-clock"></i></span>
                                </div>
                                <input type="text" name="contract_min_week_time" id="contract_min_week_time" class="form-control @error('contract_min_week_time') is-invalid @enderror" placeholder="HH:MM:SS" wire:ignore>
                            </div>
                            @error('contract_min_week_time')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Max week time --}}
                        <div class="form-group">
                            <label for="contract_max_week_time" class="text-lightblue">Maximum Week Time</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-clock"></i></span>
                                </div>
                                <input type="text" name="contract_max_week_time" id="contract_max_week_time" class="form-control @error('contract_max_week_time') is-invalid @enderror" wire:ignore placeholder="HH:MM:SS">
                            </div>
                            @error('contract_max_week_time')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Contract Start At --}}
                        <div class="form-group">
                            <label for="contract_start_at" class="text-lightblue">Start At</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-fw fa-calendar-check text-lightblue"></i>
                                    </div>
                                </div>
                                <input id="contract_start_at" name="contract_start_at" data-target="#contract_start_at" data-toggle="datetimepicker"  class="form-control datetimepicker @error('contract_start_at') is-invalid @enderror" wire:model="contract_start_at" placeholder="Choose a start date">
                                @error('contract_start_at')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        {{-- Contract End At --}}
                        <div class="form-group">
                            <label for="contract_end_at" class="text-lightblue">End At</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-fw fa-calendar-times text-lightblue"></i>
                                    </div>
                                </div>
                                <input id="contract_end_at" name="contract_end_at" data-target="#contract_end_at" data-toggle="datetimepicker"  class="form-control datetimepicker @error('contract_end_at') is-invalid @enderror" wire:model="contract_end_at" placeholder="Choose a end date">
                                @error('contract_end_at')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        {{-- Salary term --}}
                        <div class="form-group">
                            <label class="text-lightblue" for="contract_salary_term">Salary term</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-file-invoice-dollar"></i></span>
                                </div>
                                <select name="contract_salary_term" id="contract_salary_term" class="form-control" wire:model="contract_salary_term" data-placeholder="Enter salary term" data-allow-clear="true" style="width: 85%">
                                    <option value=""></option>
                                    <option value="hourly">Hourly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="annual">Annual</option>
                                </select>
                            </div>
                            @error('contract_salary_term')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Contract Salary Amount --}}
                        <div class="form-group">
                            <label for="contract_salary_amount" class="text-lightblue">Salary Amount</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-dollar-sign"></i></span>
                                </div>
                                <input type="text" name="contract_salary_amount" id="contract_salary_amount" class="form-control @error('contract_salary_amount') is-invalid @enderror" wire:ignore data-inputmask="'alias': 'currency', 'prefix': '$ ', 'placeholder': '0'" placeholder="Salary">
                            </div>
                            @error('contract_salary_amount')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="text-lightblue" for="contract_benefits">Benefits</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-file-invoice-dollar"></i></span>
                                </div>
                                <select name="contract_benefits" id="contract_benefits" multiple class="form-control" wire:model="contract_benefits" data-placeholder="Enter the benefits" data-allow-clear="true" style="width: 85%">
                                    @foreach($benefits_list as $key => $benefits)
                                        <option value="{{$benefits->id}}">{{$benefits->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('contract_benefits')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="button" wire:click="myPreviousStep"><i class="fas fa-fw fa-chevron-left"></i> Previous</button>
                <button class="btn btn-primary float-right" type="button" wire:click="myNextStep">Next <i class="fas fa-fw fa-chevron-right"></i></button>
            </div>
            <div id="thankyou-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 4 ? 'active dstepper-block':'dstepper-none'}} "aria-labelledby="thankyou-part-trigger">
                <div class="card card-primary card-outline card-outline-tabs">
                    <div class="card-header p-0 border-bottom-0">
                      <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                        <li class="nav-item">
                          <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Employee</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Address</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Contract</a>
                        </li>
                      </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                <dl class="row">
                                    <dt class="col-sm-4">Photo</dt>
                                    <dd class="col-sm-8"><img class="profile-user-img img-fluid img-circle" src="{{!empty($employee_photo) ? $employee_photo->temporaryUrl() : 'https://picsum.photos/128/128'}}" alt="Employee photo"></dd>
                                    <dt class="col-sm-4">Name</dt>
                                    <dd class="col-sm-8">{{$employee_name}}</dd>
                                    <dt class="col-sm-4">Birthdate</dt>
                                    <dd class="col-sm-8">{{\Carbon\Carbon::parse($employee_birthdate)->format('d F Y')}}</dd>
                                    <dt class="col-sm-4">Gender</dt>
                                    <dd class="col-sm-8">{{ucfirst($employee_gender)}}</dd>
                                    <dt class="col-sm-4">Social security number</dt>
                                    <dd class="col-sm-8">{{$employee_ssn}}</dd>
                                    <dt class="col-sm-4">Mobile</dt>
                                    <dd class="col-sm-8">{{$contact_mobile}}</dd>
                                    <dt class="col-sm-4">Username / Email</dt>
                                    <dd class="col-sm-8">{{$employee_email}}</dd>
                                    <dt class="col-sm-4">Password</dt>
                                    <dd class="col-sm-8">
                                          <span class="text-info passwd-txt">*********</span>
                                          <span><a href="javascript:void(0);" id="showpasswd"><i class="far fa-eye toggle-password"></i></a></span>
                                    </dd>
                                </dl>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                                <dl class="row">
                                    <dt class="col-sm-4">{{ucfirst($address_type)}}</dt>
                                    @if(!empty($address_suite))
                                    <dd class="col-sm-8">App/Suite: {{$address_suite}}</dd>
                                    <dd class="col-sm-8 offset-sm-4">{{$address_number}} {{$address_street}}</dd>
                                    @else
                                    <dd class="col-sm-8">{{$address_number}} {{$address_street}}</dd>
                                    @endif
                                    @if(!empty($region))
                                    <dd class="col-sm-8 offset-sm-4">{{$address_city}}, {{$address_region}}</dd>
                                    @else
                                    <dd class="col-sm-8 offset-sm-4">{{$address_city}} </dd>
                                    @endif
                                    @if(!empty($address_postcode))
                                    <dd class="col-sm-8 offset-sm-4">{{$address_country}}, {{$address_postcode}}</dd>
                                    @else
                                    <dd class="col-sm-8 offset-sm-4">{{$address_country}}</dd>
                                    @endif
                                </dl>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                <dl class="row">
                                    <dt class="col-sm-4">Role</dt>
                                    <dd class="col-sm-8">{{$my_role}}</dd>
                                    <dt class="col-sm-4">Availability</dt>
                                    <dd class="col-sm-8">{{ucfirst($contract_availability)}}</dd>
                                    <dt class="col-sm-4">Minimum Week Time</dt>
                                    <dd class="col-sm-8">{{$contract_min_week_time}}</dd>
                                    <dt class="col-sm-4">Maximum Week Time</dt>
                                    <dd class="col-sm-8">{{$contract_max_week_time}}</dd>
                                    <dt class="col-sm-4">Start At</dt>
                                    <dd class="col-sm-8">{{$contract_start_at==null?'':\Carbon\Carbon::parse($contract_start_at)->format('d F Y')}}</dd>
                                    <dt class="col-sm-4">End At</dt>
                                    <dd class="col-sm-8">{{$contract_end_at==null?'':\Carbon\Carbon::parse($contract_end_at)->format('d F Y')}}</dd>
                                    <dt class="col-sm-4">Salary Term</dt>
                                    <dd class="col-sm-8">{{ucfirst($contract_salary_term)}}</dd>
                                    <dt class="col-sm-4">Salary</dt>
                                    <dd class="col-sm-8">$ {{number_format($contract_salary_amount, 2)}}</dd>
                                    <dt class="col-sm-4">Benefits</dt>
                                    <dd class="col-sm-8">
                                        @if (!empty($contract_benefits))
                                            @foreach($contract_benefits as $key => $benefit)
                                                @if($key>0 && $key <count($contract_benefits))
                                                    ,
                                                @endif
                                                {{$benefits_list->where('id',$benefit)->first()->display_name}}
                                            @endforeach
                                        @endif
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <button class="btn btn-primary" type="button" wire:click="myPreviousStep"><i class="fas fa-fw fa-chevron-left"></i> Previous</button>
                <button type="submit" class="btn btn-primary float-right" wire:click="submitForm"><i class="fas fa-fw fa-save"></i> Save</button>
            </div>
        </div>
    </div>
    <script>

        document.addEventListener('livewire:load', function () {
            $('#employee_gender, #address_type, #address_city ,#address_country, #contract_role, #contract_availability, #contract_salary_term, #contract_benefits').select2({
                width: 'resolve',
                theme: 'bootstrap4',
            });

            $("#employee_birthdate").datetimepicker({
                format: 'YYYY-MM-DD',
            });

            $('#employee_birthdate').on('hide.datetimepicker', function(event) {
                data = $(this).val() //moment(, 'DD/MM/YYYY').format('DD-MM-YYYY')
                //console.log(data)
                //$(this).removeClass('is-invalid');
                @this.set('employee_birthdate', data)

            });

            $("#contract_start_at").datetimepicker({
                format: 'YYYY-MM-DD',
            });

            $('#contract_start_at').on('hide.datetimepicker', function(event) {
                data = $(this).val() //moment(, 'DD/MM/YYYY').format('DD-MM-YYYY')
                //console.log(data)
                //$(this).removeClass('is-invalid');
                @this.set('contract_start_at', data)

            });

            $("#contract_end_at").datetimepicker({
                format: 'YYYY-MM-DD',
            });

            $('#contract_end_at').on('hide.datetimepicker', function(event) {
                data = $(this).val() //moment(, 'DD/MM/YYYY').format('DD-MM-YYYY')
                //console.log(data)
                //$(this).removeClass('is-invalid');
                @this.set('contract_end_at', data)

            });

            $('#employee_gender').on('select2:select', function (event) {
                var data = $('#employee_gender').select2("val");
                //console.log(data)
                @this.set('employee_gender', data);
            });

            $('#address_city').on('select2:select', function (event) {
                var data = $('#address_city').select2("val");
                //console.log(data)
                @this.set('address_city', data);
            });

            $('#address_country').on('select2:select', function (event) {
                var data = $('#address_country').select2("val");
                //console.log(data)
                @this.set('address_country', data);
            });

            $('#contract_role').on('select2:select', function (event) {
                var data = $('#contract_role').select2("val");
                //console.log(data)
                @this.set('contract_role_id', data);
            });

            $('#contract_availability').on('select2:select', function (event) {
                var data = $('#contract_availability').select2("val");
                //console.log(data)
                @this.set('contract_availability', data);
            });

            $('#contract_salary_term').on('select2:select', function (event) {
                var data = $('#contract_salary_term').select2("val");
                //console.log(data)
                @this.set('contract_salary_term', data);
            });

            $('#contract_benefits').on('change', function (event) {
                var data = $('#contract_benefits').select2("val");
                //console.log(data)
                @this.set('contract_benefits', data);
            });

            $('#employee_ssn').inputmask({
                mask:"999999999",
                oncomplete: function(){
                    $(this).removeClass('is-invalid');
                    @this.set('employee_ssn', $(this).val())
                },
                onincomplete: function(){
                    @this.set('employee_ssn', null)
                    $(this).addClass('is-invalid');
                },
                oncleared: function(){
                    $(this).removeClass('is-invalid');
                },

            }).on("paste", function(){
                @this.set('employee_ssn', $(this).val())
            });

            $('#contract_min_week_time').inputmask({
                mask:"99:99:99",
                oncomplete: function(){
                    $(this).removeClass('is-invalid');
                    @this.set('contract_min_week_time', $(this).val())
                },
                onincomplete: function(){
                    if($(this).val()===''){
                        $(this).removeClass('is-invalid');
                    }else {
                        @this.set('contract_min_week_time', null)
                        $(this).addClass('is-invalid');
                    }
                },
                oncleared: function(){
                    $(this).removeClass('is-invalid');
                },

            }).on("paste", function(){
                @this.set('contract_min_week_time', $(this).val())
            });

            $('#contract_max_week_time').inputmask({
                mask:"99:99:99",
                oncomplete: function(){
                    $(this).removeClass('is-invalid');
                    @this.set('contract_max_week_time', $(this).val())
                },
                onincomplete: function(){
                    if($(this).val()===''){
                        $(this).removeClass('is-invalid');
                    }else {
                        @this.set('contract_max_week_time', null)
                        $(this).addClass('is-invalid');
                    }
                },
                oncleared: function(){
                    $(this).removeClass('is-invalid');
                },

            }).on("paste", function(){
                @this.set('contract_max_week_time', $(this).val())
            });


            $('#contract_salary_amount').inputmask({
                oncomplete: function(){
                    $(this).removeClass('is-invalid');
                    let data = $(this).val()
                    let amount = data.slice(2, data.length)
                    amount = amount.replace(',','')
                    //console.log(amount)
                    @this.set('contract_salary_amount', amount)
                },
                onincomplete: function(){
                    if($(this).val()===''){
                        $(this).removeClass('is-invalid');
                    }else {
                        @this.set('contract_salary_amount', null)
                        $(this).addClass('is-invalid');
                    }
                },
                oncleared: function(){
                    $(this).removeClass('is-invalid');
                },

            }).on("paste", function(){
                let data = $(this).val()
                let amount = data.slice(2, data.length)
                amount = amount.replace(',','')
                @this.set('contract_salary_amount', amount)
            });

            $("#showpasswd").on("click", function (event) {
                event.preventDefault();
                //console.log("Hello")
                var password = $("#employee_password").val();
                //console.log($(".text-info").text());
                if($(".passwd-txt").text()==="*********") {
                    $(".passwd-txt").text(password);
                    $('.toggle-password').addClass('fa-eye-slash').removeClass('fa-eye');
                    //console.log("IN")
                } else {
                    $(".passwd-txt").text("*********");
                    $('.toggle-password').addClass('fa-eye').removeClass('fa-eye-slash');
                    //console.log("OUT");
                }
            });

        })

        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (message, component) => {
                $('#employee_gender, #address_type, #address_city ,#address_country, #contract_role, #contract_availability, #contract_salary_term, #contract_benefits').select2({
                    width: 'resolve',
                    theme: 'bootstrap4',
                });
            })
        });

        window.addEventListener('swalEmployee:form', event => {
            Swal.fire({
                position: 'top-end',
                icon: event.detail.icon,
                title: event.detail.title,
                text: event.detail.text,
                showConfirmButton: false,
                timer: 5000
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    $("#employee_ssn").val('')
                    Livewire.emit('sendToEmployeeList')
                }
            });
        });

    </script>
</div>
