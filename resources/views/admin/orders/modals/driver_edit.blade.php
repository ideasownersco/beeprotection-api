{!! Form::open(['route' => ['admin.orders.driver.assign',$order->id], 'method' => 'post','files'=>true], ['class'=>'']) !!}

<div id="driver-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content c-square">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Change Driver</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    {!! Form::select('driver_id',$driverNames,null,['class'=>'form-control']) !!}
                </div>
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