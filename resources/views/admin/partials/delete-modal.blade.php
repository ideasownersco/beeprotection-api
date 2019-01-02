<div class="modal fade" id="deleteModalBox" tabindex="-1" role="dialog" aria-labelledby="CenterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="CenterModalLabel">Delete {{ isset($title) ? $title : '' }} </h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <div class="inner-xs text-center">
                    {{ isset($info) ? $info : 'Please confirm' }}
                </div>
            </div>
            <div class="modal-footer">
                {!! Form::open(['url'=> '/', 'method'=>'delete', 'id'=>'deleteModal']) !!}
                <button type="button" class="btn btn-secondary waves-effect" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary waves-effect waves-light">Yes</button>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

