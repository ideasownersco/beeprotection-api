@extends('admin.layouts.app')

@section('styles')
    @parent
    <link href="/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
@endsection

@section('scripts')
    @parent
    <script src="/plugins/timepicker/bootstrap-timepicker.js"></script>
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
    </script>
@endsection


@section('content')

    @component('admin.partials.breadcrumbs',['title' => $area->name])
        <li class="breadcrumb-item"><a href="{{ route('admin.areas.index') }}">Areas</a></li>
        <li class="breadcrumb-item active">{{ $area->name }}</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <tbody>
                        <tr>
                            <th>Name (English)</th>
                            <td>{{ $area->name_en }}</td>
                        </tr>
                        <tr>
                            <th>Name (Arabic)</th>
                            <td>{{ $area->name_ar }}</td>
                        </tr>
                        <tr>
                            <th>Active</th>
                            <td>{{ $area->active ? 'Yes' : 'No' }}</td>
                        </tr>
                        </tbody>
                    </table>

                </div>

            </div>


            {{--<div class="card-box">--}}
                {{--<a href="{{ route('admin.orders.index') }}" class="pull-right btn btn-default btn-sm waves-effect waves-light">View All</a>--}}
                {{--<h4 class="text-dark header-title m-t-0">Recent Orders for {{ $area->name }}</h4>--}}
                {{--<p class="text-muted m-b-30 font-13">&nbsp;</p>--}}

                {{--<div class="table-responsive">--}}
                    {{--<table class="table table-actions-bar">--}}
                        {{--<thead>--}}
                        {{--<tr>--}}
                            {{--<th>Order Number</th>--}}
                            {{--<th>Order Date</th>--}}
                            {{--<th>Amount</th>--}}
                            {{--<th>Address</th>--}}
                            {{--<th>Area</th>--}}
                            {{--<th>Customer</th>--}}
                            {{--<th>Status</th>--}}
                        {{--</tr>--}}
                        {{--</thead>--}}
                        {{--<tbody>--}}
                        {{--@foreach($area->jobs as $job)--}}
                            {{--@include('admin.orders._list',['order'=>$job->order])--}}
                        {{--@endforeach--}}
                        {{--</tbody>--}}
                    {{--</table>--}}
                {{--</div>--}}

            {{--</div>--}}

        </div> <!-- end col -->

        <div class="col-lg-6">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Edit Area : {{ $area->name }}</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::model($area,['route' => ['admin.areas.update',$area->id], 'method' => 'patch'], ['class'=>'']) !!}
                    @include('admin.areas.edit',['area'=>$area,'hidden' => []])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection