@extends('admin.layouts.app')

@section('breadcrumb')
    <div class="banner">
        <h2>
            <a href="{{route('admin.home')}}">Home</a>
            <i class="fa fa-angle-right"></i>
            <span>Notifications</span>
        </h2>
    </div>
@endsection

@section('content')

    @component('admin.partials.breadcrumbs',['title' => 'Notifications', ])
        <li class="breadcrumb-item active">Notifications</li>
    @endcomponent

    <div>
        {{--<h4>Send Push Notifications</h4>--}}
        <hr>

        {!! Form::open(['route' => ['admin.notifications.store'], 'method' => 'post'], ['class'=>'']) !!}

        {{ csrf_field() }}

        <div class="form-group">
            <label for="companyName">Message</label>
            {!! Form::textarea('message',null,['class'=>'form-control','placeholder'=>'Your Message']) !!}
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success" style="width: 100%">Send</button>
        </div>

        {!! Form::close() !!}
    </div>
@endsection

