@extends('layout.app')

@section('title', ' | List Documents')

@section('content')

    <section class="content-header">
        <h1>
            Documents
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Documents</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        @include('includes.flash_message')

                        @if(user_can("create_document"))
                            <a href="{{ url('/admin/documents/create') }}" class="btn btn-success btn-sm pull-right" title="Add New document">
                                <i class="fa fa-plus" aria-hidden="true"></i> Add New
                            </a>
                        @endif

                        <form method="GET" action="{{ url('/admin/documents') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0" role="search">
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
                                    <th>File</th>
                                    <th>Status</th>
                                    <th>Created at</th>
                                    @if(\Auth::user()->is_admin == 1)
                                        <th>Created by</th>
                                    @endif
                                        <th>Assigned to</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($documents as $item)
                                        <tr>
                                            @if(\Auth::user()->is_admin == 1)
                                                <td>{{ $item->id }}</td>
                                            @endif
                                            <td>{{ $item->name }}</td>
                                            <td>@if(!empty($item->file)) <a href="{{ url('uploads/documents/' . $item->file) }}"> <i class="fa fa-download"></i> {{$item->file}}</a> @endif</td>
                                            <td>{!! $item->status == 1?"<i class='label label-success'>Active</i>":"<i class='label label-danger'>Not active</i>" !!}</td>
                                            <td>{{ $item->created_at }}</td>
                                            @if(\Auth::user()->is_admin == 1)
                                                <td>{{ $item->createdBy->name }}</td>
                                            @endif
                                                <td>{{ $item->assignedTo != null ? $item->assignedTo->name : "not set" }}</td>
                                            <td>

                                                @if(user_can('view_document'))
                                                    <a href="{{ url('/admin/documents/' . $item->id) }}" title="View document"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                                @endif

                                                @if(user_can('edit_document'))
                                                    <a href="{{ url('/admin/documents/' . $item->id . '/edit') }}" title="Edit document"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                                @endif

                                                @if(user_can('assign_document'))
                                                    <a href="{{ url('/admin/documents/' . $item->id . '/assign') }}" title="Assign document"><button class="btn btn-primary btn-sm"><i class="fa fa-envelope-o" aria-hidden="true"></i> Assign</button></a>
                                                @endif

                                                @if(user_can('delete_document'))
                                                    <form method="POST" action="{{ url('/admin/documents' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                        {{ method_field('DELETE') }}
                                                        {{ csrf_field() }}
                                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete document" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                                    </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="pagination-wrapper"> {!! $documents->appends(['search' => Request::get('search')])->render() !!} </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection