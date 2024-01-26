<div class="row">
  <div class="col-12 px-2 py-2">
     <h4>{{ $row->name }}</h4>
     <div class="row mt-2">
       <div class="col-4">
          <p><strong>Countries</strong></p>
          <table class="table table-sm">
              <tbody>
                  @forelse ($row->documentCountry as $documentCountry)
                      <tr>
                        <td>{{ $documentCountry->country->name }}</td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="2"><strong>N/A</strong></td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
       </div>
     </div>
  </div>
</div>
