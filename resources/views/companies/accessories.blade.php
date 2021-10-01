@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Moment', true)
@section('plugins.Datepicker', true)
@section('plugins.Inputmask', true)
@section('title', 'Accessories')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Accessories</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item active">Accessories</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
@php
$heads = [
    '#',
    'Type',
    'Manufacturer',
    'Model',
    'Serial',
    'Buy',
    'Active',
    ['label' => 'Actions', 'no-export' => true],
];
$config = [
    'processing' => true,
    'serverSide' => true,
    'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.accessories', ['company'=>$company])],
    'responsive'=> true,
    'order' => [[0,'asc']],
    'columns' => [['data'=>'DT_RowIndex'], ['data'=>'type'], ['data'=>'manufacturer'], ['data'=>'model'], ['data'=>'serial'], ['data'=>'buy_at'], ['data'=>'discontinued_at'],['data'=>'action', 'searchable'=>false, 'orderable' => false]],
    'dom'=>'<"row" <"col-sm-1 customButton"><"col-sm-5" B><"col-sm-6 d-flex justify-content-end" f> >
            <"row" <"col-12" tr> >
            <"row" <"col-sm-6 d-flex justify-content-start" i><"col-sm-6 d-flex justify-content-end" p> >',
]
@endphp
<x-adminlte-card title="Accessories" theme="lightblue" icon="fas fa-lg fa-chair" removable collapsible>
    <x-adminlte-datatable id="accessories" :heads="$heads" :config="$config" with-buttons/>
</x-adminlte-card>
{{-- Create/Edit Accessory--}}
<x-adminlte-modal id="accessoryModal" title="Accessory" theme="lightblue" icon="fas fa-chair">
    <div class="row">
        <div class="col-sm-12">
            {{-- accessory id --}}
            <input name="accessory_id" id="accessory_id" type="hidden" value="">
            {{-- type --}}
            <div class="form-group">
                <label for="accessory_type" class="text-lightblue">Appliance/Furniture</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-list"></i></span>
                    </div>
                    <select class="form-control select2" name="accessory_type" id="accessory_type" style="width: 85%" data-placeholder="Select appliance or furniture" data-allow-clear="true">
                        <option></option>
                        @foreach ($accessory_types as $key=>$value)
                            <option value="{{$key}}">{{$value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {{-- Manufacturer --}}
            <div class="form-group">
                <label for="accessory_manufacturer" class="text-lightblue">Manufacturer</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-barcode"></i></span>
                    </div>
                    <input type="text" name="accessory_manufacturer" id="accessory_manufacturer" class="form-control" placeholder="Enter the manufacturer">
                </div>
            </div>
            {{-- Model --}}
            <div class="form-group">
                <label for="accessory_model" class="text-lightblue">Model</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-barcode"></i></span>
                    </div>
                    <input type="text" name="accessory_model" id="accessory_model" class="form-control" placeholder="Enter the model">
                </div>
            </div>
            {{-- Serial --}}
            <div class="form-group">
                <label for="accessory_serial" class="text-lightblue">Serial number</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-barcode"></i></span>
                    </div>
                    <input type="text" name="accessory_serial" id="accessory_serial" class="form-control" placeholder="Enter the serial number">
                </div>
            </div>
            {{-- Buy at --}}
            <div class="form-group">
                <label for="accessory_buy_at" class="text-lightblue">Buy At</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">
                            <i class="fas fa-fw fa-calendar-check text-lightblue"></i>
                        </div>
                    </div>
                    <input id="accessory_buy_at" name="accessory_buy_at" data-target="#accessory_buy_at" data-toggle="datetimepicker"  class="form-control datetimepicker" placeholder="Choose a start date">
                </div>
            </div>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
        <x-adminlte-button theme="success" onclick="saveForm()" label="Save"/>
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

        $(".customButton").html('@permission("accessory-create")<div class="dt-buttons"><button type="button" class="btn btn-block btn-default addAccessory"><i class="fas fa-lg fa-plus-square text-primary"></i> New</button></div>@endpermission');

        $('.select2').each(function() {
            $(this).select2({
                width: 'resolve',
                theme: 'bootstrap4',
                dropdownParent: $(this).parent()
            });
        })

        $("#accessory_buy_at").datetimepicker({
            format: 'YYYY-MM-DD',
        });

        $(".addAccessory").on("click", function(){
            $("#accessoryModal").modal('show');
        })

        $("#accessoryModal").on('hidden.bs.modal', function(){
            $(".invalid-feedback").remove()
            $("#accessory_id").val('')
            $("#accessory_type").val(null).trigger('change')
            $("#accessory_type").removeClass('is-invalid')
            $("#accessory_manufacturer").val('')
            $("#accessory_manufacturer").removeClass('is-invalid')
            $("#accessory_model").val('')
            $("#accessory_model").removeClass('is-invalid')
            $("#accessory_serial").val('')
            $("#accessory_serial").removeClass('is-invalid')
            $("#accessory_buy_at").datetimepicker('clear');
            $("#accessory_buy_at").removeClass('is-invalid')
        })
    })

    $("#accessories").on('click', '.editAccessoryButton', function(){
        var accessory_id = $(this).val()
        $.ajax({
                url:"{{route('company.accessory.edit', ['company'=>$company, 'accessory'=>$accessory])}}",
                type: "GET",
                cache: false,
                data: {
                    accessory_id:accessory_id
                },
                success: function(response) {
                    //console.log(response.building)
                    $("#accessory_id").val(response.accessory.id)
                    $.each(response.accessory.team_settings, function(key, value){
                        $("#accessory_type").val(value.id).trigger('change')
                    })
                    $("#accessory_manufacturer").val(response.accessory.manufacturer)
                    $("#accessory_model").val(response.accessory.model)
                    $("#accessory_serial").val(response.accessory.serial)
                    $("#accessory_buy_at").val(moment(response.accessory.buy_at).format('YYYY-MM-DD'))

                    $("#accessoryModal").modal('show');
                },
                error: function(jsXHR, status, error){
                    console.log(jsXHR)
                }
            })
    })
    function saveForm() {
        var accessory_id = $('#accessory_id').val()
        var accessory_type = $("#accessory_type").val()
        var accessory_manufacturer = $("#accessory_manufacturer").val()
        var accessory_model = $("#accessory_model").val()
        var accessory_serial = $("#accessory_serial").val()
        var accessory_buy_at = $("#accessory_buy_at").val()
        $(".invalid-feedback").remove()
        $("#accessory_type").removeClass('is-invalid')
        $("#accessory_manufacturer").removeClass('is-invalid')
        $("#accessory_model").removeClass('is-invalid')
        $("#accessory_serial").removeClass('is-invalid')
        $("#accessory_buy_at").removeClass('is-invalid')
        $.ajax({
            url:"{{route('company.accessories.store', ['company'=>$company])}}",
            type: "POST",
            cache: false,
            data: {
                id:accessory_id,
                type: accessory_type,
                manufacturer: accessory_manufacturer,
                model:accessory_model,
                serial: accessory_serial,
                buy_at:accessory_buy_at,
            },
            success: function(response) {
                $("#accessoryModal").modal('hide');
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 2000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $("#accessories").DataTable().ajax.reload();
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
                    if(key==="type") {
                        newNode = inputElement.parentNode
                        newNode.parentNode.insertBefore(spanTag, newNode.nextSibling)
                    } else {
                        inputElement.parentNode.insertBefore(spanTag, inputElement.nextSibling)
                    }
                })
            }
        })
        //Console.log("Accessory saved")
    }
    </script>
@stop
