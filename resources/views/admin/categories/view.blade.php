@extends('admin.layouts.app')

@section('scripts')
    @parent
    <script>
      $(document).ready(function () {
        $('.order').change(function(e){
          var id = this.selectedOptions[0].value;
          var order  = this.selectedOptions[0].text;
          var url = '{{ route('admin.packages.reorganize') }}';
          $.ajax({
            url: url,
            type: 'post',
            data: { id: id, order: order},
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (res){
              console.log('res',res);
            },
            error: function (err){
              console.log('err',err);
            }
          });

        });
      });
    </script>
@endsection

@section('content')

    @component('admin.partials.breadcrumbs',['title' => $category->name_en])
        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
        <li class="breadcrumb-item active">{{ $category->name_en }}</li>
    @endcomponent

    <div class="row">
        <div class="col-lg-6">
            <div class="card-box">
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <tbody>
                        @if($category->image)
                            <tr>
                                <th>Image</th>
                                <td><img src="{{ $category->image }}" class="thumb-sm"/></td>
                            </tr>
                        <tbody>
                        @endif
                        <tr>
                            <th>Name in English</th>
                            <td>{{ $category->name_en }}</td>
                        </tr>
                        <tr>
                            <th>Name in Arabic</th>
                            <td>{{ $category->name_ar }}</td>
                        </tr>
                        </tbody>
                    </table>

                </div>

            </div>

            <div class="card-box">

                <h4 class="text-dark header-title m-t-0">Packages</h4>
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name (English)</th>
                            <th>Name (Arabic)</th>
                            <th>Category</th>
                            <th>Order</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        @foreach($category->packages as $package)
                            <tr>
                                <td>{{ $package->id }} </td>
                                <td><a href="{{ route('admin.packages.show',$package->id) }}">{{ $package->name_en }}</a></td>
                                <td><a href="{{ route('admin.packages.show',$package->id) }}">{{ $package->name_ar }}</a></td>
                                <td>
                                    @if($package->category)
                                        <a href="{{ route('admin.categories.show',$package->category->id) }}">{{ $package->category->name }}</a>
                                    @endif
                                </td>
                                <td>
                                    <select name="order" class="order form-control">
                                        @foreach($packageIDs as $value)
                                            <option id="{{$package->id}}" value="{{$package->id}}" @if($value == $package->order) selected="selected" @endif>{{ $value }}</option>
                                        @endforeach
                                    </select>
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
                <h4 class="text-dark header-title m-t-0">Add Category</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::model($category,['route' => ['admin.categories.update',$category->id], 'method' => 'patch','files'=>true], ['class'=>'']) !!}
                    @include('admin.categories.edit',['category'=>$category])
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection