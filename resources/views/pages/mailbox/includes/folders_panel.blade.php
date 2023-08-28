<div class="box box-solid">
    <div class="box-header with-border">
        <h3 class="box-title">Folders</h3>

        <div class="box-tools">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            @foreach($folders as $folder)
                <li class="{{ Request::segment(3)=='' && $folder->title=='Inbox'?'active':(Request::segment(3) == $folder->title?'active':'') }}"><a href="{{ url('admin/mailbox/' . $folder->title) }}"><i class="{{ $folder->icon }}"></i> {{ $folder->title }}
                        @if($folder->title=='Inbox' && $unreadMessages)<span class="label label-primary pull-right">{{$unreadMessages}}</span> @endif
                     </a></li>
            @endforeach
        </ul>
    </div>
    <!-- /.box-body -->
</div>