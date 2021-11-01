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
                    <div class="col-sm-6">
                        <x-adminlte-card title="Lessee" theme="lightblue" theme-mode="outline">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="text-lightblue" for="address_suite">Name</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user"></i></span>
                                            </div>
                                            <input type="text" name="lessee_name" id="lessee_name" wire:model.lazy="lessee_name" class="form-control @error('lessee_name') is-invalid @enderror" placeholder="Enter Lessee name">
                                        </div>
                                        @error('lessee_name')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- Birthdate --}}
                                    <div class="form-group">
                                        <label for="employee_birthdate" class="text-lightblue">Birthdate</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-fw fa-birthday-cake text-lightblue"></i>
                                                </div>
                                            </div>
                                            <input id="lessee_birthdate" name="lessee_birthdate" data-target="#lessee_birthdate" data-toggle="datetimepicker"  class="form-control datetimepicker @error('lessee_birthdate') is-invalid @enderror" wire:model="lessee_birthdate" placeholder="Choose a date...">
                                            @error('lessee_birthdate')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- Gender --}}
                                    <div class="form-group">
                                        <label class="text-lightblue" for="lessee_gender">Gender</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-venus-mars"></i></span>
                                            </div>
                                            <select name="lessee_gender" id="lessee_gender" class="form-control" wire:model="lessee_gender" data-placeholder="Enter lessee gender"  data-allow-clear="true" style="width: 80%">
                                                <option value=""></option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                                <option value="other">Other</option>
                                            </select>
                                        </div>
                                        @error('lessee_gender')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- Email--}}
                                    <div class="form-group">
                                        <label for="lessee_email" class="text-lightblue">Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-at"></i></span>
                                            </div>
                                            <input type="email" name="lessee_email" id="lessee_email" class="form-control @error('lessee_email') is-invalid @enderror" wire:model.lazy="lessee_email" placeholder="Enter lessee email">
                                        </div>
                                        @error('lessee_email')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    {{-- Mobile--}}
                                    <div class="form-group">
                                        <label for="lessee_mobile" class="text-lightblue">Mobile</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-mobile-alt"></i></span>
                                            </div>
                                            <input type="text" name="lessee_mobile" id="lessee_mobile" class="form-control @error('lessee_mobile') is-invalid @enderror" wire:model.lazy="lessee_mobile" placeholder="Enter lessee mobile">
                                        </div>
                                        @error('lessee_mobile')
                                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </x-adminlte-card>
                    </div>
                    <div class="col-sm-6">
                        <x-adminlte-card title="Lessee old adresse" theme="lightblue" theme-mode="outline">
                            <div class="row">
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
                                            <select class="form-control" name="address_city" id="address_city" wire:model="address_city" style="width: 80%" data-placeholder="Select city" data-allow-clear="true">
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
                                            <select name="address_country" id="address_country" wire:model="address_country" data-placeholder="Enter your country" data-allow-clear="true" style="width: 80%">
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
                        </x-adminlte-card>
                    </div>
                </div>

                <button class="btn btn-primary float-right" wire:click="myNextStep">Next <i class="fas fa-fw fa-chevron-right"></i></button>
            </div>
            <div id="adress-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 2 ? 'active dstepper-block':'dstepper-none'}}   "aria-labelledby="adress-part-trigger">
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-card title="Lease" theme="lightblue" theme-mode="outline">
                            <div class="row">
                                <div class="col-md-6">
                                    {{-- Term --}}
                                    <div class="form-group">
                                        <label class="text-lightblue" for="lease_term">Term</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-hourglass-half"></i></span>
                                            </div>
                                            <select name="lease_term" id="lease_term" class="form-control" wire:model="lease_term" data-placeholder="Enter lease term"  data-allow-clear="true" style="width: 80%">
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
                                        <label for="lease_start" class="text-lightblue">Lease start</label>
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
                                        <label for="lease_end" class="text-lightblue">Lease end</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-fw fa-calendar-check text-lightblue"></i>
                                                </div>
                                            </div>
                                            <input id="lease_end" name="lease_end" data-target="#lease_end" data-toggle="datetimepicker"  class="form-control datetimepicker @error('lease_end') is-invalid @enderror" wire:model="lease_end" placeholder="Choose a date...">
                                            @error('lease_end')
                                                <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                @endif
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
                            </div>
                        </x-adminlte-card>
                    </div>
                </div>
                <button class="btn btn-primary" type="button" wire:click="myPreviousStep"><i class="fas fa-fw fa-chevron-left"></i> Previous</button>
                <button class="btn btn-primary float-right" type="button" wire:click="myNextStep">Next <i class="fas fa-fw fa-chevron-right"></i></button>
            </div>
            <div id="lease-part" role="tabpanel" class="bs-stepper-pane fade {{$currentStep == 3 ? 'active dstepper-block':'dstepper-none'}}    "aria-labelledby="lease-part-trigger">

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
            $('#lessee_gender, #address_type, #address_city ,#address_country, #lease_term').select2({
                width: 'resolve',
                theme: 'bootstrap4',
            });

            $("#lessee_birthdate").datetimepicker({
                format: 'YYYY-MM-DD',
            });

            $('#lessee_birthdate').on('hide.datetimepicker', function(event) {
                data = $(this).val() //moment(, 'DD/MM/YYYY').format('DD-MM-YYYY')
                //console.log(data)
                //$(this).removeClass('is-invalid');
                @this.set('lessee_birthdate', data)

            });

            //lease_start
            $("#lease_start").datetimepicker({
                format: 'YYYY-MM-DD',
            });

            $('#lease_start').on('hide.datetimepicker', function(event) {
                data = $(this).val() //moment(, 'DD/MM/YYYY').format('DD-MM-YYYY')
                //console.log(data)
                //$(this).removeClass('is-invalid');
                @this.set('lease_start', data)

            });

            $("#lease_end").datetimepicker({
                format: 'YYYY-MM-DD',
            });

            $('#lease_end').on('hide.datetimepicker', function(event) {
                data = $(this).val() //moment(, 'DD/MM/YYYY').format('DD-MM-YYYY')
                //console.log(data)
                //$(this).removeClass('is-invalid');
                @this.set('lease_end', data)

            });

            $('#lessee_gender').on('select2:select', function (event) {
                var data = $('#lessee_gender').select2("val");
                //console.log(data)
                @this.set('lessee_gender', data);
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
            //lease_term
            $('#lease_term').on('select2:select', function (event) {
                var data = $('#lease_term').select2("val");
                //console.log(data)
                @this.set('lease_term', data);
            });
        })
        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (message, component) => {
                $('#lessee_gender, #address_type, #address_city ,#address_country, #lease_term').select2({
                    width: 'resolve',
                    theme: 'bootstrap4',
                });
                $("#lease_end").datetimepicker({
                    format: 'YYYY-MM-DD',
                });
            })
        })
    </script>
</div>
