@extends('adminlte::page')
@section('plugins.Sweetalert2', true)
@section('plugins.Select2', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('title', 'Bill')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0 text-dark">Invoice</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{route('company.show', ['company' => $company])}}">{{$company->display_name}}</a></li>
                <li class="breadcrumb-item active"> <a href="{{route('company.invoices', ['company' => $company])}}">Invoices</a></li>
                <li class="breadcrumb-item active">{{$bill->id}}</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container-fluid -->
@stop
@section('content')
<div class="card">
    <div class="card-body">
      <div class="container mb-5 mt-3">
        <div class="row d-flex align-items-baseline">
          <div class="col-xl-9">

          </div>
          <div class="col-xl-3 float-end">
            <a class="btn btn-light text-capitalize border-0" data-mdb-ripple-color="dark"><i
                class="fas fa-print text-primary"></i> Print</a>
            <a class="btn btn-light text-capitalize" data-mdb-ripple-color="dark" href="{{route('company.invoice.download', ['company'=>$company, 'bill'=>$bill])}}"><i
                class="far fa-file-pdf text-danger"></i> Export</a>
          </div>
          <hr>
        </div>

        <div class="container">
          <div class="col-md-12">
            <div class="text-center">
                <div class="image">
                    <img src="{{asset('storage/images/profile/companies/'.$company->logo)}}" class="profile-user-img img-fluid img-circle" alt="{{$company->description}}">
                </div>
            <!--  <i class="fab fa-mdb fa-4x ms-0" style="color:#5d9fc5 ;"></i> -->
              <p class="pt-0">{{$company->display_name}}</p>
            </div>

          </div>


          <div class="row">
            <div class="col-xl-8">
              <ul class="list-unstyled">
                @foreach ($tenants as $tenant)
                <li class="text-muted">To: <span style="color:#5d9fc5 ;">{{$tenant->name}}</span></li>
                @endforeach
                <li class="text-muted">{{$tenant->addresses->first()->number}}, {{$tenant->addresses->first()->street}}, {{$tenant->addresses->first()->suite}}, {{$tenant->addresses->first()->city}}</li>
                <li class="text-muted">{{$tenant->addresses->first()->region}}, {{$tenant->addresses->first()->country}}</li>
                <li class="text-muted"><i class="fas fa-envelope"></i> {{$tenant->email}}</li>
                @foreach ($tenant->contacts as $contact)
                    @if(strcmp($contact->type,'mobile')==0)
                    <li class="text-muted"><i class="fas fa-phone"></i> {{$contact->description}} - {{ucfirst($contact->priority)}}</li>
                    @endif
                @endforeach

              </ul>
            </div>
            <div class="col-xl-4">
              <p class="text-muted">Invoice</p>
              <ul class="list-unstyled">
                <li class="text-muted"><i class="fas fa-circle" style="color:#3c8dbc ;"></i> <span
                    class="fw-bold">ID:</span>#{{$bill->number}}-{{$bill->created_at->format('Y')}}</li>
                <li class="text-muted"><i class="fas fa-circle" style="color:#3c8dbc ;"></i> <span
                    class="fw-bold">Period: </span>{{$bill->period_from->format('Y-m')}} / {{$bill->period_to->format('Y-m')}}</li>
                <li class="text-muted"><i class="fas fa-circle" style="color:#3c8dbc ;"></i> <span
                    class="fw-bold">Due date: </span>{{$bill->payment_due_date->format('Y-m-d')}}</li>
                <li class="text-muted"><i class="fas fa-circle" style="color:#3c8dbc ;"></i> <span
                    class="me-1 fw-bold">Status:</span><span class="badge bg-warning text-black fw-bold">
                    {{ucfirst($bill->status)}}</span></li>
                @if(strcmp($bill->status, "payed")==0)
                <li class="text-muted"><i class="fas fa-circle" style="color:#3c8dbc ;"></i> <span
                    class="fw-bold">Payment date: </span> {{$bill->payments->first()->payed_at->format('Y-m-d')}}</li>
                @endif
              </ul>
            </div>
          </div>

          <div class="row my-2 mx-1 justify-content-center">
            <table class="table table-striped table-borderless">
              <thead style="background-color:#3c8dbc ;" class="text-white">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Description</th>
                  <th scope="col">Amount</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($bill_lines as $key=>$line)
                  <tr>
                    <th scope="row">{{$key+1}}</th>
                    <td>{{$line['name']}}</td>
                    <td>${{number_format($line['amount'], 2)}}</td>
                  </tr>
                @endforeach
              </tbody>

            </table>
          </div>
          <div class="row">
            <div class="col-xl-9">
              <p class="ms-3">Add additional notes and payment information</p>

            </div>
            <div class="col-xl-3">
              <ul class="list-unstyled">
                <li class="text-muted ms-3"><span class="text-black me-4"></span></li>
                <li class="text-muted ms-3 mt-2"><span class="text-black me-4"></span></li>
              </ul>
              <p class="text-black float-start"><span class="text-black me-3"> Total Amount</span><span
                  style="font-size: 25px;">${{$bill->total_amount}}</span></p>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-xl-9">
              <p>{{Carbon\Carbon::now()}}</p>
            </div>
            <div class="col-xl-3">
              <p>Created at:{{$bill->created_at}}</p>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@stop

@section('js')
<script>
    $(function(){
        $(".customButton").html('@permission("bill-create")<div class="dt-buttons"><button type="button" class="btn btn-block btn-default addBill"><i class="fas fa-lg fa-plus-square text-primary"></i> New</button></div>@endpermission');

        $(".addBill").on("click", function(event){
            event.preventDefault();
            $("#billModal").modal('show');
        })
    })

    </script>
@stop
