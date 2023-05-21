@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Moment', true)
@section('plugins.Datepicker', true)
@section('plugins.Inputmask', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('title', 'Apartment Show')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ucfirst($apartment->building->display_name)}} apart. #{{$apartment->number}} </h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.apartments', ['company' => $company])}}">Apartments</a></li>
                <li class="breadcrumb-item active">{{ucfirst($apartment->building->display_name)}} #{{$apartment->number}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
@php
    $heads = [
        ['label'=>''],
        'Type',
        'Assigned',
        'Removed',
        'Price',
        'Description',
        'Actions'
    ];
    $config = [
        'processing' => true,
        'serverSide' => true,
        'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.apartment.show', ['company'=>$company, 'apartment'=>$apartment])],
        'responsive'=> true,
        'order' => [[0,'asc']],
        'columns' => [['className'=>'details-control', 'searchable'=>false, 'orderable' => false, 'data'=>null, 'defaultContent'=>''],['data'=>'type'],['data'=>'assigned_at'], ['data'=>'removed_at'], ['data'=>'price'], ['data'=>'description'], ['data'=>'action', 'searchable'=>false, 'orderable' => false, 'no-export' => true,]],
        'dom'=>'<"row" <"col-sm-1 customButton"><"col-sm-5" B><"col-sm-6 d-flex justify-content-end" f> >
                <"row" <"col-12" tr> >
                <"row" <"col-sm-6 d-flex justify-content-start" i><"col-sm-6 d-flex justify-content-end" p> >',
    ]
@endphp
<x-adminlte-card title="Purple Card" theme="lightblue" icon="fas fa-lg fa-fan" removable collapsible>
    <div class="row">
        <div class="col-12 col-md-12 col-lg-8 order-2 order-md-1">
            <div class="row">
                <div class="col-12 col-sm-4">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-center text-muted">Estimated budget</span>
                            <span class="info-box-number text-center text-muted mb-0">2300</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-center text-muted">Total amount spent</span>
                            <span class="info-box-number text-center text-muted mb-0">2000</span>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="info-box bg-light">
                        <div class="info-box-content">
                            <span class="info-box-text text-center text-muted">Estimated project duration</span>
                            <span class="info-box-number text-center text-muted mb-0">20</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- DEUX -->
            <div class="row">
                <div class="col-12">
                    <x-adminlte-card title="Apartment accessories" icon="fas fa-lg fa-file-contract" removable collapsible>
                        <x-adminlte-datatable id="accessoriesapartTable" :heads="$heads" :config="$config" with-buttons/>
                    </x-adminlte-card>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-lg-4 order-1 order-md-2">
            <h3 class="text-primary"><i class="fas fa-paint-brush"></i> Description</h3>
            <p class="text-muted">{{$apartment->description}}</p>
            <br>
            <div class="text-muted">
                <p class="text-sm">Active lease
                    <b class="d-block">
                        @if($apartment->leases->isNotEmpty() && $apartment->leases->last()->isActive())
                        <b class="d-block"><a href="{{route('company.lease.show', ['company'=>$company, 'lease'=>$apartment->leases->last()])}}">{{$apartment->leases->last()->code}}</a></b>
                        @else
                            Free
                        @endif
                    </b>
                </p>
                <p class="text-sm">Project Leader
                    <b class="d-block">Tony Chicken</b>
                </p>
            </div>
            <h5 class="mt-5 text-muted">Project files</h5>
            <ul class="list-unstyled">
                <li>
                    <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Functional-requirements.docx</a>
                </li>
                <li>
                    <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-pdf"></i> UAT.pdf</a>
                </li>
                <li>
                    <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-envelope"></i> Email-from-flatbal.mln</a>
                </li>
                <li>
                    <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-image "></i> Logo.png</a>
                </li>
                <li>
                    <a href="" class="btn-link text-secondary"><i class="far fa-fw fa-file-word"></i> Contract-10_12_2014.docx</a>
                </li>
            </ul>
            <div class="text-center mt-5 mb-3">
                <a href="#" class="btn btn-sm btn-primary">Add files</a>
                <a href="#" class="btn btn-sm btn-warning">Report contact</a>
            </div>
        </div>
    </div>
</x-adminlte-card>
<x-adminlte-modal id="ApartmentAccessoryModal" title="Accessory" theme="lightblue" icon="fas fa-couch">
    <class class="row">
        <class class="col-md-12">
            <input type="hidden" name="accessory_id" id="accessory_id" value="">
            {{-- Manufaturer --}}
            <div class="form-group">
                <label for="accessory_manufacturer" class="text-lightblue">Manufaturer</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-industry"></i></span>
                    </div>
                    <input type="text" name="accessory_manufacturer" id="accessory_manufacturer" class="form-control" disabled>
                </div>
            </div>
            {{-- Serial --}}
            <div class="form-group">
                <label for="accessory_serial" class="text-lightblue">Serial</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-barcode"></i></span>
                    </div>
                    <input type="text" name="accessory_serial" id="accessory_serial" class="form-control" disabled>
                </div>
            </div>
            {{-- Assigned_at --}}
            <div class="form-group">
                <label for="accessory_assigned_at" class="text-lightblue">Assigned</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-calendar"></i></span>
                    </div>
                    <input type="text" name="accessory_assigned_at" id="accessory_assigned_at" class="form-control" disabled>
                </div>
            </div>
            {{-- Removed_at --}}
            <div class="form-group">
                <label for="accessory_removed_at" class="text-lightblue">Removed</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-calendar"></i></span>
                    </div>
                    <input type="text" class="form-control datetimepicker" name="accessory_removed_at" id="accessory_removed_at" data-target="#accessory_removed_at" data-toggle="datetimepicker">
                </div>
            </div>
            {{-- Price --}}
            <div class="form-group">
                <label for="accessory_price" class="text-lightblue">Price</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-hand-holding-usd"></i></span>
                    </div>
                    <input type="text" name="accessory_price" id="accessory_price" class="form-control inputmask" data-inputmask="'alias': 'currency', 'prefix': '$ ', 'rightAlign': 'true','placeholder': '0'">
                </div>
            </div>
            {{-- Description --}}
            <div class="form-group">
                <label for="accessory_price" class="text-lightblue">Description</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-file"></i></span>
                    </div>
                    <textarea class="form-control" name="accessory_description" id="accessory_description" rows="3"></textarea>
                </div>
            </div>
        </class>
    </class>
    <x-slot name="footerSlot">
        <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
        <x-adminlte-button theme="success" onclick="saveApartAccessory()" label="Save"/>
    </x-slot>
</x-adminlte-modal>
@stop

@section('css')
    <style>
        td.details-control {
            background: url('../resources/details_open.png') no-repeat center center;
            cursor: pointer;
        }
        tr.shown td.details-control {
            background: url('../resources/details_close.png') no-repeat center center;
        }
    </style>
@stop

