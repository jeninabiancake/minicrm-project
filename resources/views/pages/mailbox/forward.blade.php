@extends('layout.app')

@section('title', ' | Mailbox | Forward message')

@section('content')

    <section class="content-header">
        <h1>
            Mailbox
            @if($unreadMessages)
                <small>{{$unreadMessages}} new messages</small>
            @endif
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('/admin') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="{{ url('/admin/mailbox') }}"> Mailbox</a></li>
            <li class="active">Forward</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <a href="{{ url('admin/mailbox') }}" class="btn btn-primary btn-block margin-bottom">Back to inbox</a>

                @include('pages.mailbox.includes.folders_panel')
            </div>
            <div class="col-md-9">
                <form method="post" action="{{ url('admin/mailbox-forward/' . $mailbox->id) }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Forward Message</h3>
                        </div>

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="form-group">
                                <?php $selected_receivers = old('receiver_id') ?>
                                <select name="receiver_id[]" id="receiver_id" multiple class="form-control">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $selected_receivers!=null && in_array($user->id, $selected_receivers)?"selected":"" }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="subject" placeholder="Subject:" value="{{ old("subject")!=null?old("subject"):$mailbox->subject }}">
                            </div>
                            <div class="form-group">
                                <textarea id="compose-textarea" class="form-control" name="body" style="height: 300px">
                                    {{ old("body")!=null?old("body"):$mailbox->body }}
                                </textarea>
                            </div>
                            <div class="form-group">

                                @include('pages.mailbox.includes.attachments', ['mailbox' => $mailbox])

                                <div class="btn btn-default btn-file">
                                    <i class="fa fa-paperclip"></i> Attachments
                                    <input type="file" name="attachments[]" multiple>
                                </div>
                                <p class="help-block">Max. {{ (int)(ini_get('upload_max_filesize')) }}M</p>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <div class="pull-right">
                                <button type="submit" class="btn btn-default"><i class="fa fa-reply"></i> Send</button>
                            </div>
                        </div>
                        <!-- /.box-footer -->
                    </div>
                    <!-- /. box -->
                </form>
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
@endsection

@section('scripts')

    <script>
        $(function () {
            //Add text editor
            $("#compose-textarea").wysihtml5();

            $("#receiver_id").select2({placeholder: "To:"});
        });
    </script>

@endsection