<form id="document-uploader" method="post" action="">
    @csrf
    <div class="form-group col-4">
        <label for="document-dropdown">Select Document to Upload</label>
        <select data-style="border-light bg-white" id="document-list" class="form-control select">
            <option value="">--Select Document--</option>
            @foreach(config('documents') as $key => $document)
                <optgroup label="{{ $document['group_name'] }}" data-cat="{{ $key }}">
                    @foreach($document['document_lists'] as $list)
                        <option value="{{ $list['id'] }}">{{ $list['name'] }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>
    </div>

    <div class="form-group col-4">

    </div>
</form>