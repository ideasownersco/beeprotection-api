@extends('admin.layouts.app')

@section('styles')
    @parent
    <link href="/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="/plugins/fullcalendar/css/fullcalendar.min.css" rel="stylesheet" />

@endsection

@section('scripts')
    @parent
    <script src="/plugins/moment/moment.js"></script>
    <script src='/plugins/fullcalendar/js/fullcalendar.min.js'></script>
    <script>
      var payload = {!!  $payload  !!};
      console.log('payload',payload);
      $('#calendar').fullCalendar({
        header: false,
        events:payload,

      });
      $('#prev').on('click', function() {
        $('#calendar').fullCalendar('prev'); // call method
      });
      $('#next').on('click', function() {
        $('#calendar').fullCalendar('next'); // call method
      });

      $('#calendar').fullCalendar('gotoDate', '{!! $goToDate !!}');
      // $.fullCalendar.moment('2014-05-01');
    </script>
@endsection

@section('content')

    @component('admin.partials.breadcrumbs',['title' => 'Revenue'])
        <li class="breadcrumb-item active">Revenue</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-12">

            <div class="row">
                <div class="col-lg-6">
                    <div class="card-box">

                        <div class="col-12">

                            <div class="row">
                                <div class="col-2">
                                    <a class="btn btn-info pull-left" href="{{route('admin.revenue.index',['month'=>$prevMonth])}}" id='prev'>
                                        <i class="fa fa-arrow-circle-left fa-2x"></i>
                                    </a>
                                </div>
                                <div class="col-8">
                                    <h4 style="text-align: center">{{$month}}</h4>
                                </div>
                                <div class="col-2">
                                    <a class="btn btn-info pull-right" href="{{route('admin.revenue.index',['month'=>$nextMonth])}}" id='next'>
                                        <i class="fa fa-arrow-circle-right fa-2x"></i>
                                    </a>
                                </div>
                            </div>

                        </div>

                        <div id="calendar"></div>
                    </div>
                </div> <!-- end col -->

                <div class="col-lg-3">
                </div>
            </div>

        </div>
    </div>

@endsection