@section('js')
    <script>
        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            // `d` is the original data object for the row
            return '<table class="table">'+
                '<tr>'+
                    '<td>Manufacturer:</td>'+
                    '<td>'+d.manufacturer+'</td>'+
                    '</tr>'+
                '<tr>'+
                    '<td>Serial:</td>'+
                    '<td>'+d.serial+'</td>'+
                    '</tr>'+
                '<tr>'+
                    '<td>Discontinued:</td>'+
                    '<td>'+d.discontinued_at+'</td>'+
                    '</tr>'+
                '</table>';
        }

        $(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(".customButton").html('@permission("lease-update") @if($apartment->leases->isNotEmpty() && $apartment->leases->last()->isActive())<div class="dt-buttons"><button type="button" class="btn btn-block btn-default addApartAccess"><i class="fas fa-lg fa-plus-square text-primary"></i></button></div>@endif @endpermission');

            $(".datetimepicker").datetimepicker({
                format: 'YYYY-MM-DD',
            });
            $('.inputmask').inputmask()

            $("#ApartmentAccessoryModal").on('hidden.bs.modal', function(){
                $(".invalid-feedback").remove()
                $("#accessory_id").val('')
                $("#accessory_manufacturer").val('')
                $("#accessory_manufacturer").removeClass('is-invalid')
                $("#accessory_serial").val('')
                $("#accessory_serial").removeClass('is-invalid')
                $("#accessory_assigned_at").val('')
                $("#accessory_assigned_at").removeClass('is-invalid')
                $("#accessory_removed_at").val('')
                $("#accessory_removed_at").removeClass('is-invalid')
                $("#accessory_price").val('')
                $("#accessory_price").removeClass('is-invalid')
                $("#accessory_description").val('')
                $("#accessory_description").removeClass('is-invalid')
            })


        })

            $('#accessoriesapartTable tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                //console.log(tr)

                var row = table.row(tr);

                if ( row.child.isShown() ) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                }
                else {
                    // Open this row
                    row.child( format(row.data()) ).show();
                    tr.addClass('shown');
                }

            });


        $("#accessoriesapartTable").on('click','.editApartAccessButton', function(){
            let apacc = $(this).val();
            //console.log(apacc);

            $.ajax({
                type: 'GET',
                url: "{{route('company.apartaccessory.edit',['company'=>$company])}}" ,
                cache: false,
                data: {
                    id_apartaccess: apacc
                },
                dataType: 'JSON',
                success: function (results) {
                    $("#accessory_id").val(results.accessory.id)
                    $("#accessory_manufacturer").val(results.accessory.manufacturer)
                    $("#accessory_serial").val(results.accessory.serial)
                    $("#accessory_assigned_at").val(results.accessory.assigned_at)
                    $("#accessory_removed_at").val(results.accessory.removed_at)
                    $("#accessory_price").val(results.accessory.price)
                    $("#accessory_description").val(results.accessory.description)
                    $("#ApartmentAccessoryModal").modal('show');
                    //console.log(results.accessory)
                }
            });
        })

        $("#accessoriesapartTable").on('click','.removeApartAccessButton', function(){
            let apacc = $(this).val();
            //console.log(apacc);

            Swal.fire({
                    title: 'Do you want to remove it!',
                    text: 'The remove date will be today',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, remove it!'
                }).then((result) => {
                        //if user clicks on delete
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'PUT',
                            url: "{{route('company.apartaccessory.remove',['company'=>$company])}}" ,
                            cache: false,
                            data: {
                                id_apartaccess: apacc
                            },
                            dataType: 'JSON',
                            success: function (results) {
                                if (results.success === true) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: results.message,
                                        showConfirmButton: false,
                                        timer: 2000
                                    }).then((result) => {
                                        if (result.dismiss === Swal.DismissReason.timer) {
                                            $("#accessoriesapartTable").DataTable().ajax.reload();
                                        }
                                    })
                                } else {
                                    swal.fire("Error!", results.message, "error");
                                }
                            }
                        });
                        //console.log('assign');
                    }
                });

        })

        function saveApartAccessory() {
            let id = $("#accessory_id").val()
            let assigned_at = $("#accessory_assigned_at").val()
            let removed_at = $("#accessory_removed_at").val()
            let price = $("#accessory_price").val()
            let description = $("#accessory_description").val()

            let amount = price.slice(2, price.length)

            amount = amount.replace(',','')
            console.log(id)

            $(".invalid-feedback").remove()
            $("#accessory_assigned_at").removeClass('is-invalid')
            $("#accessory_removed_at").removeClass('is-invalid')
            $("#accessory_price").removeClass('is-invalid')
            $("#accessory_description").removeClass('is-invalid')

            $.ajax({
                url:"{{route('company.apartaccessory.update', ['company'=>$company])}}",
                type: "POST",
                cache: false,
                data: {
                    id: id,
                    assigned_at: assigned_at,
                    removed_at: removed_at,
                    price: amount,
                    description: description
                },
                success: function(response) {
                    $("#ApartmentAccessoryModal").modal('hide')
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 4000
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            $("#accessoriesapartTable").DataTable().ajax.reload();
                        }
                    })
                },
                error: function(jsXHR, status, errors){
                    $.each(jsXHR.responseJSON.errors, function(key, value){
                        var inputElement = document.getElementById("accessory_"+key);
                        $("#accessory_"+key).addClass('is-invalid')

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
