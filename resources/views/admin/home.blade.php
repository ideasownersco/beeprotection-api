@extends('admin.layouts.app')

@section('styles')
    @parent
    <link href="/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="/plugins/timetable/dist/styles/demo.css" rel="stylesheet">
    <link href="/plugins/timetable/dist/styles/timetablejs.css" rel="stylesheet">
    <style>
        .timetable .info {
            background-color: #33b5e5;
            border: 1px solid #707070;
        }
        .timetable .success {
            background-color: #2bbbad;
            border: 1px solid #707070;
        }
        .timetable .warning {
            background-color: #ff8800;
            border: 1px solid  #707070;
        }
        .timetable .danger {
            background-color: #ef4554;
            border: 1px solid  #707070;
        }
    </style>
    @parent
@endsection

@section('scripts')
    @parent
    <script src="/js/lodash.js"></script>
    <script src="/plugins/moment/moment.js"></script>
    <script src="/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="/plugins/timetable/dist/scripts/timetable.js"></script>
    <script type="text/javascript" src="/plugins/d3/d3.min.js"></script>
    <script type="text/javascript" src="/plugins/c3/c3.min.js"></script>

    <script>
      $(document).ready(function() {
        $('#datepicker').datepicker();
        var timetable = new Timetable();
        timetable.setScope(9,23); // optional, only whole hours between 0 and 23
        timetable.useTwelveHour();
        timetable.addLocations({!! $driverNames !!});
        _.map({!! $events !!},function(order) {
          var fromDate = moment(order.from).toDate();
          var fromDay = fromDate.getDay();
          var fromYear = fromDate.getFullYear();
          var fromMonth = fromDate.getMonth();
          var fromHours = fromDate.getHours();
          var fromMinutes = fromDate.getMinutes();
          var toDate = moment(order.to).toDate();

          var toYear = toDate.getFullYear();
          var toMonth = toDate.getMonth();
          var toDay = toDate.getDay();
          var toHours = toDate.getHours();
          var toMinutes = toDate.getMinutes();

          var options = {
            url: '/admin/orders/'+order.id,
            class: order.class, // additional css class
            data: { // each property will be added to the data-* attributes of the DOM node for this event
              id: order.id
            },
            onClick: function(event, timetable, clickEvent) {
              event.preventDefault();
            } // custom click handler, which is passed the event object and full timetable as context
          };

          timetable.addEvent(order.name, order.driver,
            new Date(fromYear,fromMonth,fromDay,fromHours,fromMinutes),
            new Date(toYear,toMonth,toDay,toHours,toMinutes),
            options
          );

        });
        var renderer = new Timetable.Renderer(timetable);
        renderer.draw('.timetable'); // any css selector

      });

      !function($) {
        "use strict";

        var Opportunities = function() {};

        Opportunities.prototype.init = function () {

          //Pie Chart
          c3.generate({
            bindto: '#pie-chart',
            data: {
              columns: [
                ['Working', '{{  $working }}'],
                ['Driving', '{{ $driving }}'],
                ['Pending', '{{ $pending }}'],
                ['Completed', '{{ $completed }}']
              ],
              type : 'pie'
            },
            color: {
              pattern: ["#34d3eb", "#ffbd4a", "#f05050", "#81c868"]
              // pattern: ["#34d3eb"]
            },
            pie: {
              label: {
                show: false
              }
            }
          });

        },
          $.Opportunities = new Opportunities, $.Opportunities.Constructor = Opportunities

      }(window.jQuery),

        function($) {
          "use strict";
          $.Opportunities.init()
        }(window.jQuery);

      $('#datepicker-autoclose').datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'd M',
      });

        {{--(function($) {--}}
        {{--'use strict';--}}
        {{--nv.addGraph(function() {--}}
        {{--var chart = nv.models.pieChart()--}}
        {{--.x(function(d) { return d.label })--}}
        {{--.y(function(d) { return d.value })--}}
        {{--.showLabels(true);--}}

        {{--d3.select("#chart1 svg")--}}
        {{--.datum(exampleData)--}}
        {{--.transition().duration(1200)--}}
        {{--.call(chart);--}}

        {{--return chart;--}}
        {{--});--}}

        {{--function exampleData() {--}}
        {{--return  [--}}
        {{--{--}}
        {{--"label": "Completed "+' ({{$completed}})',--}}
        {{--"value" : '{{$completed}}',--}}
        {{--"color" : "#5fbeaa"--}}
        {{--} ,--}}
        {{--{--}}
        {{--"label": "Pending"+' ({{$pending}})',--}}
        {{--"value" : '{{$pending}}',--}}
        {{--'color': '#f05050'--}}
        {{--} ,--}}
        {{--{--}}
        {{--"label": "Working"+' ({{$working}})',--}}
        {{--"value" : '{{$working}}',--}}
        {{--'color': '#5d9cec'--}}
        {{--} ,--}}
        {{--{--}}
        {{--"label": "Driving"+' ({{$driving}})',--}}
        {{--"value" : '{{$driving}}',--}}
        {{--'color': '#ffbd4a'--}}
        {{--} ,--}}
        {{--];--}}
        {{--}--}}
        {{--})(jQuery);--}}
    </script>
@endsection

