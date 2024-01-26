@extends('layouts/contentLayoutMaster')

@section('title', 'Pages')
@section('page-style')
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.bubble.css')) }}">
@endsection
@section('breadcumb-right')
    <a 
    href="{{ route('admin.page.create') }}" class="btn-icon btn btn-primary btn-round btn-sm dropdown-toggle"
    >
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
                                <table id="page-table" class="table table-hover w-100 zero-configuration">
                                    <thead>

                                    <tr>
                                        <th>Title</th>
                                        <th>URL</th>
                                        <th>Action</th>
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
        $(document).ready(function () {
            @if(session('success'))
            toast('success', "{{session('success')}}")
            @endif

            @if(session('error'))
            toast('error', "{{session('error')}}")
            @endif
            // Datatable
            dataTable = $("#page-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 100,
                "fixedHeader": true,
                ajax: {
                    url: route('admin.pages').url(),
                    data: function (d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                    data: 'title',
                    name: 'title'
                },
                    {
                        data: 'url',
                        name: 'url'
                    },
                    {
                        data: 'action'
                    }

                ],

                'createdRow': function (row, data, dataIndex) {
                    // $(row).addClass('action-row');
                    // let id = data['id'];
                    // let editUrl = route('admin.user.edit', id).url();
                    // $(row).attr('data-url', editUrl);
                    // $(row).attr('data-target', "#dynamic-modal");
                    // $(row).attr('data-toggle', "modal");
                },
                initComplete: function (res, json) {

                }
            });


        });

    </script>
@endsection
