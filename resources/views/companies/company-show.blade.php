@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.Inputmask', true)
@section('title', 'Company details')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Company show</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                @permission('clients-create')<li class="breadcrumb-item"><a href="{{route('admin.index')}}">Admin</a></li>@endpermission
                <li class="breadcrumb-item active">{{$company->display_name}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <x-adminlte-profile-widget name="{{$company->display_name}}" desc="{{$company->description}}" theme="lightblue"
                img="{{asset('storage/images/profile/companies/'.$company->logo)}}" layout-type="classic">
                <x-adminlte-profile-row-item icon="fas fa-fw fa-hashtag" class="mr-1 border-bottom" title="Business number" text="{{ucfirst(__($company->bn))}}"/>
                <x-adminlte-profile-row-item icon="fas fa-fw fa-file-contract" class="mr-1 mb-2" title="Legal form" text="{{$company->legalform}}"/>
                <x-adminlte-button label="Edit profile" class="btn-block editcompany"  theme="primary"/>
            </x-adminlte-profile-widget>

            {{-- Address Compenents --}}
            <livewire:addresses :model="$company" />
        </div>
        <div class="col-md-6">
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                  <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="custom-tabs-four-home-tab" data-toggle="pill" href="#custom-tabs-four-home" role="tab" aria-controls="custom-tabs-four-home" aria-selected="true">Home</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-four-profile-tab" data-toggle="pill" href="#custom-tabs-four-profile" role="tab" aria-controls="custom-tabs-four-profile" aria-selected="false" aria-disabled="true">Profile</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-four-messages-tab" data-toggle="pill" href="#custom-tabs-four-messages" role="tab" aria-controls="custom-tabs-four-messages" aria-selected="false">Messages</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" id="custom-tabs-four-settings-tab" data-toggle="pill" href="#custom-tabs-four-settings" role="tab" aria-controls="custom-tabs-four-settings" aria-selected="false">Settings</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="tab-content" id="custom-tabs-four-tabContent">
                    <div class="tab-pane fade active show" id="custom-tabs-four-home" role="tabpanel" aria-labelledby="custom-tabs-four-home-tab">
                       Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin malesuada lacus ullamcorper dui molestie, sit amet congue quam finibus. Etiam ultricies nunc non magna feugiat commodo. Etiam odio magna, mollis auctor felis vitae, ullamcorper ornare ligula. Proin pellentesque tincidunt nisi, vitae ullamcorper felis aliquam id. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin id orci eu lectus blandit suscipit. Phasellus porta, ante et varius ornare, sem enim sollicitudin eros, at commodo leo est vitae lacus. Etiam ut porta sem. Proin porttitor porta nisl, id tempor risus rhoncus quis. In in quam a nibh cursus pulvinar non consequat neque. Mauris lacus elit, condimentum ac condimentum at, semper vitae lectus. Cras lacinia erat eget sapien porta consectetur.
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-profile" role="tabpanel" aria-labelledby="custom-tabs-four-profile-tab">
                       Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam.
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-messages" role="tabpanel" aria-labelledby="custom-tabs-four-messages-tab">
                       Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna.
                    </div>
                    <div class="tab-pane fade" id="custom-tabs-four-settings" role="tabpanel" aria-labelledby="custom-tabs-four-settings-tab">
                       Pellentesque vestibulum commodo nibh nec blandit. Maecenas neque magna, iaculis tempus turpis ac, ornare sodales tellus. Mauris eget blandit dolor. Quisque tincidunt venenatis vulputate. Morbi euismod molestie tristique. Vestibulum consectetur dolor a vestibulum pharetra. Donec interdum placerat urna nec pharetra. Etiam eget dapibus orci, eget aliquet urna. Nunc at consequat diam. Nunc et felis ut nisl commodo dignissim. In hac habitasse platea dictumst. Praesent imperdiet accumsan ex sit amet facilisis.
                    </div>
                  </div>
                </div>
                <!-- /.card -->
              </div>
        </div>
        <div class="col-md-3">
            <livewire:contacts :model="$company" />
        </div>
        <x-adminlte-modal id="editcompanymodal" title="Edit Company" theme="lightblue" icon="fas fa-building" static-backdrop>
            {{-- Photo --}}
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle mb-2 imagePreview" src="" alt="Company logo">
            </div>
            <form id="companyform" method="POST" enctype="multipart/form-data">
                {{-- File upload --}}
                <x-adminlte-input-file name="company_logo" placeholder="Choose your logo...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-file-image"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input-file>
                {{-- Display Name --}}
                <x-adminlte-input name="company_display_name" placeholder="Company name">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-building"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                {{-- Slug --}}
                <x-adminlte-input name="company_slug" placeholder="Company Slug" disabled>
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-building"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                {{-- Business number --}}
                <x-adminlte-input name="company_bn" placeholder="Business number">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-hashtag"></i>
                        </div>
                    </x-slot>
                </x-adminlte-input>
                {{-- Legal form--}}
                <x-adminlte-select2 name="company_legalform" label-class="text-lightblue" data-placeholder="Select company legal form...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-fw fa-file-contract"></i>
                        </div>
                    </x-slot>
                    <option />
                    <option>Sole proprietorship</option>
                    <option>Business corporation</option>
                    <option>General partnership</option>
                    <option>Limited partnership</option>
                    <option>Cooperative</option>
                </x-adminlte-select2>
                {{-- Description --}}
                <x-adminlte-textarea name="company_description" placeholder="Insert company description...">
                    <x-slot name="prependSlot">
                        <div class="input-group-text bg-lightblue">
                            <i class="fas fa-lg fa-file-alt"></i>
                        </div>
                    </x-slot>
                </x-adminlte-textarea>
            </form>
            {{-- Footer button --}}
            <x-slot name="footerSlot">
              <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
              <x-adminlte-button theme="success" label="Accept" onclick="saveForm()" />
            </x-slot>
        </x-adminlte-modal>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script type="text/javascript">
        $(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#company_bn").inputmask({
                mask: "999999999",
                //placeholder: "*",
                showMaskOnHover: true,
                showMaskOnFocus: false,
            })

            $(".editcompany").click(function(){
                event.preventDefault();
                $.ajax({
                    url:"{{route('company.edit', ['company'=>$company])}}",
                    type: "GET",
                    cache: false,
                    success: function(response) {
                        if(response.company.logo === null) {
                            $(".imagePreview").attr("src", "https://picsum.photos/id/1/128")
                        } else {
                            $(".imagePreview").attr("src", document.location.origin+"/storage/images/profile/companies/"+response.company.logo)
                        }
                        $("#company_display_name").val(response.company.display_name)
                        $("#company_slug").val(response.company.slug)
                        $("#company_bn").val(response.company.bn)
                        $("#company_legalform").val(response.company.legalform).trigger('change')
                        $("#company_description").val(response.company.description)
                        //console.log(response.company)
                    },
                    error: function(jsXHR, status, error){
                        console.log(jsXHR)
                    }
                })
                $("#editcompanymodal").modal('show');
            })

            $("#editcompanymodal").on('hidden.bs.modal', function(){
                var fileInput = document.getElementById('company_logo')
                fileInput.value = ''
                fileInput.dispatchEvent(new Event('change'))

                $("#companyform").trigger('reset')
                $(".invalid-feedback").remove()
                $("#company_display_name").removeClass('is-invalid')
                $("#company_slug").removeClass('is-invalid')
                $("#company_bn").removeClass('is-invalid')
                $("#company_description").removeClass('is-invalid')
            });
        })

        $("#company_logo").on('change', function(event){
            let file = this.files[0]
            if(file && file.size <= 20971520) {
                let data = new FormData();
                data.append('file', file)
                $.ajax({
                    url:"{{route('company.logoupdate', ['company'=>$company])}}",
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
                        var inputElement = document.getElementById("company_logo");

                        var spanTag = document.createElement("span");
                        spanTag.classList.add('invalid-feedback')
                        spanTag.classList.add('d-block')
                        spanTag.setAttribute('role', 'alert')

                        var strong = document.createElement("strong")
                        strong.innerHTML=jsXHR.responseJSON.errors.file[0]

                        spanTag.appendChild(strong)

                        var inputParent = inputElement.parentNode
                        inputParent.parentNode.insertBefore(spanTag, inputParent.nextSibling)
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
        })

        function saveForm(){

            $.ajax({
                url:"{{route('company.update', ['company'=>$company])}}",
                type: "PUT",
                cache: false,
                data: {
                    display_name: $("#company_display_name").val(),
                    slug:$("#company_slug").val(),
                    bn: $("#company_bn").val(),
                    legalform: $("#company_legalform").val(),
                    description:$("#company_description").val(),
                },
                success: function(response) {
                    $("#editcompanymodal").modal('hide');
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
                        var inputElement = document.getElementById("company_"+key);
                        $("#company_"+key).addClass('is-invalid')

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
