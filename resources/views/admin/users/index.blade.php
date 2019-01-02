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

    @component('admin.partials.breadcrumbs',['title' => $title])
        <li class="breadcrumb-item active">{{ $title }}</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-8">
            <div class="card-box">
                {!! Form::open(['action' => 'Admin\UsersController@index','method' => 'GET']) !!}
                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group contact-search ">
                            <input type="text" id="search" name="search" value="{{$searchQuery}}" class="form-control" placeholder="Search name, email, mobile">
                            <button type="submit" class="btn btn-white"><i class="fa fa-search"></i></button>
                        </div> <!-- form-group -->
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group contact-search ">
                            <button type="submit" class="btn btn-default btn-md waves-effect waves-light m-b-30"><i class="fa fa-search"></i>Search </button>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}

                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Type</th>
                            <th><a href="{{ action('Admin\UsersController@index',['sort' => 'active'])  }}">{{ isset($sort) && $sort == 'active-asc' ? '<i class="fa fa-arrow-up">': $sort == 'active-desc' ?  '<i class="fa fa-arrow-up">' : '' }}Active</a></th>
                            <th>Registered</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$user->id}}</td>
                                <td><a href="{{ route('admin.users.show',$user->id) }}">{{ $user->name }}</a></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->mobile }}</td>
                                <td>{{ $user->type_formatted }}</td>
                                <td>
                                    @if($user->active)
                                        <span class="label label-success ">Yes</span>
                                    @else
                                        <span class="label label-danger ">No</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->diffForHumans()}}</td>
                                <td>
                                    <a href="{{ route('admin.users.show',$user->id) }}" class="table-action-btn"><i class="md md-edit"></i></a>
                                    <a href="#" data-toggle="modal" data-target="#deleteModalBox"
                                       data-link="{{route('admin.users.destroy',$user->id)}}"
                                       class="table-action-btn"><i class="md md-close"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>


                <div class="text-center">
                    {{ $users->appends(request()->except('page'))->links() }}
                </div>

            </div>
        </div> <!-- end col -->

        <div class="col-lg-4">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Add User</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::open(['route' => ['admin.users.store'], 'method' => 'post','files'=>true], ['class'=>'']) !!}
                    @include('admin.users.edit',['hidden' => []])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.delete-modal',['title' => 'User'])

@endsection