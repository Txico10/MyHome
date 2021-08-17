<div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="text-lightblue" for="contact_type">Contact type</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-address-card"></i></span>
                    </div>
                    <select name="contact_type" id="contact_type" wire:model="contact_type" data-placeholder="Select contact type" data-allow-clear="true" style="width: 85%">
                        <option value=""></option>
                        <option value="phone">Telephone</option>
                        <option value="mobile">Mobile</option>
                        <option value="email">Email</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                @error('contact_type')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="contact_description" class="text-lightblue">Contact</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-address-card"></i></span>
                    </div>
                    <input type="text" name="contact_description" id="contact_description" class="form-control @error('contact_description') is-invalid @enderror" wire:model.lazy="contact_description" placeholder="Enter contact">
                </div>
                @error('contact_description')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label class="text-lightblue" for="contact_priority">Contact priority</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-address-card"></i></span>
                    </div>
                    <select name="contact_priority" id="contact_priority" wire:model="contact_priority" data-placeholder="Select contact priority" data-allow-clear="true" style="width: 85%">
                        <option value=""></option>
                        <option value="main">Main</option>
                        <option value="emergency">Emergency</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                @error('contact_priority')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="contact_name" class="text-lightblue">Contact owner</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-address-card"></i></span>
                    </div>
                    <input type="text" name="contact_name" id="contact_name" class="form-control @error('contact_name') is-invalid @enderror" wire:model.lazy="contact_name" placeholder="Enter contact name">
                </div>
                @error('contact_name')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function () {

            $('#contact_type').on('select2:select', function (event) {
                var data = $('#contact_type').select2("val");
                //console.log(data)
                @this.set('contact_type', data);
            });

            $('#contact_priority').on('select2:select', function (event) {
                var data = $('#contact_priority').select2("val");
                //console.log(data)
                @this.set('contact_priority', data);
            });
        })

        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (message, component) => {
                $('#contact_priority, #contact_type').select2({
                    width: 'resolve',
                    theme: 'bootstrap4',
                });
            })
        });
    </script>
</div>
