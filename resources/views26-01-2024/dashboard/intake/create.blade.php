<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="intake-create-form" action="{{ route('admin.intake.store') }}" method="post">
            @csrf


            <div class="form-group">
                <label for="name">Name</label>
                <select class='form-control select @error('name') {{ errCls() }} @enderror' name="name" id="name"
                        value="{{old('name')}}" data-live-search="true">
                    <option value=" ">--Select Name--</option>
                    @foreach (config('setting.intake_names') as $name)
                        <option value="{{ $name }}">{{ $name }}</option>
                    @endforeach
                </select>
                @error('name')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Type</label>
                <select class='form-control select @error('type') {{ errCls() }} @enderror' name="type" id="type"
                        value="{{ old('type') }}" data-live-search="true">
                    <option value=" ">--Select type--</option>
                    @foreach (config('setting.intake_type') as $type)
                        <option value="{{ $type }}">{{ $type }}</option>
                    @endforeach
                </select>
                @error('type')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add</button>
            </div>
    </div>
    </form>
</div>
</div>
