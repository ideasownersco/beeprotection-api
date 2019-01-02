<div class="form-group">
    <label for="companyName">Name (English)</label>
    {!! Form::text('name_en',null,['class'=>'form-control','placeholder'=>'Name (English)']) !!}
</div>

<div class="form-group">
    <label for="companyName">Name (Arabic)</label>
    {!! Form::text('name_ar',null,['class'=>'form-control','placeholder'=>'Name (Arabic)']) !!}
</div>

<div class="form-group">
    <label for="image">Category Image</label>
    <input name="image" type="file" id="image">
</div>

{{--<span class="form-g"></span>--}}

{{--<div class="form-group">--}}
    {{--<label for="companyName"><a href="#" id="attach">attach to category</a></label>--}}
{{--</div>--}}


<div class="form-group">
    <button type="submit" class="btn btn-success" style="width: 100%">Save</button>
</div>