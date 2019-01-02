@extends('admin.layouts.app')

@section('content')

    @component('admin.partials.breadcrumbs',['title' => $title])
        <li class="breadcrumb-item active">{{ $title }}</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-12">

            <div class="row">
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-panel widget-style-2 bg-white">
                        <i class="md md-add-shopping-cart text-pink"></i>
                        <h2 class="m-0 text-dark counter font-600">{{ $todaysCount }}</h2>
                        <div class="text-muted m-t-5">Today's Orders</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-panel widget-style-2 bg-white">
                        <i class="md md-add-shopping-cart text-pink"></i>
                        <h2 class="m-0 text-dark counter font-600">{{ $monthsCount }}</h2>
                        <div class="text-muted m-t-5">This Month's Orders</div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-6 col-xl-3">
                    <div class="widget-panel widget-style-2 bg-white">
                        <i class="md md-add-shopping-cart text-pink"></i>
                        <h2 class="m-0 text-dark counter font-600">{{ $yearsCount }}</h2>
                        <div class="text-muted m-t-5">This Year's Orders</div>
                    </div>
                </div>
            </div>


            <div class="card-box">


                {!! Form::open(['action' => 'Admin\OrdersController@index','method' => 'GET']) !!}
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group contact-search ">
                            <input type="text" id="search" name="search" value="{{$searchQuery}}" class="form-control" placeholder="Search ID (Ex: 1, 2, 3)">
                            <button type="submit" class="btn btn-white"><i class="fa fa-search"></i></button>
                        </div> <!-- form-group -->
                    </div>

                    <div class="col-lg-1">
                        <div class="form-group contact-search ">
                            <button type="submit" class="btn btn-default btn-md waves-effect waves-light m-b-30"><i class="fa fa-search"></i>Search </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}


                <div class="dropdown pull-left" style="margin-bottom: 20px;">
                    <a href="#" title="" class="btn btn-default" data-toggle="dropdown" aria-expanded="false">
                        Orders
                        <i class="fa fa-cog icon_8"></i>
                        {{ ucfirst($requestedStatus) }}
                        <i class="fa fa-chevron-down icon_8"></i>
                        <div class="ripple-wrapper">
                        </div>
                    </a>
                    <ul class="dropdown-menu float-right">
                        <li>
                            <a href="{{ action('Admin\OrdersController@index',['status'=>'all']) }}">
                                <i class="fa fa-list icon_9"></i>
                                All Orders
                            </a>
                        </li>
                        <li>
                            <a href="{{ action('Admin\OrdersController@index',['status'=>'completed']) }}">
                                <i class="fa fa-check icon_9"></i>
                                Completed
                            </a>
                        </li>
                        <li>
                            <a href="{{ action('Admin\OrdersController@index',['status'=>'driving']) }}">
                                <i class="fa fa-close icon_9"></i>
                                Driving
                            </a>
                        </li>
                        <li>
                            <a href="{{ action('Admin\OrdersController@index',['status'=>'working']) }}">
                                <i class="fa fa-download icon_9"></i>
                                Working
                            </a>
                        </li>
                        <li>
                            <a href="{{ action('Admin\OrdersController@index',['status'=>'pending']) }}">
                                <i class="fa fa-close icon_9"></i>
                                Pending
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="dropdown pull-right" style="margin-bottom: 20px;">

                    <a href="{{ action('Admin\OrdersController@export',['status'=>$requestedStatus,'type'=>'all']) }}" title="" class="btn btn-default"  aria-expanded="false">
                        Export to Excel
                        <i class="fa fa-rocket icon_8"></i>
                    </a>
                </div>

                <div class="table-responsive m-t-10">
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
                            <th>Status</th>
                            <th>Created</th>
                            <th>Payment</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            @include('admin.orders._list',['order'=>$order])
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="text-center">
                    {{ $orders->appends(request()->except('page'))->links() }}
                </div>

            </div>
        </div> <!-- end col -->
    </div>

    @include('admin.partials.delete-modal',['title' => 'Category'])

@endsection