@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('title', 'Roles')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Roles List</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Admin</a></li>
                <li class="breadcrumb-item active">Roles</li>
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
    ['label'=>'Actions', 'width'=>16],
  ];

  $config = [
    'processing' => true,
    'serverSide' => true,
    'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('admin.roles')],
    'responsive'=> true,
    'order' => [[0,'asc']],
    'columns' => [['data'=>'DT_RowIndex'], ['data'=>'name'], ['data'=>'display_name'], ['data'=>'description'], ['data'=>'updated_at'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]],
  ]
@endphp
<div class="row">
    <div class="col-md-9">
        <x-adminlte-card title="Roles" theme="lightblue" icon="fas fa-lg fa-user-shield" removable collapsible>
            <x-adminlte-datatable id="rolesTable" :heads="$heads" :config="$config" />
        </x-adminlte-card>
    </div>
    <div class="col-md-3">
        @permission('roles-create')
        @if(session('success'))
            <x-adminlte-alert theme="success" title="Success" dismissable>
                {{session('success')}}
            </x-adminlte-alert>
        @endif
        <x-adminlte-card title="Roles form" theme="lightblue" icon="fas fa-lg fa-pencil-alt" removable collapsible>
            <form method="POST" action="{{route('admin.roles.store')}}">
                @csrf
                <input type="hidden" name="role_id" id="role_id" value="">
                <div class="form-group">
                    <label for="role_name" class="text-lightblue">Role name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user-cog"></i></span>
                        </div>
                        <input type="text" name="role_name" id="role_name" value="{{old('role_name')}}" class="form-control @error('role_name') is-invalid @enderror" placeholder="Enter role name">
                    </div>
                    @error('role_name')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="role_display_name" class="text-lightblue">Role display name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user-cog"></i></span>
                        </div>
                        <input type="text" name="role_display_name" id="role_display_name" value="{{old('role_display_name')}}" class="form-control @error('role_display_name') is-invalid @enderror" placeholder="Enter role display name">
                    </div>
                    @error('role_display_name')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="role_description" class="text-lightblue">Role description</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user-cog"></i></span>
                        </div>
                        <textarea name="role_description" id="role_description" class="form-control @error('role_description') is-invalid @enderror" rows="3" placeholder="Enter role description">{{old('role_description')}}</textarea>
                    </div>
                    @error('role_description')
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
@stop

@section('footer')
@include('includes.footer')
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
            $("#role_id").val('')
        })
        $("#rolesTable").on("click", ".editRoleButton",function(event){
            event.preventDefault();
            var role_id = $(this).val();
            //console.log(role_id)
            let _url = '/admin/roles/'+role_id+'/edit'

            $.ajax({
                url:_url,
                type: "GET",
                dataType: "json",
                cache: false,
                success: function(response) {
                    $("#role_id").val(response.role.id)
                    $("#role_name").val(response.role.name)
                    $("#role_display_name").val(response.role.display_name)
                    $("#role_description").val(response.role.description)

                },
                error: function(response, textStatus){
                  $.each(response.responseJSON.errors, function(key, value){
                    toastr.error(value);
                  })
                }
            });

        });

        $("#rolesTable").on("click", ".deleteRoleButton", function(event){
            event.preventDefault();
            var role_id = $(this).val();
            let _url = "/admin/roles/"+role_id

            Swal.fire({
                title: 'Are You Sure?',
                text: 'The role will be deleted!',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete!'
            }).then((result) => {
                //if user clicks on delete
                if (result.isConfirmed) {
                  $.ajax({
                    url:_url,
                    type: "DELETE",
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
                                $("#rolesTable").DataTable().ajax.reload();
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
