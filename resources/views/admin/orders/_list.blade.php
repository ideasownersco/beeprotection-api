<tr>
    <td><a href="{{ route('admin.orders.show',$order->id) }}">{{ $order->id }} </a></td>
    <td>
        @if($order->free_wash)
            Free Wash
        @else
            @foreach($order->packages as $package)
                <b>{{$package->category->name}}</b> :
                <a href="{{ route('admin.packages.show',$package->id) }}">{{ $package->name }} </a><br>
                @if($order->services->count())
                    <b>Add ons</b>
            (
                    @foreach($order->services as $service)
                        <a href="{{ route('admin.services.show',$service->id) }}">{{$service->name}}</a>
                        @if(!$loop->last), @endif
                    @endforeach
                    )
                @endif
                @if(!$loop->last)
                    <hr>
                @endif
            @endforeach
        @endif
    </td>
    <td>{{ $order->scheduled_time }} </td>
    <td>{{ $order->total }} KD</td>
    <td>{{ optional($order->address)->formatted_address }} </td>
    <td>
        @if($order->job && $order->job->driver)
            <a href="{{ route('admin.drivers.show',$order->job->driver->id) }}">{{ $order->job->driver->user->name }}</a>
        @endif
    </td>
    <td>

        @if($order->customer_name)
            {{ $order->customer_name }} ({{$order->customer_mobile}})
        @else
            @if($order->user)
                <a href="{{ route('admin.users.show',$order->user->id) }}">{{ $order->user->name }} ({{$order->user->mobile}})</a>
            @endif
        @endif
    </td>
    {{--<td><a href="{{ route('admin.users.show',$order->user->id) }}">{{ $order->user->name }} ({{$order->user->mobile}})</a> </td>--}}
    <td>
        @if($order->job)
            <btn class="btn btn-{{$order->job->button_name}} btn-xs">{{ optional($order->job)->status }}</btn>
        @endif
    </td>
    <td>{{ $order->created_at->format('d-m-Y g:i a') }} </td>
    <td>{{ strtoupper($order->payment_mode) }}</td>
    <td>
        {{--        <a href="{{ route('admin.orders.show',$order->id) }}" class="table-action-btn"><i class="md md-edit"></i></a>--}}
        <a href="#" data-toggle="modal" data-target="#deleteModalBox"
           data-link="{{route('admin.orders.destroy',$order->id)}}"
           class="table-action-btn"><i class="md md-close"></i></a>
    </td>
</tr>
