@extends('adminlte::page')
@section('plugins.Moment', true)
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datepicker', true)
@section('plugins.DateRangePicker', true)
@section('plugins.Inputmask', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('title', 'Bills')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Bills</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item active">Invoices</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
@php
    $heads = [
        'Number',
        'Lease',
        'Tenant',
        'Period',
        'Month',
        'Year',
        'Total',
        'Date',
        'Payment until',
        'Status',
        'Actions'
    ];
    $config = [
        'processing' => true,
        'serverSide' => true,
        'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.invoices', ['company'=>$company])],
        'responsive'=> true,
        'order' => [[0,'asc']],
        'columns' => [['data'=>'number'],  ['data'=>'lease'], ['data'=>'tenant'],['data'=>'period'], ['data'=>'month'], ['data'=>'year'], ['data'=>'total_amount'], ['data'=>'created_at'],['data'=>'payment_due_date'] , ['data'=>'status'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]],
        'dom'=>'<"row" <"col-sm-1 customButton"><"col-sm-5" B><"col-sm-6 d-flex justify-content-end" f> >
                <"row" <"col-12" tr> >
                <"row" <"col-sm-6 d-flex justify-content-start" i><"col-sm-6 d-flex justify-content-end" p> >',
    ]
@endphp
<div class="row">
    <div class="col-md-12">
        <x-adminlte-card title="Bills" theme="lightblue" icon="fas fa-lg fa-file-contract" removable collapsible>
            <x-adminlte-datatable id="billsTable" :heads="$heads" :config="$config" with-buttons/>
        </x-adminlte-card>
    </div>
</div>
<x-adminlte-modal id="paymentModal" title="Bill payment" theme="lightblue" icon="fas fa-file-invoice-dollar">
    <div class="row">
        <div class="col-md-12">
            {{-- Bill number --}}
            <div class="form-group">
                <label for="address_number" class="text-lightblue">Bill number</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-file-invoice"></i></span>
                    </div>
                    <input type="text" name="payment_bill_number" id="payment_bill_number" class="form-control" disabled>
                    <input type="hidden" name="bill_id" id="bill_id">
                    <input type="hidden" name="payment_created_at" id="payment_created_at">
                </div>
            </div>
            {{-- Payer --}}
            <div class="form-group">
                <label for="payer_name" class="text-lightblue">Payer</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user-alt"></i></span>
                    </div>
                    <select class="form-control select2" name="payment_payer_name" id="payment_payer_name" style="width: 85%" data-placeholder="Select payer" data-allow-clear="true">
                        <option></option>
                    </select>
                </div>
            </div>
            {{-- Payer Email --}}
            <div class="form-group">
                <label for="payer_email" class="text-lightblue">Payer email</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-at"></i></span>
                    </div>
                    <input type="text" name="payment_payer_email" id="payment_payer_email" class="form-control" placeholder="Payer email">
                </div>
            </div>
            {{-- Payment date--}}
            <div class="form-group">
                <label for="payment_at" class="text-lightblue">Payment date</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-calendar-alt"></i></span>
                    </div>
                    <input id="payment_at" name="payment_at" data-target="#payment_at" data-toggle="datetimepicker"  class="form-control datetimepicker" placeholder="Choose a date...">
                </div>
            </div>
            {{-- Payment amount --}}
            <div class="form-group">
                <label for="payment_amount" class="text-lightblue">Payment amount</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-money-bill-alt"></i></span>
                    </div>
                    <input type="text" name="payment_amount" id="payment_amount" class="form-control rent-amount" data-inputmask="'alias': 'currency', 'prefix': '$ ', 'placeholder': '0'" placeholder="Amount">
                </div>
            </div>
            {{-- Payment method --}}
            <div class="form-group">
                <label for="payment_method" class="text-lightblue">Payment method</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-cash-register"></i></span>
                    </div>
                    <select class="form-control select2" name="payment_method" id="payment_method" style="width: 85%" data-placeholder="Select payment method" data-allow-clear="true">
                        <option></option>
                        <option value="credit">Credit card</option>
                        <option value="cheque">Cheque</option>
                        <option value="cash">Cash</option>
                    </select>
                </div>
            </div>
            {{-- payment_method_num --}}
            <div class="form-group" id="myDiv">
                <label for="payment_method_num" class="text-lightblue">Number</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-hashtag"></i></span>
                    </div>
                    <input type="text" name="payment_method_num" id="payment_method_num" class="form-control" placeholder="Payment number">
                </div>
            </div>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
        <x-adminlte-button theme="success" onclick="savePaymentForm()" label="Pay"/>
    </x-slot>
