@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('title', 'Show Permission')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Permission details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Admin</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.permissions')}}">Permissions</a></li>
                <li class="breadcrumb-item active">{{$permission->display_name}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <x-adminlte-card title="Permission" theme="lightblue" icon="fas fa-lg fa-cogs" removable collapsible>
                <strong><i class="fas fa-user-cog mr-1"></i> Name</strong>
                <p>{{$permission->name}}</p>
                <hr>
                <strong><i class="fas fa-user-cog mr-1"></i> Display name</strong>
                <p>{{$permission->display_name}}</p>
                <hr>
                <strong><i class="fas fa-comment mr-1"></i> Description</strong>
                <p>{{$permission->description}}</p>
                <hr>
                <strong><i class="fas fa-calendar-day mr-1"></i> Last Update</strong>
                <p>{{$permission->updated_at->format('d F Y')}}</p>
            </x-adminlte-card>
        </div>
        <div class="col-md-9">
            @role('superadministrator')
            @php
                $heads = [
                    '#',
                    'Name',
                    'Email',
                    'Companies',
                    'Action'
                ];

                $config = [
                    'processing' => true,
                    'serverSide' => true,
                    'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('admin.permissions.show', ['permission'=>$permission])],
                    'responsive'=> true,
                    'order' => [[0,'asc']],
                    'columns' => [['data'=>'DT_RowIndex'], ['data'=>'name'], ['data'=>'email'], ['data'=>'companies'], ['data'=>'action', 'searchable'=>false, 'orderable' => false]]
                ]
            @endphp
            <x-adminlte-card title="Users with {{$permission->display_name}} Permission" theme="lightblue" icon="fas fa-lg fa-user-shield" removable collapsible>
                <x-adminlte-datatable id="permissionUsers" :heads="$heads" :config="$config" />
            </x-adminlte-card>
            @endrole
        </div>
    </div>
@stop



@section('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#permissionUsers").on("click", ".detachUserPermissionButton", function(event){
            event.preventDefault()
            var user_id = $(this).val()
            var company_id = this.dataset.company
            Swal.fire({
                title: 'Are You Sure?',
                text: 'The permission will be detached from the user!',
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete!'
            }).then((result) => {
                //if user clicks on delete
                if (result.isConfirmed) {
                  $.ajax({
                    url:"{{route('admin.permissions.detachuser', ['permission'=>$permission])}}",
                    type: "POST",
                    dataType: "json",
                    cache: false,
                    data: {
                        user_id: user_id,
                        company_id: company_id,
                    },
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
                                $("#permissionUsers").DataTable().ajax.reload();
                            }
                        })
                    },
                    error: function(response, textStatus){
                        Swal.fire({
                            position: 'top-end',
                            icon: 'error',
                            title: "There's un error",
                            showConfirmButton: false,
                            timer: 3000
                        })
                    }
                  });
                }
            });
        });
    </script>
@stop
