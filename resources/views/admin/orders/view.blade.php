@extends('admin.layouts.app')

@section('styles')
    @parent
    <link href="/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
@endsection

@section('scripts')
    @parent
    <script src="/plugins/timepicker/bootstrap-timepicker.js"></script>
    <script src="https://maps.google.com/maps/api/js?key=AIzaSyCpQX4H0QPxVgKuNMZ0ELG_ymgT8RHcKh4"></script>
    <script src="/plugins/gmaps/gmaps.min.js"></script>

    <script>
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

        // $('#cust-edit-modal').modal('show');
      });

      var GoogleMap = function() {};

      var lat = {!! $order->address ? $order->address->latitude : '29.3772392006689' !!}
      var lng = {!! $order->address ? $order->address->longitude : '47.98511826155676' !!}

      GoogleMap.prototype.createMarkers = function($container) {
        var map = new GMaps({
          div: $container,
          lat: lat,
          lng: lng
        });
        map.addMarker({
          lat: lat,
          lng: lng,
          draggable: true,
          dragend: function(event) {
            // var lat = event.latLng.lat();
            // var lng = event.latLng.lng();
            $('#latitude').val(event.latLng.lat());
            $('#longitude').val(event.latLng.lng());
          },
        });
      };
      GoogleMap.prototype.init = function() {
        var $this = this;
        $(document).ready(function(){
          $this.createMarkers('#gmaps-markers');
        });
      };

      $('#address-edit-modal').on('shown.bs.modal', function (e) {
        $.GoogleMap = new GoogleMap;
        $.GoogleMap.init();
      });

    </script>

@endsection

