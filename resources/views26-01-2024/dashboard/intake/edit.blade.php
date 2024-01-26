<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="intake-edit-form" action="{{ route('admin.intake.update', $intake->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <select class='form-control select @error('name') {{ errCls() }} @enderror' name="name" id="name"
                        value="{{old('name', $intake->name)}}" data-live-search="true">
                    <option value=" ">--Select Name--</option>
                    @foreach (config('setting.intake_names') as $name)
                        <option @if ($intake->name == $name ) selected @endif value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('name')
                {!! errMsg($message) !!}
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select class='form-control select @error('type') {{ errCls() }} @enderror' name="type" id="type"
                        value="{{ old('type', $intake->type) }}" data-live-search="true">
                    <option value=" ">--Select type--</option>
                    @foreach (config('setting.intake_type') as $type)
                        <option @if ($intake->type == $type ) selected @endif value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
                @error('type')
                {!! errMsg($message) !!}
                @enderror
            </div>
            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
            </div>
    </div>
    </form>
        @if($intake->campusIntakeProgram->count() > 0)
            <div class="col-md-12">
                <p>{{ config('setting.delete_notice') }}</p>
            </div>
         @else
          <div class="form-group delete mx-1" style="margin-top:1%">
              <form  id="delete-form" method="POST" action="{{ route('admin.intake.destroy', $intake->id) }}" >
                  @csrf
                  @method('DELETE')
              <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
              </form>
          </div>
        @endif
</div>
</div>
