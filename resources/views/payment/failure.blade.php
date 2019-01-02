@extends('layouts.master')

@section('content')
    <div class="container text-center">
        <i class="fa fa-frown-o fa-5x" style="padding:20px"></i>
        <h1 style="color: #d63a00">Payment Failed </h1>
        <h1 style="color: #d63a00">{{ __('فشلت عملية الدفع') }}</h1>

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
                <td>Transaction Date</td>
                <td style="font-weight: bolder">{{$order->created_at->format('d-m-Y g:ia')}}</td>
            </tr>
            </tbody>
        </table>

        {{--Order Number : #{{$order->id}} <br>--}}
        {{--Transaction ID : {{$order->transaction_id}} <br>--}}
        {{--Payment ID : {{$order->payment_id}} <br>--}}
        {{--Total : {{$order->total}} KD <br>--}}
        {{--Transaction Date : {{$order->created_at->format('d-m-Y g:ia')}}--}}
    </div>
@endsection
