<form method="POST" action="{{ route('student.profile-step', 'general_information') }}" id="general-information">
    @csrf
    <h3 class="font-weight-bold">Personal Information</h3>
    <p>(As indicated on your passport)</p>
    {{-- First Row --}}
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="first_name">First Name<span class="required text-danger">*</span></label>
                <input type="text"
                    class="form-control @error('first_name') {{ errCls() }} @enderror check-profile"
                    id="first_name" name="first_name" value="{{ old('first_name', $user->name) }}"
                    data-check="complete">
                @error('first_name')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="last_name">Last Name<span class="required text-danger">*</span></label>
                <input type="text"
                    class="form-control @error('last_name') {{ errCls() }} @enderror check-profile" id="last_name"
                    name="last_name" value="{{ old('last_name', $user->last_name) }}" data-check="complete">
                @error('last_name')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="personal-number">Phone Number<span class="required text-danger">*</span></label>
                <input placeholder="9876543210" type="text" name="personal_number"
                    class="form-control  @error('personal_number') {{ errCls() }} @enderror check-profile"
                    id="personal-number" value="{{ old('personal_number', $user->personal_number) }}"
                    data-check="complete">
                <span class="text-dark text-bold-500">Enter valid 10 digit Phone Number. eg. 9876543210</span>
                @error('personal_number')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>
    </div>
    {{-- Second Row --}}
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="dob">Date of Birth<span class="required text-danger">*</span></label>
                <input placeholder="Select Date of Birth" type="text"
                    class="form-control picker__input pickadate @error('dob') {{ errCls() }} @enderror check-profile"
                    id="dob" name="dob" value="{{ old('dob', $user->dob) }}" data-check="complete">

                @error('dob')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="language">Primary Language<span class="required text-danger">*</span></label>
                <input type="text" name="language"
                    class="form-control @error('language') {{ errCls() }} @enderror check-profile" id="language"
                    value="{{ old('language', $user->language) }}" placeholder="Primary Language"
                    data-check="complete">
                @error('language')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="country">Country of Citizenship<span class="required text-danger">*</span></label>
                <select data-style="border-light bg-white" data-style="border-light bg-white" name="country"
                    class="form-control select @error('country') {{ errCls() }} @enderror check-profile"
                    id="country" data-live-search="true" data-check="complete">
                    <option value="">--Select--</option>
                    @foreach (config('countries') as $country)
                        <option {{ $country->id == old('country', $user->country) ? 'selected' : '' }}
                            value="{{ $country->id }}">
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
                @error('country')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>
    </div>

    {{-- Third Row --}}
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="passport_number">Passport Number<span class="required text-danger">*</span></label>
                <input type="text" name="passport_number"
                    class="form-control @error('passport_number') {{ errCls() }} @enderror check-profile"
                    id="passport-number" value="{{ old('passport_number', $user->passport) }}"
                    placeholder="Enter Passport Number" data-check="complete">
                <span class="text-dark text-bold-500">e.g J1239349</span>
                @error('passport_number')
                    {!! errMsg($message) !!}
                @enderror

            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="maritial_status">Marital status<span class="required text-danger">*</span></label>
                <select data-style="border-light bg-white" name="maritial_status" id="maritial_status"
                    class="form-control select @error('maritial_status') {{ errCls() }} @enderror check-profile"
                    data-check="complete">
                    <option value="">---Select Status---</option>
                    @foreach (config('setting.status') as $status => $value)
                        <option value="{{ $status }}"
                            {{ $status == old('maritial_status', $user->maritial_status) ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                @error('maritial_status')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="gender">Gender<span class="required text-danger">*</span></label>
                <select data-style="border-light bg-white"
                    class="form-control select @error('gender') {{ errCls() }} @enderror check-profile"
                    name="gender" id="gender" class="form-control" data-check="complete">
                    <option value="">---Select Gender---</option>
                    @foreach (config('setting.gender') as $gender => $value)
                        <option value="{{ $gender }}"
                            {{ $gender == old('gender', $user->gender) ? 'selected' : '' }}>
                            {{ $value }}</option>
                    @endforeach
                </select>
                @error('gender')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>
    </div>

    <h3 class="font-weight-bold">Address Detail</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="language">Address<span class="required text-danger">*</span></label>
                <input type="text" name="address"
                    class="form-control @error('address') {{ errCls() }} @enderror check-profile" id="address"
                    value="{{ old('address', $user->address->address ?? '') }}" placeholder="Enter Address"
                    data-check="complete">
                @error('address')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="country">Country<span class="required text-danger">*</span></label>
                <select data-style="border-light bg-white"
                    class='form-control select @error('address_country')
                    {{ errCls() }} @enderror check-profile'
                    name="address_country" id="address_country" data-live-search="true" data-check="complete">
                    <option value="">--Select Country--</option>
                    @foreach (config('countries') as $country)
                        <option
                            {{ $country->id == old('address_country', $user->address->country_id ?? '') ? 'selected' : '' }}
                            value="{{ $country->id }}">{{ $country->name }} </option>
                    @endforeach
                </select>
                @error('address_country')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="state">State/Province<span class="required text-danger">*</span></label>
                <select data-style="border-light bg-white"
                    class='form-control select @error('state') {{ errCls() }}
                    @enderror check-profile'
                    name="state" id="state" data-live-search="true" data-check="complete">
                    <option value="">--Select State --</option>
                    @foreach (config('states') as $state)
                        <option {{ $state->id == old('state', $user->address->state_id ?? '') ? 'selected' : '' }}
                            value="{{ $state->id }}">
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
                @error('state')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="city">City/Town<span class="required text-danger">*</span></label>
                <select data-style="border-light bg-white"
                    class='form-control select @error('city') {{ errCls() }}
                    @enderror check-profile'
                    name="city" id="city" data-live-search="true" data-check="complete">
                    <option value="">--Select City --</option>
                    <option value='new-city'>Add New City</option>
                    @foreach (config('cities') as $city)
                        <option {{ $city->id == old('city', $user->address->city_id ?? '') ? 'selected' : '' }}
                            value="{{ $city->id }}">{{ $city->name }}
                        </option>
                    @endforeach
                </select>
                @error('city')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group">
                <label for="postal">Postal/Zip Code<span class="required text-danger">*</span></label>
                <input type="text" name="postal"
                    class="form-control @error('postal') {{ errCls() }} @enderror check-profile" id="postal"
                    value="{{ old('postal', $user->postal) }}" placeholder="Enter Postal/Zip Code"
                    data-check="complete">
                @error('postal')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>


    </div>


    <div class="row city-box d-none">
        <div class="col-md-4">
            <div class="form-group">
                <label for="postal">City Name<span class="required text-danger">*</span></label>
                <input type="text" name="city_name"
                    class="form-control @error('city_name') {{ errCls() }} @enderror check-profile"
                    id="city_name" value="{{ old('city_name') }}" placeholder="Enter City Name"
                    data-check="complete">
                @error('city_name')
                    {!! errMsg($message) !!}
                @enderror
            </div>
        </div>
    </div>
    <div class="row mt-2 ">
        <div class="col-md-12 text-right">
            <input type="hidden" id="move_edu" value="0">
            <button type="submit" data-move="0" class="btn btn-primary" id="general-information-btn">Save</button>
            <button type="button" class="btn btn-primary" id="next-general-btn">Next</button>
        </div>
    </div>
</form>

<script>
    function generalInformationScript() {
        // Plugin initilization
        $("#address_country").selectpicker();
        $("#state").selectpicker();
        $("#city").selectpicker();
        $("#maritial_status").selectpicker();
        $("#country").selectpicker();
        $("#gender").selectpicker();

        $('#dob').pickadate({
            format: 'dd-mmmm-yyyy',
            max: 'Today',
            min: [1970, 3, 20],
            selectYears: 60,
            selectMonths: true,
        });
        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z]+$/i.test(value);
        }, "Please only enter characters.");
        // Client side validations
        validateForm($('#general-information'), {
            rules: {
                first_name: {
                    required: true,
                },
                email: {
                    required: false,
                },
                dob: {
                    required: false,
                },
                country: {
                    required: false,
                },
                last_name: {
                    required: false,
                },
                personal_number: {
                    required: false,
                    number: true,
                    maxlength: 10,
                    minlength: 10
                },
                gender: {
                    required: false
                },
                maritial_status: {
                    required: false
                },
                language: {
                    required: false,
                    lettersonly: false
                },
                postal: {
                    required: false,
                    number: true,
                    maxlength: 6,
                },
                address: {
                    required: false,
                },
                passport_number: {
                    required: false,
                    // minlength: 8,
                    // maxlength: 8,
                },
                city: {
                    required: false
                },
                state: {
                    required: false
                },
                address_country: {
                    required: false
                }

            },
            messages: {
                passport_number: {
                    pattern: "Please enter valid passport number"
                }
            }
        });
        // $('#general-information').valid();
        // form submission
        submitForm($('#general-information'), {
            beforeSubmit: function() {
                submitLoader("#general-information-btn");
            },
            success: function(data) {
                setAlert(data);
                if (data.success) {
                    if ($("#move_edu").val() == 1) {
                        nextStep();
                    }
                    submitReset('#general-information-btn');
                    // checkGenInfo();
                } else {
                    $(".general_information_box").html(data);
                    generalInformationScript();
                }
                // previousStep();
            },
            complete: function() {
                submitReset('#general-information-btn');
                updateProgressLabel();
            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
            }
        });
    }

    function updateState(id) {
        getContent({
            url: "{{ url('admin/state/address') }}" + "/" + id,
            success: function(data) {
                let html = '';
                data.forEach(state => html += `<option value='${state.id}'>${state.name}</option>`);
                $('#state').html('');
                $('#state').append(html);
                $("#state").selectpicker('refresh');
            }
        });
    }

    function updateCity(id) {
        getContent({
            url: "{{ url('admin/city/address') }}" + '/' + id,
            success: function(data) {
                let html =
                    "<option value=''>--Select City --</option><option value='new-city'>Add New City</option>";
                data.forEach(state => html += `<option value='${state.id}'>${state.name}</option>`);
                $('#city').html('');
                $('#city').append(html);
                $("#city").selectpicker('refresh');
            }
        });
    }
</script>
