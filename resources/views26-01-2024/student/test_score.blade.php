<div class="row">
  <div class="col-12">
       <h1>Add Test</h1>
       <div class="row">
         <div class="col-3">
            <select id="add-test" class="form-control my-2">
              <option value="">--Select Test--</option>
              @foreach ($testTypes as $testType)
              <option value="{{ $testType->id }}" @if(in_array($testType->id,$testIds)) disabled @endif>{{ $testType->test_name }} {{ in_array($testType->id,$testIds)? '( Already added )':'' }}</option>
              @endforeach
          </select>
         </div>
       </div>
       <div class="container">
        <div class="test-form"></div>
      </div>
      <div class="table-responsive">
        <table id="testscore-table" class="table table-hover w-100 zero-configuration">
            <thead class="d-none">
              <th>Test</th>
              <th>Action</th>
            </thead>
        </table>
      </div>
  </div>
</div>
  <div class="row mt-2">
    <div class="col-md-12">
      <button type="button" class="btn btn-primary previous" >Previous</button>
      <button type="button" class="btn btn-primary float-right next">Next</button>
    </div>
  </div>
