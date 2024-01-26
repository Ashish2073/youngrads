<div class="row justify-content-center">
    <div class="col-md-6 col-sm-12">
        @include('dashboard.inc.message')
        <form id="document-edit-form"
            action="{{ route('admin.application-document.update', $applicationDocument->id) }}" method="post">
            @csrf
            @method('PUT')
            @include('dashboard.common.fields.text', [
            'label_name' => 'Name',
            'id' => 'name',
            'name' => 'name',
            'placeholder' => 'Enter Name',
            'input_attribute' => [
            'type' => 'text',
            'value' => old('name',$applicationDocument->name),
            ],
            'classes' => '',
            ])


            <div class="form-group">
                <label for="university">Country</label>
                <select class='form-control country @error(' countries') {{ errCls() }} @enderror' name="countries[]"
                    data-live-search="true" multiple>
                    <option value="">--Select Country--</option>
                    @foreach (config('contries') as $country)
                        <option
                            {{ $country->id == old('countries') || in_array($country->id, $countryId) ? 'selected' : '' }}
                            value="{{ $country->id }}">
                            {{ $country->name }}
                        </option>
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
                        <input type="radio"
                            {{ old('document_required', $applicationDocument->document_required) == 1 ? 'checked' : '' }}
                            class="form-check-input" name="document_required" value="1" checked>Yes
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio"
                            {{ old('document_required', $applicationDocument->document_required) == 0 ? 'checked' : '' }}
                            class="form-check-input" name="document_required" value="0">No
                    </label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
            </div>


        </form>
        @if($applicationDocument->hasUserDocuments())
                <p>{{ config('setting.delete_notice') }}</p>
        @else
          <div class="mt-1">
            <form  id="delete-form" method="POST" action="{{ route('admin.application-document.destroy', $applicationDocument->id) }}" >
              @csrf
              @method('DELETE')
                <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
            </form>
          </div>
        @endif
    </div>
</div>



