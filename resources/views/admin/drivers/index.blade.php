@extends('admin.layouts.app')

@section('styles')
    @parent
    <link href="/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
@endsection

@section('scripts')
    @parent
    <script src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
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

        $('#h_start_time').timepicker({
          defaultTIme: false,
          icons: {
            up: 'md md-expand-less',
            down: 'md md-expand-more'
          }
        });

        $('#h_end_time').timepicker({
          defaultTIme: false,
          icons: {
            up: 'md md-expand-less',
            down: 'md md-expand-more'
          }
        });

        $('#datepicker').datepicker();

      });
    </script>
@endsection

@section('content')

    @component('admin.partials.breadcrumbs',['title' => $title])
        <li class="breadcrumb-item active">{{ $title }}</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Working Hours</th>
                            {{--<th>Active</th>--}}
                            <th>Status</th>
                            <th >Action</th>
                        </tr>
                        </thead>
                        @foreach($drivers as $driver)
                            <tr>
                                <td><a href="{{ route('admin.drivers.show',$driver->id) }}">{{ $driver->user->name }}</a></td>

                                <td>{{ $driver->start_time }} - {{ $driver->end_time }}</td>
                                {{--<td>--}}
                                {{--@if($driver->active)--}}
                                {{--<span class="label label-success">Yes</span>--}}
                                {{--@else--}}
                                {{--<span class="label label-warning">No</span>--}}
                                {{--@endif--}}
                                {{--</td>--}}
                                <td>
                                    @if($driver->offline)
                                        <span class="label label-danger">Offline</span>
                                    @else
                                        <span class="label label-success">Online</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.drivers.show',$driver->id) }}" class="table-action-btn"><i class="md md-edit"></i></a>
                                    <a href="#" data-toggle="modal" data-target="#deleteModalBox"
                                       data-link="{{route('admin.drivers.destroy',$driver->id)}}"
                                       class="table-action-btn"><i class="md md-close"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

            </div>

            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Add Driver</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::open(['route' => ['admin.drivers.store'], 'method' => 'post','files'=>true], ['class'=>'']) !!}
                    @include('admin.drivers.edit',['hidden' => []])
                    {!! Form::close() !!}
                </div>
            </div>

        </div> <!-- end col -->

        <div class="col-lg-6">

            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Add Holiday For Driver</h4>
                {!! Form::open(['route' => ['admin.drivers.holiday.assign'], 'method' => 'post',], ['class'=>'']) !!}

                <div class="form-group">
                    {!! Form::select('driver_id',$driverNames,null,['class'=>'form-control']) !!}
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="date" class="form-control" placeholder="mm/dd/yyyy" id="datepicker" autocomplete="false">
                    </div>
                </div>

                <div class="form-group">
                    <label>Time From</label>
                    <div class="input-group">
                        {!! Form::text('from','05:00:00',['class'=>'form-control','id'=>'h_start_time']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="md md-access-time"></i></span>
                        </div>
                    </div><!-- input-group -->
                </div>

                <div class="form-group">
                    <label>Time To</label>
                    <div class="input-group">
                        {!! Form::text('to','23:00:00',['class'=>'form-control','id'=>'h_end_time']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="md md-access-time"></i></span>
                        </div>
                    </div><!-- input-group -->
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success" style="width: 100%">Save</button>
                </div>

                {!! Form::close() !!}

            </div>

            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Driver Holidays</h4>
                <table class="table table-actions-bar">

                    <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Date</th>
                        <th>Time From</th>
                        <th>Time To</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($driverHolidays as $holiday)
                        <tr>
                            <td>{{ $holiday->driver->user->name }}</td>
                            <td>{{ $holiday->date }}</td>
                            <td>{{ $holiday->from }}</td>
                            <td>{{ $holiday->to }}</td>
                            <td>
                                <a href="{{route('admin.drivers.holiday.delete',$holiday->id)}}"
                                   class="table-action-btn"><i class="md md-close"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @include('admin.partials.delete-modal',['title' => 'Driver'])

@endsection