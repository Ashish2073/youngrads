<div class="row">
    <div class="col-12">
        @forelse ($attachments as $attachment)
            <div class="card border">
                <div class="card-content">
                    <div class="card-body">
                        <p><strong>Sent By:</strong> {{ $attachment->user_name ?? $attachment->admin_name }}</p>
                        <p><i class="fa fa-history"></i> {{ date('d M. Y h:i A', strtotime($attachment->time)) }}</p>
                        <a href="{{ asset('/user_documents/' . $attachment->file) }}" download class="float-left mb-1">
                            <i class='fa fa-paperclip' aria-hidden='true'></i> Attachment
                        </a>
                        <button class="d-none btn btn-primary float-right btn-sm mb-1 copy-attachment"
                            data-path="{{ asset('/user_documents/' . $attachment->file) }}">Copy File Path</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center border">
                <div class="card-content">
                    <div class="card-body">
                        <strong class="text-center">N/A</strong>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
