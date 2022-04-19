@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('title', 'Apartments')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Apartments</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item active">Apartments</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
@php
    $heads = [
        'Number',
        'Building',
        'Size',
        'Description',
        ['label' => 'Actions', 'no-export' => true],
    ];
    $config = [
        'processing' => true,
        'serverSide' => true,
        'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.apartments', ['company'=>$company])],
        'responsive'=> true,
        'order' => [[1,'asc'], [0,'asc']],
        'columns' => [['data'=>'number'], ['data'=>'building_name'], ['data'=>'apartment_type'], ['data'=>'description'],['data'=>'action', 'searchable'=>false, 'orderable' => false]],
        'dom'=>'<"row" <"col-sm-1 customButton"><"col-sm-5" B><"col-sm-6 d-flex justify-content-end" f> >
                <"row" <"col-12" tr> >
                <"row" <"col-sm-6 d-flex justify-content-start" i><"col-sm-6 d-flex justify-content-end" p> >',
    ]
    @endphp
    <x-adminlte-card title="Apartments" theme="lightblue" icon="fas fa-lg fa-house-user" removable collapsible>
        <x-adminlte-datatable id="apartments" :heads="$heads" :config="$config" with-buttons/>
    </x-adminlte-card>
    {{-- Create/Edit Apartment Modal--}}
    <x-adminlte-modal id="apartmentModal" title="Apartment" theme="lightblue" icon="fas fa-house-user">
        <div class="row">
            <div class="col-sm-12">
                {{-- Apartment --}}
                <input type="hidden" id="apartment_id" value="">
                {{-- Buildings --}}
                <div class="form-group">
                    <label for="company_buildings" class="text-lightblue">Building</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-building"></i></span>
                        </div>
                        <select class="form-control select2" name="apartment_building" id="apartment_building" style="width: 85%" data-placeholder="Select Building" data-allow-clear="true">
                            <option></option>
                            @foreach($buildings as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Number --}}
                <div class="form-group">
                    <label for="apartment_number" class="text-lightblue">Number</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-door-closed"></i></span>
                        </div>
                        <input type="text" name="apartment_number" id="apartment_number" class="form-control" placeholder="Enter apartment number">
                    </div>
                </div>
                {{-- Size --}}
                <div class="form-group">
                    <label for="apartment_size" class="text-lightblue">Size</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-building"></i></span>
                        </div>
                        <select class="form-control select2" name="apartment_size" id="apartment_size" style="width: 85%" data-placeholder="Select size" data-allow-clear="true">
                            <option></option>
                            @foreach($apartment_types as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- heating_of_dweeling --}}
                <div class="form-group">
                    <label for="apartment_size" class="text-lightblue">Heating of dweeling</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-fire"></i></span>
                        </div>
                        <select class="form-control select2" name="apartment_heating_of_dweeling" id="apartment_heating_of_dweeling" style="width: 85%" data-placeholder="Select size" data-allow-clear="true">
                            <option></option>
                            @foreach($heating_of_dweelings as $key => $value)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                {{-- Description --}}
                <div class="form-group">
                    <label for="apartment_description" class="text-lightblue">Description</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-file-alt"></i></span>
                        </div>
                        <textarea id="apartment_description" name="apartment_description" class="form-control" rows="3" placeholder="Insert description..."></textarea>
                    </div>
                </div>
            </div>
        </div>
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
            <x-adminlte-button theme="success" onclick="saveApartmentForm()" label="Save"/>
        </x-slot>
    </x-adminlte-modal>
@stop

