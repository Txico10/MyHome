@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('title', 'Building Show')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Building {{$building->display_name}}</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.buildings', ['company' => $company])}}">Buildings</a></li>
                <li class="breadcrumb-item active">{{$building->display_name}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <x-adminlte-card title="Building" theme="lightblue" icon="fas fa-lg fa-building" removable collapsible>
                <dl class="row">
                    <dt class="col-sm-4">Company</dt>
                    <dd class="col-sm-8 border-bottom">{{$company->display_name}}</dd>
                    <dt class="col-sm-4">Lot</dt>
                    <dd class="col-sm-8 border-bottom">{{$building->lot}}</dd>
                    <dt class="col-sm-4">Name</dt>
                    <dd class="col-sm-8 border-bottom">{{$building->display_name}}</dd>
                    <dt class="col-sm-4">Description</dt>
                    <dd class="col-sm-8">{{$building->description}}</dd>
                </dl>
            </x-adminlte-card>
        </div>
        <div class="col-md-9">
            @php
                $heads = [
                    '#',
                    'Type',
                    'Number',
                    'Location',
                    'Description',
                    ['label' => 'Actions', 'no-export' => true],
                ];
                $config = [
                    'processing' => true,
                    'serverSide' => true,
                    'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.building.getdependencies', ['company'=>$company, 'building'=>$building])],
                    'responsive'=> true,
                    'order' => [[0,'asc']],
                    'columns' => [['data'=>'DT_RowIndex'], ['data'=>'type'], ['data'=>'number'], ['data'=>'location'], ['data'=>'description'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]],
                    'dom'=>'<"row" <"col-sm-1 customButton"><"col-sm-5" B><"col-sm-6 d-flex justify-content-end" f> >
                            <"row" <"col-12" tr> >
                            <"row" <"col-sm-6 d-flex justify-content-start" i><"col-sm-6 d-flex justify-content-end" p> >',
                ]
            @endphp
            <x-adminlte-card title="Dependencies" theme="lightblue" icon="fas fa-lg fa-boxes" removable collapsible>
                <x-adminlte-datatable id="building_dependency" :heads="$heads" :config="$config" with-buttons/>
            </x-adminlte-card>
            {{-- Create/Edit Dependencies --}}
            <x-adminlte-modal id="dependencyModal" title="Dependency" theme="lightblue" icon="fas fa-boxes" static-backdrop>
                <div class="row">
                    <div class="col-sm-12">
                        {{-- Dependency ID --}}
                        <input type="hidden" id="dependency_id" value="">
                        {{-- Type --}}
                        <div class="form-group">
                            <label for="dependency_type" class="text-lightblue">Type</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-box-open"></i></span>
                                </div>
                                <select class="form-control select2" name="dependency_type" id="dependency_type" style="width: 85%" data-placeholder="Select type" data-allow-clear="true">
                                    <option></option>
                                    @foreach ($dependency_types as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--Door Number--}}
                        <div class="form-group">
                            <label for="dependency_number" class="text-lightblue">Number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-box-open"></i></span>
                                </div>
                                <input type="text" name="dependency_number" id="dependency_number" class="form-control" placeholder="Enter the door number">
                            </div>
                        </div>
                        {{-- Location --}}
                        <div class="form-group">
                            <label for="dependency_location" class="text-lightblue">Location</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-box-open"></i></span>
                                </div>
                                <input type="text" name="dependency_location" id="dependency_location" class="form-control" placeholder="Enter the location">
                            </div>
                        </div>
                        {{-- Description --}}
                        <div class="form-group">
                            <label for="dependency_description" class="text-lightblue">Description</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-file-alt"></i></span>
                                </div>
                                <textarea name="dependency_description" id="dependency_description" class="form-control" rows="3" placeholder="Enter benefit description"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <x-slot name="footerSlot">
                    <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
                    <x-adminlte-button theme="success" onclick="saveForm()" label="Accept"/>
                </x-slot>
            </x-adminlte-modal>
        </div>
    </div>

@stop


@section('js')
    <script>
        $(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".customButton").html('@permission("dependency-create")<div class="dt-buttons"><button type="button" class="btn btn-block btn-default addDependency"><i class="fas fa-lg  fa-plus-square text-primary"></i> New</button></div>@endpermission')

            $(".addDependency").on("click", function(){
                $("#dependencyModal").modal('show');
            })

            $('.select2').each(function() {
                $(this).select2({
                    width: 'resolve',
                    theme: 'bootstrap4',
                    dropdownParent: $(this).parent()
                });
            })

            $("#dependencyModal").on('hidden.bs.modal', function(){
                $(".invalid-feedback").remove()
                $("#dependency_id").val('')
                $("#dependency_type").val(null).trigger('change')
                $("#dependency_type").removeClass('is-invalid')
                $("#dependency_number").val('')
                $("#dependency_number").removeClass('is-invalid')
                $("#dependency_location").val('')
                $("#dependency_location").removeClass('is-invalid')
                $("#dependency_description").val('')
                $("#dependency_description").removeClass('is-invalid')
            })
        })

        $("#building_dependency").on('click', '.editDependencyButton', function(){
            var dependency_id = $(this).val()
            $.ajax({
                url:"{{route('company.dependency.edit', ['company'=>$company])}}",
                type: "GET",
                cache: false,
                data: {
                    dependency_id:dependency_id
                },
                success: function(response) {
                    //console.log(response.apartment)
                    $("#dependency_id").val(response.dependency.id)
                    $.each(response.dependency.team_settings, function(key, value){
                        $("#dependency_type").val(value.id).trigger('change')
                    })
                    $("#dependency_number").val(response.dependency.number)
                    $("#dependency_location").val(response.dependency.location)
                    $("#dependency_description").val(response.dependency.description)
                    $("#dependencyModal").modal('show');
                },
                error: function(jsXHR, status, error){
                    console.log(jsXHR)
                }
            })
        })

        function saveForm() {
            $(".invalid-feedback").remove()
            $("#dependency_type").removeClass('is-invalid')
            $("#dependency_number").removeClass('is-invalid')
            $("#dependency_location").removeClass('is-invalid')
            $("#dependency_description").removeClass('is-invalid')

            $.ajax({
                url:"{{route('company.dependency.store', ['company'=>$company, 'building'=>$building])}}",
                type: "POST",
                cache: false,
                data: {
                    id:$("#dependency_id").val(),
                    type: $("#dependency_type").val(),
                    number: $("#dependency_number").val(),
                    location:$("#dependency_location").val(),
                    description: $("#dependency_description").val(),
                },
                success: function(response) {
                    $("#dependencyModal").modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            $("#building_dependency").DataTable().ajax.reload();
                        }
                    })
                },
                error: function(jsXHR, status, errors){
                    $.each(jsXHR.responseJSON.errors, function(key, value){
                        var inputElement = document.getElementById("dependency_"+key);
                        $("#dependency_"+key).addClass('is-invalid')

                        var spanTag = document.createElement("span");
                        spanTag.classList.add('invalid-feedback')
                        spanTag.classList.add('d-block')
                        spanTag.setAttribute('role', 'alert')

                        var strong = document.createElement("strong")
                        strong.innerHTML=value

                        spanTag.appendChild(strong)

                        if(key==="type") {
                            newNode = inputElement.parentNode
                            newNode.parentNode.insertBefore(spanTag, newNode.nextSibling)
                        } else {
                            inputElement.parentNode.insertBefore(spanTag, inputElement.nextSibling)
                        }

                    })

                }
            })
            //console.log("Dependency saved")
        }
    </script>
@stop
