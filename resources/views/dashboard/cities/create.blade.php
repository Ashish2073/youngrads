<div class="row">
  <div class="col-md-12">
      @include('dashboard.inc.message')
      <form id="city-create-form" action="{{ route('admin.city.store') }}" method="post">
          @csrf

          @include('dashboard.common.fields.text', [
              'label_name' => 'Name',
              'id' => 'name',
              'name' => 'name',
              'placeholder' => 'Enter City Name',
              'input_attribute' => [
                  'type' => 'text',
                  'value' => old('code'),
              ],
              'classes' => '',
          ])
          <div class="form-group">
            <label for="state">State</label>
            <select class="select-2 form-control @error('state') {{ errCls() }} @enderror" id="state" name="state">
            </select>
            @error('state')
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
