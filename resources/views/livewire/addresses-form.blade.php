<div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="text-lightblue" for="address_type">Address type</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-address-book"></i></span>
                    </div>
                    <select name="address_type" id="address_type" wire:model="address_type" data-placeholder="Enter address type" data-allow-clear="true" style="width: 85%" {{strcmp($address_type,'primary')==0 ? 'disabled':''}}>
                        <option value=""></option>
                        @if(strcmp($address_type, 'primary')==0)
                        <option value="primary">Primary</option>
                        @endif
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
                        @foreach ($all_countries as $key=>$my_country)
                        <option value="{{$key}}">{{$my_country}}</option>
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

    <script>

        document.addEventListener('livewire:load', function () {

            $('#address_type').on('select2:select', function (event) {
                var data = $('#address_type').select2("val");
                //console.log(data)
                @this.set('address_type', data);
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

        })

        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (message, component) => {
                $('#address_type, #address_country, #address_city').select2({
                    width: 'resolve',
                    theme: 'bootstrap4',
                });
            })
        });
    </script>

</div>
