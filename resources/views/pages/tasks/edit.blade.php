@extends('layout.app')

@section('title', ' | Edit task')

@section('content')


    <section class="content-header">
        <h1>
            Edit task #{{ $task->id }}
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin/') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/tasks') }}">Tasks</a></li>
            <li class="active">Edit</li>
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

                        <form method="POST" action="{{ url('/admin/tasks/' . $task->id) }}" accept-charset="UTF-8" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}

                            @include ('pages.tasks.form', ['formMode' => 'edit'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('scripts')

    <script type="text/javascript" src="{{ url('theme/views/tasks/form.js') }}"></script>

@stop