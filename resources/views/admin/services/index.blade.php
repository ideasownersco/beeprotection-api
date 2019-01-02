@extends('admin.layouts.app')

@section('content')

    @component('admin.partials.breadcrumbs',['title' => $title])
        <li class="breadcrumb-item active">{{ $title }}</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name (English)</th>
                            <th>Name (Arabic)</th>
                            <th>Package</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        @foreach($services as $service)
                            <tr>
                                <td><img src="{{$service->image}}" class="thumb-sm"/> </td>
                                <td><a href="{{ route('admin.services.show',$service->id) }}">{{ $service->name_en }}</a></td>
                                <td><a href="{{ route('admin.services.show',$service->id) }}">{{ $service->name_ar }}</a></td>
                                <td><a href="{{ route('admin.packages.show',$service->package->id) }}">{{ $service->package->name }}</a></td>
                                <td>
                                    <a href="{{ route('admin.services.show',$service->id) }}" class="table-action-btn"><i class="md md-edit"></i></a>
                                    <a href="#" data-toggle="modal" data-target="#deleteModalBox"
                                       data-link="{{route('admin.services.destroy',$service->id)}}"
                                       class="table-action-btn"><i class="md md-close"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>

            </div>
        </div> <!-- end col -->

        <div class="col-lg-6">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Add Service</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::open(['route' => ['admin.services.store'], 'method' => 'post','files'=>true], ['class'=>'']) !!}
                    @include('admin.services.edit')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection