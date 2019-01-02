<div class="form-group">
    <label for="companyName">Name</label>
    {!! Form::text('name',null,['class'=>'form-control','placeholder'=>'Name']) !!}
</div>

<div class="form-group">
    <label for="companyName">Email</label>
    {!! Form::text('email',null,['class'=>'form-control','placeholder'=>'Email']) !!}
</div>

<div class="form-group">
    <label for="companyName">Mobile</label>
    {!! Form::text('mobile',null,['class'=>'form-control','placeholder'=>'Mobile']) !!}
</div>

    <div class="form-group">
        <label for="companyName">Password</label>
        {!! Form::password('password',['class'=>'form-control','type'=>'password','placeholder'=>'Password']) !!}
    </div>

<div class="form-group">
    <label for="companyName">Confirm Password</label>
    {!! Form::password('password_confirmation',['class'=>'form-control','type'=>'password','placeholder'=>'Confirm Password']) !!}
</div>

<div class="form-group">
    <label>Work Starting At</label>
    <div class="input-group">
        {!! Form::text('start_time',$driver->start_time,['class'=>'form-control','id'=>'start_time']) !!}
        <div class="input-group-append">
            <span class="input-group-text"><i class="md md-access-time"></i></span>
        </div>
    </div><!-- input-group -->
</div>

<div class="form-group">
    <label>Work Ending At</label>
    <div class="input-group">
        {!! Form::text('end_time',$driver->end_time,['class'=>'form-control','id'=>'end_time']) !!}
        <div class="input-group-append">
            <span class="input-group-text"><i class="md md-access-time"></i></span>
        </div>
    </div><!-- input-group -->
</div>

<div class="form-group">
    <label for="image">Status</label><br>
    {!! Form::radio('offline',0,$driver->offline ? false : true) !!}Online &nbsp;
    {!! Form::radio('offline',1,$driver->offline ? true : false) !!}Offline
</div>

<div class="form-group">
    <label for="image">Driver Image</label>
    <input name="image" type="file" id="image" class="form-control">
</div>

<div class="form-group">
    <button type="submit" class="btn btn-success" style="width: 100%">Save</button>
</div>