@section('content')

    @component('admin.partials.breadcrumbs',['title' => $order->user->name])
        <li class="breadcrumb-item"><a href="{{ route('admin.drivers.index') }}">Drivers</a></li>
        <li class="breadcrumb-item active">{{ $order->name }}</li>
    @endcomponent

    @include('admin.orders.modals.customer_edit',[
        'name' => $order->customer_name ? $order->customer_name : $order->user->name,
        'email' => $order->customer_email ? $order->customer_email : $order->user->email,
        'mobile' => $order->customer_mobile ? $order->customer_mobile : $order->user->mobile,
    ])
    @include('admin.orders.modals.driver_edit')
    @include('admin.orders.modals.type_edit')
    @include('admin.orders.modals.amount_edit')
    @include('admin.orders.modals.datetime_edit')
    @include('admin.orders.modals.address_edit')

    @include('admin.orders.modals.job_status_edit')
    @include('admin.orders.modals.address_edit')

    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <tbody>
                        <tr>
                            <th>Order Number</th>
                            <td>{{ $order->id }}</td>
                        </tr>
                        <tr>
                            <th>Customer Info
                                <br>
                                <a href="#" data-toggle="modal" data-target="#customer-edit-modal">
                                    Edit
                                </a>
                            </th>
                            <td>
                                @if($order->customer_name)
                                    {{ $order->customer_name }}
                                    <br>
                                    {{$order->customer_mobile}}
                                    <br>
                                    {{ $order->user->email }}
                                @else
                                    <a href="{{ route('admin.users.show',$order->user->id) }}">{{ $order->user->name }}</a>
                                    <br>
                                    {{$order->user->mobile}}
                                    <br>
                                    {{ $order->user->email }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Driver
                                <br>
                                <a href="#" data-toggle="modal" data-target="#driver-edit-modal">
                                    Change
                                </a>
                            </th>

                            <td>
                                @if($order->job && $order->job->driver)
                                    <a href="{{ route('admin.drivers.show',$order->job->driver->id) }}">{{ optional($order->job->driver->user)->name }}</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Wash Types
                                {{--<br>--}}
                                {{--<a href="#" data-toggle="modal" data-target="#type-edit-modal">--}}
                                    {{--Edit--}}
                                {{--</a>--}}
                            </th>

                            <td>
                                @if($order->free_wash)
                                    Free Wash
                                @else
                                    @foreach($order->packages as $package)
                                        <b>{{$package->category->name}}</b> :
                                        <a href="{{ route('admin.packages.show',$package->id) }}">{{ $package->name }} </a><br>
                                        @if($order->services->count())
                                            <b>Add ons</b>
                                            @foreach($order->services as $service)
                                                <a href="{{ route('admin.services.show',$service->id) }}">{{ $service->name }} </a>
                                            @endforeach
                                        @endif
                                        @if(!$loop->last)
                                            <hr>
                                        @endif
                                    @endforeach
                                @endif
                            </td>
                            {{--<td>{{ implode($order->packages->pluck('name_ar')->toArray(),', ')}}</td>--}}
                        </tr>
                        <tr>
                            <th>Amount
                                <br>
                                <a href="#" data-toggle="modal" data-target="#amount-edit-modal">
                                    Edit
                                </a>
                            </th>
                            <td>{{ $order->total}} KD</td>
                        </tr>
                        <tr>
                            <th>Date
                                {{--<br>--}}
                                {{--<a href="#" data-toggle="modal" data-target="#datetime-edit-modal">--}}
                                    {{--Edit--}}
                                {{--</a>--}}
                            </th>
                            <td>{{ $order->scheduled_time}} - {{ $order->time_to }}</td>
                        </tr>
                        <tr>
                            <th>Address
                                <br>
                                <a href="#" data-toggle="modal" data-target="#address-edit-modal">
                                    Edit
                                </a>
                            </th>
                            <td>{{ optional($order->address)->formatted_address }} </td>
                        </tr>
                        {{--<tr>--}}
                        {{--<th>Started Driving at</th>--}}
                        {{--<td>{{ $order->job->started_driving_at_formatted }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<th>Stopped Driving at</th>--}}
                        {{--<td>{{ $order->job->stopped_driving_at_formatted }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<th>Started Working at</th>--}}
                        {{--<td>{{ $order->job->started_working_at_formatted }}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<th>Stopped Working at</th>--}}
                        {{--<td>{{ $order->job->stopped_working_at_formatted }}</td>--}}
                        {{--</tr>--}}
                        <tr>
                            <th>Job Status
                                <br>
                                <a href="#" data-toggle="modal" data-target="#job-status-edit-modal">
                                    Edit
                                </a>
                            </th>
                            <td>{{ optional($order->job)->status }}</td>
                        </tr>
                        @if($order->job)
                            <tr>
                                <th>Photos</th>
                                <td>
                                    @foreach($order->job->photos as $photo)
                                        <a href="{{ $photo->url }}" target="_blank">
                                            <img src="{{ $photo->url }}" class="thumb-lg" />
                                        </a>
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <th>Photos Approved By Customer</th>
                                <td>{{ optional($order->job)->photos_approved ? 'Yes' : 'No' }}</td>
                            </tr>
                            <tr>
                                <th>Photo Comment</th>
                                <td>{{ optional($order->job)->photo_comment }}</td>
                            </tr>
                        @endif

                        <tr>
                            <th>Payment</th>
                            <td>{{ strtoupper($order->payment_mode) }}</td>
                        </tr>

                        </tbody>
                    </table>

                </div>

            </div>
        </div> <!-- end col -->


        <div class="col-lg-6">

            <div class="row">

                {{--<div class="col-lg-6">--}}
                {{--<div class="card-box">--}}
                {{--<h4 class="text-dark header-title m-t-0">Change Driver</h4>--}}

                {{--{!! Form::open(['route' => ['admin.orders.driver.assign',$order->id], 'method' => 'post','files'=>true], ['class'=>'']) !!}--}}

                {{--<div class="form-group">--}}
                {{--{!! Form::select('driver_id',$driverNames,null,['class'=>'form-control']) !!}--}}
                {{--</div>--}}

                {{--<div class="form-group">--}}
                {{--<button type="submit" class="btn btn-success" style="width: 100%">Save</button>--}}
                {{--</div>--}}

                {{--{!! Form::close() !!}--}}

                {{--</div>--}}
                {{--</div>--}}
                <div class="col-lg-6">
                    <div class="card-box">
                        <a href="{{ route('admin.invoice',$order->id) }}" class="btn btn-success">Print Invoice</a>
                    </div>
                </div>

            </div>

            @if($order->job)
                <div class="card-box">

                    <div class="bg-light">

                        <section id="cd-timeline" class="cd-container">
                            <div class="cd-timeline-block">
                                <div class="cd-timeline-img cd-success">
                                    <i class="md md-directions-car"></i>

                                </div> <!-- cd-timeline-img -->

                                <div class="cd-timeline-content">
                                    <h3>Started Driving</h3>
                                    <span class="cd-date">{{ $order->job->started_driving_at_formatted }}</span>
                                </div> <!-- cd-timeline-content -->
                            </div> <!-- cd-timeline-block -->

                            <div class="cd-timeline-block">
                                <div class="cd-timeline-img cd-danger">
                                    <i class="md md-home"></i>
                                </div> <!-- cd-timeline-img -->

                                <div class="cd-timeline-content">
                                    <h3>Reached at</h3>
                                    {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, optio, dolorum provident rerum aut hic quasi placeat iure tempora laudantium ipsa ad debitis unde?</p>--}}
                                    {{--<button type="button" class="btn btn-primary btn-rounded waves-effect waves-light m-t-15">See more detail</button>--}}
                                    <span class="cd-date">{{ $order->job->stopped_driving_at_formatted }}</span>
                                </div> <!-- cd-timeline-content -->
                            </div> <!-- cd-timeline-block -->

                            <div class="cd-timeline-block">
                                <div class="cd-timeline-img cd-info">
                                    <i class="md md-local-car-wash"></i>
                                </div> <!-- cd-timeline-img -->

                                <div class="cd-timeline-content">
                                    <h3>Started Working</h3>
                                    {{--<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi, obcaecati, quisquam id molestias eaque asperiores voluptatibus cupiditate error assumenda delectus odit similique earum voluptatem doloremque dolorem ipsam quae rerum quis. Odit, itaque, deserunt corporis vero ipsum nisi eius odio natus ullam provident pariatur temporibus quia eos repellat ... <a href="#">Read more</a></p>--}}
                                    <span class="cd-date">{{ $order->job->started_working_at_formatted }}</span>
                                </div> <!-- cd-timeline-content -->
                            </div> <!-- cd-timeline-block -->

                            <div class="cd-timeline-block">
                                <div class="cd-timeline-img cd-pink">
                                    <i class="md md-done-all"></i>
                                </div> <!-- cd-timeline-img -->

                                <div class="cd-timeline-content">
                                    <h3>Stopped Working</h3>
                                    <img src="assets/images/small/img1.jpg" alt="">
                                    <span class="cd-date">{{ $order->job->stopped_working_at_formatted }}</span>
                                </div> <!-- cd-timeline-content -->
                            </div> <!-- cd-timeline-block -->

                        </section> <!-- cd-timeline -->
                    </div>

                </div>
            @endif

        </div>
    </div>

@endsection