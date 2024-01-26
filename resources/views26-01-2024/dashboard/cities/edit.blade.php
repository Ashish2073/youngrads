<div class="row">
  <div class="col-md-12">
      @include('dashboard.inc.message')
      <form id="city-create-form" action="{{ route('admin.city.update', $city->id) }}" method="post">
          @csrf
          @method('put')
          @include('dashboard.common.fields.text', [
              'label_name' => 'Name',
              'id' => 'name',
              'name' => 'name',
              'placeholder' => 'Enter City Name',
              'input_attribute' => [
                  'type' => 'text',
                  'value' => old('code', $city->name),
              ],
              'classes' => '',
          ])
          <div class="form-group">
            <label for="state">State</label>
            <select class="select-2 form-control @error('state') {{ errCls() }} @enderror" id="state" name="state">
              <option value="{{ $city->getState->id }}">{{ $city->getState->name }}</option>
            </select>
            @error('state')
              <p class="text-danger">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-group">
              <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
          </div>
  </div>
  </form>
  @if($city->address->count() > 0)
    <div class="col-md-12">
        <p>{{ config('setting.delete_notice') }}</p>
    </div>
  @else
      <div class="form-group delete mx-1" style="margin-top:1%">
        <form  id="delete-form" method="POST" action="{{ route('admin.city.destroy', $city->id) }}" >
          @csrf
          @method('DELETE')
            <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
          </form>
        </div>
  @endif
</div>
</div>
