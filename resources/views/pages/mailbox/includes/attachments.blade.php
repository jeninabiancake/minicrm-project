<ul class="mailbox-attachments clearfix">

    @if($mailbox->attachments->count())
        @foreach($mailbox->attachments as $attachment)
            <li>
                <span class="mailbox-attachment-icon"><i class="fa {{ in_array(pathinfo(public_path('uploads/mailbox/' . $attachment->attachment), PATHINFO_EXTENSION), ["jpg", "jpeg", "png", "gif"])?'fa-image':'fa-file' }}"></i></span>

                <div class="mailbox-attachment-info">
                    <a href="{{ url('uploads/mailbox/' . $attachment->attachment) }}" class="mailbox-attachment-name"><i class="fa fa-paperclip"></i> {{ $attachment->attachment }}</a>
                    <span class="mailbox-attachment-size">
                                                {{ filesize(public_path('uploads/mailbox/' . $attachment->attachment))/1024 }} KB
                                                <a href="{{ url('uploads/mailbox/' . $attachment->attachment) }}" class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                            </span>
                </div>
            </li>
        @endforeach
    @endif
</ul>