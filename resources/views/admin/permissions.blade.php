@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)

@section('title', 'Permissions')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Permissions List</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Admin</a></li>
                <li class="breadcrumb-item active">Permissions</li>
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
    'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('admin.permissions')],
    'responsive'=> true,
    'order' => [[0,'asc']],
    'columns' => [['data'=>'DT_RowIndex'], ['data'=>'name'], ['data'=>'display_name'], ['data'=>'description'], ['data'=>'updated_at'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]],
  ]
@endphp
<div class="row">
    <div class="col-md-9">
        <x-adminlte-card title="Permissions" theme="lightblue" icon="fas fa-lg fa-cogs" removable collapsible>
            <x-adminlte-datatable id="permissionsTable" :heads="$heads" :config="$config" />
        </x-adminlte-card>
    </div>
    <div class="col-md-3">
        @permission('permissions-create')
        @if(session('success'))
            <x-adminlte-alert theme="success" title="Success" dismissable>
                {{session('success')}}
            </x-adminlte-alert>
        @endif
        <x-adminlte-card title="Permissions form" theme="lightblue" icon="fas fa-lg fa-pencil-alt" removable collapsible>
            <form method="POST" action="{{route('admin.permissions.store')}}">
                @csrf
                <input type="hidden" name="permission_id" id="permission_id" value="">
                <div class="form-group">
                    <label for="permission_name" class="text-lightblue">Permission name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user-cog"></i></span>
                        </div>
                        <input type="text" name="permission_name" id="permission_name" value="{{old('permission_name')}}" class="form-control @error('permission_name') is-invalid @enderror" placeholder="Enter permission name">
                    </div>
                    @error('permission_name')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="permission_display_name" class="text-lightblue">Permission display name</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user-cog"></i></span>
                        </div>
                        <input type="text" name="permission_display_name" id="permission_display_name" value="{{old('permission_display_name')}}" class="form-control @error('permission_display_name') is-invalid @enderror" placeholder="Enter permission display name">
                    </div>
                    @error('permission_display_name')
                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="permission_description" class="text-lightblue">Permission description</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text text-lightblue"><i class="fas fa-fw fa-user-cog"></i></span>
                        </div>
                        <textarea name="permission_description" id="permission_description" class="form-control @error('permission_description') is-invalid @enderror" rows="3" placeholder="Enter permission description">{{old('permission_description')}}</textarea>
                    </div>
                    @error('permission_description')
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



@section('js')
    <script>
        $(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#btnReset").click(function(){
                $("#permission_id").val('')
            })

            $("#permissionsTable").on("click", ".editPermissionButton",function(event){
                event.preventDefault();
                var permission_id = $(this).val();
                //console.log(role_id)
                let _url = '/admin/permissions/'+permission_id+'/edit'

                $.ajax({
                    url:_url,
                    type: "GET",
                    dataType: "json",
                    cache: false,
                    success: function(response) {
                        $("#permission_id").val(response.permission.id)
                        $("#permission_name").val(response.permission.name)
                        $("#permission_display_name").val(response.permission.display_name)
                        $("#permission_description").val(response.permission.description)

                    },
                    error: function(response, textStatus){
                      $.each(response.responseJSON.errors, function(key, value){

                      })
                    }
                });
            });

            $("#permissionsTable").on("click", ".deletePermissionButton", function(event){
                event.preventDefault();
                var permission_id = $(this).val();
                let _url = "/admin/permissions/"+permission_id

                Swal.fire({
                    title: 'Are You Sure?',
                    text: 'The permission will be deleted!',
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
                                    $("#permissionsTable").DataTable().ajax.reload();
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
