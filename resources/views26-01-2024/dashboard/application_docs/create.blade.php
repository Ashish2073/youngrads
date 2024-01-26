<div class="row">
    <div class="col">
        @include('dashboard.inc.message')
    </div>
</div>

<form id="document-create-form" action="{{ route('admin.application-document.store') }}" method="post">
    <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12">
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


            <div class="form-group">
                <label for="university">Country</label>
                <select class='form-control country @error('countries') {{ errCls() }} @enderror'    name="countries[]"
                data-live-search="true"
                     multiple>
                    <option value="">--Select Country--</option>
                    @foreach (config('contries') as $country)
                        <option {{ $country->id == old('countries') ? 'selected' : '' }} value="{{ $country->id }}">
                            {{ $country->name }}</option>
                    @endforeach
                </select>
                @error('countries')
                <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group d-none">
              <label>Document Required: </label>
              <div class="form-check-inline">
                <label class="form-check-label">
                  <input {{ old('document_required') == 1 ? "checked" : "" }} type="radio" class="form-check-input" name="document_required" value="1" checked>Yes
                </label>
              </div>
              <div class="form-check-inline">
                <label class="form-check-label">
                  <input {{ old('document_required') == 0 ? "checked" : "" }} type="radio" class="form-check-input" name="document_required" value="0">No
                </label>
              </div>
            </div>

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
    
</form>
