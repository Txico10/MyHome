<div>
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
    <script>
        document.addEventListener('livewire:load', function () {
            $('#contract_role, #contract_availability, #contract_salary_term, #contract_benefits').select2({
                width: 'resolve',
                theme: 'bootstrap4',
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
        })

        document.addEventListener("DOMContentLoaded", () => {
            Livewire.hook('message.processed', (message, component) => {
                $('#contract_role, #contract_availability, #contract_salary_term, #contract_benefits').select2({
                    width: 'resolve',
                    theme: 'bootstrap4',
                });
            })
        });

        window.addEventListener('closeNewContractModal', event => {
            $('#contract_min_week_time').val('')
            $('#contract_max_week_time').val('')
            $('#contract_salary_amount').val('')
            $("#contract_start_at").datetimepicker('clear');
            $("#contract_end_at").datetimepicker('clear');
        });

        window.addEventListener('swalContract:form', event => {
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
