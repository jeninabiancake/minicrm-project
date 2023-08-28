<div class="row">
    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading">Basic details</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                            <label for="name" class="control-label">{{ 'Name' }}</label>
                            <input class="form-control" name="name" type="text" id="name" value="{{ isset($task->name) ? $task->name : ''}}" >
                            {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {{ $errors->has('contact_type') ? 'has-error' : ''}}">
                            <label for="contact_type" class="control-label">{{ 'Contact type' }}</label>
                            <select class="form-control" name="contact_type" id="contact_type">
                                <option value="">Select type</option>
                                @foreach($contact_statuses as $contact_status)
                                    <option value="{{ $contact_status->name }}" {{ isset($task->contact_type) && $task->contact_type == $contact_status->name ? 'selected' : ''}}>{{ $contact_status->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->first('contact_type', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('contact_id') ? 'has-error' : ''}}">
                            <label for="contact_id" class="control-label">{{ 'Contact name' }}</label>
                            <select name="contact_id" id="contact_id" class="form-control" data-selected-value="{{ isset($task->contact_id) ? $task->contact_id : '' }}"></select>
                            {!! $errors->first('contact_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('type_id') ? 'has-error' : ''}}">
                            <label for="type_id" class="control-label">{{ 'Task type' }}</label>
                            <select name="type_id" id="type_id" class="form-control">
                                @foreach($task_types as $type)
                                    <option value="{{ $type->id }}" {{ isset($task->type_id) && $task->type_id == $type->id ? 'selected' : ''}}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->first('type_id', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                            <label for="status" class="control-label">{{ 'Contact status' }}</label>
                            <select name="status" id="status" class="form-control">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}" {{ isset($task->status) && $task->status == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                                @endforeach
                            </select>
                            {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group {{ $errors->has('priority') ? 'has-error' : ''}}">
                            <label for="priority" class="control-label">{{ 'Priority' }}</label>
                            <select class="form-control" name="priority" id="priority">
                                <option value="">Select priority</option>
                                @foreach(array('Low', 'Normal', 'High', 'Urgent') as $value)
                                    <option value="{{ $value }}" {{ isset($task->priority) && $task->priority == $value ? 'selected' : ''}}>{{ $value }}</option>
                                @endforeach
                            </select>
                            {!! $errors->first('priority', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
                            <label for="description" class="control-label">{{ 'Description' }}</label>
                            <textarea class="form-control" name="description" type="text" id="description">{{ isset($task->description) ? $task->description : ''}}</textarea>
                            {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading">Task dates</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('start_date') ? 'has-error' : ''}}">
                            <label for="start_date" class="control-label">{{ 'Start date' }}</label>
                            <input class="form-control" name="start_date" type="text" id="start_date" value="{{ isset($task->start_date) ? $task->start_date : ''}}" >
                            {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('end_date') ? 'has-error' : ''}}">
                            <label for="end_date" class="control-label">{{ 'End date' }}</label>
                            <input class="form-control" name="end_date" type="text" id="end_date" value="{{ isset($task->end_date) ? $task->end_date : ''}}" >
                            {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group {{ $errors->has('complete_date') ? 'has-error' : ''}}">
                            <label for="complete_date" class="control-label">{{ 'Completed date' }}</label>
                            <input class="form-control" name="complete_date" type="text" id="complete_date" value="{{ isset($task->complete_date) ? $task->complete_date : ''}}" >
                            {!! $errors->first('complete_date', '<p class="help-block">:message</p>') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="documents" class="control-label">{{ 'Documents' }} <i class="fa fa-link"></i></label>
    <select name="documents[]" id="documents" multiple class="form-control">
        @foreach($documents as $document)
            <option value="{{ $document->id }}" {{ isset($selected_documents) && in_array($document->id, $selected_documents)?"selected":"" }}>{{ $document->name }}</option>
        @endforeach
    </select>
</div>

@if(\Auth::user()->is_admin == 1)
    <div class="form-group {{ $errors->has('assigned_user_id') ? 'has-error' : ''}}">
        <label for="assigned_user_id" class="control-label">{{ 'Assigned User' }}</label>
        <select name="assigned_user_id" id="assigned_user_id" class="form-control">
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ isset($task->assigned_user_id) && $task->assigned_user_id == $user->id?"selected":"" }}>{{ $user->name }}</option>
            @endforeach
        </select>

        {!! $errors->first('assigned_user_id', '<p class="help-block">:message</p>') !!}
    </div>
@endif


<div class="form-group">
    <input class="btn btn-primary" type="submit" value="{{ $formMode === 'edit' ? 'Update' : 'Create' }}">
</div>

<input type="hidden" id="getContactsAjaxUrl" value="{{ url('/admin/api/contacts/get-contacts-by-status') }}" />