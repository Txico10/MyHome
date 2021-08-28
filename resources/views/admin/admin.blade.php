@extends('adminlte::page')

@section('title', 'Admin')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Admin</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item active"><a href="#">Admin</a></li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <x-adminlte-info-box title="CPU Traffic" text="10%" icon="fas fa-cog" icon-theme="info"/>
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <x-adminlte-info-box title="Likes" text="41,410" icon="fas fa-thumbs-up" icon-theme="danger"/>
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix hidden-md-up"></div>

        <div class="col-12 col-sm-6 col-md-3">
            <x-adminlte-info-box title="Sales" text="760" icon="fas fa-shopping-cart" icon-theme="success"/>
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <x-adminlte-info-box title="New Members" text="2,000" icon="fas fa-users" icon-theme="warning"/>
        </div>
        <!-- /.col -->
    </div>
    <div class="row">
        <div class="col-lg-6">
            <x-adminlte-card title="Clients" theme="lightblue" theme-mode="outline" icon="fas fa-lg fa-store" removable collapsible>
                A removable and collapsible card with purple theme...
            </x-adminlte-card>
            <x-adminlte-card title="Users" theme="lightblue" theme-mode="outline" icon="fas fa-lg fa-users" removable collapsible>
                A removable and collapsible card with purple theme...
            </x-adminlte-card>
        </div>
        <!-- /.col-md-6 -->
        <div class="col-lg-6">
            <x-adminlte-card title="Roles" theme="lightblue" theme-mode="outline" icon="fas fa-lg fa-user-shield" removable collapsible>
                A removable and collapsible card with purple theme...
            </x-adminlte-card>
            <x-adminlte-card title="Permissions" theme="lightblue" theme-mode="outline" icon="fas fa-lg fa-user-tag" removable collapsible>
                A removable and collapsible card with purple theme...
            </x-adminlte-card>
        </div>
      <!-- /.col-md-6 -->
    </div>
    <!-- /.row -->
</div>
@stop



@section('js')
    <script> //console.log('Hi permissions!'); </script>
@stop
