@extends('admin.layouts.app')

@section('scripts')
    @parent
    <script>
        $(document).ready(function () {
          $('.order').change(function(e){
            var id = this.selectedOptions[0].value;
            var order  = this.selectedOptions[0].text;
            var url = '{{ route('admin.categories.reorganize') }}';
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
                            <th>ID</th>
                            <th>Name (English)</th>
                            <th>Name (Arabic)</th>
                            <th>Order</th>
                            <th class="text-center">Action</th>
                        </tr>
                        </thead>
                        @foreach($categories as $category)
                            <tr>
                                <td><img src="{{$category->image}}" class="thumb-sm"/> </td>
                                <td>{{ $category->id }}</td>
                                <td><a href="{{ route('admin.categories.show',$category->id) }}">{{ $category->name_en }}</a></td>
                                <td><a href="{{ route('admin.categories.show',$category->id) }}">{{ $category->name_ar }}</a></td>
                                <td>
                                    <select name="order" class="order form-control">
                                    @foreach($categoryIDs as $value)
                                        <option id="{{$category->id}}" value="{{$category->id}}" @if($value == $category->order) selected="selected" @endif>{{ $value }}</option>
                                    @endforeach
                                    </select>
                                </td>
                                <td>
                                    <a href="{{ route('admin.categories.show',$category->id) }}" class="table-action-btn"><i class="md md-edit"></i></a>
                                    <a href="#" data-toggle="modal" data-target="#deleteModalBox"
                                       data-link="{{route('admin.categories.destroy',$category->id)}}"
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
                    {!! Form::open(['route' => ['admin.categories.store'], 'method' => 'post','files'=>true], ['class'=>'']) !!}
                    @include('admin.categories.edit')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.delete-modal',['title' => 'Category'])

@endsection