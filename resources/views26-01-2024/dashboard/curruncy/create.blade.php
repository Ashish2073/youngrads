<div class="row">
    <div class="col-md-12">
        @include('dashboard.inc.message')
        <form id="currency-create-form" action="{{ route('admin.currency.store') }}" method="post">
            @csrf

            @include('dashboard.common.fields.text', [
                'label_name' => 'Name',
                'id' => 'name',
                'name' => 'name',
                'placeholder' => 'Enter Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('name'),
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
                    'value' => old('symbol'),
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
                    'value' => old('rate'),
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
                            'value' => old('code'),
                        ],
                        'classes' => '',
             ])

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add Intake</button>
            </div>
    </div>
    </form>
</div>
</div>
