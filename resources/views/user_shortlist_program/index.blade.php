@extends('layouts/contentLayoutMaster')

@section('title', 'Shortlisted Programs')

@section('vendor-style')

@endsection
@section('page-style')

@endsection

@section('content')
<section id="basic-datatable">
  <div class="row">
      <div class="col-12">
          <div class="card">
              {{-- <div class="card-header">
            </div> --}}
              <div class="card-content">
                  <div class="card-body card-dashboard">
                      <div class="table-responsive">
                          <table id="shortlist-table" class="table w-100 zero-configuration">
                              <thead>
                                <tr>
                                  <th>University Name</th>
                                    <th>Campus Name</th>
                                    <th>Program Name</th>
                                    <th>Action</th>
                                    <th>Application</th>
                                 </tr>
                              </thead>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</section>
@endsection

@section('vendor-script')

@endsection
@section('page-script')
    <!-- Page js files -->
    <script>
      var dataTable;
       $(document).ready(function(){

        dataTable = $("#shortlist-table").DataTable({
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: "{{route('shortlist-programs')}}",
                    data: function (d) {

                    }
                },
                columns: [
                  {    name: 'university',
                        data: 'university'
                    },
                   {
                     name: 'campus',
                     data: 'campus'
                   },
                   
                    {
                      name:'program',
                      data:'program'
                    },
                    {
                      name: 'action',
                      data: 'action'
                    },
                    {
                      data:'check_apply'
                    }
                ],
                responsive: false,

                drawCallback: function(setting, data) {

                },

                aLengthMenu: [[10, 15, 20], [10, 15, 20]],

                bInfo: false,
                pageLength: 10,
                initComplete: function (settings, json) {
                    $(".dt-buttons .btn").removeClass("btn-secondary");
                    $(".table-img").each(function() {
                        $(this).parent().addClass('product-img');
                    });
                }
            });
            // submitLoader("#first"); submitReset('#first', 'Next');
            $(document).on('click','.remove', function(){
                let that = $(this);
                 if(confirm('Are you sure ?')){
                    $.ajax({
                      url:"{{route('shortlist-programs-remove')}}",
                      type: 'POST',
                      data: {
                          _token: "{{ csrf_token() }}",
                          id : $(this).data('id')
                      },
                      beforeSend: function(){
                        // shortlist-programs
                        that.attr('disabled', true).prepend("<i class='fa fa-spinner fa-spin'></i> ");
                      },
                      success: function(data){
                            setAlert(data);
                            dataTable.draw('page');
                          that.removeAttr('disabled').html('');
                      }
                  });

                 }

            });


           //apply now
            $(document).on('click','.apply',function(){
                 id = $(this).data('id')
                 $('.dynamic-title').text('Apply Now');

                  $.ajax({
                      url: "{{url('apply-application')}}"+"/"+ id,
                      beforeSend: function(){
                        $('.dynamic-apply').html("Loading"); 
                      },
                      success: function(data){
                        $('.dynamic-apply').html(data);
                      }
                  })
            });


       });

    </script>
@endsection