@section('js')
    <script>
        $(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.select2').each(function() {
                $(this).select2({
                    width: 'resolve',
                    theme: 'bootstrap4',
                    dropdownParent: $(this).parent()
                });
            })

            $(".customButton").html('@permission("apartment-create")<div class="dt-buttons"><button type="button" class="btn btn-block btn-default addApartment"><i class="fas fa-lg fa-plus-square text-primary"></i> New</button></div>@endpermission');

            $(".addApartment").on("click", function() {
                $("#apartmentModal").modal('show');
            })

            $("#apartmentModal").on('hidden.bs.modal', function(){
                $("#apartment_id").val('')
                $("#apartment_building").val(null).trigger('change')
                $("#apartment_building").removeClass('is-invalid')
                $("#apartment_number").val('')
                $("#apartment_number").removeClass('is-invalid')
                $("#apartment_size").val(null).trigger('change')
                $("#apartment_size").removeClass('is-invalid')
                $("#apartment_heating_of_dweeling").val(null).trigger('change')
                $("#apartment_heating_of_dweeling").removeClass('is-invalid')
                $("#apartment_description").val('')
                $("#apartment_description").removeClass('is-invalid')
                $(".invalid-feedback").remove()
            })
        })

        $("#apartments").on('click', '.editApartmentButton', function(){
            var apartment_id = $(this).val()
            $.ajax({
                    url:"{{route('company.apartment.edit', ['company'=>$company, 'apartment'=>$apartment])}}",
                    type: "GET",
                    cache: false,
                    data: {
                        apartment_id:apartment_id
                    },
                    success: function(response) {
                        //console.log(response.apartment)
                        $("#apartment_id").val(response.apartment.id)
                        $("#apartment_building").val(response.apartment.building_id).trigger('change')
                        $("#apartment_number").val(response.apartment.number)
                        $.each(response.apartment.team_settings, function(key, value){
                            //console.log(value)
                            if(value.type==='apartment') {
                                $("#apartment_size").val(value.id).trigger('change')
                            }
                            if(value.type==='heating_of_dweeling') {
                                $("#apartment_heating_of_dweeling").val(value.id).trigger('change')
                            }

                        })
                        $("#apartment_description").val(response.apartment.description)

                        $("#apartmentModal").modal('show');
                    },
                    error: function(jsXHR, status, error){
                        console.log(jsXHR)
                    }
                })
        })

        function saveApartmentForm(){
            var apartment_id = $("#apartment_id").val()
            var building_id = $("#apartment_building").val()
            var number = $("#apartment_number").val()
            var size = $("#apartment_size").val()
            var heat = $("#apartment_heating_of_dweeling").val()
            var description = $("#apartment_description").val()

            $(".invalid-feedback").remove()
            $("#apartment_building").removeClass('is-invalid')
            $("#apartment_number").removeClass('is-invalid')
            $("#apartment_size").removeClass('is-invalid')
            $("#apartment_heating_of_dweeling").removeClass('is-invalid')
            $("#apartment_description").removeClass('is-invalid')


            $.ajax({
                url:"{{route('company.apartment.store', ['company'=>$company])}}",
                type: "POST",
                cache: false,
                data: {
                    id:apartment_id,
                    building: building_id,
                    number: number,
                    size:size,
                    heating_of_dweeling:heat,
                    description: description,
                },
                success: function(response) {
                    $("#apartmentModal").modal('hide');
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            $("#apartments").DataTable().ajax.reload();
                        }
                    })
                },
                error: function(jsXHR, status, errors){
                    $.each(jsXHR.responseJSON.errors, function(key, value){
                        var inputElement = document.getElementById("apartment_"+key);
                        $("#apartment_"+key).addClass('is-invalid')

                        var spanTag = document.createElement("span");
                        spanTag.classList.add('invalid-feedback')
                        spanTag.classList.add('d-block')
                        spanTag.setAttribute('role', 'alert')

                        var strong = document.createElement("strong")
                        strong.innerHTML=value

                        spanTag.appendChild(strong)

                        if(key==="building" || key ==="size" || key ==='heating_of_dweeling') {
                            newNode = inputElement.parentNode
                            newNode.parentNode.insertBefore(spanTag, newNode.nextSibling)
                        } else {
                            inputElement.parentNode.insertBefore(spanTag, inputElement.nextSibling)
                        }

                    })

                }
            })
            //console.log('Apartment form saved')
        }
    </script>
@stop
