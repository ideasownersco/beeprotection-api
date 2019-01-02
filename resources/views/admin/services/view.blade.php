@extends('admin.layouts.app')

@section('content')

    @component('admin.partials.breadcrumbs',['title' => 'Service : ' . $service->name . ' - ' . $service->package->name . ' - ' . $service->package->category->name])
        <li class="breadcrumb-item"><a href="{{ route('admin.packages.index') }}">Packages</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.packages.show',$service->package->id) }}">{{ $service->package->name }}</a></li>
        <li class="breadcrumb-item active">{{ $service->name_en }}</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">{{ 'Service : ' . $service->name . ' - ' . $service->package->name . ' - ' . $service->package->category->name }}</h4>
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <tbody>
                        @if($service->image)
                            <tr>
                                <th>Image</th>
                                <td><img src="{{ $service->image }}" class="thumb-sm"/></td>
                            </tr>
                        <tbody>
                        @endif
                        <tr>
                            <th>Name in English</th>
                            <td>{{ $service->name_en }}</td>
                        </tr>
                        <tr>
                            <th>Name in Arabic</th>
                            <td>{{ $service->name_ar }}</td>
                        </tr>
                        <tr>
                            <th>Description in English</th>
                            <td>{{ $service->description_en }}</td>
                        </tr>
                        <tr>
                            <th>Description in Arabic</th>
                            <td>{{ $service->description_ar }}</td>
                        </tr>
                        <tr>
                            <th>Price</th>
                            <td>{{ $service->price }}</td>
                        </tr>
                        <tr>
                            <th>Duration</th>
                            <td>{{ $service->duration }}</td>
                        </tr>

                        <tr>
                            <th>Included</th>
                            <td>{{ $service->included ? 'Yes' : 'No' }}</td>
                        </tr>

                        </tbody>
                    </table>

                </div>

            </div>
        </div> <!-- end col -->

        <div class="col-lg-6">
            <div class="card-box">
                <h4 class="text-dark header-title m-t-0">Edit {{ 'Service : ' . $service->name . ' - ' . $service->package->name . ' - ' . $service->package->category->name }}</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::model($service,['route' => ['admin.services.update',$service->id], 'method' => 'patch','files'=>true], ['class'=>'']) !!}
                    @include('admin.services.edit',['category'=>$service])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection