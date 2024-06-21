<div class="row justify-content-center">
    <div class="col-md-6">
        @include('dashboard.inc.message')
        <form id="document-create-form" action="{{ route('admin.document-type.store') }}" method="post">
            @csrf
            @include('dashboard.common.fields.text', [
                'label_name' => 'Title',
                'id' => 'title',
                'name' => 'title',
                'placeholder' => 'Enter Title',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('title'),
                ],
                'classes' => '',
            ])


            <div class="form-group">
                <label for="document_limit">Document Limit</label>
                <select class='form-control select @error('document_limit') {{ errCls() }} @enderror'
                    name="document_limit" data-live-search="true" id="document_limit  ">
                    <option value="">--Select Document--</option>

                    @foreach ($documentLimits as $documentLimit)
                        <option value="{{ $documentLimit }}">{{ $documentLimit }}</option>
                    @endforeach
                </select>
                @error('document_limit')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label>Document Required</label>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="document_required" value="1">Yes
                    </label>
                </div>
                <div class="form-check-inline">
                    <label class="form-check-label">
                        <input type="radio" class="form-check-input" name="document_required" value="0"
                            checked>No
                    </label>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add</button>
            </div>
    </div>
    </form>
</div>
</div>
