@extends('layout.app')

@section('title', ' | List Contacts')

@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    @include('includes.flash_message')

                    @if(user_can("create_contact"))
                        <a href="{{ url('/admin/contacts/create') }}" class="btn btn-success btn-sm pull-right" title="Add New contact">
                            <i class="fa fa-plus" aria-hidden="true"></i> Add New
                        </a>
                    @endif

                    <form method="GET" action="{{ url('/admin/contacts') }}" accept-charset="UTF-8" class="form-inline my-2 my-lg-0" role="search">
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
                                    <th>Id</th>
                                @endif
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
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
                                @foreach($contacts as $item)
                                    <tr>
                                        @if(\Auth::user()->is_admin == 1)
                                            <td>{{ $item->id }}</td>
                                        @endif
                                        <td>{{ $item->first_name }}</td>
                                        <td>{{ $item->middle_name }}</td>
                                        <td>{{ $item->last_name }}</td>
                                        <td><i class="btn bg-maroon">{{ $item->getStatus->name }}</i></td>
                                        @if(\Auth::user()->is_admin == 1)
                                            <td>{{ $item->createdBy->name }}</td>
                                        @endif
                                        <td>{{ $item->assignedTo != null ? $item->assignedTo->name : "not set" }}</td>
                                        
                                        <td>{{ $item->created_at }}</td>
                                        <td>

                                            @if(user_can('view_contact'))
                                                <a href="{{ url('/admin/contacts/' . $item->id) }}" title="View contact"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</button></a>
                                            @endif

                                            @if(user_can('edit_contact'))
                                                <a href="{{ url('/admin/contacts/' . $item->id . '/edit') }}" title="Edit contact"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                                            @endif

                                            @if(user_can('assign_contact'))
                                                <a href="{{ url('/admin/contacts/' . $item->id . '/assign') }}" title="Assign contact"><button class="btn btn-primary btn-sm"><i class="fa fa-envelope-o" aria-hidden="true"></i> Assign</button></a>
                                            @endif

                                            @if(user_can('delete_contact'))
                                                <form method="POST" action="{{ url('/admin/contacts' . '/' . $item->id) }}" accept-charset="UTF-8" style="display:inline">
                                                    {{ method_field('DELETE') }}
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete contact" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="pagination-wrapper"> {!! $contacts->appends(['search' => Request::get('search')])->render() !!} </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection