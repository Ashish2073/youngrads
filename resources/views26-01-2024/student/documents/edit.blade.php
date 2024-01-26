<form id="add-document-form" action="{{ route('student.document.upload') }}" method="post">
    @csrf
    
    <input type="hidden" name="document_type" value="other">

    <div class="row">
        <div class="col">
            <input placeholder="Type Document Name..." type="text" name="document_name" class="form-control" id="document_name" value="">
        </div>
        <div class="col">
            <div class="custom-file text-left">
                <input name="document_file" type="file" class="custom-file-input">
                <label class="custom-file-label">
                    Choose file
                </label>
            </div>
            <div class="form-group progress-indicator d-none">
                <div class="progress progress-bar-primary progress-xl">
                    <div class="progress-bar" style="width:0%">0%</div>
                </div>
            </div>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary mb-2">Upload</button>
        </div>
    </div>
</form>
