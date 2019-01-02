@extends('admin.layouts.app')

@section('content')

    @component('admin.partials.breadcrumbs',['title' => 'Package : ' .$package->name_en . ' - '. $package->category->name])
        <li class="breadcrumb-item"><a href="{{ route('admin.packages.index') }}">Packages</a></li>
        <li class="breadcrumb-item active">{{ $package->name_en }}</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">{{ $package->name . ' - '.  $package->category->name }} </h4>
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <tbody>
                        {{--@if($package->image)--}}
                            {{--<tr>--}}
                                {{--<th>Image</th>--}}
                                {{--<td><img src="{{ $package->image }}" class="thumb-sm"/></td>--}}
                            {{--</tr>--}}
                        {{--@endif--}}
                        <tr>
                            <th>Name in English</th>
                            <td>{{ $package->name_en }}</td>
                        </tr>
                        <tr>
                            <th>Name in Arabic</th>
                            <td>{{ $package->name_ar }}</td>
                        </tr>
                        <tr>
                            <th>Description in English</th>
                            <td>{{ $package->description_en }}</td>
                        </tr>
                        <tr>
                            <th>Description in Arabic</th>
                            <td>{{ $package->description_ar }}</td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td>{{ $package->price }}</td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>{{ $package->duration }}</td>
                        </tr>

                        </tbody>
                    </table>

                </div>

            </div>

            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Edit Package : Package {{ $package->name . ' - '.  $package->category->name }} </h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::model($package,['route' => ['admin.packages.update',$package->id], 'method' => 'patch','files'=>true], ['class'=>'']) !!}
                    @include('admin.packages.edit',['category'=>$package])
                    {!! Form::close() !!}
                </div>
            </div>

            {{--<div class="card-box">--}}
            {{--<h4 class="text-dark header-title m-t-0">Edit Package : Package {{ $package->name . ' - '.  $package->category->name }} </h4>--}}
            {{--<hr>--}}
            {{--<div class="m-t-20">--}}

            {{--{!! Form::open(['route' => ['admin.packages.services.update',$package->id], 'method' => 'post'], ['class'=>'']) !!}--}}

            {{--<div class="form-group">--}}
            {{--{!! Form::label('category', 'Select a category', ['class' => 'control-label']) !!} <span class="red">*</span>--}}
            {{--<select name="services[]" class="col-lg-12 select2 multiselect multiselect-inverse" multiple>--}}
            {{--@foreach($parentCategory->children as $childCategory)--}}
            {{--<option value="{{ $childCategory->id }}"--}}
            {{--@if(in_array($childCategory->id,$selectedCategories))--}}
            {{--selected="selected"--}}
            {{--@endif--}}
            {{-->--}}
            {{--{{ $childCategory->name }}--}}
            {{--</option>--}}
            {{--@endforeach--}}
            {{--</select>--}}
            {{--</div>--}}

            {{--<div class="form-group">--}}
            {{--<button type="submit" class="btn btn-success" style="width: 100%">Save</button>--}}
            {{--</div>--}}

            {{--{!! Form::close() !!}--}}
            {{--</div>--}}
            {{--</div>--}}

        </div> <!-- end col -->

        <div class="col-lg-6">

            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Services for Package {{ $package->name . ' - '.  $package->category->name }}</h4>
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name (English)</th>
                            <th>Name (Arabic)</th>
                            <th>Package</th>
                            <th>Included</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        @foreach($package->services as $service)
                            <tr>
                                <td><img src="{{$service->image}}" class="thumb-sm"/> </td>
                                <td><a href="{{ route('admin.services.show',$service->id) }}">{{ $service->name_en }}</a></td>
                                <td><a href="{{ route('admin.services.show',$service->id) }}">{{ $service->name_ar }}</a></td>
                                <td><a href="{{ route('admin.packages.show',$service->package->id) }}">{{ $service->package->name }}</a></td>
                                <td>{{ $service->included ? 'Yes' : 'No' }}</td>
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

            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Add Service for Package {{ $package->name . ' - '.  $package->category->name }}</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::open(['route' => ['admin.services.store'], 'method' => 'post','files'=>true], ['class'=>'']) !!}
                    {!! Form::hidden('package_id',$package->id) !!}
                    @include('admin.services.edit')
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>

@endsection