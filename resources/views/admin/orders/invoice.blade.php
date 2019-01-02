@extends('admin.layouts.app')

@section('content')

    @component('admin.partials.breadcrumbs',['title' => 'Invoice'])
        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.orders.show',$order->id) }}">{{ $order->id }}</a></li>
        <li class="breadcrumb-item active">Invoice</li>
    @endcomponent

    @include('partials.invoice')

@endsection