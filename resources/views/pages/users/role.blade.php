@extends('layout.app')

@section('title', ' | Select role')

@section('content')

    <section class="content-header">
        <h1>
            Select role
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/users') }}"> Users </a></li>
            <li class="active">Select role</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('/admin/users') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/admin/users/role/' . $user->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('put') }}

                            <div class="form-group {{ $errors->has('role_id') ? 'has-error' : ''}}">
                                <label for="role_id" class="control-label">{{ 'Role' }}</label>

                                <select name="role_id" id="role_id" class="form-control">
                                    <option value="">select role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ isset($user->roles[0]) && $role->id == $user->roles[0]->id?"selected":"" }}>{{ $role->name }}</option>
                                    @endforeach
                                </select>

                                {!! $errors->first('role_id', '<p class="help-block">:message</p>') !!}
                            </div>

                            <div class="form-group">
                                <input class="btn btn-primary" type="submit" value="Update">
                            </div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection