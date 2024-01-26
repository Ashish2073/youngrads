@if (!is_null($application_docs))
    <h4>
        Application Specific Documents
    </h4>
    <div class="d-flex flex-wrap">
        @foreach ($application_docs as $document)
            <div class="item">
                <label class="">{{ $document->name }}</label>
                <div class="d-flex flex-wrap">

                    @forelse ($document->uploaded as $doc)
                        <div class="item mr-50">
                            <div class="badge badge-primary dropdown p-50 mb-50">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">
                                    <i class="feather icon-paperclip"></i>
                                    <span>{{ $document->name }}</span>

                                </a>
                                <div class="dropdown-menu" x-placement="bottom-start">
                                    <a class="dropdown-item" download
                                        href="{{ asset($doc->documentFile->file->location) }}">
                                        <i class='fa fa-download'></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <strong>N/A</strong>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
    <hr />
@endif

@foreach ($documents as $key => $document)
    @php $document_type = $document['document_type']; @endphp

    <h4>
        {{ $document['group_name'] }}
    </h4>

    <div class="d-flex flex-wrap">
        @forelse ($document['document_lists'] ?? [] as $list)
            <div class="item mr-50">
                <label class="">{{ $list['name'] }}</label>
                <div class="d-flex align-items-top flex-wrap">
                    @forelse ($list['document_list'] ?? [] as $list)
                        <div class="item mr-50">
                            @forelse ($list->documents as $doc)
                                <div class="badge badge-primary dropdown p-50 mb-50">
                                    <a class="dropdown-toggle" data-toggle="dropdown" href="#"
                                        aria-expanded="true">
                                        <i class="feather icon-paperclip"></i>
                                        <span>{{ $list->name }}</span>
                                    </a>
                                    <div class="dropdown-menu " x-placement="bottom-left">
                                        <a class="dropdown-item" download
                                            href="{{ asset($doc->documentFile->file->location) }}">
                                            <i class='fa fa-download'></i> Download
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <strong>N/A</strong>
                            @endforelse
                        </div>
                    @empty
                        <strong>N/A</strong>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="col-12">
                <strong>N/A</strong>
            </div>
        @endforelse
    </div>

    <hr />
@endforeach


<h4>
    Other Documents

</h4>
<div class="d-flex flex-wrap">
    @forelse ($other_docs ?? [] as $other_document)
        <div class="item mr-50">
            <div class="badge badge-primary dropdown p-50 mb-50">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="true">
                    <i class="feather icon-paperclip"></i>
                    <span>{{ $other_document->document_name }}</span>
                </a>
                <div class="dropdown-menu" x-placement="bottom-start">
                    <a class="dropdown-item" download href="{{ asset($other_document->documentFile->file->location) }}">
                        <i class='fa fa-download'></i> Download
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <strong>N/A</strong>
        </div>
    @endforelse
</div>
<hr />
