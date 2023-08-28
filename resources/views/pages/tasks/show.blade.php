@extends('layout.app')

@section('title', ' | Show task')

@section('content')

    <section class="content-header">
        <h1>
            task #{{ $task->id }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/tasks') }}">Tasks</a></li>
            <li class="active">Show</li>
        </ol>
    </section>


    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{ url('/admin/tasks') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>

                        @if(user_can('edit_task'))
                            <a href="{{ url('/admin/tasks/' . $task->id . '/edit') }}" title="Edit task"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        @endif

                        @if(user_can('delete_task'))
                            <form method="POST" action="{{ url('admin/tasks' . '/' . $task->id) }}" accept-charset="UTF-8" style="display:inline">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete task" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                            </form>
                        @endif

                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    @if(\Auth::user()->is_admin == 1)
                                        <tr>
                                            <th>ID</th><td>{{ $task->id }}</td>
                                        </tr>
                                    @endif
                                    <tr><th> Name </th><td> {{ $task->name }} </td></tr>
                                    <tr><th> Priority </th><td> {{ $task->priority }} </td></tr>
                                    <tr><th> Status </th><td> {{ $task->getStatus->name }} </td></tr>
                                    <tr>
                                        <th>Type</th><td> {{ $task->type->name }}</td>
                                    </tr>
                                    <tr><th>Start date</th> <td>{{ $task->start_date }}</td></tr>
                                    <tr><th>End date</th> <td>{{ $task->end_date }}</td></tr>
                                    <tr><th>Complete date</th> <td>{{ $task->complete_date }}</td></tr>
                                    <tr><th>Contact</th> <td> {!! !empty($task->contact_type)&&!empty($task->contact_id)?'<a href="' . url('/admin/contacts/' . $task->contact_id) . '">'.$task->contact->getName() . " (<i class=\"btn bg-maroon\">".$task->contact_type."</i>)".'</a>':"" !!}</td></tr>
                                    <tr>
                                        <th>Description</th> <td>{!! $task->description !!}</td>
                                    </tr>
                                    @if(\Auth::user()->is_admin == 1)
                                        <tr><th> Created by </th><td>{{ $task->createdBy->name }}</td></tr>
                                        <tr><th> Modified by </th><td>{{ isset($task->modifiedBy->name)?$task->modifiedBy->name:"" }}</td></tr>
                                    @endif

                                    <tr><th> Assigned to </th><td>{{ $task->assignedTo != null ?$task->assignedTo->name : "not set" }}</td></tr>
                                    <tr><th> Created at </th><td>{{ $task->created_at }}</td></tr>
                                    <tr><th> Modified at </th><td>{{ $task->updated_at }}</td></tr>
                                    @if($task->documents->count() > 0)
                                        <tr><th>Documents </th> <td>@foreach($task->documents as $document) <a href="{{ url('uploads/documents/' . $document->file) }}">{{ $document->name }}</a> - @endforeach</td></tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection