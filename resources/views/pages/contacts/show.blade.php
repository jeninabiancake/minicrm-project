@extends('layout.app')

@section('title', ' | Show contact')

@section('content')

    <section class="content-header">
        <h1>
            contact #{{ $contact->id }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/contacts') }}">Contacts</a></li>
            <li class="active">Show</li>
        </ol>
    </section>


    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <a href="{{ url('/admin/contacts') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>

                        @if(user_can('edit_contact'))
                            <a href="{{ url('/admin/contacts/' . $contact->id . '/edit') }}" title="Edit contact"><button class="btn btn-primary btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</button></a>
                        @endif

                        @if(user_can('delete_contact'))
                            <form method="POST" action="{{ url('admin/contacts' . '/' . $contact->id) }}" accept-charset="UTF-8" style="display:inline">
                                {{ method_field('DELETE') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete contact" onclick="return confirm('Confirm delete?')"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</button>
                            </form>
                        @endif
                        <br/>
                        <br/>

                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    @if(\Auth::user()->is_admin == 1)
                                        <tr>
                                            <th>ID</th><td>{{ $contact->id }}</td>
                                        </tr>
                                    @endif
                                    <tr><th> First Name </th><td> {{ $contact->first_name }} </td>
                                    </tr><tr><th> Middle Name </th><td> {{ $contact->middle_name }} </td></tr>
                                    <tr><th> Last Name </th><td> {{ $contact->last_name }} </td></tr>
                                    <tr><th> Status </th><td> <i class="btn bg-maroon">{{ $contact->getStatus->name }}</i> </td></tr>
                                    <tr><th> Referral source </th><td> {{ $contact->referral_cource }} </td></tr>
                                    <tr><th> Position title </th><td> {{ $contact->position_title }} </td></tr>
                                    <tr><th> Industry </th><td> {{ $contact->industry }} </td></tr>
                                    <tr><th> Project type </th><td> {{ $contact->project_type }} </td></tr>
                                    <tr><th> Project description </th><td> {{ $contact->project_description }} </td></tr>
                                    <tr><th> Description </th><td> {{ $contact->description }} </td></tr>
                                    <tr><th> Company </th><td> {{ $contact->company }} </td></tr>
                                    <tr><th> Budget </th><td> {{ $contact->budget }} </td></tr>
                                    <tr><th> Website </th><td> {{ $contact->website }} </td></tr>
                                    <tr><th> Linkedin </th><td> {{ $contact->linkedin }} </td></tr>
                                    <tr><th> Street </th><td> {{ $contact->address_street }} </td></tr>
                                    <tr><th> City </th><td> {{ $contact->address_city }} </td></tr>
                                    <tr><th> State </th><td> {{ $contact->address_state }} </td></tr>
                                    <tr><th> Country </th><td> {{ $contact->address_country }} </td></tr>
                                    <tr><th> Zipcode </th><td> {{ $contact->address_zipcode }} </td></tr>
                                    @if(\Auth::user()->is_admin == 1)
                                        <tr><th> Created by </th><td>{{ $contact->createdBy->name }}</td></tr>
                                        <tr><th> Modified by </th><td>{{ isset($contact->modifiedBy->name)?$contact->modifiedBy->name:"" }}</td></tr>
                                    @endif

                                    <tr><th> Assigned to </th><td>{{ $contact->assignedTo != null ?$contact->assignedTo->name : "not set" }}</td></tr>
                                    <tr><th> Created at </th><td>{{ $contact->created_at }}</td></tr>
                                    <tr><th> Modified at </th><td>{{ $contact->updated_at }}</td></tr>
                                    @if($contact->emails->count() > 0)
                                        <tr><th>Emails </th> <td>{{ implode(", ", array_column($contact->emails->toArray(), "email")) }}</td></tr>
                                    @endif
                                    @if($contact->phones->count() > 0)
                                        <tr><th>Phones </th> <td>{{ implode(", ", array_column($contact->phones->toArray(), "phone")) }}</td></tr>
                                    @endif
                                    @if($contact->documents->count() > 0)
                                        <tr><th>Documents </th> <td>@foreach($contact->documents as $document) <a href="{{ url('uploads/documents/' . $document->file) }}">{{ $document->name }}</a> - @endforeach</td></tr>
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