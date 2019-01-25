{!! Form::open(['route' => ['admin.orders.job.status.update',$order->id], 'method' => 'POST'], ['class'=>'']) !!}

<div id="job-status-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content c-square">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Change Job Status</h4>
            </div>
            <div class="modal-body">
                <select name="status" class="form-control  c-square c-border-2px c-theme">
                    <option value="pending" {{ optional($order->job)->status === 'pending' ? "selected" : "" }}>Pending</option>
                    <option value="driving" {{ optional($order->job)->status === 'driving' ? "selected" : "" }}>Driving</option>
                    <option value="reached" {{ optional($order->job)->status === 'reached' ? "selected" : "" }}>Reached</option>
                    <option value="working" {{ optional($order->job)->status === 'working' ? "selected" : "" }}>Working</option>
                    <option value="completed" {{ optional($order->job)->status === 'completed' ? "selected" : "" }}>Completed</option>
                    <option value="cancelled" {{ optional($order->job)->status === 'cancelled' ? "selected" : "" }}>Cancelled</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn c-theme-btn c-btn-border-2x c-btn-square c-btn-bold c-btn-uppercase" data-dismiss="modal">Close</button>
                <button type="submit" class="btn c-theme-btn c-btn-square c-btn-bold c-btn-uppercase">Submit</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
{!! Form::close() !!}