<div class="default-collapse collapse-bordered collapse-icon accordion-icon-rotate">

    @php
        $application_docs = $application->requiredDocuments();
        $i = 0;
        $j = 0;
        foreach ($application_docs as $doc) {
            $j++;
            $i += $doc->uploaded->count();
        }
        if ($application->status == App\Models\UserApplication::PENDING || $application->status == App\Models\UserApplication::APPLICANT_ACTION_REQUIRED) {
            $enable_docs = true;
        } else {
            $enable_docs = false;
        }
    @endphp
    @if ($j != 0)
        <div class="card collapse-header">
            <div class="card-header collapse-header div-application-documents" data-toggle="collapse" role="button"
                data-target="#application_document" aria-expanded="true" aria-controls="application_document">
                <span class="lead collapse-title">
                    @if ($i < $application_docs->count())
                        <i class="fa fa-warning text-danger"></i>
                        Application Documents
                        <label class="text-danger">[ Document(s) missing ]</label>
                    @else
                        <i class="fa fa-check text-success"></i>
                        Application Documents
                    @endif
                </span>
            </div>
            <div id="application_document" role="tabpanel" aria-labelledby="application_document" class="collapse"
                aria-expanded="true" style="">
                <div class="card-content">
                    <div class="card-body">
                        @foreach ($application->requiredDocuments() as $document)
                            <form data-file="{{ $document->uploaded->count() == 0 ? 0 : 1 }}"
                                class="document-upload-form mb-2" method="post"
                                action="{{ route('student.document.upload') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="document_type_id" value="{{ $document->id }}" />
                                <input type="hidden" name="document_type" value="application_document" />
                                <input type="hidden" name="document_name" value="{{ $document->name }}" />
                                <input type="hidden" name="application_id" value="{{ $application->id }}" />
                                @if ($enable_docs)
                                    <div class="form-group mb-50">
                                        <label for="">{{ $document->name }} {!! $document->required
                                            ? "<span
                                                                                                                                                                    class='text-danger required'>*</span>"
                                            : '' !!}</label>
                                        <div class="custom-file">
                                            <input data-div="application-documents" name="document_file" type="file"
                                                class="custom-file-input">
                                            <label class="custom-file-label">
                                                @if ($document->uploaded->count() == 0)
                                                    Choose file
                                                @else
                                                    Replace file
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group progress-indicator d-none">
                                    <div class="progress progress-bar-primary progress-xl">
                                        <div class="progress-bar" style="width:0%">0%</div>
                                    </div>
                                </div>
                                @foreach ($document->uploaded as $doc)
                                    <div class="badge badge-primary dropdown p-50">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"
                                            aria-expanded="true">
                                            <i class="feather icon-paperclip"></i>
                                            <span>{{ $document->name }}</span>
                                        </a>
                                        <div class="dropdown-menu" x-placement="bottom-start">
                                            @if ($enable_docs)
                                                <a data-div="application-documents"
                                                    class="dropdown-item delete-document"
                                                    data-url="{{ route('student.document.delete', $doc->id) }}">
                                                    <i class="fa fa-trash"></i> Delete</a>
                                            @endif
                                            <a class="dropdown-item" download
                                                href="{{ asset($doc->documentFile->file->location) }}">
                                                <i class='fa fa-download'></i> Download
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </form>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @foreach (config('documents') as $key => $document)
        @php $document_type = $document['document_type']; @endphp
        @php $element = \Str::slug($document['group_name'], "-"); @endphp
        <div class="card collapse-header">
            <div id="{{ $element }}" class="card-header collapse-header div-{{ $element }}"
                data-toggle="collapse" role="button" data-target="#{{ $element }}" aria-expanded="true"
                aria-controls="{{ $element }}">
                <span class="lead collapse-title">
                    @php
                        $i = 0;
                        $document_count = 0;
                        foreach ($document['document_lists'] ?? [] as $list) {
                            if ($document_type == 'study_levels' && $list['name'] == 'Other') {
                                continue;
                            }
                            $document_count++;
                            foreach ($list['document_list'] ?? [] as $list) {
                                $i += $list->documents->count();
                            }
                        }
                    @endphp
                    @if ($i < $document_count)
                        <i class="fa fa-warning text-danger"></i>
                        {{ $document['group_name'] }}
                        <label class="text-danger">[ Document(s) missing ]</label>
                    @else
                        <i class="fa fa-check text-success"></i>
                        {{ $document['group_name'] }}
                    @endif
                </span>
            </div>
            <div id="{{ $element }}" role="tabpanel" aria-labelledby="{{ $element }}" class="collapse"
                aria-expanded="true" style="">
                <div class="card-content">
                    <div class="card-body">
                        @forelse ($document['document_lists'] ?? [] as $list)
                            @if ($document_type == 'study_levels' && $list['name'] == 'Other')
                                @continue
                            @endif
                            <h6 class="">{{ $list['name'] }} <span class="required text-danger">*</span>
                            </h6>
                            @foreach ($list['document_list'] ?? [] as $list)
                                <form data-file="{{ $list->documents->count() == 0 ? 0 : 1 }}"
                                    class="document-upload-form mb-2" method="post"
                                    action="{{ route('student.document.upload') }}">
                                    @csrf
                                    <input type="hidden" name="document_type_id" value="{{ $list->id }}">
                                    <input type="hidden" name="document_type" value="{{ $document_type }}">
                                    <input type="hidden" name="document_name" value="{{ $list->name }}" />
                                    @if ($enable_docs)
                                        <div class="form-group mb-50">
                                            @if ($document_type != 'document_types')
                                                <label for="">{{ $list->name }}</label>
                                            @endif
                                            <div class="custom-file">
                                                <input data-div="{{ $element }}" name="document_file"
                                                    type="file" class="custom-file-input">
                                                <label class="custom-file-label">
                                                    @if ($list->documents->count() == 0)
                                                        Choose file
                                                    @else
                                                        Replace file
                                                    @endif
                                                </label>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group progress-indicator d-none">
                                        <div class="progress progress-bar-primary progress-xl">
                                            <div class="progress-bar" style="width:0%">0%</div>
                                        </div>
                                    </div>
                                    @foreach ($list->documents as $doc)
                                        <div class="badge badge-primary dropdown p-50">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"
                                                aria-expanded="true">
                                                <i class="feather icon-paperclip"></i>
                                                <span>{{ $list->name }}</span>
                                            </a>
                                            <div class="dropdown-menu" x-placement="bottom-start">
                                                @if ($enable_docs)
                                                    <a data-div="{{ $element }}"
                                                        class="dropdown-item delete-document"
                                                        data-url="{{ route('student.document.delete', $doc->id) }}">
                                                        <i class="fa fa-trash"></i> Delete</a>
                                                @endif
                                                <a class="dropdown-item" download
                                                    href="{{ asset($doc->documentFile->file->location) }}">
                                                    <i class='fa fa-download'></i> Download
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach

                                </form>
                            @endforeach
                            <hr>
                        @empty
                            <label>No Test Scores added!</label>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
