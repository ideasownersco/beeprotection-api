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
        $('#name_en').timepicker({
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
        <div class="col-lg-6">
            <div class="card-box">
                <div class="table-responsive m-t-10">
                    <table class="table table-actions-bar">
                        <thead>
                        <tr>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        @foreach($timings as $timing)
                            <tr>
                                <td>{{ $timing->name_en }}</td>
                                <td>
                                    <a href="#" data-toggle="modal" data-target="#deleteModalBox"
                                       data-link="{{route('admin.timings.destroy',$timing->id)}}"
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
                <h4 class="text-dark header-title m-t-0">Add Timing</h4>
                <hr>
                <div class="m-t-20">
                    {!! Form::open(['route' => ['admin.timings.store'], 'method' => 'post','files'=>true], ['class'=>'']) !!}
                    @include('admin.timings.edit')
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection