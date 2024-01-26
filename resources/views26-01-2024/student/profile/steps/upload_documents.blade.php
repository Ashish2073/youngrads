{{-- <div class="row">
    <div class="col-12">
        @include('student.profile.steps.document_uploader')
    </div>
</div> --}}
@php
// dd(config('documents'));
@endphp
<div class="row">
    <div class="col-12 ">
        @foreach (config('documents') as $key => $document)
            @php $document_type = $document['document_type']; @endphp
            <div class="">
                <div class="">
                    <h4 class="">
                        {{ $document['group_name'] }}
                    </h4>
                </div>
                <div class="">
                    <div class="">
                        <div class="row ">
                        @forelse ($document['document_lists'] ?? [] as $list)
                            @if ($document_type == 'study_levels' && $list['name'] == 'Other')
                                @continue
                            @endif
                            <div class="col-md-4">
                                <div class="p-1 mb-1 shadow border" style="border-radius: 0.5rem; box-shadow: unset;">
                                    <h4 class="card-title">{{ $list['name'] }} 
                                    </h4>
                                    <div class="">
                                        <div class="row align-items-top">
                                            @forelse ($list['document_list'] ?? [] as $list)

                                                <div class="col-12">
                                                    <form data-file="{{ $list->documents->count() == 0 ? 0 : 1 }}" class="document-upload-form mb-2" method="post"
                                                        action="{{ route('student.document.upload') }}">
                                                        @csrf
                                                        <input type="hidden" name="document_type_id"
                                                            value="{{ $list->id }}">
                                                        <input type="hidden" name="document_type"
                                                            value="{{ $document_type }}">
                                                        <input type="hidden" name="document_name"
                                                            value="{{ $list->name }}" />
                                                        <div class="form-group mb-50">
                                                            @if ($document_type != 'document_types')
                                                                <label for="">{{ $list->name }}</label><span class="required text-danger">*</span>
                                                            @endif
                                                            <div class="custom-file">
                                                                <input name="document_file" type="file"
                                                                    class="custom-file-input">
                                                                <label class="custom-file-label">
                                                                    @if ($list->documents->count() == 0)
                                                                        Choose file
                                                                    @else
                                                                        Replace file
                                                                    @endif
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="form-group progress-indicator d-none">
                                                            <div class="progress progress-bar-primary progress-xl">
                                                                <div class="progress-bar" style="width:0%">0%</div>
                                                            </div>
                                                        </div>
                                                        @foreach ($list->documents as $doc)
                                                            <div class="badge badge-primary dropdown p-50">
                                                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"
                                                                    aria-expanded="false">
                                                                    <i class="feather icon-paperclip"></i>
                                                                    <span>{{ $list->name }}</span>
                                                                </a>
                                                                <div class="dropdown-menu" x-placement="bottom-start">
                                                                    <a class="dropdown-item delete-document"
                                                                        data-url="{{ route('student.document.delete', $doc->id) }}">
                                                                        <i class="fa fa-trash"></i> Delete</a>
                                                                    <a class="dropdown-item" download
                                                                        href="{{ asset($doc->documentFile->file->location) }}">
                                                                        <i class='fa fa-download'></i> Download
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </form>
                                                </div>
                                            @empty
                                                <span>N/A</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-4">
                                <span>N/A</span>
                            </div>
                        @endforelse
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="">
            <div class="d-flex align-items-center mb-2">
                <div class="item mr-2">
                    <h4 class="">
                        Other Documents
                    </h4>
                </div>
                <div class="item">
                    <button id='add-document-btn' data-url="{{ route('other_document.create') }}" class='btn btn-icon btn-outline-primary'> Add Document</button>
                </div>
                
            </div>
            <div class="">
                <div class="">
                    <div class="row">
                        <div class="col-12">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                           <div id="document-action-box">

                           </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div id="document-listing-box">
                                @forelse(config('other_documents') as $other_document)
                                    @include('student.documents.create', compact('other_document'))
                                @empty
                                    <p class="mb-2">No other documents added yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <button type="button" class="btn btn-primary previous">Previous</button>
    </div>
</div>

<script>
    function uploadDocumentScript() {
        var form;
        submitForm($('.document-upload-form'), {
            beforeSubmit: function(formData, jqForm, options) {
                if(jqForm.data('file')) {
                    if (confirm("Are you sure want to replace existing document?")) {
                        
                    } else {
                        return false;
                    }
                } 
                form = jqForm;
                jqForm.find('.progress-indicator').removeClass('d-none');
            },
            uploadProgress: function(event, position, total, percentComplete) {
                $("input[type='file']").attr('disabled', 'disabled');

                var percentVal = percentComplete + '%';
                form.find('.progress-indicator').find('.progress-bar').css('width', percentVal);
                form.find('.progress-indicator').find('.progress-bar').html(percentVal);
            },
            success: function(data) {
                setAlert(data);
                window.lastScrollPosition = window.scrollY;
                $(".icons-tab-steps").steps('previous');
                $(".icons-tab-steps").steps('next');
            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
            },
            complete: function(responseText, statusText, xhr) {
                form.find('.progress-indicator').addClass('d-none');
                $("input[type='file']").removeAttr('disabled');
            }
        });

        editOtherDocumentScript();
    }

    function manageOtherDocumentScript() {
        validateForm($(".add-document-form"), {
            "rules" : {},
            "messages": {}
        });
        submitForm($('.add-document-form'), {
            beforeSubmit: function(formData, jqForm, options) {
                form = jqForm;
                jqForm.find('.progress-indicator').removeClass('d-none');
            },
            uploadProgress: function(event, position, total, percentComplete) {
                $("input[type='file']").attr('disabled', 'disabled');

                var percentVal = percentComplete + '%';
                form.find('.progress-indicator').find('.progress-bar').css('width', percentVal);
                form.find('.progress-indicator').find('.progress-bar').html(percentVal);
            },
            success: function(data) {
                setAlert(data);
                window.lastScrollPosition = window.scrollY;
                $(".icons-tab-steps").steps('previous');
                $(".icons-tab-steps").steps('next');
            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
            },
            complete: function(responseText, statusText, xhr) {
                form.find('.progress-indicator').addClass('d-none');
                $("input[type='file']").removeAttr('disabled');
            }
        });
    }

    function editOtherDocumentScript() {
        $(".edit-document-form").each(function() {
            validateForm($(this), {
                "rules" : {},
                "messages": {}
            });
            var form1;
            submitForm($(this), {
                beforeSubmit: function(formData, jqForm, options) {
                    form1 = jqForm;
                    jqForm.find('.progress-indicator').removeClass('d-none');
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    $("input[type='file']").attr('disabled', 'disabled');

                    var percentVal = percentComplete + '%';
                    form1.find('.progress-indicator').find('.progress-bar').css('width', percentVal);
                    form1.find('.progress-indicator').find('.progress-bar').html(percentVal);
                },
                success: function(data) {
                    setAlert(data);
                    window.lastScrollPosition = window.scrollY;
                    $(".icons-tab-steps").steps('previous');
                    $(".icons-tab-steps").steps('next');
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                },
                complete: function(responseText, statusText, xhr) {
                    form1.find('.progress-indicator').addClass('d-none');
                    $("input[type='file']").removeAttr('disabled');
                }
            });
        });
        
    }
</script>
