@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.Moment', true)
@section('plugins.Datepicker', true)
@section('plugins.Inputmask', true)
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
                <x-adminlte-profile-row-item icon="fas fa-fw fa-user-check" class="mr-1 border-bottom" title="Status" text="{{$user->status?__('Active'):__('Inactive')}}"/>
                <x-adminlte-profile-row-item icon="fas fa-fw fa-envelope" class="mr-1 border-bottom" title="Email" text="{{$user->email}}"/>
                <x-adminlte-profile-row-item icon="fas fa-fw fa-hashtag" class="mr-1 mb-2" title="SSN" text="{{$user->ssn}}"/>
                <x-adminlte-button label="Edit profile" class="btn-block editUser"  theme="primary"/>
            </x-adminlte-profile-widget>
            <!--User contact-->
            <livewire:addresses :model="$user" />
        </div>
        <div class="col-md-6">
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
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Activity</a></li>
                        <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">Timeline</a></li>
                        <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Change password</a></li>
                     </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="activity">
                            <!-- Post -->
                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="https://picsum.photos/id/1/128" alt="user image">
                                    <span class="username">
                                        <a href="#">Jonathan Burke Jr.</a>
                                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                    </span>
                                    <span class="description">Shared publicly - 7:30 PM today</span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    Lorem ipsum represents a long-held tradition for designers,
                                    typographers and the like. Some people hate it and argue for
                                    its demise, but others ignore the hate as they create awesome
                                    tools to help create filler text for everyone from bacon lovers
                                    to Charlie Sheen fans.
                                </p>

                                <p>
                                    <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                    <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                    <span class="float-right">
                                        <a href="#" class="link-black text-sm">
                                            <i class="far fa-comments mr-1"></i> Comments (5)
                                        </a>
                                    </span>
                                </p>

                                <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                            </div>
                            <!-- /.post -->

                            <!-- Post -->
                            <div class="post clearfix">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="https://picsum.photos/id/1/128" alt="User Image">
                                    <span class="username">
                                        <a href="#">Sarah Ross</a>
                                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                    </span>
                                    <span class="description">Sent you a message - 3 days ago</span>
                                </div>
                                <!-- /.user-block -->
                                <p>
                                    Lorem ipsum represents a long-held tradition for designers,
                                    typographers and the like. Some people hate it and argue for
                                    its demise, but others ignore the hate as they create awesome
                                    tools to help create filler text for everyone from bacon lovers
                                    to Charlie Sheen fans.
                                </p>

                                <form class="form-horizontal">
                                    <div class="input-group input-group-sm mb-0">
                                        <input class="form-control form-control-sm" placeholder="Response">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-danger">Send</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.post -->

                            <!-- Post -->
                            <div class="post">
                                <div class="user-block">
                                    <img class="img-circle img-bordered-sm" src="https://picsum.photos/id/1/128" alt="User Image">
                                    <span class="username">
                                        <a href="#">Adam Jones</a>
                                        <a href="#" class="float-right btn-tool"><i class="fas fa-times"></i></a>
                                    </span>
                                    <span class="description">Posted 5 photos - 5 days ago</span>
                                </div>
                                <!-- /.user-block -->
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <img class="img-fluid" src="https://picsum.photos/1250/835" alt="Photo">
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-sm-6">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <img class="img-fluid mb-3" src="https://picsum.photos/1254/836" alt="Photo">
                                                <img class="img-fluid" src="https://picsum.photos/2000/1333" alt="Photo">
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-sm-6">
                                                <img class="img-fluid mb-3" src="https://picsum.photos/2000/1320" alt="Photo">
                                                <img class="img-fluid" src="https://picsum.photos/1250/835" alt="Photo">
                                            </div>
                                            <!-- /.col -->
                                        </div>
                                        <!-- /.row -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                                <p>
                                    <a href="#" class="link-black text-sm mr-2"><i class="fas fa-share mr-1"></i> Share</a>
                                    <a href="#" class="link-black text-sm"><i class="far fa-thumbs-up mr-1"></i> Like</a>
                                    <span class="float-right">
                                        <a href="#" class="link-black text-sm">
                                            <i class="far fa-comments mr-1"></i> Comments (5)
                                        </a>
                                    </span>
                                </p>

                                <input class="form-control form-control-sm" type="text" placeholder="Type a comment">
                            </div>
                            <!-- /.post -->
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="timeline">
                            <!-- The timeline -->
                            <div class="timeline timeline-inverse">
                                <!-- timeline time label -->
                                <div class="time-label">
                                    <span class="bg-danger">
                                        10 Feb. 2014
                                    </span>
                                </div>
                                <!-- /.timeline-label -->
                                <!-- timeline item -->
                                <div>
                                    <i class="fas fa-envelope bg-primary"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> 12:05</span>

                                        <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                                        <div class="timeline-body">
                                            Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                                            weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                            jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                                            quora plaxo ideeli hulu weebly balihoo...
                                        </div>
                                        <div class="timeline-footer">
                                            <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                            <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- END timeline item -->
                                <!-- timeline item -->
                                <div>
                                    <i class="fas fa-user bg-info"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> 5 mins ago</span>

                                        <h3 class="timeline-header border-0"><a href="#">Sarah Young</a> accepted your friend request</h3>
                                    </div>
                                </div>
                                <!-- END timeline item -->
                                <!-- timeline item -->
                                <div>
                                    <i class="fas fa-comments bg-warning"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> 27 mins ago</span>

                                        <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                                        <div class="timeline-body">
                                            Take me to your leader!
                                            Switzerland is small and neutral!
                                            We are more like Germany, ambitious and misunderstood!
                                        </div>
                                        <div class="timeline-footer">
                                            <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- END timeline item -->
                                <!-- timeline time label -->
                                <div class="time-label">
                                    <span class="bg-success">
                                        3 Jan. 2014
                                    </span>
                                </div>
                                <!-- /.timeline-label -->
                                <!-- timeline item -->
                                <div>
                                    <i class="fas fa-camera bg-purple"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> 2 days ago</span>

                                        <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                                        <div class="timeline-body">
                                            <img src="https://placehold.it/150x100" alt="...">
                                            <img src="https://placehold.it/150x100" alt="...">
                                            <img src="https://placehold.it/150x100" alt="...">
                                            <img src="https://placehold.it/150x100" alt="...">
                                        </div>
                                    </div>
                                </div>
                                <!-- END timeline item -->
                                <div>
                                    <i class="far fa-clock bg-gray"></i>
                                </div>
                            </div>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="settings">

                            <form action="{{route('user.updatepasswd', ['user'=>$user])}}" method="POST">
                                @csrf
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
        <div class="col-md-3">
            <!-- Contacts -->
            <livewire:contacts :model="$user" />
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
                    //toastr.success(response.message)
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
                //toastr.error("The file selected is too big")
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

</script>
@stop
