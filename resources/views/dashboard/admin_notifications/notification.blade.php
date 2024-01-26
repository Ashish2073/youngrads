<div class="row">
  <div class="col-12">
    @if(isset($row->data['Link']) && !empty($row->data['Link']))
      <p>
        <a href="{{ $row->data['Link'] }}">
          {{$row->data['Title']}}
        </a>
      </p>
    @else
      <p>
        {{$row->data['Title']}}
      </p>
    @endif
      @if ($row->read_at == '')
        <button class="btn btn-primary btn-sm read " data-id="{{ $row->id }}">Mark As Read</button>
      @else
         {{-- <p class=" text-primary">Seen</p> --}}
      @endif
  </div>
</div>
