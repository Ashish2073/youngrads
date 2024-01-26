@extends('layouts/contentLayoutMaster')

@section('title', 'Application')

@section('vendor-style')

@endsection
@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/pages/app-chat.css')) }}" />
@endsection

@section('content')
    @php
        $user = $application->user;
        $progress_detail = $user->profileCompleteDetail($application);
        // dd($progress_detail);
    @endphp
    <div class="row match-height">
        <div class="col-md-6">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="{{ $application->campusProgram->campus->getLogo() }}" alt=""
                                    class="img-fluid" />
                            </div>
                            <div class="col-md-8">
                                <h2><a target="_blank"
                                        href='{{ route('campus-search', $application->campusProgram->campus->id) }}'>{{ $application->campusProgram->campus->name }}</a>
                                </h2>
                                <h5 class="pb-1"><a target="_blank"
                                        href="{{ route('program-details', $application->campusProgram->id) }}">{{ $application->campusProgram->program->name }}</a>
                                </h5>
                                <h5 class="pb-1"><i class="fa fa-university" aria-hidden="true"></i>
                                    {{ $application->campusProgram->campus->university->name }}</h5>
                                <strong><i class="fa fa-map-marker" aria-hidden="true"></i> Address:</strong>
                                @php
                                    $address = $application->campusProgram->campus->address;
                                @endphp
                                @if (isset($address) || !is_null($address))
                                    <p>
                                        {{ $address->address }}, {{ $address->country->name }}, {{ $address->state->name }},
                                        {{ $address->city->name }}
                                    </p>
                                @else
                                    <div>
                                        <strong>N/A</strong>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <div class="card-body collapse-icon accordion-icon-rotate">
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="default-collapse collapse-bordered">
                                    <div class="card collapse-header">
                                        <div id="headingCollapse2" class="card-header collapse-header"
                                            data-toggle="collapse" role="button" data-target="#collapse2"
                                            aria-expanded="true" aria-controls="collapse2">
                                            <span class="lead collapse-title">
                                                Application Information</span>
                                        </div>
                                        <div id="collapse2" role="tabpanel" aria-labelledby="headingCollapse2"
                                            class="collapse show" aria-expanded="false" style="">
                                            <div class="card-content">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Application ID:</strong></div>
                                                            <div class="">{{ $application->application_number }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Applied Intake:</strong></div>
                                                            <div>{{ $application->intake->name }} -
                                                                {{ $application->year }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Application Status:</strong></div>
                                                            <div>
                                                                @php
                                                                    if ($application->status == 'pending') {
                                                                        $class = 'badge-warning';
                                                                    } elseif ($application->status == 'open') {
                                                                        $class = 'badge-info';
                                                                    } elseif ($application->status == 'close' || $application->status == 'archive') {
                                                                        $class = 'badge-danger';
                                                                    } else {
                                                                        $class = 'badge-success';
                                                                    }
                                                                    $status_meta = config('setting.application.status_meta.' . $application->status);
                                                                    echo "<span class='badge p-50 badge-{$status_meta['color']}'><i class='{$status_meta['icon_class']}'></i> " . config('setting.application.status')[$application->status] . '</span>';
                                                                @endphp
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Applied Date:</strong></div>
                                                            <div>{{ date('d M. Y', strtotime($application->created_at)) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card collapse-header">
                                        <div id="headingCollapse1" class="card-header collapsed" data-toggle="collapse"
                                            role="button" data-target="#collapse1" aria-expanded="false"
                                            aria-controls="collapse1">
                                            <span class="lead collapse-title">
                                                @if (!$progress_detail['general_information']['status'])
                                                    <i class="fa fa-warning text-primary"></i>
                                                    Personal Information - ({{ $user->getFullName() }})
                                                    (<a target="_blank"
                                                        href="{{ route('student.edit-profile') . '?step=0' }}">Complete</a>)
                                                @else
                                                    <i class="fa fa-check text-success"></i>
                                                    Personal Information
                                                @endif
                                            </span>
                                        </div>
                                        <div id="collapse1" role="tabpanel" aria-labelledby="headingCollapse1"
                                            class="collapse" style="">
                                            <div class="card-content">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Email Address:</strong></div>
                                                            <div>{{ $user->email ?? 'N/A' }}</div>
                                                        </div>
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Date of Birth:</strong></div>
                                                            <div>
                                                                {{ $user->dob ? date('d M. Y', strtotime($user->dob)) : 'N/A' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Phone Number:</strong></div>
                                                            <div>{{ $user->personal_number ?? 'N/A' }}</div>
                                                        </div>
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Primary Language:</strong></div>
                                                            <div>{{ $user->language ?? 'N/A' }}</div>
                                                        </div>
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Gender:</strong></div>
                                                            <div>{{ $user->gender ? ucfirst($user->gender) : 'N/A' }}</div>
                                                        </div>
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Marital Status:</strong></div>
                                                            <div>
                                                                {{ $user->maritial_status ? ucfirst($user->maritial_status) : 'N/A' }}
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4 mb-1">
                                                            <div><strong>Passport Number:</strong></div>
                                                            <div>{{ $user->passport ?? 'N/A' }}</div>
                                                        </div>
                                                        <div class="col-md-6 mb-1">
                                                            <div><strong>Country of Citizenship:</strong></div>
                                                            <div>{{ $user->citizenship->name ?? 'N/A' }}</div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card collapse-header">
                                        <div id="headingCollapse3" class="card-header collapse-header"
                                            data-toggle="collapse" role="button" data-target="#collapse3"
                                            aria-expanded="false" aria-controls="collapse3">
                                            <span class="lead collapse-title">
                                                @if (!$progress_detail['education_history']['status'])
                                                    <i class="fa fa-warning text-primary"></i>
                                                    Education
                                                    (<a target="_blank"
                                                        href="{{ route('student.edit-profile') . '?step=1' }}">Complete</a>)
                                                @else
                                                    <i class="fa fa-check text-success"></i>
                                                    Education
                                                @endif
                                            </span>
                                        </div>
                                        <div id="collapse3" role="tabpanel" aria-labelledby="headingCollapse3"
                                            class="collapse" aria-expanded="false">
                                            <div class="card-content">
                                                <div class="card-body">
                                                    @if (count($progress_detail['education_history']['require_levels'] ?? []) != 0)
                                                        <p> Please add
                                                            @foreach ($progress_detail['education_history']['require_levels'] as $level)
                                                                <strong>{{ $level->name }}</strong>,
                                                            @endforeach
                                                            Education details
                                                        </p>
                                                    @endif
                                                    <div class="table-responsive">
                                                        <table class="table table-sm ">
                                                            <thead>
                                                                <th>Study Level</th>
                                                                <th>Institute</th>
                                                                <th>Marks</th>
                                                                <th>Country</th>
                                                            </thead>
                                                            <tbody>

                                                                @forelse($user->academics as $academic)
                                                                    <tr>
                                                                        <td>{{ $academic->getStudyLevel->name == 'Other' ? 'Other - ' . $academic->sub_other : $academic->getStudyLevel->name ?? 'N/A' }}
                                                                        </td>
                                                                        <td>{{ $academic->board_name }}</td>
                                                                        <td>{{ $academic->marks . ' ' . ucfirst($academic->marks_unit) }}
                                                                        </td>
                                                                        <td>{{ $academic->getCountry->name }}</td>
                                                                    <tr>
                                                                    @empty
                                                                    <tr>
                                                                        <td colspan="4">N/A</td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card collapse-header">
                                        <div id="headingCollapse4" class="card-header" data-toggle="collapse"
                                            role="button" data-target="#collapse4" aria-expanded="false"
                                            aria-controls="collapse4">
                                            <span class="lead collapse-title">
                                                @if (!$progress_detail['test_scores']['status'])
                                                    <i class="fa fa-warning text-primary"></i>
                                                    Test Scores
                                                    (<a target="_blank"
                                                        href="{{ route('student.edit-profile') . '?step=2' }}">Complete</a>)
                                                @else
                                                    <i class="fa fa-check text-success"></i>
                                                    Test Scores
                                                @endif
                                            </span>
                                        </div>
                                        <div id="collapse4" role="tabpanel" aria-labelledby="headingCollapse4"
                                            class="collapse" aria-expanded="false">
                                            <div class="card-content">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                @forelse ($user->tests as $userTest)
                                                                    <tr>
                                                                        <td>@include('student.profile_test', [
                                                                            'userTest' => $userTest,
                                                                        ])
                                                                        </td>
                                                                    </tr>
                                                                @empty
                                                                    <tr>
                                                                        <td colspan="2"><strong>N/A</strong></td>
                                                                    </tr>
                                                                @endforelse
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card collapse-header">
                                        <div id="headingCollapse5" class="card-header" data-toggle="collapse"
                                            role="button" data-target="#collapse5" aria-expanded="false"
                                            aria-controls="collapse5">
                                            <span class="lead collapse-title">
                                                @if (!$progress_detail['background_information']['status'])
                                                    <i class="fa fa-warning text-primary"></i>
                                                    Background Information
                                                    (<a target="_blank"
                                                        href="{{ route('student.edit-profile') . '?step=4' }}">Complete</a>)
                                                @else
                                                    <i class="fa fa-check text-success"></i>
                                                    Background Information
                                                @endif
                                            </span>
                                        </div>
                                        <div id="collapse5" role="tabpanel" aria-labelledby="headingCollapse5"
                                            class="collapse" aria-expanded="false">
                                            <div class="card-content">
                                                <div class="card-body">
                                                    <div class="mb-1">
                                                        <strong>Have you ever applied for Visa:</strong>
                                                        {{ intval($user->meta('applied_visa')) ? 'Yes' : 'No' }}
                                                    </div>

                                                    <div class="mb-1">
                                                        <strong>Any Visa Refusal:</strong>
                                                        {{ intval($user->meta('visa_refusal')) ? 'Yes' : 'No' }}
                                                    </div>

                                                    @if (intval($user->meta('applied_visa')) || intval($user->meta('visa_refusal')))
                                                        <div><strong>Additional Details:</strong></div>
                                                        {{ $user->meta('visa_refusal_details') != '' ? $user->meta('visa_refusal_details') : 'N/A' }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card overflow-hidden ">
                <div class="card-header">
                    <div class="row w-100">
                        <div class="col-md-8">
                            <ul class="nav nav-tabs justify-content-start" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link" id="home-tab-center" data-toggle="tab" href="#home-center"
                                        aria-controls="home-center" role="tab" aria-selected="true">Messages</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" id="service-tab-center" data-toggle="tab"
                                        href="#service-center" aria-controls="service-center" role="tab"
                                        aria-selected="false">Documents</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " id="service-tab-center" data-toggle="tab"
                                        href="#application-activity" aria-controls="application-activity" role="tab"
                                        aria-selected="false">Timeline</a>
                                </li>
                            </ul>

                        </div>
                        <div class="application-status-box col-md-4 text-right px-0">
                            @if (
                                $application->status == App\Models\UserApplication::PENDING ||
                                    $application->status == App\Models\UserApplication::APPLICANT_ACTION_REQUIRED)
                                <form id="submit-application-form" method="post"
                                    action="{{ route('submit_to_ygrad') }}">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="application_id" value="{{ $application->id }}" />
                                    <button id="submit-application-btn" type="submit"
                                        class="btn-outline-primary btn">Submit to YGrad</button>
                                </form>
                            @else
                                @php $status_meta = config('setting.application.status_meta')[$application->status]; @endphp

                                <label
                                    class="text-{{ $status_meta['color'] }} border border-{{ $status_meta['color'] }} p-50">
                                    <i class='{{ $status_meta['icon_class'] }}'></i>
                                    {{ $status_meta['description'] ?? config('setting.application.status')[$application->status] }}
                                </label>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body py-0">
                        <div class="tab-content">
                            {{-- Message --}}
                            <div class="tab-pane " id="home-center" aria-labelledby="home-tab-center" role="tabpanel">
                                @include('application_message.index', [
                                    'id' => $application->id,
                                    'gaurd' => 'user',
                                    'auth' => $user->id,
                                ])
                            </div>

                            {{-- Documents --}}
                            <div class="tab-pane active" id="service-center" aria-labelledby="service-tab-center"
                                role="tabpanel">
                                <div id="application-document-view"></div>
                            </div>

                            {{-- Activity --}}
                            <div class="tab-pane " id="application-activity" aria-labelledby="application-activity"
                                role="tabpanel">
                                <div class="application-timeline-view"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="application_id" value="{{ $application->id }}">
@endsection

@section('foot_script')

    <script>
        $(document).ready(function() {
            let application_id = $("input[name='application_id']").val();
            $("body").on("change", "input[name='document_file']", function() {
                $(this).closest('.document-upload-form').submit();
                window.collapseDiv = $(this).data("div");
                // $(".document-upload-form").submit();
            });

            $("body").on("click", ".delete-document", function() {
                window.collapseDiv = $(this).data("div");
                let url = $(this).data('url');
                $.ajax({
                    url: url,
                    beforeSend: function() {
                        if (!confirm("Are you sure want to delete document?")) {
                            return false;
                        }
                    },
                    success: function() {
                        window.lastScrollPosition = window.scrollY;
                        getDocumentView();

                    },
                    error: function() {

                    },
                    complete: function() {

                    }
                })
            });

            submitForm($('#submit-application-form'), {
                beforeSubmit: function() {
                    submitLoader("#submit-application-btn");
                },
                success: function(data) {
                    setAlert(data);
                    if (data.success) {
                        $(".application-status-box").html("");
                        if (data.hasOwnProperty('submitted')) {
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                    }
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                },
                complete: function() {
                    submitReset("#submit-application-btn");
                }
            });


            initMessageScript(application_id);
            getDocumentView();
            getTimelineView(application_id);
        });

        function getTimelineView(id) {
            getContent({
                url: "{{ url('application') }}" + "/" + id + "/" + "timeline",
                beforeSend: function() {
                    $(".application-timeline-view").html("<span class='text-center'>Loading...</span>");
                },
                success: function(data) {
                    $(".application-timeline-view").html(data);
                }
            });
        }

        function getDocumentView() {
            // route('application.document.view', $("input[name='application_id']").val()).url(),
            getContent({
                url: "{{ url('application') }}" + "/" + $("input[name='application_id']").val() + "/" +
                    "documents",
                success: function(data) {
                    if (window.lastScrollPosition) {
                        window.scrollTo(0, lastScrollPosition);
                    }
                    $('#application-document-view').html(data);
                    uploadDocumentScript();
                    if (window.collapseDiv) {
                        $(`.div-${window.collapseDiv}`).click();
                    }
                }
            });
        }

        function uploadDocumentScript() {
            var form;
            submitForm($('.document-upload-form'), {
                beforeSubmit: function(formData, jqForm, options) {
                    if (jqForm.data('file')) {
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
                    getDocumentView();
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
    </script>
@endsection
