@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.Moment', true)
@section('plugins.Datepicker', true)
@section('plugins.Inputmask', true)
@section('plugins.iCheck', true)
{{-- @section('plugins.Toastr', true)--}}

@section('title', 'Profile')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    My profile
                    <small>new</small>
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                    <li class="breadcrumb-item active">User profile</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <!-- User profile -->
            <x-adminlte-profile-widget name="{{$user->name}}" desc="{{$user->roles->first()->display_name}}" theme="lightblue"
                img="{{!empty($user->photo)? asset('storage/images/profile/users/'.$user->photo) :'https://picsum.photos/id/1/100'}}" layout-type="classic">
                <x-adminlte-profile-row-item icon="fas fa-fw fa-venus-mars" class="mr-1 border-bottom" title="Gender" text="{{ucfirst(__($user->gender))}}"/>
                <x-adminlte-profile-row-item icon="fas fa-fw fa-birthday-cake" class="mr-1 border-bottom" title="Birthdate" text="{{$user->birthdate->format('d F Y')}}"/>
                <x-adminlte-profile-row-item icon="fas fa-fw fa-user-check" class="mr-1 border-bottom" title="Status" text="{{$user->active?__('Active'):__('Inactive')}}"/>
                <x-adminlte-profile-row-item icon="fas fa-fw fa-envelope" class="mr-1 border-bottom" title="Email" text="{{$user->email}}"/>
                <x-adminlte-profile-row-item icon="fas fa-fw fa-hashtag" class="mr-1 mb-2" title="SSN" text="{{$user->ssn}}"/>
                <x-adminlte-button label="Edit profile" class="btn-block editUser"  theme="primary"/>
            </x-adminlte-profile-widget>
            <!--User contact-->
            <livewire:addresses :model="$user" />
            <livewire:contacts :model="$user" />
        </div>
        <div class="col-md-9">
            @if(session()->has('message'))
            <x-adminlte-alert theme="success" title="Success">
                {{session()->get('message')}}
            </x-adminlte-alert>
            @endif
            @if($errors->any())
            <x-adminlte-alert theme="danger" title="Danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-adminlte-alert>
            @endif
            <div class="card">
                <div class="card-header p-2">
                    <ul class="nav nav-pills" id="tabMenu">
                        <li class="nav-item"><a class="nav-link active" href="#contracts" data-toggle="tab">Contracts</a></li>
                        <li class="nav-item"><a class="nav-link" href="#notifications" data-toggle="tab">Notifications</a></li>
                        <li class="nav-item"><a class="nav-link" href="#reset_password" data-toggle="tab">Change password</a></li>
                     </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="contracts">
                            @php
                                $heads = [
                                    '#',
                                    'Code',
                                    'Company',
                                    'Role',
                                    'Availability',
                                    'Start date',
                                    'End date',
                                    'Status',
                                    'Signature',
                                    'Actions',
                                ];
                                $config = [
                                    'processing' => true,
                                    'serverSide' => true,
                                    'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('user.contracts', ['user'=>$user])],
                                    'responsive'=> true,
                                    'order' => [[0,'asc']],
                                    'columns' => [['data'=>'DT_RowIndex'], ['data'=>'code'], ['data'=>'company'],['data'=>'role'], ['data'=>'availability'], ['data'=>'start_at'], ['data'=>'end_at'], ['data'=>'agreement_status'], ['data'=>'acceptance_at'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]],
                                ]
                            @endphp
                            <x-adminlte-card title="Contracts" theme="lightblue" icon="fas fa-lg fa-file-contract" removable collapsible>
                                <x-adminlte-datatable id="userContracts" :heads="$heads" :config="$config"/>
                            </x-adminlte-card>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="notifications">
                            @livewire('notifications', ['user' => $user], key($user->id))
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="reset_password">

                            <form action="{{route('user.updatepasswd', ['user'=>$user])}}" method="POST">
                                @csrf
                                <input type="hidden" name="tab" value="reset_password" />
                                <x-adminlte-input name="old_password" label="Old Password" placeholder="Old password" type="password" label-class="text-lightblue">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text">
                                            <i class="fas fa-key text-lightblue"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-input name="new_password" label="New Password" placeholder="New password" type="password" label-class="text-lightblue">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text">
                                            <i class="fas fa-user-lock text-lightblue"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-input name="new_password_confirmation" label="Repete new Password" placeholder="Repete new password" type="password" label-class="text-lightblue">
                                    <x-slot name="prependSlot">
                                        <div class="input-group-text">
                                            <i class="fas fa-user-lock text-lightblue"></i>
                                        </div>
                                    </x-slot>
                                </x-adminlte-input>
                                <x-adminlte-button class="btn-flat float-right" type="submit" label="Submit" theme="success" icon="fas fa-lg fa-save"/>
                            </form>

                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div><!-- /.card-body -->
            </div>
        </div>
        @php
            $config = ['format' => 'DD/MM/YYYY'];
        @endphp
        <x-adminlte-modal id="editusermodal" title="Edit User" theme="lightblue" icon="fas fa-user-edit" static-backdrop>
            {{-- Photo --}}
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle mb-2 imagePreview" src="" alt="User profile picture">
            </div>
            <form id="userform" method="POST" enctype="multipart/form-data">
                {{-- File upload --}}
                <x-adminlte-input-file name="user_photo" placeholder="Choose your photo...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-camera"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-file>
                {{-- Name --}}
                <x-adminlte-input name="user_name" placeholder="User name">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-user"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                {{-- Name --}}
                <x-adminlte-input name="user_email" placeholder="mail@example.com" type="email">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-envelope"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                {{-- Birthdate --}}
                <x-adminlte-input-date name="user_birthdate" :config="$config" placeholder="Choose your birthdate day...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-birthday-cake"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-date>
                {{-- Gender--}}
                <x-adminlte-select2 name="user_gender" label-class="text-lightblue" data-placeholder="Select your gender...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-venus-mars"></i>
                        </div>
                    </x-slot>
                    <option/>
                    <option>male</option>
                    <option>female</option>
                    <option>othe</option>
                </x-adminlte-select2>
                {{-- SSN --}}
                <x-adminlte-input name="user_ssn" placeholder="Social security number">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-hashtag"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                {{-- Password--}}
                <x-adminlte-input name="user_password" placeholder="Current password" type="password">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-lock"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
            </form>
            {{-- Footer button --}}
            <x-slot name="footerSlot">
                <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
                <x-adminlte-button theme="success" label="Accept" onclick="saveForm()" />
            </x-slot>
        </x-adminlte-modal>
        <x-adminlte-modal id="contractSignatureModal" title="Contract signature" theme="lightblue" icon="fas fa-file-signature" static-backdrop>
            {{-- Form body --}}
            <x-adminlte-card theme-mode="outline">
                <div class="row">
                    <div class="col-sm-12">
                        <h3>Sign your contract</h3>
                        <div class="form-group">
                            <input type="hidden" name="contract_id" id="contract_id" value="">
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <strong class="text-lightblue">Contract acceptance</strong>
                        <div class="form-group">
                            <div class="icheck-primary">
                                <input type="radio" id="acceptance" value="accepted" name="agreement_status" checked=""/>
                                <label for="acceptance" class="text-success">I accept this contract</label>
                            </div>
                            <div class="icheck-primary">
                                <input type="radio" id="refusal" value="refused" name="agreement_status" />
                                <label for="refusal" class="text-danger">I refuse this contract</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="form-group">
                            <label for="signaturePassword" class="text-lightblue">Sign with your password</label>
                            <input type="password" name="signaturePassword" class="form-control" id="signaturePassword" placeholder="Password">
                        </div>
                        <div class="form-group mb-0">
                            <div class="icheck-primary">
                                <input type="checkbox" name="terms" id="conditionsTermsCheck">
                                <label for="conditionsTermsCheck">I agree to the <a href="#">terms of service</a>.</label>
                            </div>
                        </div>
                        <div class="icheck-primary">
                            <input type="checkbox" name="checkAcceptDate" id="checkboxAcceptDate" >
                            <label for="checkboxAcceptDate">
                                Signed {{now()->format('d F Y')}}
                            </label>
                        </div>
                    </div>
                </div>
            </x-adminlte-card>
            {{-- Footer button --}}
            <x-slot name="footerSlot">
                <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
                <x-adminlte-button theme="success" label="Sign the contract" onclick="signContract()"/>
            </x-slot>
        </x-adminlte-modal>
    </div>
