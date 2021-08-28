<div>
    @php
        $config = ['format' => 'DD/MM/YYYY'];
    @endphp
    <div id="companyCreateForm" class="bs-stepper linear">
        <div class="bs-stepper-header" role="tablist">
            <div class="step {{$currentStep==1? 'active': ''}}" data-target="#company-part">
                <button type="button" class="step-trigger" role="tab" id="company-part-trigger" aria-controls="company-part" aria-selected="{{$currentStep ==   1 ? 'true':'false' }}" {{$currentStep == 1 ? '':'disabled'}}>
                    <span class="bs-stepper-circle"><span class="fas fas fa-building"></span></span>
                    <span class="bs-stepper-label">Company</span>
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
            <div class="step {{$currentStep == 3 ? 'active':'' }}" data-target="#owner-part">
                <button type="button" class="step-trigger" role="tab" id="owner-part-trigger" aria-controls="owner-part" aria-selected="{{$currentStep == 3 ?   'true':'false'}}" {{$currentStep == 3 ? '':'disabled'}}>
                    <span class="bs-stepper-circle"><span class="fas fa-user"></span></span>
                    <span class="bs-stepper-label">Owner</span>
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
            <div id="company-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 1 ? 'active dstepper-block':'dstepper-none'}}  "aria-labelledby="company-part-trigger">
                <div class="text-center pb-3">
                    @if(!empty($logo))
                        <img class="profile-user-img img-fluid img-circle" src="{{$logo->temporaryUrl()}}" alt="Company profile picture">
                    @else
                        <img class="profile-user-img img-fluid img-circle" src="https://picsum.photos/128/128" alt="Company profile picture">
                    @endif
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-file-image"></i></span>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="logo" wire:model="logo">
                                <label class="custom-file-label" for="logo">Choose your logo</label>
                            </div>
                        </div>
                        @error('logo')
                            <span class="error invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="text-lightblue" for="display_name">Company name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-building"></i></span>
                            </div>
                            <input type="text" name="display_name" id="display_name" wire:model.lazy="display_name" class="form-control @error('display_name')  is-invalid @enderror" placeholder="Company name">
                        </div>
                        @error('display_name')
                            <span class="error invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="text-lightblue" for="slug">Company slug</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-building"></i></span>
                            </div>
                            <input type="text" name="slug" id="slug" wire:model="slug" class="form-control @error('slug') is-invalid @enderror" placeholder="Company slug" disabled>
                        </div>
                        @error('slug')
                            <span class="error invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="text-lightblue" for="business_number">Business Number</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-hashtag"></i></span>
                            </div>
                            <input type="text" name="business_number" id="business_number" class="form-control @error ('business_number') is-invalid @enderror" placeholder="Company business number" wire:ignore>
                        </div>
                        @error('business_number')
                            <span class="error invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="text-lightblue" for="legal_form">Legal form</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-balance-scale"></i></span>
                            </div>
                            <select name="legal_form" id="legal_form" class="form-control" wire:model="legal_form" data-placeholder="Enter legal form"  data-allow-clear="true" style="width: 85%">
                                <option value=""></option>
                                <option value="Sole proprietorship">Sole proprietorship</option>
                                <option value="Business corporation">Business corporation</option>
                                <option value="General partnership">General partnership</option>
                                <option value="Limited partnership">Limited partnership</option>
                                <option value="Cooperative">Cooperative</option>
                            </select>
                        </div>
                        @error('legal_form')
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="text-lightblue" for="description">Description</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-comment"></i></span>
                            </div>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3"  placeholder="Enter description" wire:model.lazy="description"></textarea>
                        </div>
                        @error('description')
                            <span class="error invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
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

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_phone" class="text-lightblue">Phone number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-phone-alt"></i></span>
                                </div>
                                <input type="text" name="contact_phone" id="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" wire:model.lazy="contact_phone" placeholder="Enter the phone number">
                            </div>
                            @error('contact_phone')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="contact_email" class="text-lightblue">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-at"></i></span>
                                </div>
                                <input type="text" name="contact_email" id="contact_email" class="form-control @error('contact_email') is-invalid @enderror" wire:model.lazy="contact_email" placeholder="Enter the email">
                            </div>
                            @error('contact_email')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary" type="button" wire:click="myPreviousStep"><i class="fas fa-fw fa-chevron-left"></i> Previous</button>
                <button class="btn btn-primary float-right" type="button" wire:click="myNextStep">Next <i class="fas fa-fw fa-chevron-right"></i></button>
            </div>
            <div id="owner-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 3 ? 'active dstepper-block':'dstepper-none'}}    "aria-labelledby="owner-part-trigger">
                <div class="row">
                    <div class="col-md-6">
                        {{-- Name --}}
                        <div class="form-group">
                            <label for="owner_name" class="text-lightblue">Name</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user"></i></span>
                                </div>
                                <input type="text" name="owner_name" id="owner_name" class="form-control @error('owner_name') is-invalid @enderror" wire:model.lazy="owner_name" placeholder="Enter owner name">
                            </div>
                            @error('owner_name')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Birthdate --}}
                        <div class="form-group">
                            <label for="owner_name" class="text-lightblue">Birthdate</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fas fa-fw fa-birthday-cake text-lightblue"></i>
                                    </div>
                                </div>
                                <input id="owner_birthdate" name="owner_birthdate" data-target="#owner_birthdate" data-toggle="datetimepicker"  class="form-control datetimepicker @error('owner_birthdate') is-invalid @enderror" wire:model="owner_birthdate" placeholder="Choose a date...">
                                @error('owner_birthdate')
                                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>
                        {{-- Gender --}}
                        <div class="form-group">
                            <label class="text-lightblue" for="owner_gender">Gender</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-venus-mars"></i></span>
                                </div>
                                <select name="owner_gender" id="owner_gender" class="form-control" wire:model="owner_gender" data-placeholder="Enter your gender" data-allow-clear="true" style="width: 85%">
                                    <option value=""></option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            @error('owner_gender')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- SSN--}}
                        <div class="form-group">
                            <label for="owner_ssn" class="text-lightblue">Social Security number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-hashtag"></i></span>
                                </div>
                                <input type="text" name="owner_ssn" id="owner_ssn" class="form-control @error('owner_ssn') is-invalid @enderror" wire:model.lazy="owner_ssn" placeholder="Social security number">
                            </div>
                            @error('owner_ssn')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Mobile--}}
                        <div class="form-group">
                            <label for="owner_mobile" class="text-lightblue">Mobile</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-mobile-alt"></i></span>
                                </div>
                                <input type="text" name="owner_mobile" id="owner_mobile" class="form-control @error('owner_mobile') is-invalid @enderror" wire:model.lazy="owner_mobile" placeholder="Enter owner mobile">
                            </div>
                            @error('owner_mobile')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Email--}}
                        <div class="form-group">
                            <label for="owner_email" class="text-lightblue">Email</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-at"></i></span>
                                </div>
                                <input type="email" name="owner_email" id="owner_email" class="form-control @error('owner_email') is-invalid @enderror" wire:model.lazy="owner_email" placeholder="Enter owner email">
                            </div>
                            @error('owner_email')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Password --}}
                        <div class="form-group">
                            <label for="owner_password" class="text-lightblue">Password</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-key"></i></span>
                                </div>
                                <input type="password" name="owner_password" id="owner_password" class="form-control @error('owner_password') is-invalid @enderror" wire:model.lazy="owner_password" placeholder="Enter owner password">
                            </div>
                            @error('owner_password')
                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        {{-- Password confirmation--}}
                        <div class="form-group">
                            <label for="owner_password_confirmation" class="text-lightblue">Password confirmation</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-key"></i></span>
                                </div>
                                <input type="password" name="owner_password_confirmation" id="owner_password_confirmation" class="form-control @error('owner_password_confirmation') is-invalid @enderror" wire:model.lazy="owner_password_confirmation" placeholder="Confirm password">
                            </div>
                            @error('owner_repete_password')
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
                          <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Company</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false">Address</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Owner</a>
                        </li>
                      </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="custom-tabs-four-tabContent">
                            <div class="tab-pane fade show active" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                                <dl class="row">
                                    <dt class="col-sm-4">Logo</dt>
                                    <dd class="col-sm-8"><img class="profile-user-img img-fluid img-circle" src="{{!empty($logo) ? $logo->temporaryUrl() : 'https://picsum.photos/128/128'}}" alt="My Logo"></dd>
                                    <dt class="col-sm-4">Name</dt>
                                    <dd class="col-sm-8">{{$display_name}}</dd>
                                    <dt class="col-sm-4">slug</dt>
                                    <dd class="col-sm-8">{{$slug}}</dd>
                                    <dt class="col-sm-4">Business number</dt>
                                    <dd class="col-sm-8">{{$business_number}}</dd>
                                    <dt class="col-sm-4">Legal form</dt>
                                    <dd class="col-sm-8">{{$legal_form}}</dd>
                                    <dt class="col-sm-4">Description</dt>
                                    <dd class="col-sm-8">{{$description}}</dd>
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
                                    @if(!empty($contact_phone))
                                    <dt class="col-sm-4">Phone</dt>
                                    <dd class="col-sm-8">{{$contact_phone}}</dd>
                                    @endif
                                    @if(!empty($contact_email))
                                    <dt class="col-sm-4">Email</dt>
                                    <dd class="col-sm-8">{{$contact_email}}</dd>
                                    @endif
                                </dl>
                            </div>
                            <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                                <dl class="row">
                                    <dt class="col-sm-4">Name</dt>
                                    <dd class="col-sm-8">{{$owner_name}}</dd>
                                    <dt class="col-sm-4">Birthdate</dt>
                                    <dd class="col-sm-8">{{$owner_birthdate}}</dd>
                                    <dt class="col-sm-4">Gender</dt>
                                    <dd class="col-sm-8">{{$owner_gender}}</dd>
                                    @if(!empty($owner_ssn))
                                    <dt class="col-sm-4">Social security number</dt>
                                    <dd class="col-sm-8">{{$owner_ssn}}</dd>
                                    @endif
                                    <dt class="col-sm-4">Mobile</dt>
                                    <dd class="col-sm-8">{{$owner_mobile}}</dd>
                                    <dt class="col-sm-4">Username / Email</dt>
                                    <dd class="col-sm-8">{{$owner_email}}</dd>
                                    <dt class="col-sm-4">Password</dt>
                                    <dd class="col-sm-8">
                                          <span class="text-info passwd-txt">*********</span>
                                          <span><a href="javascript:void(0);" id="showpasswd"><i class="far fa-eye toggle-password"></i></a></span>
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
            $('#legal_form, #address_type, #address_city ,#address_country, #owner_gender').select2({
                width: 'resolve',
                theme: 'bootstrap4',
            });

            $("#owner_birthdate").datetimepicker({
                format: 'YYYY-MM-DD',
            });

            $('#legal_form').on('select2:select', function (event) {
                var data = $('#legal_form').select2("val");
                //console.log(data)
                @this.set('legal_form', data);
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

            $('#owner_gender').on('select2:select', function (event) {
                var data = $('#owner_gender').select2("val");
                //console.log(data)
                @this.set('owner_gender', data);
            });

            $('#business_number').inputmask({
                mask:"999999999",
                oncomplete: function(){
                    $(this).removeClass('is-invalid');
                    @this.set('business_number', $(this).val())
                },
                onincomplete: function(){
                    @this.set('business_number', null)
                    $(this).addClass('is-invalid');
                },
                oncleared: function(){
                    $(this).removeClass('is-invalid');
                },

            }).on("paste", function(){
                @this.set('business_number', $(this).val())
            });

            $('#owner_birthdate').on('hide.datetimepicker', function(event) {
                data = $(this).val() //moment(, 'DD/MM/YYYY').format('DD-MM-YYYY')
                //console.log(data)
                //$(this).removeClass('is-invalid');
                @this.set('owner_birthdate', data)

            });

            $("#showpasswd").on("click", function (event) {
                event.preventDefault();
                //console.log("Hello")
                var password = $("#owner_password").val();
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
                $('#legal_form, #address_type, #address_city, #address_country,  #owner_gender').select2({
                    width: 'resolve',
                    theme: 'bootstrap4',
                });
            })
        });

        window.addEventListener('swalCompany:created', event => {
            Swal.fire({
                position: 'top-end',
                icon: event.detail.icon,
                title: event.detail.title,
                text: event.detail.text,
                showConfirmButton: false,
                timer: 5000
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    $("#business_number").val('')
                    location.reload()
                }
            });
        });

        window.addEventListener('swalCompany:notcreated', event => {
            Swal.fire({
                position: 'top-end',
                icon: event.detail.icon,
                title: event.detail.title,
                text: event.detail.text,
                showConfirmButton: false,
                timer: 5000
            });
        });

    </script>
</div>
