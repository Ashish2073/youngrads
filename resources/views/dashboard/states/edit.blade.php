<div class="row">
  <div class="col-md-12">
      @include('dashboard.inc.message')
      <form id="state-edit-form" action="{{ route('admin.state.update', $state->id) }}" method="post">
          @csrf
          @method('put')
          @include('dashboard.common.fields.text', [
              'label_name' => 'Name',
              'id' => 'name',
              'name' => 'name',
              'placeholder' => 'Enter State Name',
              'input_attribute' => [
                  'type' => 'text',
                  'value' => old('code', $state->name),
              ],
              'classes' => '',
          ])

          <div class="form-group">
            <label for="country">Country</label>
            <select class="select-2 form-control @error('country') {{ errCls() }} @enderror" id="country" name="country">
               <option value="{{ $state->getCountry->id }}">{{ $state->getCountry->name }}</option>
            </select>
            @error('country')
              <p class="text-danger">{{ $message }}</p>
            @enderror
          </div>
          <div class="form-group">
              <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
          </div>
  </div>
  </form>
  @if($state->address->count() > 0)
    <div class="col-md-12">
        <p>{{ config('setting.delete_notice') }}</p>
    </div>
  @else
      <div class="form-group delete mx-1" style="margin-top:1%">
        <form  id="delete-form" method="POST" action="{{ route('admin.state.destroy', $state->id) }}" >
          @csrf
          @method('DELETE')
            <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
          </form>
        </div>
  @endif
</div>
</div>
