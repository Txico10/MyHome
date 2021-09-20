@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.Moment', true)
@section('plugins.Datepicker', true)
@section('plugins.Inputmask', true)
@section('plugins.iCheck', true)
@section('title', 'Employee Show')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Employees</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.employees', ['company' => $company])}}">Employees</a></li>
                <li class="breadcrumb-item active">{{$employee->name}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Employee profile -->
        <div class="card card-widget widget-user-2">
            <div class="widget-user-header bg-gradient-lightblue" style="">
                <div class="widget-user-image">
                    <img class="img-circle elevation-2" src="{{!empty($employee->photo)? asset('storage/images/profile/users/'.$employee->photo) :'https://picsum.photos/id/1/100'}}" alt="User avatar:{{$employee->name}}">
                </div>
                <h3 class="widget-user-username mb-0">{{$employee->name}}</h3>
                <h5 class="widget-user-desc">{{$role_name}}</h5>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class="p-0 col-12 mr-1 border-bottom">
                        <span class="nav-link">
                            <i class="fas fa-fw fa-venus-mars"></i>
                            Sex
                            <span class="float-right">
                                {{Str::ucfirst($employee->gender) }}
                            </span>
                        </span>
                    </div>
                    <div class="p-0 col-12 mr-1 border-bottom">
                        <span class="nav-link">
                            <i class="fas fa-fw fa-birthday-cake"></i>
                            Birthdate
                            <span class="float-right">
                                {{$employee->birthdate->format('d F Y')}}
                            </span>
                        </span>
                    </div>
                    <div class="p-0 col-12 mr-1 border-bottom">
                        <span class="nav-link">
                            <i class="fas fa-fw fa-user-check"></i>
                            Status
                            <span class="float-right">
                                {{$employee->active?"Active":"Inactive"}}
                            </span>
                        </span>
                    </div>
                    <div class="p-0 col-12 mr-1 border-bottom">
                        <span class="nav-link">
                            <i class="fas fa-fw fa-envelope"></i>
                            Email
                            <span class="float-right">
                                {{$employee->email}}
                            </span>
                        </span>
                    </div>
                    <div class="p-0 col-12 mr-1 mb-2">
                        <span class="nav-link">
                            <i class="fas fa-fw fa-hashtag"></i>SSN
                            <span class="float-right">{{$employee->ssn}}</span>
                        </span>
                    </div>
                    <button type="button" class="btn btn-primary btn-block">Edit profile </button>
                </div>
            </div>
        </div>
        <!--User contact-->
        <livewire:addresses :model="$employee" />
        <livewire:contacts :model="$employee" />
    </div>
    <div class="col-md-9">
        @php
            $heads = [
                '#',
                'Code',
                'Role',
                'Availability',
                'Start date',
                'End date',
                'Status',
                'Acceptance date',
                ['label' => 'Actions', 'no-export' => true],
            ];
            $config = [
                'processing' => true,
                'serverSide' => true,
                'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.employees.contract', ['company'=>$company, 'employee'=>$employee])],
                'responsive'=> true,
                'order' => [[0,'asc']],
                'columns' => [['data'=>'DT_RowIndex'], ['data'=>'code'], ['data'=>'role'], ['data'=>'availability'], ['data'=>'start_at'], ['data'=>'end_at'], ['data'=>'agreement_status'], ['data'=>'acceptance_at'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]],
                'dom'=>'<"row" <"col-sm-1 customButton"><"col-sm-5" B><"col-sm-6 d-flex justify-content-end" f> >
                        <"row" <"col-12" tr> >
                        <"row" <"col-sm-6 d-flex justify-content-start" i><"col-sm-6 d-flex justify-content-end" p> >',

            ]
        @endphp
        <x-adminlte-card title="Contracts" theme="lightblue" icon="fas fa-lg fa-file-contract" removable collapsible>
            <x-adminlte-datatable id="employeeContracts" :heads="$heads" :config="$config" with-buttons/>
        </x-adminlte-card>
        {{-- Themed --}}
        <x-adminlte-modal id="newContractModal" title="New Contract" size="lg" theme="lightblue" icon="fas fa-file-contract" static-backdrop scrollable>

            @livewire('employee-contract-form', ['company' => $company, 'employee'=>$employee])

            <x-slot name="footerSlot">
                <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
                <x-adminlte-button theme="success" onclick="saveForm()" label="Accept"/>
            </x-slot>
        </x-adminlte-modal>
        <x-adminlte-modal id="contractTerminationModal" title="Contract termination" theme="lightblue" icon="fas fa-file-contract" static-backdrop scrollable>
            <div class="row">
                <div class="col-md-12">
                    <h4 class="text-muted">Termination reason</h4>
                    <div class="form-group">
                        <input type="hidden" id="contract_id" name="contract_id" value="">
                    </div>
                    <div class="form-group clearfix">
                        @foreach ($contract_terminations as $contract_termination)
                            <div class="icheck-danger">
                                <input type="radio" id="{{$contract_termination->name}}" value="{{$contract_termination->id}}" name="{{$contract_termination->type}}" />
                                <label for="{{$contract_termination->name}}" class="text-lightblue">{{$contract_termination->display_name}} <a href="#" class="ml-1" title="{{$contract_termination->description}}"><i class="far fa-fw fa-question-circle"></i></a></label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <label class="text-lightblue">Description</label>
                        <textarea class="form-control" id="termination_description" rows="3" placeholder="Enter ..."></textarea>
                    </div>
                </div>
            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button class="mr-auto" theme="danger" label="Dismiss" data-dismiss="modal"/>
                <x-adminlte-button theme="success" onclick="saveContractTermination()" label="Accept"/>
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

            $(".customButton").html('@permission("contract-create")<div class="dt-buttons"><button type="button" class="btn btn-block btn-default addEmployeeContract"><i class="fas fa-lg fa-plus-square text-primary"></i> New</button></div>@endpermission');

            $("#newContractModal").on('hidden.bs.modal', function(){
                Livewire.emit('resetEmployeeContractInput')
            });
            $('.addEmployeeContract').on('click', function(){
                Livewire.emit('contractCheck')
            })
            $("#contractTerminationModal").on('hidden.bs.modal', function(){
                $(".invalid-feedback").remove()
                $("#termination_description").val('')
                $("input[name='contract_termination']").prop('checked', false);
            });
        })
        $("#employeeContracts").on("click", ".contractTerminate", function(event){
            $('#contract_id').val($(this).val())
            //console.log(this.dataset.agreement_status)
            if (this.dataset.agreement_status==="pending" || this.dataset.agreement_status==="published") {
                $("#excuded").prop('checked', true)
                $("input[name='contract_termination']").prop('disabled', true);
            } else {
                $("#excuded").prop('checked', false)
                $("input[name='contract_termination']").prop('disabled', false);
                $("#excuded").prop('disabled', true)
                test = $("input[name='contract_termination']")
                if (!test.filter(':checked').length) {
                    test[1].checked = true;
                }

            }
            $('#contractTerminationModal').modal('show');
        })


        $("#employeeContracts").on("click", ".contractPublish", function(event){
            event.preventDefault();
            var contract_id = $(this).val();
            Swal.fire({
                title: 'Are You Sure?',
                text: 'The contract will be send for signature!',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Send contract!'
            }).then((result) => {
                //if user clicks on delete
                if (result.isConfirmed) {
                  $.ajax({
                    url:"{{route('company.employees.contract.change-status', ['company'=>$company, 'employee'=>$employee])}}",
                    type: "POST",
                    data:{
                        contract_id:contract_id
                    },
                    dataType: "json",
                    cache: false,
                    success: function(response) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                //console.log('I was closed by the timer')
                                $("#employeeContracts").DataTable().ajax.reload();
                            }
                        })
                    },
                    error: function(response, textStatus){
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "Theres been un error",
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                  });
                }
            });
        });

        function saveForm( ) {
            Livewire.emit('saveContractForm')
        }
        function saveContractTermination() {
            var termination_id = $('input[name="contract_termination"]:checked').val()
            var termination_description = $('#termination_description').val()
            var contract_id = $('#contract_id').val()
            //console.log(termination_id+' - '+termination_description+' - '+contract_id);

            Swal.fire({
                title: 'Are You Sure?',
                text: 'The contract will be terminated!',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Terminate!'
            }).then((result) => {
                //if user clicks on delete
                if (result.isConfirmed) {
                  $.ajax({
                    url:"{{route('company.employees.contract.delete', ['company'=>$company, 'employee'=>$employee])}}",
                    type: "DELETE",
                    data:{
                        contract_id:contract_id,
                        termination_id: termination_id,
                        termination_description: termination_description
                    },
                    dataType: "json",
                    cache: false,
                    success: function(response) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.timer) {
                                $("#contractTerminationModal").modal('hide');
                                $("#employeeContracts").DataTable().ajax.reload();
                            }
                        })
                    },
                    error: function(jsXHR, status, errors){
                        $.each(jsXHR.responseJSON.errors, function(key, value){
                            var inputElement = document.getElementById(key);
                            $(key).addClass('is-invalid')

                            var spanTag = document.createElement("span");
                            spanTag.classList.add('invalid-feedback')
                            spanTag.classList.add('d-block')
                            spanTag.setAttribute('role', 'alert')

                            var strong = document.createElement("strong")
                            strong.innerHTML=value

                            spanTag.appendChild(strong)

                            inputElement.parentNode.insertBefore(spanTag, inputElement.nextSibling)
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: value,
                                showConfirmButton: false,
                                timer: 3000
                            })
                        })
                    }
                  });
                }
            });

        }
        window.addEventListener('closeContractModal', event => {
            $("#newContractModal").modal('hide');
            $("#employeeContracts").DataTable().ajax.reload();
        });
        window.addEventListener('openContractModal', event => {
            $("#newContractModal").modal('show');
        });
    </script>
@stop
