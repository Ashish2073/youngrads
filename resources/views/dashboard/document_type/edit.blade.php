<div class="row justify-content-center">
    <div class="col-md-6">
        @include('dashboard.inc.message')
        <form id="document-edit-form" action="{{ route('admin.document-type.update', $documentType->id) }}" method="post">
            @csrf
            @method('PUT')
            @include('dashboard.common.fields.text', [
                'label_name' => 'Title',
                'id' => 'title',
                'name' => 'title',
                'placeholder' => 'Enter Title',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('title', $documentType->title),
                ],
                'classes' => '',
            ])
            <div class="form-group ">
                <label for="document_limit">Document Limit</label>
                <select class='form-control select @error('document_limit') {{ errCls() }} @enderror'
                    name="document_limit" data-live-search="true" id="document_limit">
                    <option value="">--Select Document--</option>
                    {{-- <option value="1">1</option> --}}
                    @foreach ($documentLimits as $documentLimit)
                        <option @if ($documentType->document_limit == $documentLimit) selected @endIf value="{{ $documentLimit }}">
                            {{ $documentLimit }}</option>
                    @endforeach
                </select>
                @error('document_limit')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group ">
                <label>Document Required</label>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="document_required[]" value="1"
                            @if ($documentType->is_required == 1) checked @endif>Yes
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="document_required[]" value="0"
                            @if ($documentType->is_required == 0) checked @endif>No
                    </label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
            </div>
        </form>
        @if ($documentType->hasUserDocuments())
            <p>{{ config('setting.delete_notice') }}</p>
        @else
            <div class="form-group delete ">
                <form id="delete-form" method="POST"
                    action="{{ route('admin.document-type.destroy', $documentType->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
                </form>
            </div>
        @endif
    </div>
</div>
</div>
