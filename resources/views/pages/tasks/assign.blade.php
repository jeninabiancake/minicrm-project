@extends('layout.app')

@section('title', ' | Assign task')

@section('content')

    <section class="content-header">
        <h1>
            Assign task
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/tasks') }}">Tasks</a></li>
            <li class="active">Assign</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('/admin/tasks') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/admin/tasks/' . $task->id . '/assign') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            {{ method_field("put") }}

                            <div class="form-group {{ $errors->has('assigned_user_id') ? 'has-error' : ''}}">
                                <label for="assigned_user_id" class="control-label">{{ 'Assigned User' }}</label>
                                <select name="assigned_user_id" id="assigned_user_id" class="form-control">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>

                                {!! $errors->first('assigned_user_id', '<p class="help-block">:message</p>') !!}
                            </div>

                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="Assign">
                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection