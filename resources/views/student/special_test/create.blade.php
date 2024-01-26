<form action="{{ route('test-score-store') }}" class="my-2" method="POST" id="test-score-add">
  @csrf
  <h4>{{ $name }}</h4>

  <div class="row">
    <div class="col-md-6 col-12">
      <div class="form-group">
        <label for="score">Overall Score<span class=" required text-danger">*</span></label>
        <input required min="{{ $test_record->min ?? 0 }}" max="{{ $test_record->max ?? 10 }}" type="text" name="score" id="score" class="form-control @error('score') {{ errCls() }} @enderror">
        <input type="hidden" name="test_type" value="{{ $id }}">
        @error('score')
        <p class="text-danger">{{ $message }}</p>
        @enderror
      </div>
     </div>
     <div class="col-md-6 col-12">
        <div class="form-group">
          <label for="exam-date">Examination Date<span class=" required text-danger">*</span></label>
          <input required type="text" class="form-control exam-date  @error('exam_date') {{ errCls() }} @enderror work-upto" id="exam-date" name="exam_date" value="{{ old('exam_date')}}"  placeholder="Enter Examination date">
          @error('exam_date')
           <p class="text-danger">{{ $message }}</p>
          @enderror
      </div>
     </div>
  </div>
  <div class="row">
    @php $i = 0; @endphp
    @foreach ($subTests as $subTest)
        <div class="col-6 col-md-3">
          <div class="form-group">
            <label for="score">{{ Str::ucfirst($subTest->name) }} <span class=" required text-danger">*</span></label>
            <input required min="{{ $subTest->min ?? 0 }}" max="{{ $subTest->max ?? 10 }}" type="text" name="subscore[{{ $i }}]" class="form-control @error('subscore.{{ $i }}') {{ errCls() }} @enderror" placeholder="Enter {{ Str::ucfirst($subTest->name) }}">
            <input type="hidden" name="subtype[{{ $i }}]"  value="{{ $subTest->id }}">
            @error('subscore.{{ $i }}')
              <p class="text-danger">{{ $message }}</p>
            @enderror
          </div>
        </div>
        @php $i++; @endphp
    @endforeach
 </div>
 <div class="row">
  <div class="col-12">
    <button class="btn btn-primary" type="submit" id="test-add-btn">Add</button>
 </div>
 </div>
  
</form>
