@extends('layouts/contentLayoutMaster')

@section('title', 'Update Campus Program')

@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    @include('dashboard.inc.message')
                </div>
            </div>
            <form class='form form-vertical' id="campus-program-edit-form"
                action="{{ route('admin.campus-program.update', $campusProgram->id) }}" method="post">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h4>Campus Program Information</h4>
                        <hr>
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="university">University</label>
                            <select class='form-control select @error('university') {{ errCls() }} @enderror'
                                name="university" id="university" data-live-search="true">
                                <option value="">--Select University --</option>
                                @foreach (config('universties') as $unversity)
                                   
                                    <option
                                        {{ $unversity->id == old('university', $campusProgram->university_id) ? 'selected' : '' }}
                                        value={{ $unversity->id }}>{{ $unversity->name }}</option>
                                @endforeach
                            </select>
                            @error('university')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="campus">Campus</label>
                            <select required class='form-control select @error('campus') {{ errCls() }} @enderror'
                                name="campus" id="campus" data-live-search="true"
                                data-value="{{ old('campus', $campusProgram->campus_id) }}">
                                @foreach (config('campuses') as $campus)

                              
                      

                                    <option {{ $campus->id == old('campus', $campusProgram->campus_id)? 'selected' : ''  }}
                                        value={{ $campus->id }}>{{ $campus->name }}</option>
                                @endforeach
                            </select>
                            @error('campus')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="program">Program</label>
                            <select required class='form-control program @error('program') {{ errCls() }} @enderror'
                                name="program" id="program" data-live-search="true" value="{{ old('program') }}">
                                @foreach ($programs as $program)
                                    <option data-duration="{{ $program->duration }}"
                                        {{ $program->id == old('program', $campusProgram->program_id) ? 'selected' : '' }}
                                        value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach

                            </select>
                            @error('program')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-4 px-0">
                            <label for="campus_program_duration">Duration</label>
                            <input name="campus_program_duration" id="campus_program_duration" type="number"
                                value="{{ old('campus_program_duration', $campusProgram->campus_program_duration ?? 0) }}"
                                class="form-control @error('campus_program_duration') {{ errCls() }} @enderror" />
                            @error('campus_program_duration')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="intake">Intakes</label>
                            <select required class='form-control select @error('intakes') {{ errCls() }} @enderror'
                                name="intakes[]" id="intake" data-live-search="true" multiple>
                                @foreach ($intakes as $intake)
                                    <option {{ in_array($intake->id, old('intakes', $intakeIds)) ? 'selected' : '' }}
                                        value={{ $intake->id }}>{{ $intake->name }}</option>
                                @endforeach
                            </select>
                            @error('intakes')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="entery-requirement">Entry Requirement</label>
                            {{-- <input type="text" name="entry_requirement"
                            class="form-control @error('entry_requirement') {{ errCls() }} @enderror"
                            value="{{ old('entry_requirement', $campusProgram->entry_requirment) }}">
                        --}}
                            <textarea name="entry_requirement" id="entery-requirement" cols="5" rows="5"
                                class="form-control @error('entry_requirement') {{ errCls() }} @enderror">{{ old('entry_requirement', $campusProgram->entry_requirment) }}</textarea>
                            @error('entry_requirement')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>



                    </div>
                    <div class="col-md-6 col-12">
                        <div class="row">
                            <div class="col">
                                <h4>Program Fees</h4>
                                <hr>
                                @php
                                    $i = 0;
                                    $j = 0;
                                @endphp
                                @foreach ($feeTypes as $feetype)
                                    <div class="form-group mb-0">
                                        <label>{{ $feetype->name }}</label>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-8 col-12">
                                            <input min="0" max="9999999"
                                                value="{{ old("fees.{$i}.price", $campusProgramFees[$feetype->id]['fee_price']) }}"
                                                type="text" class="form-control"
                                                placeholder="Add {{ $feetype->name }} Fee"
                                                name="fees[{{ $i }}][price]">
                                        </div>
                                        <div class="col-md-4 col-12 mt-md-0 mt-50">
                                            <input value="{{ $feetype->id }}" type="hidden"
                                                name="fees[{{ $i }}][id]">
                                            <select class="select w-100 " name="fees[{{ $i }}][currency]">
                                                @foreach ($currencies as $currency)
                                                    {{-- @if ($currency->code != 'USD') @continue @endif --}}
                                                    <option
                                                        {{ $selected = $currency->id == old("fees.{$i}.currency", $campusProgramFees[$feetype->id]['fee_currency']) ? 'selected' : '' }}
                                                        value="{{ $currency->id }}">{{ $currency->code }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @php $i++; @endphp
                                @endforeach
                            </div>
                        </div>

                        <div class="row">
                            <div class="col">
                                <h4>Test Scores</h4>
                                <hr>
                                <div class="row">
                                    @foreach ($tests as $test)
                                        <div class="form-group col-6">
                                            <label>{{ $test->test_name }}</label>
                                            <input min="{{$test->min}}" max="{{$test->max}}" type="text" class="form-control"
                                                name="test[{{ $j }}][score]"
                                                value="{{ old("test.$j.score", $campusProgramTest[$test->id]['score']) }}" />
                                            <input type="hidden" name="test[{{ $j }}][type]"
                                                value="{{ $test->id }}" />
                                            <div class="vs-checkbox-con vs-checkbox-primary mt-1">
                                                <input type="checkbox" value="1"
                                                    name="test[{{ $j }}][show]"
                                                    {{ old("test.$j.show", $campusProgramTest[$test->id]['show_in_front']) == 1 ? 'checked' : '' }} />
                                                <span class="vs-checkbox">
                                                    <span class="vs-checkbox--check">
                                                        <i class="vs-icon feather icon-check"></i>
                                                    </span>
                                                </span>
                                                <span class="">Show</span>
                                            </div>
                                        </div>

                                        @php $j++ @endphp
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">

                        <div class="form-group">
                            <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </div>
            </form>
            @if ($campusProgram->userApplication->count() > 0)
                <div class="col-md-12">
                    <p>{{ config('setting.delete_notice') }}</p>
                    @php session()->put('used_campus_program',[$campusProgram->university_id,$campusProgram->campus_id, $campusProgram->program_id]); @endphp
                    <a href="{{url('admin/applications-all')}}"><p> click Here to Show Uses</p><a>
                </div>
            @else
                <div class="form-group delete" style="margin-top:1%">
                    <form id="delete-form" method="POST"
                        action="{{ route('admin.campus-program.destroy', $campusProgram->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            @endif
        </div>
    </div>


@endsection

@section('page-script')
    <script>
        $(document).ready(function() {

            $(".select").select2();

            $('.program').select2({
                placeholder: 'Program',
                multiple: false,
                // ajax: {
                //     url: route('select-programs'),
                //     dataType: 'json',
                //     type: 'POST',
                //     data: function(params) {
                //         return {
                //             name: params.term
                //         }
                //     },
                //     processResults: function(data) {
                //         return {
                //             results: data
                //         }
                //     }
                // }
            });

            $(".program").change(function() {
                let duration = $(".program").select2().find(":selected").data("duration");
                $("#campus_program_duration").val(duration);
            });

            validateForm($('#campus-program-create-form, #campus-program-edit-form'), {
                rules: {
                    universtiy: {
                        required: true
                    },

                    intakes: {
                        required: true
                    },
                    price: {
                        required: true
                    },
                    entry_requirement: {
                        required: true
                    }
                },
                messages: {}
            });

            $('#university').change(function() {
                id = $(this).val();
                updateCampuses(id);
            });
            updateCampuses($('#university').find("option:selected").val());

            submitForm($("#delete-form"), {
                beforeSubmit: function() {
                    if (!confirm('Are you sure you want to delete')) return false;
                    submitLoader("#submit-btn-delete");
                },
                success: function(data) {
                    setAlert(data);
                    if (data.success) {
                        window.location = "{{ route('admin.campus-programs') }}";
                    }
                },
                complete: function() {
                    submitReset("#submit-btn-delete");
                },
                error: function(data) {
                    toast("error", "Something went wrong.", "Error");
                }
            });

        });

        function updateCampuses(id) {
            getContent({
                url: "{{ url('admin/get-campus') }}" + "/" + id,
                success: function(data) {
                    option = '';
                    let val = $("#campus").attr('data-value');
                    let selected = "";
                    data.forEach(campus => {
                        if (val == campus.id) {
                            selected = "selected";
                        }
                        option +=
                            `<option ${selected} value=${campus.id}>${campus.name}</option>`;
                        selected = "";
                    });

                    $('#campus').find('option').remove();
                    $('#campus').append(option);
                }
            })
        }
    </script>
@endsection
