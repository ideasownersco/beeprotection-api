{!! Form::open(['route' => ['admin.orders.datetime.update',$order->id], 'method' => 'POST'], ['class'=>'']) !!}

<div id="datetime-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content c-square">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Change Date & Time</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Select Date</label>
                    <div>
                        <div class="input-group">
                            <input onChange="getTimings($(this).val());" name="date" value="{{$todaysDate}}" type="text" class="form-control" placeholder="mm/dd/yyyy" id="datepicker" autocomplete="off">
                            <div class="input-group-append">
                                <span class="input-group-text"><i class="md md-event-note"></i></span>
                            </div>
                        </div><!-- input-group -->

                    </div>
                </div>

                <div class="form-group">
                    <label>Selected Time</label>
                    <select class="custom-select mt-2 mb-2" name="time" id="time">
                        <option value="">Select Time</option>
                    </select>
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