<p>Education history</p>
<div class="divider">
  <div class="divider-text">Highest Education</div>
</div>
<form action="{{ route('highest-education') }}" method="POST" id="hightest-education-form"  enctype="multipart/form-data">
  @csrf
  <div class="row">
    <div class="col-6">
      @php
           $country = "";
           $country = DB::table('countries')->where('id',$educationCountryId)->select('id','name')->get();
      @endphp
      <div class="form-group">
          <label for="education-country">Country Of Education<span class="text-danger">*</span></label>
          <select name="country" data-style="border-light bg-white" id="education-country" class="select form-control" data-live-search="true">
             @php  $educationCountry = !empty($country[0])? $country[0]->id : "" ;  @endphp
             <option value="">--Select Country--</option>
            @foreach ($countries as $country)
              <option value="{{ $country->id }}" @if($educationCountry == $country->id || old('address_country') == $country->id) selected @endif>{{ $country->name }}</option>
            @endforeach
          </select>
      </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="highest-education">Highest Level of Education<span class="text-danger">*</span></label>
            <select data-style="border-light bg-white" name="highest_education" id="highest-education" class="form-control select">
              <option value="">--Select Level--</option>
              @foreach ($studyLevels as $studyLevel)
                @if($studyLevel->parent_id == 0 && $studyLevel->name != "Other")
                  <option value="{{ $studyLevel->id }}" @if($highestEducation == $studyLevel->id) selected @endif>{{ $studyLevel->name }}</option>
                @endif
              @endforeach
            </select>
        </div>
    </div>
</div>
</form>
{{-- <div class="row">
  <div class="col-12">
    <button type="submit" class="btn btn-primary my-2" id="hightest-education-btn">Save</button>
  </div>
</div> --}}
<div class="divider">
  <div class="divider-text">Education Details</div>
 </div>
 <button type="button" class="btn btn-primary mb-1" id="add-education"><span class='fa fa-plus'></span> Add Education Detail</button>
<div class="educaton-form">

</div>
@error('study_level')
        <p class="text-danger">{{ $message }}</p>
@enderror
<div class="table-responsive">
  <table id="education-table" class="table table-hover w-100 zero-configuration">
      <thead>
      <tr>
          <th>Study Level</th>
          <th>Name of Institute</th>
          <th>Marks</th>
          <th>Country</th>
          <th>Language</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Action</th>
      </tr>
      </thead>
  </table>
</div>
<div class="row mt-2">
  <div class="col-md-12">
     <button type="button" class="btn btn-primary previous" >Previous</button>
     <button type="submit" class="btn btn-primary float-right next" id="hightest-education-btn">Next</button>
  </div>
</div>

