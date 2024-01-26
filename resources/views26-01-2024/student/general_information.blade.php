<form method="POST" action="{{ route('general-information-store') }}" id="general-information">
  @csrf
  <h3 class="font-weight-bold">Personal Information</h3>
  <p>(As indicated on your passport)</p>
  {{-- First Row --}}
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label for="first_name">First Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('first_name') {{ errCls() }} @enderror check-profile" id="first_name" name="first_name" value="{{ old('first_name', $user->name)}}" data-check="complete">
        @error('first_name')
         <p class="text-danger">{{ $message }}</p>
        @enderror
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="last_name">Last Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('last_name') {{ errCls() }} @enderror check-profile" id="last_name" name="last_name" value="{{ old('first_name', $user->last_name)}}" data-check="complete">
        @error('last_name')
         <p class="text-danger">{{ $message }}</p>
        @enderror
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="personal-number">Phone Number<span class="text-danger">*</span></label>
        <input placeholder="+919876543210" type="text"  name="personal_number" class="form-control @error('personal-number') {{ errCls() }} @enderror check-profile" id="personal-number" value="{{ old('personal_number', $user->personal_number) }}" data-check="complete">
        @error('peronal_number')
            <p class="text-danger">{{ $message }}</p>
        @enderror
      </div>
    </div>
  </div>
  {{-- Second Row --}}
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label for="dob">Date of Birth <span class="text-danger">*</span></label>
        <input placeholder="Select Date of Birth" type="text" class="form-control picker__input pickadate @error('dob') {{ errCls() }} @enderror check-profile" id="dob" name="dob" value="{{old('dob', $user->dob)}}" data-check="complete" >
  
          @error('dob')
           <p class="text-danger">{{ $message }}</p>
          @enderror
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="language">Primary Language<span class="text-danger">*</span></label>
      <input type="text" name="language" class="form-control @error('language') {{ errCls() }} @enderror check-profile" id="language"   value="{{ old('language', $user->language) }}" placeholder="Primary Language" data-check="complete">
        @error('language')
            <p class="text-danger">{{ $message }}</p>
        @enderror
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="country">Country of Citizenship<span class="text-danger">*</span></label>
        <select data-style="border-light bg-white" data-style="border-light bg-white" name="country" class="form-control select @error('country') {{ errCls() }} @enderror check-profile" id="country" data-live-search="true" data-check="complete">
            <option value="">--Select--</option>
             @php  $userCitizenShip  = (isset($country[0]))? $country[0]->id : ''; @endphp
            @foreach ($countries as $citizenShip)
              <option value={{ $citizenShip->id }} @if($userCitizenShip == $citizenShip->id || old('country') == $citizenShip->id) selected  @endif>{{ $citizenShip->name }}</option>
            @endforeach
        </select>
        @error('country')
            <p class="text-danger">{{ $message }}</p>
        @enderror
      </div>
    </div>
  </div>

  {{-- Third Row --}}
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label for="passport_number">Passport Number<span class="text-danger">*</span></label>
        <input type="text"  name="passport_number" class="form-control @error('passport_number') {{ errCls() }} @enderror check-profile" id="passport-number" value="{{ old('passport_number', $user->passport) }}" placeholder="Enter Passport Number" data-check="complete">
        <span class="text-muted">e.g J12393496</span>
        @error('passport_number')
            <p class="text-danger">{{ $message }}</p>
        @enderror
        
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="maritial_status">Marital status <span class="text-danger">*</span></label>
        <select data-style="border-light bg-white" name="maritial_status" id="maritial_status"  class="form-control select @error('maritial_status') {{ errCls() }} @enderror check-profile" data-check="complete">
          <option value="">---Select Status---</option>
          @foreach (config('setting.status') as $status=>$value)
             <option value="{{ $status }}" @if ($user->maritial_status == $status || old('maritial_status') == $status) selected @endif>{{ $value }}</option>
          @endforeach
        </select>
        @error('maritial_status')
            <p class="text-danger">{{ $message }}</p>
        @enderror
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="gender">Gender<span class="text-danger">*</span></label>
        <select data-style="border-light bg-white" class="form-control select @error('gender') {{ errCls() }} @enderror check-profile" name="gender" id="gender" class="form-control" data-check="complete">
          <option value="">---Select Gender---</option>
          @foreach (config('setting.gender') as $gender=>$value)
            <option value="{{ $gender }}" {{ $gender == old('gender', $user->gender) ? "selected" : "" }} >{{ $value }}</option>
          @endforeach
        </select>
        @error('gender')
            <p class="text-danger">{{ $message }}</p>
        @enderror
      </div>
    </div>
  </div>

  <h3 class="font-weight-bold">Address Detail</h3>
  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label for="language">Address<span class="text-danger">*</span></label>
        <input type="text" name="address" class="form-control @error('address') {{ errCls() }} @enderror check-profile" id="address"   value="{{ old('address', $address->address??'') }}" placeholder="Enter Address" data-check="complete">
          @error('address')
              <p class="text-danger">{{ $message }}</p>
          @enderror
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      @php
        $addresCountry = ($addressCountry != "")? $addressCountry[0]->id : "";
      @endphp
        <div class="form-group">
          <label for="country">Country<span class="text-danger">*</span></label>
          <select data-style="border-light bg-white" class='form-control select @error('address_country') {{ errCls() }} @enderror check-profile' name="address_country" id="address_country" data-live-search="true" data-check="complete">
                 <option value="">--Select Country--</option>
                 @foreach ($countries ?? [] as $country)
                    <option value="{{ $country->id }}" @if($addresCountry == $country->id || old('address_country') == $country->id) selected @endif>{{ $country->name }}</option>
                 @endforeach
          </select>
          @error('address_country')
          <p class="text-danger">{{ $message }}</p>
          @enderror
      </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="state">State/Province <span class="text-danger">*</span></label>
        <select data-value="{{ $state != "" ? $state[0]->id : "" }}" data-style="border-light bg-white" class='form-control select @error('state') {{ errCls() }} @enderror check-profile' name="state" id="state"
                data-live-search="true" data-check="complete">
                {{-- @if($state != "")
                  <option value={{ $state[0]->id }}>{{ $state[0]->name }}</option>
                @else
                  <option value="">--Select State --</option>
                @endif --}}
                <option value="">--Select State --</option>
                @foreach($states ?? [] as $val)
                  <option {{ $val->id == $state[0]->id ? "selected" : "" }} value="{{ $val->id }}">{{ $val->name }}</option>
                @endforeach
        </select>
        @error('state')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-4">
      <div class="form-group">
        <label for="city">City/Town <span class="text-danger">*</span></label>
        <select data-value="{{ $city != "" ? $city[0]->id : "" }}" data-style="border-light bg-white" class='form-control select @error('city') {{ errCls() }} @enderror check-profile' name="city" id="city"
                data-live-search="true" data-check="complete">
                {{-- @if($city != "")
                 <option value={{ $city[0]->id }}>{{ $city[0]->name }}</option>

                @else
                 <option value="">--Select City --</option>
              @endif --}}
              <option value="">--Select City --</option>
              @foreach($cities ?? [] as $val)
                <option {{ $val->id == $city[0]->id ? "selected" : "" }} value="{{ $val->id }}">{{ $val->name }}</option>
              @endforeach
        </select>
        @error('city')
        <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <label for="postal">Postal/Zip Code</label>
        <input type="text"  name="postal" class="form-control @error('postal') {{ errCls() }} @enderror check-profile" id="postal" value="{{ old('postal', $user->postal) }}" placeholder="Enter Postal/Zip Code" data-check="complete">
        @error('postal')
            <p class="text-danger">{{ $message }}</p>
        @enderror
      </div>
    </div>
  </div>

<div class="row mt-2">
  <div class="col-md-12">
    <button type="submit" class="btn btn-primary float-right" id="first">Next</button>
  </div>
</div>
</form>

