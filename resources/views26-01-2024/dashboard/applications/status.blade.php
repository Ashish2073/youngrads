<div class="form-group">
<select class="form-control" id="status">
   @foreach (config('setting.application.status') as $value=>$status)
     <option data-status="{{ $application->status }}" {{ $value == $application->status ? "selected" : "" }} value="{{$value}}">{{$status}}</option>
   @endforeach
<select>
</div>
<button class=" btn btn-primary float-right" id="chage-status" data-id="{{$id}}">Change Status</button>


