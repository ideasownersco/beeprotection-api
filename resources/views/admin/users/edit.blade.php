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
    <label for="image">Admin</label><br>
    {!! Form::hidden('admin',0) !!}
    {!! Form::checkbox('admin',1,$user->admin ? 1: 0) !!}
</div>


<div class="form-group">
    <label for="image">Active</label><br>
    {!! Form::hidden('active',0) !!}
    {!! Form::checkbox('active',1,$user->active ? 1: 0) !!}
</div>

<div class="form-group">
    <label for="image">Blocked</label><br>
    {!! Form::hidden('blocked',0) !!}
    {!! Form::checkbox('blocked',1,$user->blocked ? 1: 0) !!}
</div>



<div class="form-group">
    <label for="image">Image</label>
    <input name="image" type="file" id="image">
</div>

<div class="form-group">
    <button type="submit" class="btn btn-success" style="width: 100%">Save</button>
</div>