@section('content')

    @component('admin.partials.breadcrumbs',['title' => 'Dashboard', ])
        <li class="breadcrumb-item active">Starter</li>
    @endcomponent

    <div class="row">

        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="widget-panel widget-style-2 bg-white">
                <i class="md md-attach-money text-primary"></i>
                <h2 class="m-0 text-dark counter font-600">{{ $revenue }} KD</h2>
                <div class="text-muted m-t-5">Total Revenue</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="widget-panel widget-style-2 bg-white">
                <i class="md md-add-shopping-cart text-pink"></i>
                <h2 class="m-0 text-dark counter font-600">{{ $ordersCount }}</h2>
                <div class="text-muted m-t-5">Orders</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="widget-panel widget-style-2 bg-white">
                <i class="md md-directions-car text-info"></i>
                <h2 class="m-0 text-dark counter font-600">{{ $driversCount }}</h2>
                <div class="text-muted m-t-5">Drivers</div>
            </div>
        </div>
        <div class="col-md-6 col-lg-6 col-xl-3">
            <div class="widget-panel widget-style-2 bg-white">
                <i class="md md-account-child text-custom"></i>
                <h2 class="m-0 text-dark counter font-600">{{ $customersCount }}</h2>
                <div class="text-muted m-t-5">Customers</div>
            </div>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">

            {{--<div class="form-group">--}}
            {{--<label>Select Date</label>--}}
            {{--<div>--}}
            {{--<div class="input-group">--}}
            {{--<input type="text" class="form-control" placeholder="mm/dd/yyyy" id="datepicker-autoclose">--}}
            {{--<div class="input-group-append">--}}
            {{--<span class="input-group-text"><i class="md md-event-note"></i></span>--}}
            {{--</div>--}}
            {{--</div><!-- input-group -->--}}
            {{--</div>--}}
            {{--</div>--}}

            {!! Form::open(['route'=>'admin.home','method'=>'GET']) !!}
            <div class="form-group row">
                <label class="col-1 col-form-label">Select Date</label>
                <div class="col-4">
                    <div class="input-group">
                        <input type="text" name="date" value="{{ $activeDate ? $activeDate : '' }}"class="form-control" placeholder="mm/dd/yyyy" id="datepicker">
                        {{--<div class="input-group-append">--}}
                        {{--<span class="input-group-text"><i class="md md-event-note"></i></span>--}}
                        {{--</div>--}}
                        <div class="input-group-append">
                            <button class="btn btn-dark waves-effect waves-light" type="submit">Submit</button>
                        </div>
                    </div>
                    {{--<div class="input-group">--}}
                    {{--<input type="text" class="form-control" placeholder="Recipient's username" aria-label="Recipient's username" aria-describedby="basic-addon2">--}}
                    {{--<div class="input-group-append">--}}
                    {{--<button class="btn btn-dark waves-effect waves-light" type="button">Button</button>--}}
                    {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
            {!! Form::close() !!}


            <ul class="nav nav-tabs tabs">
                @foreach($dateRange as $date)
                    <li class="@if($date === $activeDate) active @endif">
                        <a href="{{ route('admin.home',['date' => $date]) }}">
                            {{ $date }}
                        </a>
                    </li>
                @endforeach
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="{{$activeDate}}">
                    <div class="timetable"></div>
                </div>
            </div>

        </div>


    </div>

    <div class="row">


        <div class="col-lg-9">
            <div class="card-box">

                <a href="{{ route('admin.orders.index') }}" class="pull-right btn btn-default btn-sm waves-effect waves-light">View All</a>
                <h4 class="text-dark header-title m-t-0">Recent Orders</h4>
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
                            <th>Job Status</th>
                            <th>Created at</th>
                            <th>Payment</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentOrders as $order)
                            @include('admin.orders._list',['order'=>$order])
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>



        <div class="col-lg-3">

            <div class="card-box">
                <h4 class="m-t-0 m-b-20 text-dark header-title">Job Status (Total {{ $recentOrders->count() }} Orders)</h4>
                <div id="pie-chart"></div>
            </div>

            {{--<div class="card-box">--}}
            {{--<h4 class="m-t-0 m-b-20 text-dark header-title">Job Status (Total {{ $recentOrders->count() }} Orders)</h4>--}}
            {{--<div id="chart1">--}}
            {{--<svg style="height:300px;width:100%"></svg>--}}
            {{--</div>--}}
            {{--</div>--}}

            <div class="card-box" >

                <div class="timetable"></div>

                <h4 class="text-dark header-title m-t-0">Add Holiday</h4>

                {!! Form::open(['route' => 'admin.holiday', 'method' => 'post']) !!}
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" name="date" class="form-control" placeholder="mm/dd/yyyy" id="datepicker">
                        <div class="input-group-append">
                            <span class="input-group-text"><i class="md md-event-note"></i></span>
                        </div>
                    </div><!-- input-group -->
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success" style="width: 100%">Save</button>
                </div>
                {!! Form::close() !!}


                @if($holidays->count())
                    <hr>
                    <h2>Holidays</h2>
                    <div class="table-responsive">
                        <table class="table table-actions-bar">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($holidays as $holiday)
                                <tr>
                                    <td>{{ $holiday->date }}</td>
                                    <td>
                                        <a href="{{route('admin.holiday.delete',$holiday->id)}}"
                                           class="table-action-btn"><i class="md md-close"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

@endsection
