<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="intake-edit-form" action="{{ route('admin.currency.update', $currency->id) }}" method="post">
            @csrf
            @method('PUT')
            @include('dashboard.common.fields.text', [
                'label_name' => 'Name',
                'id' => 'name',
                'name' => 'name',
                'placeholder' => 'Enter Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('name', $currency->name),
                ],
                'classes' => '',
            ])

            @include('dashboard.common.fields.text', [
                'label_name' => 'Symbol',
                'id' => 'symbol',
                'name' => 'symbol',
                'placeholder' => 'Enter  Symbol',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('symbol', $currency->symbol),
                ],
                'classes' => '',
            ])

            @include('dashboard.common.fields.text', [
                'label_name' => 'Rate',
                'id' => 'rate',
                'name' => 'rate',
                'placeholder' => 'Enter  Rate',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('rate',$currency->rate),
                ],
                'classes' => '',
            ])

            @include('dashboard.common.fields.text', [
                        'label_name' => 'Code',
                        'id' => 'code',
                        'name' => 'code',
                        'placeholder' => 'Enter  Code',
                        'input_attribute' => [
                            'type' => 'text',
                            'value' => old('code', $currency->code),
                        ],
                        'classes' => '',
             ])


            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
            </div>
    </div>
    </form>
</div>
</div>
