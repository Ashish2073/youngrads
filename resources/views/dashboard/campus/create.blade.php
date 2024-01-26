
<div class="row">
    <div class="col">
        @include('dashboard.inc.message')
    </div>
</div>

<form id="course-create-form" action="{{ route('admin.campus.store') }}" method="post">
    <div class="row">
        <div class="col-md-6 col-12">
            <h4>Campus Information</h4>
            <hr />
            @csrf

            @include('dashboard.common.fields.text', [
            'label_name' => 'Campus',
            'id' => 'name',
            'name' => 'name',
            'placeholder' => 'Enter Campus Name',
            'input_attribute' => [
            'type' => 'text',
            'value' => old('name'),
            ],
            'classes' => '',
            ])


            <div class="form-group">
                <label for="university">University</label>
                <select class='form-control university @error(' university') {{ errCls() }} @enderror' name="university"
                    data-live-search="true">
                    <option value="">--Select University--</option>
                    @foreach (config('universities') as $univ)
                        <option {{ $univ->id == old('university') ? 'selected' : '' }} value="{{ $univ->id }}">
                            {{ $univ->name }}</option>
                    @endforeach
                </select>
                @error('university')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
            @include('dashboard.common.fields.text', [
            'label_name' => 'Website',
            'id' => 'website',
            'name' => 'website',
            'placeholder' => 'Enter Website',
            'input_attribute' => [
            'type' => 'url',
            'value' => old('website'),
            ],
            'classes' => '',
            'help_text' => 'e.g http(s)://example.com'
            ])

            @include('dashboard.common.fields.text', [
            'label_name' => 'Logo',
            'id' => 'logo',
            'name' => 'logo',
            'placeholder' => 'Campus Logo',
            'input_attribute' => [
            'type' => 'file',
            'value' => old('logo'),
            ],
            'classes' => '',
            ])

            {{-- @include('dashboard.common.fields.text', [
            'label_name' => 'Cover',
            'id' => 'cover',
            'name' => 'cover',
            'placeholder' => 'Enter Program Duration',
            'input_attribute' => [
            'type' => 'file',
            'value' => old('cover'),
            ],
            'classes' => '',
            ]) --}}


            
        </div>
        <div class="col-md-6 col-12">
            <h4>Address Details</h4>
            <hr>
            @include('dashboard.common.fields.text', [
            'label_name' => 'Address',
            'id' => 'address',
            'name' => 'address',
            'placeholder' => 'Enter Campus Address',
            'input_attribute' => [
            'type' => 'text',
            'value' => old('address'),
            ],
            'classes' => '',
            ])
            <div class="form-group">
                <label for="country">Country</label>
                <select  class='form-control country_id @error(' country') {{ errCls() }} @enderror' name="country"
                    id="country" data-live-search="true">
                    <option value="">--Select Country--</option>
                    @foreach (config('countries') as $country)
                        <option {{ $country->id == old('country') ? 'selected' : '' }} value="{{ $country->id }}">
                            {{ $country->name }}</option>
                    @endforeach
                </select>
                @error('country')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="state">State</label>
                <select class='form-control select @error(' state') {{ errCls() }} @enderror' name="state" id="state"
                    data-live-search="true">
                    <option value="">--Select State--</option>
                    @foreach (config('states') as $state)
                        <option {{ $state->id == old('state', $campus->address->state_id) ? 'selected' : '' }}
                            value="{{ $state->id }}">{{ $state->name }}</option>
                    @endforeach
                </select>
                @error('state')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="city">City</label>
                <select class='form-control select @error(' city') {{ errCls() }} @enderror' name="city" id="city"
                    data-live-search="true">
                    <option value="">--Select City--</option>
                    <option value='new-city'>Add New City</option>
                    @foreach (config('cities') as $city)
                        <option {{ $city->id == old('city') ? 'selected' : '' }} value="{{ $city->id }}">
                            {{ $city->name }}</option>
                    @endforeach
                </select>
                <input type="text" name="new_city" class="form-control mt-2 d-none" placeholder="Enter new City Name"
                    id="new-city">
                @error('city')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</form>
