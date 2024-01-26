@extends('layouts/contentLayoutMaster')

@section('title', 'Campus Program')

@section('breadcumb-right')
    <a href="{{ route('admin.campus-program.create') }}" class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle">
        <i class="feather icon-plus"></i>
    </a>
@endsection

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

@section('page-script')
    <script>
        $(document).ready(function() {
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
                    data: function(d) {}
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
    </script>
@endsection
