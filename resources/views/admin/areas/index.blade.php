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
    {{--<script src="/js/jquery.gmaps.js"></script>--}}

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
      });

      var GoogleMap = function() {};
      GoogleMap.prototype.createMarkers = function($container) {
        var map = new GMaps({
          div: $container,
          lat: 29.3772392006689,
          lng: 47.98511826155676
        });

        var markers = {!! $areas !!};
        markers.map(function(marker) {
          map.addMarker({
            lat: marker.latitude,
            lng: marker.longitude,
            title: marker.name_en,
            infoWindow: {
              content: '<p>Total Orders at '+ marker.name_en +' : <b>' + marker.orders_count +'</b></p>'
            }
          });
        });
        return map;
      };
      GoogleMap.prototype.init = function() {
        var $this = this;
        $(document).ready(function(){
          $this.createMarkers('#gmaps-markers');
        });
      };
      $.GoogleMap = new GoogleMap;
      // $.GoogleMap.Constructor = GoogleMap;
      $.GoogleMap.init();
    </script>
@endsection

@section('content')

    @component('admin.partials.breadcrumbs',['title' => $title])
        <li class="breadcrumb-item active">{{ $title }}</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-12">

            <div class="card-box">
                <h4 class="m-t-0 m-b-20 header-title"><b>Areas</b></h4>

                <div id="gmaps-markers" class="gmaps"></div>
            </div>

        </div>
    </div>

    <div class="row">


        <div class="col-lg-6">

            @foreach($governorates as $governorate)

                <div class="card-box">
                    <h4 class="text-dark header-title m-t-0">{{$governorate->name}}</h4>
                    <div class="table-responsive m-t-10">
                        <table class="table table-actions-bar">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Total Orders</th>
                                <th>Active</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                            @foreach($governorate->children as $area)
                                <tr>
                                    <td><a href="{{ route('admin.areas.show',$area->id) }}">{{ $area->name }}</a></td>
                                    <td><b><h5>{{ $area->orders_count }}</h5></b></td>
                                    <td>

                                        @if($area->active)
                                            <span class="label label-success ">Yes</span>
                                        @else
                                            <span class="label label-danger ">No</span>
                                    @endif
                                    {{--{{ $area->active ? 'Yes' : 'No' }}</td>--}}
                                    <td>
                                        <a href="{{ route('admin.areas.show',$area->id) }}" class="table-action-btn"><i class="md md-edit"></i></a>
                                        <a href="#" data-toggle="modal" data-target="#deleteModalBox"
                                           data-link="{{route('admin.areas.destroy',$area->id)}}"
                                           class="table-action-btn"><i class="md md-close"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                </div>
            @endforeach

        </div> <!-- end col -->


        <div class="col-lg-6">


            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Add Area</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::open(['route' => ['admin.areas.store'], 'method' => 'post'], ['class'=>'']) !!}
                    @include('admin.areas.edit',['hidden' => []])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.delete-modal',['title' => 'Driver'])

@endsection