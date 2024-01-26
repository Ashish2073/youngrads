@inject('shortList', 'App\Http\Controllers\UserShortlistProgramController')
@php
$shortFun = $shortList::checkProgram($row->campus_program);
@endphp
<div class="row">
    <div class="col-md-9  col-12">
        <h4>{{ $row->program_name }}</h4>
        <p>
            <span class="fa fa-tint text-primary pr-1"></span><strong>Study Area: </strong> {{ $row->study_area ?? "N/A" }}
        </p>
        <p>
            <span class="fa fa fa-graduation-cap text-primary pr-1" style="width: 25px;"></span> <strong>Program Level: </strong> {{ $row->program_level }}
        </p>
        <p>
            <span class="fa fa-clock-o text-primary pr-1" style="width: 25px;"></span><strong>Duration: </strong>{{ $row->duration }} Month(s)
        </p>
    </div>
    <div class="col-md-3 col-12 d-flex flex-column justify-content-start align-items-md-end">
        @if($row->program_link)
            <div class="item mt-1 mr-1">
                <a  href="{{ $row->program_link }}" target="_blank"><i class='fa fa-link'></i> Website</a>
            </div>
        @endif

        <div class="item mt-1 mr-1">
            <a href="{{ route('program-details', $row->campus_program) }}" class="text-left">
                <i class="fa fa-list"></i> View Details
            </a>
        </div>

        @if (Auth::check())
            <div class="item mt-1 mr-1">
                <button type="button" class="btn btn-sm btn-primary apply" data-id="{{ $row->campus_program }}"
                    data-toggle="modal" data-target="#apply-model"><i class="fa fa-bullseye" aria-hidden="true"></i>
                    Apply Now
                </button>
            </div>
            @if ($shortFun['count'] == 0)
                <div class="item mt-1 mr-1">
                    <button class="btn btn-icon btn-primary btn-sm  shortlist-add"
                        data-id="{{ $row->campus_program }}"><i class='fa fa-heart'></i> Shortlist
                    </button>
                </div>
            @elseif($shortFun['count'] == 1)
                <div class="item mt-1 mr-1">
                    {{-- <button class="btn btn-danger btn-sm remove" data-id="{{ $shortFun['id'] }}">Remove From
                        ShortList</button> --}}
                    <span class="text-primary"><i class="fa fa-check"></i> Shortlisted</span>
                </div>
            @endif
        @else
            <div class="item mt-1">
                <a class="btn btn-icon btn-sm btn-primary" href="{{ route('login') . '?redirect_to=' . url()->current() }}">
                    <i class="fa fa-user-circle" aria-hidden="true"></i> Login to Apply
                </a>
            </div>
        @endif
    </div>

</div>
</div>
