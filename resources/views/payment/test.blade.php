@extends('layouts.app')

@section('content')
    <div class="container text-center">
        <section class="content content_content" >
            <section class="invoice">
                <!-- title row -->
                <div class="row">
                    <div class="col-xs-12">
                        <h2 class="page-header">
                            <small class="pull-right">Date: {{ \Carbon\Carbon::now()->format('d-m-Y') }}</small>
                        </h2>
                    </div><!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row">
                    <div class="">
                        <b>Invoice : {{$order->invoice}}</b><br>
                        <br>
                        <b>Order ID:</b> {{$order->id}}<br>
                        <b>Payment Due:</b> {{$order->created_at->format('d-m-Y')}}<br>
                    </div><!-- /.col -->
                </div><!-- /.row -->

                <!-- Table row -->
                <div class="row">
                    <div class="col-xs-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Qty</th>
                                <th>Product</th>
                                <th>Date</th>
                                <th>Price</th>
                                <th>Sub Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>2</td>
                                <td>BeeWash</td>
                                <td>{{ $order->date }}</td>
                                <td>{{$order->total}} KD</td>
                                <td>{{$order->total}} KD</td>
                            </tr>
                            </tbody>
                        </table>
                    </div><!-- /.col -->
                </div><!-- /.row -->

                {!! Form::open(['url' => ['knet-pay'], 'method' => 'post'], ['class'=>'']) !!}
                <div class="row no-print">
                    <div class="col-xs-12">
                        <button class="btn btn-success pull-right"><i class="fa fa-credit-card"></i>Pay</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </section>
        </section>
    </div>
@endsection
