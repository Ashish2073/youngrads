@if ($applicationId != '')
    <ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab-fill" data-toggle="tab" href="#information" role="tab"
                aria-controls="home-fill" aria-selected="true">Information</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab-fill" data-toggle="tab" href="#message" role="tab"
                aria-controls="profile-fill" aria-selected="false">Message</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="timeline-tab-fill" data-toggle="tab" href="#timeline" role="tab"
                aria-controls="timeline-fill" aria-selected="false">Application Timeline</a>
        </li>
    </ul>
@endif
<style>
    .modal-dialog {
        /* width: 75%; */
        /* max-width: 75%; */
    }
</style>
<div class="tab-content container">
    <div class="tab-pane active " id="information" role="tabpanel" aria-labelledby="home-tab-fill">
        <section id="accordion-with-border">
            <div class="row">
                <div class="col-sm-12">
                    <div id="accordionWrapa50" role="tablist" aria-multiselectable="true">
                        <div class="collapse-icon accordion-icon-rotate">
                            <div class="accordion" id="accordionExample0">
                                <div class="collapse-border-item card collapse-header">
                                    <div class="card-header" id="heading210" data-toggle="collapse"
                                        role="button" data-target="#collapse209" aria-expanded="false"
                                        aria-controls="collapse210">
                                        <span class="lead collapse-title">
                                            General Information
                                        </span>
                                    </div>
                                    <div id="collapse209" class="collapse" aria-labelledby="heading210"
                                        data-parent="#accordionExample0">
                                        <div class="card-body">
                                            <div class="">
                                                <div class="row ">
                                                    <div class="col-6">
                                                        <strong>First Name:</strong> {{ $users->name }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Last Name:</strong> {{ $users->last_name }}
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <strong>Email:</strong> {{ $users->email }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Personal Number:</strong>
                                                        {{ $users->personal_number ?? 'N/A' }}
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        @php
                                                        $country =
                                                        DB::table('countries')->where('id',$users->country)->select('name')->get();
                                                        $contryName = (isset($country[0]->name))?
                                                        $country[0]->name : "N/A";
                                                        @endphp
                                                        <strong>Country of Citizenship:</strong>
                                                        {{ $contryName }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Gender:</strong>
                                                        {{ ucfirst($users->gender) ?? 'N/A' }}
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        <strong>Primary Language:</strong>
                                                        {{ $users->language ?? 'N/A' }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Marital status:</strong>
                                                        {{ ucfirst($users->maritial_status) ?? 'N/A' }}
                                                    </div>

                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-6">
                                                        @php $date = isset($users->dob)? date('d M Y',
                                                        strtotime($users->dob)) : "N/A" @endphp
                                                        <strong>Date Of Birth:</strong> {{ $date }}
                                                    </div>
                                                    <div class="col-6">
                                                        <strong>Passport No:</strong>
                                                        {{ $users->passport ?? 'N/A' }}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4 col-12">
                                                        <strong>Address Detail:</strong>
                                                        @if (!is_null($users->address_id))
                                                            <ul>
                                                                <li><strong>Address:</strong>
                                                                    {{ $users->address->address ?? 'N/A' }}</li>
                                                                <li><strong>City:</strong>
                                                                    {{ $users->address->city->name ?? 'N/A' }}
                                                                </li>
                                                                <li><strong>State:</strong>
                                                                    {{ $users->address->state->name ?? 'N/A' }}
                                                                </li>
                                                                <li><strong>Country:</strong>
                                                                    {{ $users->address->country->name ?? 'N/A' }}
                                                                </li>
                                                                <li><strong>Postal Code:</strong>
                                                                    {{ $users->postal ?? 'N/A' }}</li>
                                                            </ul>
                                                        @else
                                                            <p>N/A</p>
                                                        @endif

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="collapse-border-item card collapse-header">
                                    <div class="card-header" id="heading210" data-toggle="collapse"
                                        role="button" data-target="#collapse210" aria-expanded="false"
                                        aria-controls="collapse210">
                                        <span class="lead collapse-title">
                                            Education History
                                        </span>
                                    </div>
                                    <div id="collapse210" class="collapse" aria-labelledby="heading210"
                                        data-parent="#accordionExample0">
                                        <div class="table-responsive">
                                            <table class="table table-sm ">
                                                <thead>
                                                    <th>Study Level</th>
                                                    <th>Institute</th>
                                                    <th>Marks</th>
                                                    <th>Country</th>
                                                    <th>Language</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                </thead>
                                                <tbody>

                                                    @forelse($users->academics as $academic)
                                                        <tr>
                                                            <td>{{ $academic->getStudyLevel->name == 'Other' ? 'Other - ' . $academic->sub_other : $academic->getStudyLevel->name ?? 'N/A' }}
                                                            </td>
                                                            <td>{{ $academic->board_name }}</td>
                                                            <td>{{ $academic->marks . ' ' . ucfirst($academic->marks_unit) }}
                                                            </td>
                                                            <td>{{ $academic->getCountry->name }}</td>
                                                            <td>{{ $academic->language }}</td>
                                                            <td>{{ $academic->start_date == null ? 'N/A' : date('d M Y', strtotime($academic->start_date)) }}
                                                            </td>
                                                            <td>{{ $academic->end_date == null ? 'N/A' : date('d M Y', strtotime($academic->end_date)) }}
                                                            </td>
                                                        <tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="7">N/A</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="card collapse-header collapse-border-item">
                                    <div class="card-header" id="heading220" data-toggle="collapse"
                                        role="button" data-target="#collapse220" aria-expanded="false"
                                        aria-controls="collapse220">
                                        <span class="lead collapse-title">
                                            Test Scores
                                        </span>
                                    </div>
                                    <div id="collapse220" class="collapse" aria-labelledby="heading220"
                                        data-parent="#accordionExample0">
                                        <div class="card-body">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    @forelse ($userTests as $userTest)
                                                        <tr>
                                                            <td>@include('student.profile_test',['userTest'=>$userTest])
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
                                <div class="card collapse-header collapse-border-item">
                                    <div class="card-header" id="heading230" data-toggle="collapse"
                                        role="button" data-target="#collapse230" aria-expanded="false"
                                        aria-controls="collapse230">
                                        <span class="lead collapse-title">
                                            Background Information
                                        </span>
                                    </div>
                                    <div id="collapse230" class="collapse" aria-labelledby="heading230"
                                        data-parent="#accordionExample0">
                                        <div class="card-body">
                                            <div class="container">
                                                <div class="apply mb-2">
                                                    <p><strong>Have you ever applied for Visa:
                                                    </strong>{{ $applied ? 'Yes' : 'No' }}</p>

                                                </div>
                                                <div class="refuse mb-2">
                                                    <p><strong>Any Visa Refusal: </strong>{{ $refuse ? "Yes" :  "No" }}</p>
                                                    @if($refuse)

                                                        <p><strong>Additional Details: </strong></p> {{ $users->meta('visa_refusal_details') != "" ? $users->meta('visa_refusal_details') : "N/A" }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card collapse-header collapse-border-item">
                                    <div class="card-header" id="heading230" data-toggle="collapse"
                                        role="button" data-target="#collapse231" aria-expanded="false"
                                        aria-controls="collapse230">
                                        <span class="lead collapse-title">
                                            Documents
                                        </span>
                                    </div>
                                    <div id="collapse231" class="collapse" aria-labelledby="heading230"
                                        data-parent="#accordionExample0">
                                        <div class="card-body">
                                                @include('student.profile.view.document', [
                                                    'documents' => $document_lists,
                                                    'other_documents' => $other_docs,
                                                    'application_docs' => $application_docs
                                                ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if ($applicationId != '')
        <div class="tab-pane" id="message" role="tabpanel" aria-labelledby="profile-tab-fill">
            <div class="w-75 m-auto">

                @include('application_message.index',['id'=>$applicationId,'gaurd'=>'admin','auth'=> auth('admin')->user()->id ])
            </div>
        </div>
        <div class="tab-pane" id="timeline" role="tabpanel" aria-labelledby="timeline-tab-fill">
            <div class="w-75 m-auto">

                @include('application.timeline.index', compact('application'))
            </div>
        </div>
    @endif
</div>
