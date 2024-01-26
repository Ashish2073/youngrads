<div class="row mb-2">
    <div class="col-12">
        <h4>{{ $row->getType->test_name }}</h4>
        <hr>
        <div class="row">
            <div class="col-md-6 col-12">
                <p><strong>Overall Score:</strong></p>
                <p>{{ $row->score }}</p>
            </div>
            @php

            @endphp
            <div class="col-md-6 col-12">
                <p><strong>Date of Examination</strong></p>
                <p>{{ $row->exam_date == null ? 'N/A' : date('d F Y', strtotime($row->exam_date)) }} </p>
            </div>
        </div>
        <hr>
        <div class="row">
            @forelse ($row->getSubScore as $subScore)
                <div class="col-md-3 col-6">
                    <p><strong>{{ Str::ucfirst($subScore->name) }}</strong></p>
                    <p>{{ $subScore->score }}</p>
                </div>
            @empty
                <div class="col-md-3 col-6">
                    <p>N/A</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

