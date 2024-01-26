<div class="row">
  <div class="col-12">
    <div class="table-responsive">
     <table id="shortlist-table" class="table table-hover w-100 zero-configuration">
        <thead>
            <tr>
              <th>University</th>
              <th>Campus</th>
              <th>Program</th>
              <th>Detail</th>
            </tr>
        </thead>
        <tbody>
           @forelse ($shortlists as $shortlist)
               <tr>
                 <td>{{ $shortlist->university }}</td>
                 <td>{{ $shortlist->campus }}</td>
                 <td>{{ $shortlist->program }}</td>
                 <td>
                   <a href="{{ route('program-details', $shortlist->campus_program_id) }}" target="_blank">View Program</a>
                 </td>
               </tr>
           @empty
              
           @endforelse
        </tbody>
    </div>
  </div>
</div>
