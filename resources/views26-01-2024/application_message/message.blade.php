
<div class="chat {{ $class }}">
  
  <div class='chat-avatar'>
    <div class="avatar bg-light-primary">
      <div class="avatar-content">{{ $avtar }}</div>
    </div>
  </div>
 <div class='chat-body'>
    <div class='chat-content'>
      <p>
        <span class="d-block " style="font-size: 11px;">
          {{ $name }}
        </span>
        {{ $row->message }}
        <span class=" pt-50 ml-1" style="font-size: 11px;">
          {{ date("d M Y h:i A", strtotime($row->time)) }}
        </span>
      </p>
          @if($row->is_important == 0)
            <button class='btn btn-important d-none' data-id="{{ $row->id }}" title="Mark as Important">
              <i class='feather icon-heart pink text-danger'></i>
            </button>
          @else
          <button class='btn btn-important d-none' data-id="{{ $row->id }}">
            <i class='fa fa-heart  text-danger text-danger'></i>
          </button>
          @endif
      @if ($row->file)
        <a  class="{{ $attchmentClass }}" href="{{ asset('/user_documents/'.$row->file)}} " download> <i class='fa fa-paperclip' aria-hidden='true'></i> Attachment</a>
      @endif
      </div>
  </div>
</div>
