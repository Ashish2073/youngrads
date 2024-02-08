@extends('layouts/contentLayoutMaster')

@section('title', 'Campus Program')
@section('vendor-style')
    {{-- vendor css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/datatables.min.css')) }}">
@endsection

@section('breadcumb-right')
    <a href="{{ route('admin.campus-program.create') }}" class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">
        <i class="feather icon-plus"></i>
    </a>
@endsection
@if (session()->has('used_program'))
    @php $usedCampusProgram=session()->get('used_program'); @endphp

    @php
        $usedCampusProgramId = $usedCampusProgram[0];

    @endphp
@endif

@if (session()->has('used_campus'))
    @php $usedCampus=session()->get('used_campus'); @endphp

    @php
        $usedCampusUniversityId = $usedCampus[0];
        $usedCampusId = $usedCampus[1];

    @endphp
@endif

@section('content')

    <section id="basic-datatable">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        {{-- <h4 class="card-title">Students</h4> --}}
                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">

                            <div class="row application-filter align-items-center">
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="universityid">University</label>
                                        <select id="universityid" name="universityid[]" data-live-search="true" multiple
                                            class=" select form-control">
                                            @foreach ($university as $unvdata)
                                                @if (isset($unvdata->id))
                                                    <option {{-- $usedCampusUniversityId=$usedCampus[0];
                                                          $usedCampusId=$usedCampus[1]; --}}
                                                        {{ $unvdata->id == ($usedCampusUniversityId ?? '') ? 'selected' : '' }}
                                                        value="{{ $unvdata->id }}">{{ $unvdata->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="campusid">Campus</label>
                                        <select id="campusid" name="campusid[]" data-live-search="true" multiple
                                            class=" select form-control">

                                            @foreach ($campus as $camdata)
                                                @if (isset($camdata->id))
                                                    <option {{ $camdata->id == ($usedCampusId ?? '') ? 'selected' : '' }}
                                                        value="{{ $camdata->id }}">{{ $camdata->name }}</option>
                                                @endif
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <label for="programid">Program</label>
                                        <select id="programid" name="programid[]" data-live-search="true" multiple
                                            class=" select form-control">

                                            @foreach ($program as $prodata)
                                                <option
                                                    @if (isset($prodata->id)) {{ $prodata->id == ($usedCampusProgramId ?? '') ? 'selected' : '' }}
                                                     value="{{ $prodata->id }}">{{ $prodata->name }}</option> @endif
                                                    @endforeach

                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4 col-12 text-right">
                                    <button class="btn btn-primary" id="reset-filter">Reset</button>

                                </div>




                            </div>


                            <div class="table-responsive">
                                <table id="table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                        <tr>
                                        <tr>
                                            <th>University</th>
                                            <th>Campus</th>
                                            <th>Program</th>
                                            <th>Action</th>
                                        </tr>
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
    {{-- vendor files --}}
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.bootstrap4.min.js')) }}"></script>
@endsection
@section('page-script')
    <script>
        $(document).ready(function() {
            $(".select").selectpicker();

            function campusDataByUniversity(id){
             
                if (id == "universityid") {

                           $.ajax({
                          url: "{{ route('admin.university-to-campus') }}",
                          type: 'POST',
                          data: {
                          _token: "{{ csrf_token() }}",
                           universityid: $('#universityid').val(),

                          },
                         success: (data) => {
                         let univLength = data.length;
                        
                         if(univLength>0){
                          var univHTML =`<option value="${data[0].id}">${data[0].name}</option>`;
                          for (let i = 1; i < univLength; i++) {
                       
                          var univHTML = univHTML+`<option value="${data[i].id}">${data[i].name}</option>`
                       

                          }
                        }else{
                            var univHTML=`<option value=""  disabled>No Data Found</option>`;
                          
                        }
                        

                         $('#campusid').html(univHTML);
                         $("#campusid").selectpicker('refresh');
                         

                      }
                    })

                }

             }


            




            $(".application-filter").find("select").on("change", function(e) {
                campusDataByUniversity(e.target.id);

                dataTable.draw();
            });


 


            @if (session('success'))
                toast('success', "{{ session('success') }}")
            @endif

            @if (session('error'))
                toast('error', "{{ session('error') }}")
            @endif
            // Datatable
            dataTable = $("#table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 100,
                "fixedHeader": true,
                ajax: {
                    url: "{{ route('admin.campus-programs') }}",
                    data: function(d) {
                        d.campusid = $("#campusid").val();
                        d.universityid = $('#universityid').val();
                        d.programid = $('#programid').val();
                    }
                },
                columns: [{
                        name: 'university',
                        data: 'university'

                    },
                    {
                        name: 'campus',
                        data: 'campus'
                    },
                    {
                        name: 'program',
                        data: 'program'
                    },
                    {
                        data: 'action'

                    }

                ],

                'createdRow': function(row, data, dataIndex) {
                    // $(row).addClass('action-row');
                    // let id = data['id'];
                    // let editUrl = route('admin.user.edit', id).url();
                    // $(row).attr('data-url', editUrl);
                    // $(row).attr('data-target', "#dynamic-modal");
                    // $(row).attr('data-toggle', "modal");
                },
                initComplete: function(res, json) {

                }
            });




        });


        $('#reset-filter').on('click', function() {
            $(".select").selectpicker('deselectAll');
            $(".select").val("");
            $(".select").selectpicker('refresh');
            dataTable.draw();

                $.ajax({
                          url: "{{ route('admin.reset-filter') }}",
                          type: 'POST',
                          data: {
                          _token: "{{ csrf_token() }}",
                           

                          },
                         success: (data) => {

                            
                            let univData = (data.university);
                            let univLength = univData.length;
                        
                         if(univLength>0){
                          var univHTML =`<option value="${univData[0].id}">${ univData[0].name}</option>`;
                          for (let i = 1; i < univLength; i++) {
                       
                          var univHTML = univHTML+`<option value="${univData[i].id}">${univData[i].name}</option>`
                        

                          }  
                          
                    

                         $('#universityid').html(univHTML);
                         $("#universityid").selectpicker('refresh');
                         
                        }

                        let campusData = (data.campus);
                            let campusLength = campusData.length;
                        
                         if(campusLength>0){
                          var campusDataHTML =`<option value="${campusData[0].id}">${ campusData[0].name}</option>`;
                          for (let m = 1; m < campusLength; m++) {
                       
                          var campusDataHTML = campusDataHTML+`<option value="${campusData[m].id}">${campusData[m].name}</option>`
                        

                          }  
                          
                        

                         $('#campusid').html(campusDataHTML);
                         $("#campusid").selectpicker('refresh');
                         
                        }


                       





                      }
                    })
                

        });
    </script>
@endsection
