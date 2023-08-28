@extends('layout.app')

@section('title', ' | Create contact')

@section('content')

    <section class="content-header">
        <h1>
            Create New contact
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/contacts') }}">Contacts</a></li>
            <li class="active">Create</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <a href="{{ url('/admin/contacts') }}" title="Back"><button class="btn btn-warning btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</button></a>
                        <br />
                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/admin/contacts') }}" accept-charset="UTF-8" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('pages.contacts.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')

    <script type="text/javascript" src="{{ url('theme/views/contacts/form.js') }}"></script>

@stop