@extends('layouts.master')

@section('content')
    <div class="container text-center">
        <i class="fa fa-smile-o fa-5x" style="padding:20px"></i>

        <h1 style="color: #03a15e">Payment Success</h1>
        <h1 style="color: #03a15e" >{{ __('تم  عملية الدفع بنجاح') }}</h1>

        <table class="table table-striped table-condensed" style="margin-top:40px">
            <tbody>
            <tr>
                <td>Order Number</td>
                <td style="font-weight: bolder">#{{$order->id}} </td>
            </tr>
            <tr>
                <td>Transaction ID</td>
                <td style="font-weight: bolder">{{$order->transaction_id}}</td>
            </tr>
            <tr>
                <td>Payment ID </td>
                <td style="font-weight: bolder">{{$order->payment_id}} </td>
            </tr>
            <tr>
                <td>Total</td>
                <td style="font-weight: bolder">{{$order->total}} KD</td>
            </tr>
            <tr>
                <td>Appointment Date</td>
                <td style="font-weight: bolder">{{$order->scheduled_time}}</td>
            </tr>
            <tr>
                <td>Transaction Date</td>
                <td style="font-weight: bolder">{{$order->created_at->format('d-m-Y g:ia')}}</td>
            </tr>
            </tbody>
        </table>

        {{--<button class="btn btn-success">Home</button>--}}

    </div>
@endsection
