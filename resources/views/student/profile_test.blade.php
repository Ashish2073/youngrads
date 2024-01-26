<h4>{{ $userTest->getType->test_name }}</h4>

<div class="row">
    <div class="col-6">
        <p><strong>Overall Score:</strong></p>
        <p>{{ $userTest->score }}</p>
    </div>
    <div class="col-6">
        <p><strong>Date of Examination</strong></p>
        <p>{{ $userTest->exam_date == null ? 'N/A' : date('d-M-Y', strtotime($userTest->exam_date)) }} </p>
    </div>
</div>
<div class="row">
    @forelse ($userTest->getSubScore as $subScore)
        <div class="col">
            <p><strong>{{ Str::ucfirst($subScore->name) }}</strong></p>
            <p>{{ $subScore->score }}</p>
        </div>
    @empty
        
    @endforelse
</div>
