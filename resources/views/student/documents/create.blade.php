<div class="p-2 mb-2 shadow border" style="border-radius: 0.5rem; box-shadow: unset;">
<form class="{{ !isset($other_document) ? "add-document-form" : "edit-document-form"}}"  action="{{ route('student.document.upload') }}" method="post">
    @csrf
    @if(!isset($other_document)) 
        <h4>Add Document</h4>
    @endif
    
    <input type="hidden" name="document_type"
        value="other">
    @if(isset($other_document))
        <input type="hidden" name="document_id" value="{{ $other_document->id }}">
    @endif
    <div class="row">
        <div class="col-md-4 col-12">
            <input required placeholder="Type Document Name..." type="text" name="document_name" class="form-control"  value="{{ $other_document->document_name ?? "" }}">
        </div>
        <div class="col-md-4 col-12 mt-1 mt-md-0">
            <div class="custom-file text-left">
                <input {{ isset($other_document) ? "" : "required" }} name="document_file" type="file" class="custom-file-input">
                <label class="custom-file-label">
                    {{ !isset($other_document) ? "Choose File" : "Replace File" }}
                </label>
            </div>
            <div class="form-group progress-indicator d-none">
                <div class="progress progress-bar-primary progress-xl">
                    <div class="progress-bar" style="width:0%">0%</div>
                </div>
            </div>
        </div>
        <div class="col mt-1 mt-md-0">
            <button type="submit"  class="btn btn-primary mb-2 other-document-upload">Upload</button>
        </div>
    </div>
   
    @if(isset($other_document))
    <div class="row">
        <div class="col">
            <div class="badge badge-primary dropdown p-50">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#"
                    aria-expanded="false">
                    <i class="feather icon-paperclip"></i>
                    <span>{{ $other_document->document_name }}</span>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <a class="dropdown-item delete-document"
                        data-url="{{ route('student.document.delete', $other_document->id) }}">
                        <i class="fa fa-trash"></i> Delete</a>
                    <a class="dropdown-item" download
                        href="{{ asset($other_document->documentFile->file->location) }}">
                        <i class='fa fa-download'></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</form>
</div>
