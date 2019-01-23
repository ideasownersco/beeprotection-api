{!! Form::open(['route' => ['admin.orders.address.update',$order->id], 'method' => 'POST'], ['class'=>'']) !!}

<div id="address-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content c-square">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Change Address</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="companyAddress">Company Address</label>
                    <div class="map-wrapper">
                        <div id="gmaps-markers" class="gmaps"></div>
                        {!! Form::hidden('latitude',optional($order->address)->latitude, array('id' => 'latitude')) !!}
                        {!! Form::hidden('longitude',optional($order->address)->longitude, array('id' => 'longitude')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label for="companyName">Area</label>
                    {!! Form::select('area_id',$areas,optional($order->address)->area_id,['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    <label for="companyName">Block</label>
                    {!! Form::text('block',optional($order->address)->block,['class'=>'form-control','placeholder'=>'Block']) !!}
                </div>
                <div class="form-group">
                    <label for="companyName">Street</label>
                    {!! Form::text('street',optional($order->address)->street,['class'=>'form-control','placeholder'=>'Street']) !!}
                </div>
                <div class="form-group">
                    <label for="companyName">Avenue</label>
                    {!! Form::text('avenue',optional($order->address)->avenue,['class'=>'form-control','placeholder'=>'Avenue']) !!}
                </div>
                <div class="form-group">
                    <label for="companyName">Building</label>
                    {!! Form::text('building',optional($order->address)->building,['class'=>'form-control','placeholder'=>'Building']) !!}
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