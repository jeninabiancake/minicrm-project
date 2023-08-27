<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
            <label for="name" class="control-label">{{ 'Name' }}</label>
            <input class="form-control" name="name" type="text" id="name" value="{{ isset($document->name) ? $document->name : ''}}">
            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        @if(isset($document->file) && !empty($document->file))
            <a href="{{ url('uploads/documents/' . $document->file) }}"><i class="fa fa-download"></i> {{$document->file}}</a>
        @endif
        <div class="form-group {{ $errors->has('file') ? 'has-error' : ''}}">
            <label for="file" class="control-label">{{ 'File' }}</label>
            <input class="form-control" name="file" type="file" id="file">
            {!! $errors->first('file', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
            <label for="status" class="control-label">{{ 'Status' }}</label>
            <select name="status" id="status" class="form-control">
                @foreach(array(1 => "active", 2 => "not active") as $key => $value)
                    <option value="{{ $key }}" {{ isset($document->status) && $document->status == $key?"selected":"" }}>{{ $value }}</option>
                @endforeach
            </select>
            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('type') ? 'has-error' : ''}}">
            <label for="type" class="control-label">{{ 'Type' }}</label>
            <select name="type" id="type" class="form-control">
                @foreach($document_types as $document_type)
                    <option value="{{ $document_type->id }}" {{ isset($document->typ) && $document->type == $document_type->id?"selected":"" }}>{{ $document_type->name }}</option>
                @endforeach
            </select>

            {!! $errors->first('type', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('publish_date') ? 'has-error' : ''}}">
            <label for="publish_date" class="control-label">{{ 'Publish Date' }}</label>
            <input class="form-control" name="publish_date" type="text" id="publish_date" value="{{ isset($document->publish_date) ? $document->publish_date : ''}}" >
            {!! $errors->first('publish_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('expiration_date') ? 'has-error' : ''}}">
            <label for="expiration_date" class="control-label">{{ 'Expiration Date' }}</label>
            <input class="form-control" name="expiration_date" type="text" id="expiration_date" value="{{ isset($document->expiration_date) ? $document->expiration_date : ''}}" >
            {!! $errors->first('expiration_date', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

@if(\Auth::user()->is_admin == 1)
<div class="form-group {{ $errors->has('assigned_user_id') ? 'has-error' : ''}}">
    <label for="assigned_user_id" class="control-label">{{ 'Assigned User' }}</label>
    <select name="assigned_user_id" id="assigned_user_id" class="form-control">
        @foreach($users as $user)
            <option value="{{ $user->id }}" {{ isset($document->assigned_user_id) && $document->assigned_user_id == $user->id?"selected":"" }}>{{ $user->name }}</option>
        @endforeach
    </select>

    {!! $errors->first('assigned_user_id', '<p class="help-block">:message</p>') !!}
</div>
@endif

<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>