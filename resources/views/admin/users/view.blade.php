@extends('admin.layouts.app')

@section('styles')
    @parent
    <link href="/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
@endsection

@section('scripts')
    @parent
    <script src="/plugins/timepicker/bootstrap-timepicker.js"></script>
    <script>
      $(document).ready(function() {
        $('#start_time').timepicker({
          defaultTIme: false,
          icons: {
            up: 'md md-expand-less',
            down: 'md md-expand-more'
          }
        });
        $('#end_time').timepicker({
          defaultTIme: false,
          icons: {
            up: 'md md-expand-less',
            down: 'md md-expand-more'
          }
        });
      });
    </script>
@endsection


@section('content')

    @component('admin.partials.breadcrumbs',['title' => $user->name])
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Users</a></li>
        <li class="breadcrumb-item active">{{ $user->name }}</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-8">
            <div class="card-box">
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <tbody>
                        @if($user->image)
                            <tr>
                                <th>Image</th>
                                <td><img src="{{ $user->image }}" class="thumb-sm"/></td>
                            </tr>
                        <tbody>
                        @endif
                        <tr>
                            <th>Name</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Mobile</th>
                            <td>{{ $user->mobile }}</td>
                        </tr>
                        <tr>
                            <th>Type</th>
                            <td>{{ $user->type_formatted }}</td>
                        </tr>
                        <tr>
                            <th>Active</th>
                            <td>
                                @if($user->active)
                                    <span class="label label-success ">Yes</span>
                                @else
                                    <span class="label label-danger ">No</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Blocked</th>
                            <td>
                                @if($user->blocked)
                                    <span class="label label-danger ">Yes</span>
                                @else
                                    <span class="label label-success ">No</span>
                                @endif
                            </td>
                        </tr>
                        @if($user->registration_code)
                        <tr>
                            <th>Registration Code</th>
                            <td>{{ $user->registration_code }}</td>
                        </tr>
                        @endif
                        @if($user->forgot_password_code)
                            <tr>
                                <th>Forgot Password Code</th>
                                <td>{{ $user->forgot_password_code }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Registered Date</th>
                            <td>{{ $user->created_at->diffForHumans()}}</td>
                        </tr>
                        </tbody>
                    </table>

                </div>

            </div>

            <div class="card-box">
                <a href="{{ route('admin.orders.index') }}" class="pull-right btn btn-default btn-sm waves-effect waves-light">View All</a>
                <h4 class="text-dark header-title m-t-0">Recent Orders for {{ $user->name }}</h4>
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
                            <th>Status</th>
                            <th>Created</th>
                            <th>Payment</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($user->orders as $order)
                            @include('admin.orders._list',['order'=>$order])
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div> <!-- end col -->

        <div class="col-lg-4">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Edit User : {{ $user->name }}</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::model($user,['route' => ['admin.users.update',$user->id], 'method' => 'patch','files'=>true], ['class'=>'']) !!}
                    @include('admin.users.edit',['user'=>$user,'hidden' => []])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection