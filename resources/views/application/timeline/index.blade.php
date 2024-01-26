{{-- <table id="application-timeline-table" class="table w-100">
    <thead class="d-none">
        <tr>
            <th>Status</th>
            <th>Created at</th>
        </tr>
    </thead>
    <tbody class="activity-timeline timeline-left list-unstyled">

    </tbody>
</table> --}}
<ul class="activity-timeline timeline-left list-unstyled">
@forelse ($application->timeline as $timeline)    
    @php $status_meta = config('setting.application.status_meta')[$timeline->status]; @endphp
    <li>
        <div class="timeline-icon bg-{{ $status_meta['color']  }}">
            <i class="{{ $status_meta['icon_class'] }} font-medium-2 align-middle"></i>
        </div>
        <div class="timeline-info">
            <p class="font-weight-bold mb-0">
                {{ $status_meta['description'] ?? config('setting.application.status')[$timeline->status] }}
            </p>
            {{-- <span class="font-small-3">Bonbon macaroon jelly beans gummi bears jelly lollipop apple</span> --}}
        </div>
        <small class="text-dark">{{ date("d M Y h:i A", strtotime($timeline->created_at)) }}</small>
    </li>
@empty
    <li>No activity yet!</li>
@endforelse
</ul>

<script>
    var applicationTimelineTable;
    function applicationTimelineScript(id) {
        return;
        applicationTimelineTable = $("#application-timeline-table").DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: route('application.timeline', id),
                data: function(d) { }
            },
            dom: "tp",
            "order": [
                [1, "asc"]
            ],
            columns: [
                {
                    data: 'status',
                    name: 'application_timelines.status'
                },
                {
                    data: 'created_at',
                    name: 'application_timelines.created_at',
                    visible: false
                }
            ],
            "language": {
                "emptyTable": "No activity yet!"
            },
            drawCallback: function(setting, data) { },
            bInfo: false,
            pageLength: 100,
            initComplete: function(settings, json) { }
        });
    }

</script>
