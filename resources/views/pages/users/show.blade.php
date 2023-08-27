@extends('layout.app')

@section('title', ' | Show user')

@section('content')

    <section class="content-header">
        <h1>
            user #{{ $user->id }}
        </h1>

        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/users') }}"> Users </a></li>
            <li class="active">Show</li>
        </ol>
    </section>


    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        @include('includes.flash_message')

                        <a href="{{ url('/admin/users') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>

                        <a href="{{ url('/admin/users/' . $user->id . '/edit') }}" title="Edit user"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>

                        @if($user->is_admin == 0)
                            <form method="POST" action="{{ url('admin/users' . '/' . $user->id) }}" accept-charset="UTF-8" style="display:inline">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete user" onclick="return confirm('Confirm delete?');"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                            </form>
                        @endif
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>

                                    @if(!empty($user->image))
                                        <tr>
                                            <td>
                                                <img src="{{ url('uploads/users/' . $user->image) }}" class="pull-right" width="200" height="200" />
                                            </td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <th>ID</th><td>{{ $user->id }}</td>
                                    </tr>
                                    <tr><th> Name </th><td> {{ $user->name }} </td>
                                    </tr><tr><th> Email </th><td> {{ $user->email }} </td></tr>
                                    <tr><th> Position Title </th><td> {{ $user->position_title }} </td></tr>
                                    <tr><th> Phone </th><td> {{ $user->phone }} </td></tr>
                                    <tr><th> Is Admin </th><td> {!! $user->is_admin == 1? '<i class="fa fa-check"></i>':'<i class="fa fa-times"></i>' !!} </td></tr>
                                    <tr><th> Is Active </th><td> {!! $user->is_active == 1? '<i class="fa fa-check"></i>':'<i class="fa fa-ban"></i>' !!} </td></tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection