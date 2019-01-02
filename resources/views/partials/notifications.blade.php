
@if ($message = Session::get('success'))
    <div class="alert alert-success alert-block" style="margin:15px">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ $message }}
    </div>
@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-block" style="margin:15px">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ $message }}
    </div>
@endif

@if ($message = Session::get('errors'))
    <div class="alert alert-danger alert-block" style="margin:15px">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        @foreach ($message->all() as $m)
            <li>{{ $m }}  </li>
        @endforeach
    </div>
@endif

@if ($message = Session::get('warning'))
    <div class="alert alert-warning alert-block" style="margin:15px">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ $message }}
    </div>
@endif

@if ($message = Session::get('info'))
    <div class="alert alert-info alert-block" style="margin:15px">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{ $message }}
    </div>
@endif
