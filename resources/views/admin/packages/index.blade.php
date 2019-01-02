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
                            <th>ID</th>
                            <th>Name (English)</th>
                            <th>Name (Arabic)</th>
                            <th>Category</th>
                            {{--<th>Order</th>--}}
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        @foreach($packages as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td><a href="{{ route('admin.packages.show',$package->id) }}">{{ $package->name_en }}</a></td>
                                <td><a href="{{ route('admin.packages.show',$package->id) }}">{{ $package->name_ar }}</a></td>
                                <td>
                                    @if($package->category)
                                        <a href="{{ route('admin.categories.show',$package->category->id) }}">{{ $package->category->name }}</a>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin.packages.show',$package->id) }}" class="table-action-btn"><i class="md md-edit"></i></a>
                                    <a href="#" data-toggle="modal" data-target="#deleteModalBox"
                                       data-link="{{route('admin.packages.destroy',$package->id)}}"
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
                <h4 class="text-dark header-title m-t-0">Add Package</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::open(['route' => ['admin.packages.store'], 'method' => 'post','files'=>true], ['class'=>'']) !!}
                    @include('admin.packages.edit')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.delete-modal',['title' => 'Package'])

@endsection