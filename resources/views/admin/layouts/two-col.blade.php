@extends('admin.layouts.app')

@section('content')

    <div class="col-md-6">
            @yield('right')
    </div>
    <div class="col-md-6">
            @yield('left')
    </div>
@endsection
