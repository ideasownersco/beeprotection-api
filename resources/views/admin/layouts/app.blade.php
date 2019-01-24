<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>{{ isset($title) ? $title : config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta content="" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    @section('styles')
        @include('admin.partials.styles')
    @show

    <script src="/js/modernizr.min.js"></script>

</head>

<body>

@include('admin.partials.header')

<div class="wrapper">
    <div class="container-fluid">

        @include('admin.partials.notifications')
        @include('admin.partials.delete-modal')

        @yield('content')

    </div>
</div>


@include('admin.partials.footer')

@section('scripts')
    @include('admin.partials.scripts')
@show

</body>
</html>