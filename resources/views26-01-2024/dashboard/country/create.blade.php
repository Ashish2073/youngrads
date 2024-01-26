<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="country-create-form" action="{{ route('admin.country.store') }}" method="post">
            @csrf

            @include('dashboard.common.fields.text', [
                'label_name' => 'Code',
                'id' => 'contry-code',
                'name' => 'code',
                'placeholder' => 'Enter Country Code',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('code'),
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
                  'value' => old('name'),
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
                  'value' => old('phone_code'),
              ],
              'classes' => '',
            ])


            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add</button>
            </div>
    </div>
    </form>
</div>
</div>