</x-adminlte-modal>

{{-- Themed --}}
<x-adminlte-modal id="billModal" title="Create Invoice" theme="lightblue" icon="fas fa-bolt">
    <div class="row">
        <div class="col-md-6">
            {{-- Bill Code --}}
            <div class="form-group">
                <label for="payer_name" class="text-lightblue">Leases</label>
                <div class="input-group">
                    <select class="form-control select2" name="bill_code" id="bill_code" style="width: 100%" data-placeholder="All Leases" data-allow-clear="true">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            {{-- Bill period --}}
            <div class="form-group">
                <label for="bill_period" class="text-lightblue">Period</label>
                <div class="input-group">
                    <input type="text" name="bill_period" id="bill_period" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <x-slot name="footerSlot">
        <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
        <x-adminlte-button theme="success" onclick="generateBills()" label="Generate"/>
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

        $(".customButton").html('@permission("bill-create")<div class="dt-buttons"><button type="button" class="btn btn-block btn-default addBill"><i class="fas fa-lg fa-plus-square text-primary"></i> New</button></div>@endpermission');

        $('.select2').each(function() {
            $(this).select2({
                width: 'resolve',
                theme: 'bootstrap4',
                dropdownParent: $(this).parent()
            });
        })

        $('.rent-amount').inputmask();

        $(".datetimepicker").datetimepicker({
            format: 'YYYY-MM-DD',
        });

        $("#bill_period").daterangepicker({
            startDate:moment().subtract(30, 'days'),
            endDate:moment(),
            locale: {
              format: 'YYYY-M-DD',
              separator: ' / '
            }
        })

        $(".addBill").on("click", function(event){

            $.ajax({
                    url:"{{route('company.lease.getLeasesCode', ['company'=>$company])}}",
                    type: "POST",
                    cache: false,

                    success: function(response) {
                        //console.log(response.leases)

                        //console.log(response.tenants[0].bill_num)
                        $('#bill_code').select2('destroy');
                        $('#bill_code').empty()
                        $('#bill_code').prepend("<option value=''></option>")
                        $.each(response.leases, function(key, value){
                            //console.log(key)

                            if ($('#bill_code').find('option[value="' + key + '"]').length) {
                                $('#bill_code').val(key).trigger('change');
                            } else {
                                // Create a DOM Option and pre-select by default
                                var newOption = new Option(value, key, false, false);
                                // Append it to the select
                                $('#bill_code').append(newOption).trigger('change');
                            }

                        })

                        $('#bill_code').select2({
                            width: 'resolve',
                            theme: 'bootstrap4',
                            dropdownParent: $('#bill_code').parent()
                        });

                        $("#billModal").modal('show');
                    },
                    error: function(jsXHR, status, error){
                        console.log(jsXHR)
                    }
                })
        })

        $("#paymentModal").on('hidden.bs.modal', function(){
            $("#bill_id").val('')
            $("#payment_created_at").val('')
            $("#payment_bill_number").val('')
            $("#payment_bill_number").removeClass('is-invalid')
            $("#payment_payer_name").val('')
            $("#payment_payer_name").removeClass('is-invalid')
            $("#payment_payer_email").val(null).trigger('change')
            $("#payment_payer_email").removeClass('is-invalid')
            $("#payment_at").val('')
            $("#payment_at").removeClass('is-invalid')
            $("#payment_amount").val('')
            $("#payment_amount").removeClass('is-invalid')
            $("#payment_method").val(null).trigger('change')
            $("#payment_method").removeClass('is-invalid')
            $("#payment_method_num").val('')
            $("#payment_method_num").removeClass('is-invalid')
            $(".invalid-feedback").remove()
        })

        $("#payment_method").on('select2:select', function(){
            var payment_method = $(this).val()
            let val = "block"
            let origin = "none"

            if (payment_method) {
                if (payment_method=="credit") {
                    //console.log("Credit")
                    val = "block"
                    origin = "credit"
                } else if (payment_method=="cheque") {
                    //console.log("Cheque")
                    val = "block"
                    origin = "cheque"
                } else {
                    val = "none"
                    origin = "cash"
                    //console.log("Cash")
                }
                myFunction(val, origin)

            }


        })

        $("#billsTable").on('click', '.makePaymentButton', function(){
            var bill_id = $(this).val()
            let count = 0

            $.ajax({
                    url:"{{route('company.invoice.gettenant', ['company'=>$company])}}",
                    type: "POST",
                    cache: false,
                    data: {
                        bill_id:bill_id
                    },
                    success: function(response) {
                        //console.log(response.tenants[0].bill_num)
                        $('#payment_payer_name').select2('destroy');
                        $('#payment_payer_name').empty()
                        $('#payment_payer_name').prepend("<option value=''></option>")
                        $.each(response.tenants, function(key, value){
                            //console.log(key)

                            if ($('#payment_payer_name').find('option[value="' + key + '"]').length) {
                                $('#payment_payer_name').val(key).trigger('change');
                            } else {
                                // Create a DOM Option and pre-select by default
                                if (count==0) {
                                    var newOption = new Option(value.name, key, true, true);
                                    $("#bill_id").val(value.bill_id)
                                    $("#payment_created_at").val(value.created_at)
                                    $("#payment_payer_email").val(value.email)
                                    $("#payment_bill_number").val(value.bill_num)
                                    $("#payment_amount").val(value.total_amount)
                                    $("#payment_at").val(value.payment_at)
                                } else {
                                    var newOption = new Option(value.name, key, false, false);
                                }
                                count++
                                // Append it to the select
                                $('#payment_payer_name').append(newOption).trigger('change');
                            }
                        })
                        $('#payment_payer_name').select2({
                            width: 'resolve',
                            theme: 'bootstrap4',
                            dropdownParent: $('#payer_name').parent()
                        });

                        $("#paymentModal").modal('show');
                    },
                    error: function(jsXHR, status, error){
                        console.log(jsXHR)
                    }
                })

        })

        function myFunction(val, origin) {
            var x = document.getElementById("myDiv");
            x.style.display = val;

        }


    })

    function savePaymentForm(){

        var bill_id = $("#bill_id").val()
        var payment_created_at = $("#payment_created_at").val()
        var payer_email = $("#payment_payer_email").val()
        var payment_at = $("#payment_at").val()
        var payment_amount = $("#payment_amount").val()
        var payment_method = $("#payment_method").val()
        var payment_method_num = $("#payment_method_num").val()
        payment_amount = payment_amount.slice(2, payment_amount.length)
        payment_amount = payment_amount.replace(/\,/g,'')

        $("#payment_bill_number").removeClass('is-invalid')
        $("#payment_payer_email").removeClass('is-invalid')
        $("#payment_at").removeClass('is-invalid')
        $("#payment_amount").removeClass('is-invalid')
        $("#payment_method_num").removeClass('is-invalid')
        $(".invalid-feedback").remove()

        console.log(payment_amount)

        $.ajax({
            url:"{{route('company.payment.store', ['company'=>$company])}}",
            type: "POST",
            cache: false,
            data: {
                bill_id:bill_id,
                payer_email: payer_email,
                amount: payment_amount,
                method:payment_method,
                method_num:payment_method_num,
                at: payment_at,
                created_at:payment_created_at
            },
            success: function(response) {
                $("#paymentModal").modal('hide')
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 4000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $("#billsTable").DataTable().ajax.reload();
                    }
                })
            },
            error: function(jsXHR, status, errors){
                $.each(jsXHR.responseJSON.errors, function(key, value){
                    var inputElement = document.getElementById("payment_"+key);
                    $("#payment_"+key).addClass('is-invalid')

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

    function generateBills(){
        var lease_code = $("#bill_code").val()
        var bill_period = $("#bill_period").val()
        let dates = bill_period.split(' / ')

        $("#billModal").modal('hide')

        //console.log(dates[0])

        $.ajax({
            url:"{{route('company.invoice.create', ['company'=>$company])}}",
            type: "PUT",
            cache: false,
            data: {
                lease_code:lease_code,
                start_date: dates[0],
                end_date: dates[1]
            },
            success: function(response) {
                $("#paymentModal").modal('hide')
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: response.message,
                    showConfirmButton: false,
                    timer: 4000
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $("#billsTable").DataTable().ajax.reload();
                    }
                })
            },
            error: function(jsXHR, status, errors){
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'ERROR',
                    showConfirmButton: false,
                    timer: 4000
                })
            }
        })
    }
    </script>
@stop
