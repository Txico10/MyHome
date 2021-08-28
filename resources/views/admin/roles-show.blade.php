@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('plugins.iCheck', true)

@section('title', 'Show role')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Role details</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.index')}}">Admin</a></li>
                <li class="breadcrumb-item"><a href="{{route('admin.roles')}}">Roles</a></li>
                <li class="breadcrumb-item active">{{$role->display_name}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <x-adminlte-card title="Roles" theme="lightblue" icon="fas fa-lg fa-cogs" removable collapsible>
            <strong><i class="fas fa-user-cog mr-1"></i> Name</strong>
            <p>{{$role->name}}</p>
            <hr>
            <strong><i class="fas fa-user-cog mr-1"></i> Display name</strong>
            <p>{{$role->display_name}}</p>
            <hr>
            <strong><i class="fas fa-comment mr-1"></i> Description</strong>
            <p>{{$role->description}}</p>
            <hr>
            <strong><i class="fas fa-calendar-day mr-1"></i> Last Update</strong>
            <p>{{$role->updated_at->format('d F Y')}}</p>
        </x-adminlte-card>
    </div>

    <div class="col-md-9">
        @php
            $heads = [
                '#',
                'Name',
                'Email',
                'Companies',
            ];
            $config = [
                'processing' => true,
                'serverSide' => true,
                'ajax' => ['headers'=> ['X-CSRF-TOKEN'=>csrf_token()], 'url'=> route('admin.roles.show', ['role'=>$role])],
                'responsive'=> true,
                'order' => [[0,'asc']],
                'columns' => [['data'=>'DT_RowIndex'], ['data'=>'name'], ['data'=>'email'], ['data'=>'companies']]
            ]
        @endphp
        <x-adminlte-card title="Users with {{$role->display_name}} Role" theme="lightblue" icon="fas fa-lg fa-user-shield" removable collapsible>
            <x-adminlte-datatable id="roleUsers" :heads="$heads" :config="$config" />
        </x-adminlte-card>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <x-adminlte-card title="{{$role->display_name}} Permissions" class="color-palette-box" theme="lightblue" icon="fas fa-lg fa-user-cog" removable collapsible>
            @foreach ($permissions->chunk(6) as $key=>$chunk)
                @if($key>0 && $key < $permissions->count())
                    <hr>
                @endif
                <div class="row">
                    @foreach ($chunk as $permission)
                        <div class="col-sm-4 col-md-2">
                            <div class="form-group clearfix">
                                <div class="icheck-primary d-inline">
                                    <div class="form-group clearfix">
                                        <div class="icheck-primary d-inline">
                                          <input type="checkbox" id="{{$permission->name}}" class="permission_box" value="{{$permission->id}}" {{$permission->selected==1?'checked': ''}} data-selected="{{$permission->selected}}">
                                          <label for="{{$permission->name}}">
                                            {{$permission->display_name}}
                                          </label>
                                        </div>
                                      </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </x-adminlte-card>
    </div>
</div>
@stop

@section('footer')
@include('includes.footer')
@stop

@section('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".permission_box").on('click', function(event){
            //event.preventDefault()

            var permission_id = $(this).val()
            var selected = this.dataset.selected
            var id = $(this).attr('id')
            //console.log(id)

            $.ajax({
                url:"{{route('admin.roles.permission', ['role'=>$role])}}",
                type: "POST",
                dataType: "json",
                cache: false,
                data:{
                    permission_id:permission_id,
                    selected: selected
                },
                success: function(response) {
                    $("#"+id).attr("data-selected", response.selected)
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    //console.log(response.name+" - "+ response.selected)

                },
                error: function(response, textStatus){
                    $.each(response.responseJSON.errors, function(key, value){
                        Swal.fire(
                          'Error!',
                          value,
                          'error'
                        )
                    })
                }
            });
        })
    </script>
@stop
