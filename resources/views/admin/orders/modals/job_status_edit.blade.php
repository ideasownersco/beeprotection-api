{!! Form::open(['route' => ['admin.orders.job.status.update',$order->id], 'method' => 'POST'], ['class'=>'']) !!}

<div id="job-status-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content c-square">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Change Job Status</h4>
            </div>
            <div class="modal-body">
                <select name="status" class="form-control  c-square c-border-2px c-theme">
                    <option value="pending" {{ $order->job->status === 'pending' ? "selected" : "" }}>Pending</option>
                    <option value="driving" {{ $order->job->status === 'driving' ? "selected" : "" }}>Driving</option>
                    <option value="reached" {{ $order->job->status === 'reached' ? "selected" : "" }}>Reached</option>
                    <option value="working" {{ $order->job->status === 'working' ? "selected" : "" }}>Working</option>
                    <option value="completed" {{ $order->job->status === 'completed' ? "selected" : "" }}>Completed</option>
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