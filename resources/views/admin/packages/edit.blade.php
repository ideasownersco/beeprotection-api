<div class="form-group">
    <label for="companyName">Category</label>
    {!! Form::select('category_id',$categories,null,['class'=>'form-control']) !!}
</div>

<div class="form-group">
    <label for="companyName">Name (English)</label>
    {!! Form::text('name_en',null,['class'=>'form-control','placeholder'=>'Name (English)']) !!}
</div>

<div class="form-group">
    <label for="companyName">Name (Arabic)</label>
    {!! Form::text('name_ar',null,['class'=>'form-control','placeholder'=>'Name (Arabic)']) !!}
</div>


<div class="form-group">
    <label for="companyName">Description (Arabic)</label>
    {!! Form::textarea('description_ar',null,['class'=>'form-control editor_en','placeholder'=>'Description (Arabic)','rows'=>2]) !!}
</div>

<div class="form-group">
    <label for="companyName">Description (English)</label>
    {!! Form::textarea('description_en',null,['class'=>'form-control editor_en','placeholder'=>'Description (English)','rows'=>2]) !!}
</div>

<div class="form-group">
    <label for="companyName">Price</label>
    {!! Form::text('price',null,['class'=>'form-control','placeholder'=>'Amount (ex:10)']) !!}
</div>

<div class="form-group">
    <label for="companyName">Duration (minutes ex:10)</label>
    {!! Form::text('duration',null,['class'=>'form-control','placeholder'=>'Working Duration (ex:10)']) !!}
</div>

<div class="form-group">
    <label for="image">Category Image</label>
    <input name="image" type="file" id="image">
</div>

<div class="form-group">
    <button type="submit" class="btn btn-success" style="width: 100%">Save</button>
</div>