@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Inputmask', true)
@section('title', 'Buildings')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Buildings</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item active">Buildings</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
    @php
    $heads = [
        '#',
        'Lot',
        'Name',
        'Address',
        '# Apart',
        '# Depend',
        'Tenants',
        ['label' => 'Actions', 'no-export' => true],
    ];
    $config = [
        'processing' => true,
        'serverSide' => true,
        'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.buildings', ['company'=>$company])],
        'responsive'=> true,
        'order' => [[0,'asc']],
        'columns' => [['data'=>'DT_RowIndex'], ['data'=>'lot'], ['data'=>'display_name'], ['data'=>'address'], ['data'=>'apart_count'], ['data'=>'depend_count'], ['data'=>'tenant_count'],['data'=>'action', 'searchable'=>false, 'orderable' => false]],
        'dom'=>'<"row" <"col-sm-1 customButton"><"col-sm-5" B><"col-sm-6 d-flex justify-content-end" f> >
                <"row" <"col-12" tr> >
                <"row" <"col-sm-6 d-flex justify-content-start" i><"col-sm-6 d-flex justify-content-end" p> >',
    ]
    @endphp
    <x-adminlte-card title="Buildings" theme="lightblue" icon="fas fa-lg fa-building" removable collapsible>
        <x-adminlte-datatable id="buildings" :heads="$heads" :config="$config" with-buttons/>
    </x-adminlte-card>
    {{-- Create/Edit Building --}}
    <x-adminlte-modal id="buildingModal" title="Building" theme="lightblue" icon="fas fa-building" static-backdrop>
        <div class="row">
            <input type="hidden" id="building_id" value="">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="text-lightblue" for="building_lot">Lot</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-lightblue"><i class="fas fa-fw fa-landmark"></i></div>
                        </div>
                        <input type="text" name="building_lot" id="building_lot" class="form-control" placeholder="Enter the building lot">
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="text-lightblue" for="name">Name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text text-lightblue"><i class="fas fa-fw fa-building"></i></div>
                        </div>
                        <input type="text" name="building_display_name" id="building_display_name" class="form-control" placeholder="Enter the building name">
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="building_description" class="text-lightblue">
                        Description
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fas fa-fw fa-file-alt text-lightblue"></i>
                            </div>
                        </div>
                        <textarea id="building_description" name="building_description" class="form-control" rows="3" placeholder="Insert description..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
            <x-adminlte-button theme="success" onclick="saveForm()" label="Accept"/>
        </x-slot>
    </x-adminlte-modal>
    {{-- Edit Building Address--}}
    <x-adminlte-modal id="buildingAddressModal" title="Address" theme="lightblue" icon="fas fa-map-marker-alt">
        <div class="row">
            <div class="col-md-12">
                <input type="hidden" name="address_id", id="address_id", value="">
                {{-- Number --}}
                <div class="form-group">
                    <label for="address_number" class="text-lightblue">Building number</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-building"></i></span>
                        </div>
                        <input type="text" name="address_number" id="address_number" class="form-control" placeholder="Enter number">
                    </div>
                </div>
                {{-- Street --}}
                <div class="form-group">
                    <label for="address_street" class="text-lightblue">Street</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-road"></i></span>
                        </div>
                        <input type="text" name="address_street" id="address_street" class="form-control" placeholder="Enter the street">
                    </div>
                </div>
                {{-- City --}}
                <div class="form-group">
                    <label for="address_city" class="text-lightblue">City</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map-marked-alt"></i></span>
                        </div>
                        <select class="form-control select2" name="address_city" id="address_city" style="width: 85%" data-placeholder="Select city" data-allow-clear="true">
                            <option></option>
                        </select>
                    </div>
                </div>

                {{-- Region --}}
                <div class="form-group">
                    <label for="address_region" class="text-lightblue">Region</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map-marked-alt"></i></span>
                        </div>
                        <input type="text" name="address_region" id="address_region" class="form-control" placeholder="Enter the region" disabled>
                    </div>
                </div>
                {{-- Country --}}
                <div class="form-group">
                    <label class="text-lightblue" for="address_country">Country</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map-marked-alt"></i></span>
                        </div>
                        <select class="form-control select2" name="address_country" id="address_country" data-placeholder="Enter your country" data-allow-clear="true" style="width: 85%">
                            <option></option>
                        </select>
                    </div>
                </div>
                {{-- Postal/Zip Code --}}
                <div class="form-group">
                    <label for="address_postcode" class="text-lightblue">Postal/Zip Code</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-map"></i></span>
                        </div>
                        <input type="text" name="address_postcode" id="address_postcode" class="form-control" placeholder="Enter the postal/zip code">
                    </div>
                </div>
            </div>
        </div>
        <x-slot name="footerSlot">
            <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
            <x-adminlte-button theme="success" onclick="saveAddressForm()" label="Save"/>
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

        $(".customButton").html('@permission("building-create")<div class="dt-buttons"><button type="button" class="btn btn-block btn-default addBuilding"><i class="fas fa-lg fa-plus-square text-primary"></i> New</button></div>@endpermission');

        $('.select2').each(function() {
            $(this).select2({
                width: 'resolve',
                theme: 'bootstrap4',
                dropdownParent: $(this).parent()
            });
        })

        $("#building_lot").inputmask({
            mask: "9 999 999",
            //placeholder: "*",
            showMaskOnHover: true,
            showMaskOnFocus: false,
        })

        $(".addBuilding").on("click", function(event){
            event.preventDefault();
            $("#buildingModal").modal('show');
        })

        $("#buildingModal").on('hidden.bs.modal', function(){
            $(".invalid-feedback").remove()
            $("#building_id").val('')
            $("#building_lot").val('')
            $("#building_lot").removeClass('is-invalid')
            $("#building_display_name").val('')
            $("#building_display_name").removeClass('is-invalid')
            $("#building_description").val('')
            $("#building_description").removeClass('is-invalid')
        })


        $("#buildingAddressModal").on('hidden.bs.modal', function(){
            $("#address_id").val('')
            $("#address_number").val('')
            $("#address_number").removeClass('is-invalid')
            $("#address_street").val('')
            $("#address_street").removeClass('is-invalid')
            $("#address_city").val(null).trigger('change')
            $("#address_city").removeClass('is-invalid')
            $("#address_region").val('')
            $("#address_region").removeClass('is-invalid')
            $("#address_country").val(null).trigger('change')
            $("#address_country").removeClass('is-invalid')
            $("#address_postcode").val('')
            $("#address_postcode").removeClass('is-invalid')
            $(".invalid-feedback").remove()
        })

        $("#address_country").on('select2:select', function(){
            var country = $(this).val()
            $.ajax({
                url:"{{route('address.getCities')}}",
                type: "GET",
                cache: false,
                data: {
                    address_country:country
                },
                success: function(response) {

                    $('#address_city').select2('destroy');
                    $('#address_city').empty()
                    $('#address_city').prepend("<option value=''></option>")

                    $.each(response.cities, function(key, value){
                        // Set the value, creating a new option if necessary
                        if ($('#address_city').find('option[value="' + key + '"]').length) {
                            $('#address_city').val(key).trigger('change');
                        } else {
                            // Create a DOM Option and pre-select by default
                            var newOption = new Option(value, key, false, false);

                            // Append it to the select
                            $('#address_city').append(newOption).trigger('change');
                        }
                    })

                    $('#address_city').select2({
                        width: 'resolve',
                        theme: 'bootstrap4',
                        dropdownParent: $('#address_city').parent()
                    });

                    $('#address_region').val('')
                    //console.log(cities_tag)

                },
                error: function(jsXHR, status, error){
                    console.log(jsXHR)
                }
            })
            //console.log("Country "+country+" selected");
        })

        $("#address_city").on('select2:select', function(){
            var country = $("#address_country").val()
            var city = $("#address_city").val()
            $.ajax({
                url:"{{route('address.getRegion')}}",
                type: "GET",
                cache: false,
                data: {
                    address_country:country,
                    address_city: city
                },
                success: function(response) {

                    $('#address_region').val(response.address_region);


                },
                error: function(jsXHR, status, error){
                    console.log(jsXHR)
                }
            })

        })

    })

    $("#buildings").on('click', '.editBuildingButton', function(){
        var building_id = $(this).val()
        $.ajax({
                url:"{{route('company.building.edit', ['company'=>$company, 'building'=>$building])}}",
                type: "GET",
                cache: false,
                data: {
                    building_id:building_id
                },
                success: function(response) {
                    //console.log(response.building)
                    $("#building_id").val(response.building.id)
                    $("#building_lot").val(response.building.lot)
                    $("#building_display_name").val(response.building.display_name)
                    $("#building_description").val(response.building.description)

                    $("#buildingModal").modal('show');
                },
                error: function(jsXHR, status, error){
                    console.log(jsXHR)
                }
            })
    })

    $("#buildings").on('click', '.editAddressButton', function(){
        var building_id = $(this).val()
        $.ajax({
                url:"{{route('company.building.getAddress', ['company'=>$company, 'building'=>$building])}}",
                type: "GET",
                cache: false,
                data: {
                    building_id:building_id
                },
                success: function(response) {
                    //console.log(response.countries)
                    $("#address_id").val(response.address.id)
                    $("#address_number").val(response.address.number)
                    $("#address_street").val(response.address.street)

                    $('#address_city').select2('destroy');
                    $('#address_city').empty()
                    $('#address_city').prepend("<option value=''></option>")
                    $.each(response.cities, function(key, value){
                        // Set the value, creating a new option if necessary
                        if ($('#address_city').find('option[value="' + key + '"]').length) {
                            $('#address_city').val(key).trigger('change');
                        } else {
                            // Create a DOM Option and pre-select by default
                            var newOption = new Option(value, key, false, false);

                            // Append it to the select
                            $('#address_city').append(newOption).trigger('change');
                        }
                    })

                    if (response.address.city!=null) {
                        if ($('#address_city').find('option[value="' + response.address.city + '"]').length) {
                            $('#address_city').val(response.address.city).trigger('change');
                        }
                    }

                    $('#address_city').select2({
                        width: 'resolve',
                        theme: 'bootstrap4',
                        dropdownParent: $('#address_city').parent()
                    });

                    $("#address_region").val(response.address.region)

                    $.each(response.countries, function(key, value){
                        // Set the value, creating a new option if necessary
                        if ($('#address_country').find('option[value="' + key + '"]').length) {
                            $('#address_country').val(key).trigger('change');
                        } else {
                            // Create a DOM Option and pre-select by default
                            var newOption = new Option(value, key, false, false);

                            // Append it to the select
                            $('#address_country').append(newOption).trigger('change');
                        }
                    })

                    if (response.address.country==null) {
                        if ($('#address_country').find('option[value="CAN"]').length) {
                            $('#address_country').val('CAN').trigger('change');
                        }
                    } else {
                        if ($('#address_country').find('option[value="' + response.address.country + '"]').length) {
                            $('#address_country').val(response.address.country).trigger('change');
                        }
                    }

                    $("#address_postcode").val(response.address.postcode)

                    $("#buildingAddressModal").modal('show');
                },
                error: function(jsXHR, status, error){
                    console.log(jsXHR)
                }
            })
    })

    function saveForm() {
        var building_id = $("#building_id").val()
        var lot = $("#building_lot").val()
        var display_name = $("#building_display_name").val()
        var description = $("#building_description").val()

        $(".invalid-feedback").remove()
        $("#building_lot").removeClass('is-invalid')
        $("#building_display_name").removeClass('is-invalid')
        $("#building_description").removeClass('is-invalid')

        $.ajax({
            url:"{{route('company.building.store', ['company'=>$company])}}",
            type: "POST",
            cache: false,
            data: {
                building_id:building_id,
                lot: lot,
                display_name: display_name,
                description: description,
            },
            success: function(response) {
                $("#buildingModal").modal('hide')
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $("#buildings").DataTable().ajax.reload();
                    }
                })
            },
            error: function(jsXHR, status, errors){
                $.each(jsXHR.responseJSON.errors, function(key, value){
                    var inputElement = document.getElementById("building_"+key);
                    $("#building_"+key).addClass('is-invalid')

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

    function saveAddressForm(){
        var address_id = $("#address_id").val()
        var address_number = $("#address_number").val()
        var address_street = $("#address_street").val()
        var address_city = $("#address_city").val()
        var address_region = $("#address_region").val()
        var address_country = $("#address_country").val()
        var address_postcode = $("#address_postcode").val()

        let _url = '/address/'+address_id

        $.ajax({
            url:_url,
            type: "PUT",
            cache: false,
            data: {
                number:address_number,
                street:address_street,
                city: address_city,
                region:address_region,
                country:address_country,
                postcode:address_postcode,
            },
            success: function(response) {
                $("#buildingAddressModal").modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $("#buildings").DataTable().ajax.reload();
                    }
                })
            },
            error: function(jsXHR, status, errors){
                $.each(jsXHR.responseJSON.errors, function(key, value){
                    var inputElement = document.getElementById("address_"+key);
                    $("#address_"+key).addClass('is-invalid')

                    var spanTag = document.createElement("span");
                    spanTag.classList.add('invalid-feedback')
                    spanTag.classList.add('d-block')
                    spanTag.setAttribute('role', 'alert')

                    var strong = document.createElement("strong")
                    strong.innerHTML=value
                    spanTag.appendChild(strong)
                    if (key==="city" || key ==="country") {
                        newNode = inputElement.parentNode
                        newNode.parentNode.insertBefore(spanTag, newNode.nextSibling)
                    } else {
                        inputElement.parentNode.insertBefore(spanTag, inputElement.nextSibling)
                    }

                })

            }
        })
    }
    </script>
@stop
