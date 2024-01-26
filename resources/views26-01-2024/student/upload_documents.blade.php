
@inject('student', 'App\Http\Controllers\StudentController')
<div class="row">
  <div class="col-12">
      <h1>Upload Document</h1>
      <div class="row">
         <div class="col-3">
          <select id="add-document" class="form-control my-2 select">
            <option value="">--Select Document--</option>
            <optgroup label="Special Test" data-cat="tests">
                @forelse ($userTests as $userTest)
                <option value="{{ $userTest->id }}" data-type="{{ $userTest->getType->test_name }}" @if(in_array($userTest->id,$userTestIds)) disabled @endif data-limit="1">{{ $userTest->getType->test_name }}{{ in_array($userTest->id,$userTestIds)?'[Document added]':''}}</option>
              @empty
                <option value="" disabled>No Test Added</option>
              @endforelse
            </optgroup>
            <optgroup label="Education" data-cat="study_levels">
                @forelse ($userStudyLevels as $userStudyLevel)
                  <option value="{{ $userStudyLevel->id }}" data-type="{{ $userStudyLevel->getStudyLevel->name }}" @if(in_array($userStudyLevel->id,$userStudyIds)) disabled @endif data-limit="{{ $userStudyLevel->getStudyLevel->document_limit }}">{{ $userStudyLevel->getStudyLevel->name }}{{ in_array($userStudyLevel->id,$userStudyIds)?'(Document added)':''}}</option>
                  @empty
                  <option value="" disabled>No Education Added</option>
                @endforelse
            </optgroup>
            <optgroup label="Other" data-cat="other">
              @foreach ($documentTyps as $documentType)
                <option value="{{ $documentType->id }}" data-type="Other">{{ $documentType->title }}</option>
              @endforeach
            </optgroup>
          </select>
         </div>
       </div>
       <div class="row">
         <div class="col-3">
            <div class="document-form"></div>
         </div>
       </div>
       <div class="table-responsive">
        <table id="document-table" class="table table-hover w-100 zero-configuration">
            <thead>
              <th>File</th>
              <th>Type</th>
              <th>Action</th>
            </thead>
        </table>
      </div>
  </div>
</div>
 </div>
 <div class="col-md-2 mt-2"></div>
<div>
<div class="row">
   <div class="col-12">
    <button type="button" class="btn btn-primary previous" >Previous</button>
   </div>
</div>
