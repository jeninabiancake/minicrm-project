@extends('layout.app')

@section('title', ' | List Tasks')

@section('content')

    <section class="content-header">
        <h1>
            Tasks
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Tasks</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        @include('includes.flash_message')

                        @if(user_can("create_task"))
                            <a href="{{ url('/admin/tasks/create') }}" class="btn btn-success btn-sm pull-right" title="Add New task">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a>
                        @endif

                        <form method="GET" action="{{ url('/admin/tasks') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0" role="search">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                                <span class="input-group-btn">
                                    <button class="btn btn-secondary" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                        </form>

                        <br/>
                        <br/>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    @if(\Auth::user()->is_admin == 1)
                                        <th>#</th>
                                    @endif
                                    <th>Name</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    @if(\Auth::user()->is_admin == 1)
                                        <th>Created by</th>
                                    @endif
                                    <th>Assigned to</th>
                                    <th>Created at</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($tasks as $item)
                                        <tr>
                                            @if(\Auth::user()->is_admin == 1)
                                                <td>{{ $item->id }}</td>
                                            @endif
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->priority }}</td>
                                            <td>{{ $item->getStatus->name }}</td>
                                            @if(\Auth::user()->is_admin == 1)
                                                <td>{{ $item->createdBy->name }}</td>
                                            @endif
                                            <td>{{ $item->assignedTo != null ? $item->assignedTo->name : "not set" }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                @if(user_can('view_task'))
                                                    <a href="{{ url('/admin/tasks/' . $item->id) }}" title="View task"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                                @endif

                                                @if(user_can('edit_task'))
                                                    <a href="{{ url('/admin/tasks/' . $item->id . '/edit') }}" title="Edit task"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                                @endif

                                                @if(user_can('assign_task'))
                                                    <a href="{{ url('/admin/tasks/' . $item->id . '/assign') }}" title="Assign task"><button class="btn btn-primary btn-sm"><i class="fa fa-envelope-o" aria-hidden="true"></i> Assign</button></a>
                                                @endif

                                                @if(user_can('update_task_status'))
                                                    <a href="{{ url('/admin/tasks/' . $item->id . '/update-status') }}" title="Update task status"><button class="btn btn-primary btn-sm"><i class="fa fa-star" aria-hidden="true"></i> Update status</button></a>
                                                @endif

                                                @if(user_can('delete_task'))
                                                    <form method="POST" action="{{ url('/admin/tasks' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete task" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $tasks->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection