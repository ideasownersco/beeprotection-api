@extends('admin.layouts.app')

@section('styles')
    @parent
    <link href="/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="/plugins/fullcalendar/css/fullcalendar.min.css" rel="stylesheet" />

@endsection

@section('scripts')
    @parent
    <script src="/plugins/timepicker/bootstrap-timepicker.js"></script>
    <script src="/plugins/moment/moment.js"></script>
    <script src='/plugins/fullcalendar/js/fullcalendar.min.js'></script>
    <script>
      var payload = {!!  $payload  !!};


      $('#calendar').fullCalendar({
        header: false,
        events:payload,
      });
      $('#calendar').fullCalendar('gotoDate', '{!! $goToDate !!}');

      $(document).ready(function() {
        $('#start_time').timepicker({
          defaultTIme: false,
          icons: {
            up: 'md md-expand-less',
            down: 'md md-expand-more'
          }
        });
        $('#end_time').timepicker({
          defaultTIme: false,
          icons: {
            up: 'md md-expand-less',
            down: 'md md-expand-more'
          }
        });

      });
    </script>
@endsection


@section('content')

    @component('admin.partials.breadcrumbs',['title' => $driver->user->name])
        <li class="breadcrumb-item"><a href="{{ route('admin.drivers.index') }}">Drivers</a></li>
        <li class="breadcrumb-item active">{{ $driver->name }}</li>
    @endcomponent

    <div class="row">

        <div class="col-lg-12">
            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-panel widget-style-2 bg-white">
                        <i class="md md-add-shopping-cart text-pink"></i>
                        <h2 class="m-0 text-dark counter font-600">{{ $todaysCount }}</h2>
                        <div class="text-muted m-t-5">Today's Orders</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-panel widget-style-2 bg-white">
                        <i class="md md-add-shopping-cart text-pink"></i>
                        <h2 class="m-0 text-dark counter font-600">{{ $monthsCount }}</h2>
                        <div class="text-muted m-t-5">This Month's Orders</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-panel widget-style-2 bg-white">
                        <i class="md md-add-shopping-cart text-pink"></i>
                        <h2 class="m-0 text-dark counter font-600">{{ $yearsCount }}</h2>
                        <div class="text-muted m-t-5">This Year's Orders</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card-box">
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <tbody>
                        @if($driver->image)
                            <tr>
                                <th>Image</th>
                                <td><img src="{{ $driver->user->image }}" class="thumb-sm"/></td>
                            </tr>
                        <tbody>
                        @endif
                        <tr>
                            <th>Name</th>
                            <td>{{ $driver->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $driver->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td>{{ $driver->user->mobile }}</td>
                        </tr>
                        <tr>
                            <th>Working Hours </th>
                            <td>{{ $driver->start_time }} - {{ $driver->end_time }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{{ $driver->offline ? 'Offline' : 'Online' }}</td>
                        </tr>
                        </tbody>
                    </table>

                </div>

            </div>


            <div class="card-box">
                <a href="{{ route('admin.orders.index') }}" class="pull-right btn btn-default btn-sm waves-effect waves-light">View All</a>
                <h4 class="text-dark header-title m-t-0">Recent Orders for {{ $driver->user->name }}</h4>
                <p class="text-muted m-b-30 font-13">&nbsp;</p>

                <div class="table-responsive">
                    <table class="table table-actions-bar">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Time</th>
                            <th>Amount</th>
                            <th>Address</th>
                            <th>Driver</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Payment</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            @include('admin.orders._list',['order'=>$order])
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div> <!-- end col -->

        <div class="col-lg-5">

            <div class="card-box">

                <div class="col-12">

                    <div class="row">
                        <div class="col-2">
                            <a class="btn btn-info pull-left" href="{{route('admin.drivers.show',['id'=>$driver->id,'month'=>$prevMonth])}}" id='prev'>
                                <i class="fa fa-arrow-circle-left fa-2x"></i>
                            </a>
                        </div>
                        <div class="col-8">
                            <h4 style="text-align: center">{{$month}}</h4>
                        </div>
                        <div class="col-2">
                            <a class="btn btn-info pull-right" href="{{route('admin.drivers.show',['id'=>$driver->id,'month'=>$nextMonth])}}" id='next'>
                                <i class="fa fa-arrow-circle-right fa-2x"></i>
                            </a>
                        </div>
                    </div>

                </div>

                <div id="calendar"></div>
            </div>

            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Edit Driver : {{ $driver->user->name }}</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::model($driver->user,['route' => ['admin.drivers.update',$driver->id], 'method' => 'patch','files'=>true], ['class'=>'']) !!}
                    @include('admin.drivers.edit',['driver'=>$driver,'hidden' => []])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection