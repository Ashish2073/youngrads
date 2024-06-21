@inject('shortList', 'App\Http\Controllers\UserShortlistProgramController')
@extends(Auth::check() ? 'layouts.contentLayoutMaster' : 'layouts.beta_layout')
@section('title', $program[0]->program)
@section('content')
    <section id="basic-datatable" class="{{ auth()->check() ? '' : 'px-3' }}">
        <div class="row">
            <div class="col-12">
                <div class="card">

                    <div class="card-content">
                        <div class="card-body card-dashboard">
                            <div class="row">
                                <div class="col-md-3 col-12 text-center">
                                    <h4>{{ $program[0]->program }}</h4>
                                    <img src="{{ $campus[0]->getLogo() }}" alt="" class="img-fluid">
                                </div>
                                <div class="col-md-6 col-12">
                                    <p><span class="fa fa-university text-primary"></span> <strong>University:</strong>
                                        {{ $campus[0]->university }}</p>
                                    <p><span class="fa fa-building-o text-primary"></span> <strong>Campus :</strong> <a
                                            href="{{ route('campus-search', $campus[0]->campus_id) }}">{{ $campus[0]->campus }}</a>
                                    </p>
                                    <p>
                                        <span class="fa fa-tint text-primary"></span> <strong>Study Area:</strong>
                                        {{ $program[0]->study ?? 'N/A' }}
                                    </p>
                                    <p><span class="fa fa-building-o text-primary"></span> <strong>Program Level:</strong>
                                        {{ $program[0]->program_level }}</p>

                                    <p><span class="fa fa-globe text-primary"></span> <strong>Country:</strong>
                                        {{ $countryName }}</p>
                                    <p><span class="fa fa-clock-o text-primary"></span> <strong>Duration:</strong>
                                        {{ $campusProgram->campus_program_duration }} Month(s)</p>

                                    <p><span class="fa fa-user-plus text-primary"></span> <strong>Intake:</strong>
                                        @forelse($intakes as $intake)
                                            <span class="badge badge-primary">{{ $intake->name }}</span>
                                        @empty
                                        @endforelse
                                    </p>

                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <p><strong>Program Fees:</strong></p>
                                            <table class="table table-sm">
                                                <tbody>
                                                    @forelse ($campusIntakesFees as $campusIntakesFee)
                                                        <tr>

                                                            <td>{{ $campusIntakesFee->name }}</td>
                                                            <td>
                                                                @if ($campusIntakesFee->fee_price == 0)
                                                                    N/A
                                                                @else
                                                                    {{-- {{ html_entity_decode($campusIntakesFee->currency) }} --}}
                                                                    {!! $campusIntakesFee->currency !!}
                                                                    {{ $campusIntakesFee->fee_price }}
                                                                    {{ $campusIntakesFee->code }}
                                                                @endif
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

                                        <div class="row">
                                            <div class="col-12">
                                                <p><strong>Entry Requirement</strong></p>
                                                <p>{{ $campusProgram->entry_requirment }}</p>
                                            </div>


                                            @if ($campusProgram->waiver_in_english == 'yes')
                                                <div class="col-12">
                                                    <p><strong>English Waiver Requirment</strong></p>
                                                    <p>{{ $campusProgram->waiver_requirement_detail }}</p>
                                                </div>
                                            @endif

                                            @if ($campusProgram->waiver_in_english == 'no')
                                                <div class="col-12">
                                                    <p><strong>English Waiver</strong></p>
                                                    <p>No</p>
                                                </div>
                                            @endif


                                        </div>
                                    </div>


                                    <div class="col-md-6 col-12">
                                        <p><strong>Required Test Details:</strong></p>
                                        <table class="table table-sm">
                                            <tbody>
                                                @forelse ($testScores as $testScore)
                                                    <tr>
                                                        <td>TestName</td>
                                                        <td>{{ strtoupper($testScore->test) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Required OverAll Score</td>
                                                        <td>{{ $testScore->score }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Individual Required Score In Each Part Of
                                                            Exam</td>
                                                        <td>{{ $testScore->nlt_score }}</td>
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

                                <div class="col-md-3 col-12 text-md-right text-md-left">
                                    @if (auth()->check())
                                        <button type="button" class="btn btn-icon btn-primary apply mb-1"
                                            data-id="{{ $id }}" data-toggle="modal" data-target="#apply-model"><i
                                                class="fa fa-bullseye" aria-hidden="true"></i> Apply Now</button>
                                        <div id="shortlist">

                                            @if ($shortList::checkProgram($id)['count'] == 0)
                                                <button class="btn btn-icon btn-primary  shortlist-add"
                                                    data-id="{{ $id }}"><i class='fa fa-heart'></i>
                                                    Shortlist</button>
                                            @elseif($shortList::checkProgram($id)['count'] == 1)
                                                {{-- <button class="btn btn-danger remove"
                                                    data-id="{{ $shortList::checkProgram($id)['id'] }}">Remove From
                                                    ShortList</button> --}}
                                                <span class="text-primary"><i class="fa fa-check"></i> Shortlisted</span>
                                            @endif
                                        @else
                                            <a href="{{ route('login') . '?redirect_to=' . urlencode(url()->current()) }}"
                                                class="btn btn-icon btn-primary" data-id="{{ $id }}"><i
                                                    class="fa fa-bullseye" aria-hidden="true"></i> Login to Apply</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
@endsection

@section('page-script')
    <!-- Page js files -->
    <script>
        $(document).ready(function() {
            $(document).on('click', '.shortlist-add', function() {
                let that = $(this);
                $.ajax({
                    url: "{{ route('shortlist-programs-add') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        campus_program_id: $(this).data('id')
                    },
                    beforeSend: function() {
                        that.attr('disabled', true).prepend(
                            "<i class='fa fa-spinner fa-spin'></i> ");
                    },
                    success: function(data) {
                        setAlert(data);
                        let button =
                            `<button class="btn btn-danger btn-sm float-right remove" data-id=${data.id}>Remove From ShortList</button>`;
                        button =
                            `<span class="text-primary"><i class="fa fa-check"></i> Shortlisted</span>`;
                        $('#shortlist').remove(that);
                        $('#shortlist').html(button);

                    }
                });

            });

            $(document).on('click', '.remove', function() {
                let that = $(this);
                let button =
                    `<button class="btn btn-primary btn-sm float-right shortlist-add" data-id="{{ $id }}">ShortList</button>`;

                if (confirm('Are you sure  you want to remove this from shortlist ?')) {
                    $.ajax({
                        url: "{{ route('shortlist-programs-remove') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: $(this).data('id')
                        },
                        beforeSend: function() {
                            // shortlist-programs
                            that.attr('disabled', true).prepend(
                                "<i class='fa fa-spinner fa-spin'></i> ");
                        },
                        success: function(data) {
                            setAlert(data);
                            //that.removeAttr('disabled').html('Remove Program');
                            $('#shortlist').remove(that);
                            $('#shortlist').html(button);

                        }
                    });
                }
            });

            $(document).on('click', '.apply', function() {
                id = $(this).data('id')
                $('.apply-title').text('Apply Now');
                $.ajax({
                    url: "{{ url('apply-application') }}" + '/' + id,
                    beforeSend: function() {
                        $('.dynamic-apply').html("Loading");
                    },
                    success: function(data) {
                        $('.dynamic-apply').html(data);
                    }
                })
            });

        });
    </script>
@endsection
