@extends('layouts/contentLayoutMaster')

@section('title', 'Create Campus Program')

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col">
                    @include('dashboard.inc.message')
                </div>
            </div>
            <form class='form form-vertical' id="campus-program-create-form" action="{{ route('admin.campus-program.store') }}"
                method="post">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h4>Campus Program Information</h4>
                        <hr>
                        @csrf
                        <div class="form-group">
                            <label for="university">University</label>
                            <select required
                                class='form-control w-100 select @error('university') {{ errCls() }} @enderror'
                                name="university" id="university" data-live-search="true" value="{{ old('university') }}">
                                <option value="">-- Select University --</option>
                                @foreach (config('universties') as $university)
                                    <option {{ $university->id == old('university') ? 'selected' : '' }}
                                        value={{ $university->id }}>{{ $university->name }}</option>
                                @endforeach
                            </select>
                            @error('university')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="campus">Campus</label>
                            <select required
                                class='form-control w-100 select @error('campus') {{ errCls() }} @enderror'
                                name="campus" id="campus" data-live-search="true" data-value="{{ old('campus') }}">
                                <option value="">-- Select Campus --</option>
                                @foreach (config('campuses') ?? [] as $campus)
                                    <option {{ $campus->id == old('campus') ? 'selected' : '' }}
                                        value="{{ $campus->id }}">{{ $campus->name }}</option>
                                @endforeach
                            </select>
                            @error('campus')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="program">Programs</label>
                            <select required
                                class='form-control w-100 select program @error('program') {{ errCls() }} @enderror'
                                name="program" id="program" data-live-search="true" value="{{ old('program') }}">
                                <option value="">--Select Program--</option>
                                @foreach (config('programs') as $program)
                                    <option data-duration="{{ $program->duration }}"
                                        {{ $program->id == old('program') ? 'selected' : '' }}
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
                                value="{{ old('campus_program_duration', $program[0]->campus_program_duration ?? 0) }}"
                                class="form-control campus_program_duration @error('campus_program_duration') {{ errCls() }} @enderror" />
                            @error('campus_program_duration')
                                <p class="text-danger">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="intake">Intakes</label>
                            <select required class='form-control select @error('intakes') {{ errCls() }} @enderror'
                                name="intakes[]" id="intake" data-live-search="true" multiple>
                                @foreach ($intakes as $intake)
                                    <option {{ in_array($intake->id, old('intakes', [])) ? 'selected' : '' }}
                                        value="{{ $intake->id }}">{{ $intake->name }}</option>
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
                                value="{{ old('entry_requirement') }}" placeholder="Entry Requirment">
                            --}}
                            <textarea name="entry_requirement" id="entery-requirement" cols="5" rows="5"
                                class="form-control @error('entry_requirement') {{ errCls() }} @enderror">{{ old('entry_requirement') }}</textarea>
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
                                {{-- fee type section --}}
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
                                            <input min="0" max="9999999" value="{{ old("fees.{$i}.price") }}"
                                                type="text" class="form-control"
                                                placeholder="Enter {{ $feetype->name }}"
                                                name="fees[{{ $i }}][price]">
                                        </div>
                                        <div class="col-md-4 col-12 mt-md-0 mt-50">
                                            <input type="hidden" value="{{ $feetype->id }}"
                                                name="fees[{{ $i }}][id]">
                                            <select class="select w-100" name="fees[{{ $i }}][currency]">
                                                {{-- <option value="">--Currency--</option>
                                                --}}
                                                @foreach ($currencies as $currency)
                                                    {{-- @if ($currency->code != 'USD') @continue @endif --}}
                                                    <option
                                                        {{ $selected = $currency->id == old("fees.{$i}.currency") ? 'selected' : '' }}
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
                                                value="{{ old("test.$j.score") }}"
                                                name="test[{{ $j }}][score]">
                                            <div class="vs-checkbox-con vs-checkbox-primary mt-1">
                                                <input type="checkbox" value="1"
                                                    {{ old("test.$j.show") == 1 ? 'checked' : '' }}
                                                    name="test[{{ $j }}][show]">
                                                <span class="vs-checkbox">
                                                    <span class="vs-checkbox--check">
                                                        <i class="vs-icon feather icon-check"></i>
                                                    </span>
                                                </span>
                                                <span class="">Show</span>
                                            </div>
                                        </div>
                                        <input type="hidden" name="test[{{ $j }}][type]"
                                            value="{{ $test->id }}">
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
                            <button type="submit" id="submit-btn" class="btn btn-primary">Add</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection

@section('page-script')
    <script>
        $(document).ready(function() {

            $(".select").select2();

            $('.program').select2({
                placeholder: '--Select Program--',
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

            validateForm($('#campus-program-create-form'), {
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
                    },
                    campus_program_duration: {
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
