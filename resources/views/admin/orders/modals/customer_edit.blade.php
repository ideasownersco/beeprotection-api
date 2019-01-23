{!! Form::open(['route' => ['admin.orders.customer.update',$order->id], 'method' => 'POST'], ['class'=>'']) !!}
<div id="customer-edit-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content c-square">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Edit Customer</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="companyName">Name</label>
                    {!! Form::text('name',$name,['class'=>'form-control','placeholder'=>'Name']) !!}
                </div>

                <div class="form-group">
                    <label for="companyName">Email</label>
                    {!! Form::text('email',$email,['class'=>'form-control','placeholder'=>'Email']) !!}
                </div>

                <div class="form-group">
                    <label for="companyName">Mobile</label>
                    {!! Form::text('mobile',$mobile,['class'=>'form-control','placeholder'=>'Mobile']) !!}
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