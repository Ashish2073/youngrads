@php
    $campus = App\Models\CampusProgram::find($row->campusprogram_id);
    $fees = $campus->fees;
    $feeTypeRecords = App\Models\FeeType::all();
    // foreach($feeTypeRecords as $feeTypeRecord) {
    //     $feeArr[\Str::slug($feeTypeRecord->name, "_")] = [];
    // }
    foreach ($fees as $fee) {
        $feeArr[\Str::slug($fee->fee->name, '_')] = [
            'price' => $fee->fee_price,
            'currency' => $fee->currency,
        ];
    }
@endphp
<div class="row px-md-1 py-1">
    <div class="col-12">
        {{-- Row 1 --}}
        <div class="row align-items-start">
            <div class="col-md-9 col-12">
                <h3><a target="_blank" class='text-dark'
                        href="{{ route('program-details', $row->campusprogram_id) }}">{{ $row->program }} <i
                            class='fa text-light fa-external-link'></i></a></h3>
            </div>
            <div class="col-md-3 col-12 mt-1 mt-md-0 text-right">
                @if (auth()->check())
                    @if (is_null($row->shortlist_id))
                        <button class="btn btn-icon text-primary text-left  shortlist-add"
                            data-id="{{ $row->campusprogram_id }}">
                            <span class="fa fa-heart"></span>
                            Shortlist
                        </button>
                    @else
                        <span class="text-primary"><i class="fa fa-check"></i> Shortlisted</span>
                    @endif
                @endif
            </div>
        </div>
        {{-- Row 2 --}}
        <div class="row align-items-start mt-1">
            <div class="col-md-3 col-6">
                <x-icon-text icon="fa fa-university" heading="University">
                    {{ $row->universtiy ?? 'N/A' }}
                </x-icon-text>
            </div>
            <div class="col-md-3 col-6">
                <x-icon-text icon="fa fa-map-marker" heading="Campus">
                    @if ($row->campus)
                        <a target="_blank" href="{{ route('campus-search', $row->campus_id) }}">
                            {{ $row->campus }}
                            <i class="fa fa-external-link"></i>
                        </a>
                    @else
                        N/A
                    @endif

                </x-icon-text>
            </div>
            <div class="col-md-3 col-6">
                <x-icon-text icon="fa fa-globe" heading="Country">
                    {{ $row->country ?? 'N/A' }}
                </x-icon-text>
            </div>
            <div class="col-md-3 col-6">
                <x-icon-text icon="fa fa-clock-o" heading="Duration">
                    {{ $row->duration }} Month(s)
                </x-icon-text>
            </div>

        </div>
        {{-- Row 3 --}}
        <div class="row align-items-start mt-1">
            <div class="col-md-3 col-6">
                <x-icon-text icon="fa fa-money" heading="Tuition Fees">
                    @if (isset($feeArr['tuition_fees']))
                        @php
                            $c_code = $feeArr['tuition_fees']['currency']->code == 'INR' ? '' : $feeArr['tuition_fees']['currency']->symbol;
                        @endphp
                        {!! $feeArr['tuition_fees']['price'] == 0
                            ? 'N/A'
                            : $c_code . '' . $feeArr['tuition_fees']['price'] . ' ' . $feeArr['tuition_fees']['currency']->code !!}
                    @else
                        N/A
                    @endif
                </x-icon-text>
            </div>
            <div class="col-md-3 col-6">
                <x-icon-text icon="fa fa-money" heading="Application Fees">
                    @if (isset($feeArr['application_fees']))
                        {!! $feeArr['application_fees']['price'] == 0
                            ? 'N/A'
                            : $c_code . '' . $feeArr['application_fees']['price'] . ' ' . $feeArr['application_fees']['currency']->code !!}
                    @else
                        N/A
                    @endif
                </x-icon-text>
            </div>
            <div class="col-md-3 col-12">
                <x-icon-text icon="fa fa-user-plus" heading="Intakes">
                    @php $intakes = App\Models\CampusProgramIntake::where('campus_program_id', $row->campusprogram_id)->get(); @endphp
                    @forelse ($intakes as $intake)
                        <span class="badge badge-primary mb-50">{{ $intake->intake->name }}</span>
                    @empty
                        <span>N/A</span>
                    @endforelse
                </x-icon-text>
            </div>

            <div class="col-md-3 col-12 mt-1 mt-md-0 text-right">
                @if (Auth::check())
                    <button type="button" class="btn btn-icon btn-outline-primary apply"
                        data-id="{{ $row->campusprogram_id }}" data-toggle="modal" data-target="#apply-model">
                        <i class="fa fa-bullseye" aria-hidden="true"></i> Apply
                    </button>
                @else
                    <a class="btn btn-icon btn-outline-primary text-left apply-guest" href="{{ route('login') }}">
                        <i class="fa fa-user-circle" aria-hidden="true"></i> Login to Apply
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
