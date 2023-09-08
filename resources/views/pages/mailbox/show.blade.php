@extends('layout.app')

@section('title', ' | Mailbox | Show Mail')

@section('content')

    <section class="content-header">
        <h1>
            Show Mail
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/mailbox') }}"> Mailbox</a></li>
            <li class="active">Show Mail</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <a href="{{ url('admin/mailbox') }}" class="btn btn-primary btn-block margin-bottom">Back to inbox</a>

                @include('pages.mailbox.includes.folders_panel')
            </div>

            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Read Mail</h3>
                    </div>

                    @include('includes.flash_message')

                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <div class="mailbox-read-info">
                            <h3>{{ $mailbox->subject }}</h3>
                            <h5>From: {{ $mailbox->sender->email }}
                                <span class="mailbox-read-time pull-right">{{ !empty($mailbox->time_sent)?date("d M. Y h:i A", strtotime($mailbox->time_sent)):"not sent yet" }}</span></h5>
                        </div>

                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            {!! $mailbox->body !!}
                        </div>
                        <!-- /.mailbox-read-message -->
                    </div>
                    <!-- /.box-body -->
                    <div class="box-footer">

                        @include('pages.mailbox.includes.attachments', ['mailbox' => $mailbox])

                    </div>
                </div>
                <!-- /. box -->

                @if($mailbox->replies->count() > 0)
                    <h3>Replies</h3>
                    @foreach($mailbox->replies as $reply)
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><strong>From: </strong>{{ $reply->sender->name }}</h3>
                            </div>
                            <div class="box-body no-padding">
                                <div class="mailbox-read-info">
                                    <h3>{{ $reply->subject }}</h3>
                                    <h5>From: {{ $reply->sender->email }}
                                        <span class="mailbox-read-time pull-right">{{ !empty($reply->time_sent)?date("d M. Y h:i A", strtotime($reply->time_sent)):"not sent yet" }}</span></h5>
                                </div>
                                <div class="mailbox-read-message">
                                    {!! $reply->body !!}
                                </div>
                            </div>
                            <div class="box-footer">
                                @include('pages.mailbox.includes.attachments', ['mailbox' => $reply])
                            </div>
                        </div>
                    @endforeach
                @endif

            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection