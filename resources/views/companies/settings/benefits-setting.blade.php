@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('title', 'Benefits setting')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Employee benefits</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item"><a href="#">Settings</a></li>
                <li class="breadcrumb-item active">Employee benefits</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
@php
  $heads = [
    '#',
    'Name',
    'Display name',
    'Description',
    'Last update',
    'Actions',
  ];

  $config = [
    'processing' => true,
    'serverSide' => true,
    'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('company.benefits-setting', ['company'=>$company])],
    'responsive'=> true,
    'order' => [[0,'asc']],
    'columns' => [['data'=>'DT_RowIndex'], ['data'=>'name'], ['data'=>'display_name'], ['data'=>'description'], ['data'=>'updated_at'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]],
  ]
@endphp
<div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            <x-adminlte-card title="Employee benefits" theme="lightblue" icon="fas fa-lg fa-notes-medical" removable collapsible>
                <x-adminlte-datatable id="benefitsTable" :heads="$heads" :config="$config" />
            </x-adminlte-card>
        </div>
        <div class="col-md-3">
            @permission(['benefitsSetting-create', 'benefitsSetting-update'])
            @if(session('success'))
                <x-adminlte-alert theme="success" title="Success" dismissable>
                    {{session('success')}}
                </x-adminlte-alert>
            @endif
            <x-adminlte-card title="Employee beenfits form" theme="lightblue" icon="fas fa-lg fa-pencil-alt" removable collapsible>
                <form method="POST" action="{{route('company.benefits-setting.store', ['company'=>$company])}}">
                    @csrf
                    <input type="hidden" name="benefit_id" id="benefit_id" value="">
                    <div class="form-group">
                        <label for="benefit_name" class="text-lightblue">Benefit name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-book-medical"></i></span>
                            </div>
                            <input type="text" name="benefit_name" id="benefit_name" value="{{old('benefit_name')}}" class="form-control @error('benefit_name') is-invalid @enderror" placeholder="Enter benefit name">
                        </div>
                        @error('benefit_id')
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        @error('benefit_name')
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="benefit_display_name" class="text-lightblue">Benefit display name</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-book-medical"></i></span>
                            </div>
                            <input type="text" name="benefit_display_name" id="benefit_display_name" value="{{old('benefit_display_name')}}" class="form-control @error('benefit_display_name') is-invalid @enderror" placeholder="Enter benefit display name">
                        </div>
                        @error('benefit_display_name')
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="benefit_description" class="text-lightblue">Benefit description</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-book-medical"></i></span>
                            </div>
                            <textarea name="benefit_description" id="benefit_description" class="form-control @error('benefit_description') is-invalid @enderror" rows="3" placeholder="Enter benefit description">{{old('benefit_description')}}</textarea>
                        </div>
                        @error('benefit_description')
                            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <button class="btn btn-danger" id="btnReset" type="reset">Reset</button>
                        <button class="btn btn-primary float-right" type="submit">Save</button>
                    </div>
                </form>
            </x-adminlte-card>
            @endpermission
        </div>
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
            $("#btnReset").click(function(){
                $("#benefit_id").val('')
            })
            $("#benefitsTable").on("click", ".editBenefitButton",function(event){
                event.preventDefault();
                var benefit_id = $(this).val();

                $.ajax({
                    url:"{{route('company.benefits-setting.edit', ['company'=>$company])}}",
                    type: "GET",
                    data: {
                        benefit_id:benefit_id
                    },
                    dataType: "json",
                    cache: false,
                    success: function(response) {
                        $("#benefit_id").val(response.benefit.id)
                        $("#benefit_name").val(response.benefit.name)
                        $("#benefit_display_name").val(response.benefit.display_name)
                        $("#benefit_description").val(response.benefit.description)

                    },
                    error: function(response, textStatus){
                      $.each(response.responseJSON.errors, function(key, value){
                        console.log(value);
                      })
                    }
                });

            });
            $("#benefitsTable").on("click", ".deleteBenefitButton", function(event){
                event.preventDefault();
                var benefit_id = $(this).val();

                Swal.fire({
                    title: 'Are You Sure?',
                    text: 'The benefit will be deleted!',
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Delete!'
                }).then((result) => {
                    //if user clicks on delete
                    if (result.isConfirmed) {
                      $.ajax({
                        url:"{{route('company.benefits-setting.delete', ['company'=>$company])}}",
                        type: "DELETE",
                        data:{
                            benefit_id:benefit_id
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
                                    $("#benefitsTable").DataTable().ajax.reload();
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
        })
    </script>
@stop