</div>
@stop

@section('footer')
    @include('includes.footer')
@stop

@section('js')
<script type="text/javascript">
    $(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show')

        $("#user_ssn").inputmask({
            mask: "999999999",
            //placeholder: "*",
            showMaskOnHover: true,
            showMaskOnFocus: false,
        })
        $(".editUser").on("click", function(event){
            event.preventDefault();
            $.ajax({
                url:"{{route('user.edit', ['user'=>$user])}}",
                type: "GET",
                cache: false,
                success: function(response) {
                    if(response.user.photo === null) {
                        $(".imagePreview").attr("src", "https://picsum.photos/id/1/128")
                    } else {
                        $(".imagePreview").attr("src", document.location.origin+"/storage/images/profile/users/"+response.user.photo)
                    }
                    $("#user_name").val(response.user.name)
                    //$("#user_name").addClass('is-valid')
                    $("#user_email").val(response.user.email)
                    //$("#user_email").addClass('is-valid')
                    $("#user_birthdate").val(moment(response.user.birthdate).format('DD/MM/YYYY'))
                    //$("#user_birthdate").addClass('is-valid')
                    $("#user_gender").val(response.user.gender).trigger('change')
                    $("#user_ssn").val(response.user.ssn)
                    //$("#user_ssn").addClass('is-valid')
                    //console.log(response)
                },
                error: function(jsXHR, status, error){
                    console.log(jsXHR)
                }
            })
            $("#editusermodal").modal('show');
        })

        $("#editusermodal").on('hidden.bs.modal', function(){
            var fileInput = document.getElementById('user_photo')
            fileInput.value = ''
            fileInput.dispatchEvent(new Event('change'))

            $("#user_birthdate").datetimepicker('clear');
            $("#userform").trigger('reset')
            $(".invalid-feedback").remove()
            $("#user_name").removeClass('is-invalid')
            $("#user_email").removeClass('is-invalid')
            $("#user_birthdate").removeClass('is-invalid')
            $("#user_ssn").removeClass('is-invalid')
            $("#user_password").removeClass('is-invalid')
        });

        $("#contractSignatureModal").on('hidden.bs.modal', function(){
            $('#contract_id').val('')
            $("input[name='agreement_status']").prop('checked', false);
            $("#acceptance").prop('checked', true)
            $('#signaturePassword').val('')
            $("#signaturePassword").removeClass('is-invalid')
            $("#conditionsTermsCheck").prop('checked', false)
            $("#checkboxAcceptDate").prop('checked', false)
            var inputElement = document.getElementById('conditionsTermsCheck');
            var label = inputElement.parentNode.querySelector("label[for='conditionsTermsCheck']");
            label.classList.remove('text-danger')
            var inputElement = document.getElementById('checkboxAcceptDate');
            var label = inputElement.parentNode.querySelector("label[for='checkboxAcceptDate']");
            label.classList.remove('text-danger')
            $(".invalid-feedback").remove()

        })

        $("#userContracts").on("click", ".contractSignature", function(){
            $('#contract_id').val($(this).val())
            $("#contractSignatureModal").modal('show');
            //console.log($(this).val())
        })
    });

    $("#user_photo").on("change", function(){
        let file = this.files[0]
        if(file && file.size <= 20971520) {
            let data = new FormData();
            data.append('file', file)
            $.ajax({
                url:"{{route('user.photo.store', ['user'=>$user])}}",
                type: "POST",
                cache: false,
                data: data,
                contentType: false,
                processData: false,

                success: function(response) {
                    const reader = new FileReader()
                    reader.addEventListener("load", function(){
                        $(".imagePreview").attr("src", this.result)
                    })
                    reader.readAsDataURL(file)
                    $(".invalid-feedback").remove()
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 3000
                    })
                },
                error: function(jsXHR, status, errors){
                    var inputElement = document.getElementById("user_photo");

                    var spanTag = document.createElement("span");
                    spanTag.classList.add('invalid-feedback')
                    spanTag.classList.add('d-block')
                    spanTag.setAttribute('role', 'alert')

                    var strong = document.createElement("strong")
                    strong.innerHTML=jsXHR.responseJSON.errors.file[0]

                    spanTag.appendChild(strong)

                    var inputParent = inputElement.parentNode
                    inputParent.parentNode.insertBefore(spanTag, inputParent.nextSibling)

                    //inputElement.parentNode.insertBefore(spanTag, inputElement.nextSibling)

                }
            });

        } else {
            if(file){
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'The file selected is too big',
                    showConfirmButton: false,
                    timer: 3000
                })
            }

        }
        //console.log(file)
    })

    function saveForm(){
        $(".invalid-feedback").remove()
        var birth = moment($("#user_birthdate").val(), 'DD/MM/YYYY').format('YYYY-MM-DD')

        $.ajax({
            url:"{{route('user.update', ['user'=>$user])}}",
            type: "PATCH",
            cache: false,
            data: {
                name: $("#user_name").val(),
                email: $("#user_email").val(),
                birthdate: birth,
                gender:$("#user_gender").val(),
                ssn:$("#user_ssn").val(),
                password:$("#user_password").val()
            },
            success: function(response) {
                $("#editusermodal").modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        //console.log('I was closed by the timer')
                        location.reload()
                    }
                })
            },
            error: function(jsXHR, status, errors){
                $.each(jsXHR.responseJSON.errors, function(key, value){
                    var inputElement = document.getElementById("user_"+key);
                    $("#user_"+key).addClass('is-invalid')

                    var spanTag = document.createElement("span");
                    spanTag.classList.add('invalid-feedback')
                    spanTag.classList.add('d-block')
                    spanTag.setAttribute('role', 'alert')

                    var strong = document.createElement("strong")
                    strong.innerHTML=value

                    spanTag.appendChild(strong)

                    inputElement.parentNode.insertBefore(spanTag, inputElement.nextSibling)

                })

            }
        })

    }

    function signContract() {
        $(".invalid-feedback").remove()
        $("#signaturePassword").removeClass('is-invalid')
        var conditionsTermsCheckElement = document.getElementById('conditionsTermsCheck');
        var conditionsTermsChecklabel = conditionsTermsCheckElement.parentNode.querySelector("label[for='conditionsTermsCheck']");
        conditionsTermsChecklabel.classList.remove('text-danger')
        var inputElement = document.getElementById('checkboxAcceptDate');
        var label = inputElement.parentNode.querySelector("label[for='checkboxAcceptDate']");
        label.classList.remove('text-danger')

        var step = $('input[name="agreement_status"]:checked').val()
        var contract_id = $('#contract_id').val()
        if ($('input[name="terms"]').is(':checked')) {
            terms_check = 1
        } else {
            terms_check = 0
        }
        if ($('input[name="checkAcceptDate"]').is(':checked')) {
            date_check = 1
        } else {
            date_check = 0
        }
        $.ajax({
            url:"{{route('user.contracts.signed', ['user'=>$user])}}",
            type: "POST",
            cache: false,
            data: {
                contract_id: $('#contract_id').val(),
                agreement_status: step,
                signaturePassword: $("#signaturePassword").val(),
                conditionsTermsCheck:terms_check,
                checkboxAcceptDate:date_check,
            },
            success: function(response) {
                $("#contractSignatureModal").modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 3000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        //console.log('I was closed by the timer')
                        $("#userContracts").DataTable().ajax.reload();
                    }
                })
            },
            error: function(jsXHR, status, errors){
                $.each(jsXHR.responseJSON.errors, function(key, value){

                    if (key==="signaturePassword") {
                        var inputElement = document.getElementById(key);
                        $("#"+key).addClass('is-invalid')

                        var spanTag = document.createElement("span");
                        spanTag.classList.add('invalid-feedback')
                        spanTag.classList.add('d-block')
                        spanTag.setAttribute('role', 'alert')

                        var strong = document.createElement("strong")
                        strong.innerHTML=value

                        spanTag.appendChild(strong)

                        inputElement.parentNode.insertBefore(spanTag, inputElement.nextSibling)
                    } else {
                        var inputElement = document.getElementById(key);
                        var label = inputElement.parentNode.querySelector("label[for='" + key + "']");
                        //var label = inputElement.nextElementSibling
                        label.classList.add('text-danger')
                        var spanTag = document.createElement("span");
                        spanTag.classList.add('error')
                        spanTag.classList.add('invalid-feedback')
                        spanTag.setAttribute('style', 'display: inline;')

                        spanTag.innerHTML=value
                        newParent=inputElement.parentNode
                        newParent.parentNode.insertBefore(spanTag, newParent.nextSibling)
                        //console.log(label)
                    }
                })

            },
        })
        //console.log(step+"-"+contract_id+" - "+password+" - "+terms_check+" - "+date_check)
    }

</script>
@stop
