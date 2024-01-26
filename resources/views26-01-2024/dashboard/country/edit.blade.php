<div class="row">
  <div class="col-md-12">
      @include('dashboard.inc.message')
      <form id="country-edit-form" action="{{ route('admin.country.update', $country->id ) }}" method="post">
          @csrf
          @method('put')
          @include('dashboard.common.fields.text', [
              'label_name' => 'Code',
              'id' => 'contry-code',
              'name' => 'code',
              'placeholder' => 'Enter Country Code',
              'input_attribute' => [
                  'type' => 'text',
                  'value' => old('code', $country->code),
              ],
              'classes' => '',
          ])

          @include('dashboard.common.fields.text', [
            'label_name' => 'Name',
            'id' => 'contry-name',
            'name' => 'name',
            'placeholder' => 'Enter Country Name',
            'input_attribute' => [
                'type' => 'text',
                'value' => old('name', $country->name),
            ],
            'classes' => '',
          ])

          @include('dashboard.common.fields.text', [
            'label_name' => 'Phone Code',
            'id' => 'phone-code',
            'name' => 'phone_code',
            'placeholder' => 'Enter Phone Code',
            'input_attribute' => [
                'type' => 'text',
                'value' => old('phone_code', $country->phonecode),
            ],
            'classes' => '',
          ])


          <div class="form-group">
              <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
          </div>
  </div>
  </form>
  @if($country->address->count() > 0)
    <div class="col-md-12">
        <p>{{ config('setting.delete_notice') }}</p>
    </div>
  @else
      <div class="form-group delete mx-1" style="margin-top:1%">
        <form  id="delete-form" method="POST" action="{{ route('admin.country.destroy', $country->id) }}" >
          @csrf
          @method('DELETE')
            <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
          </form>
        </div>
  @endif
</div>
</div>
