@include('admin.partials.styles')
<div class="row">
    <div class="col-md-12">
        <div class="card-box">
            <!-- <div class="panel-heading">
                <h4>Invoice</h4>
            </div> -->
            <div class="panel-body">
                <div class="clearfix">
                    {{--<div class="pull-left">--}}
                        {{--<h4 class="text-right"><img src="{{ asset('/images/logo-sm.png') }}"></h4>--}}
                    {{--</div>--}}
                    <div class="pull-right">
                        <h4 class="font-16">Invoice # <br>
                            <strong>{{$order->invoice}}</strong>
                        </h4>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">

                        <div class="pull-left m-t-30">
                            <address>
                                <strong>BeeProtection, Co.</strong><br>
                                Alrai–Block 1<br>
                                Ali Aldoub Street 9<br>
                                <abbr title="Phone">P:</abbr> (965) 98009966 – 98009977
                            </address>
                        </div>
                        <div class="pull-right m-t-30">
                            <p class="m-t-10"><strong>Order ID: </strong> #{{$order->id}}</p>
                            <p><strong>Appointment Time: </strong> {{$order->date_formatted}}, {{ $order->time_formatted }}</p>
                            <p><strong>Order Date: </strong> {{$order->created_at->format('d-m-Y')}}</p>
                            @if($order->free_wash)
                                <p><strong>Free Wash : </strong> {{strtoupper($order->free_wash ? 'Yes' : 'No')}}</p>
                            @else
                                <p><strong>Payment Method : </strong> {{strtoupper($order->payment_mode)}}</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="m-h-50"></div>
                @if($order->free_wash)

                    <h1>Free Wash</h1>
                @else
                    <div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table m-t-30">
                                        <thead>
                                        <tr><th>#</th>
                                            <th>Driver</th>
                                            <th>Package</th>
                                            <th>Addons</th>
                                            <th>Quantity</th>
                                            <th>Cost</th>
                                            <th>Total</th>
                                        </tr></thead>
                                        <tbody>
                                        @foreach($order->packages as $package)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $order->job->driver->user->name }}</td>
                                                <td>
                                                    {{ $package->name }}
                                                </td>
                                                <td>
                                                    @foreach($order->services as $service)
                                                        @if($service->package_id === $package->id)
                                                            {{ $service->name }},
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>1</td>
                                                <td>{{$package->price}} KD</td>
                                                <td>{{$package->price}} KD</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-end">
                            <div class="col-md-3">
                                <p class="text-right"><b>Sub-total:</b> {{$order->total}} KD</p>
                                <hr>
                                <h3 class="text-right">{{$order->total}} KD</h3>
                            </div>
                        </div>
                    </div>
                @endif
                {{--<hr>--}}
                {{--<div class="d-print-none">--}}
                    {{--<div class="text-right">--}}
                        {{--<a href="javascript:window.print()" class="btn btn-inverse waves-effect waves-light"><i class="fa fa-print"></i></a>--}}
                    {{--</div>--}}
                {{--</div>--}}
            </div>
        </div>

    </div>

</div>