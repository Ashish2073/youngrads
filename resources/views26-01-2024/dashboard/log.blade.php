@extends('dashboard.layouts.app')
@section('head_script')
    <link rel="stylesheet" href="{{ asset('dashboard/js/plugins/json-viewer/jquery.json-viewer.css') }}">

    <style>
        .properties {
            /* max-width:100px; */
        }
    </style>
@endsection
@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="active">Users</li>
    </ul>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class='fa fa-bar-chart-o'></i> User Activities
                    </h3>
                </div>
                <div class="panel-body">
                    <table class="table table-sm" id="log-table">
                        <thead>
                        <!-- <th>ID</th> -->
                        <th>Description</th>
                        <th>Type</th>
                        <th>Causer</th>
                        <th>IP</th>
                        <th>Date</th>
                        <th>Properties</th>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <!-- <th>ID</th> -->
                        <th>Description</th>
                        <th>Type</th>
                        <th>Causer</th>
                        <th>IP</th>
                        <th>Date</th>
                        <th>Properties</th>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('foot_script')
    <script src="{{ asset('dashboard/js/plugins/json-viewer/jquery.json-viewer.js') }}"></script>

    <script>
        $(document).ready(function () {
            dataTable = $("#log-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                ajax: {
                    url: window.Laravel.appUrl + '/admin/log',
                    data: function (d) {
                        // d.account_type = $('select[name="account_type"]').val();
                    },
                    complete: function () {
                        $(".json-viewer").each(function (index, value) {
                            let val = $(this).html();
                            val = JSON.parse(val);
                            $(this).jsonViewer(val, {
                                collapsed: true
                            });
                        });
                    }
                },
                'createdRow': function (row, data, dataIndex) {
                    // $(row).addClass('action-row');
                    // $(row).attr('data-url', s + "/blog/" + data[7] + "/edit");
                    // $(row).attr('data-target', "#dynamic-modal");
                    // $(row).attr('data-toggle', "modal");
                },
                "order": [[4, "desc"]],
                columns: [
                    // { data: 'subject_id', name: 'subject_id' },
                    {data: 'description', name: 'description'},
                    {data: 'subject_type', name: 'subject_type'},
                    {data: 'email', name: 'users.email',},
                    {data: 'ip_address', name: 'properties'},
                    {data: 'a_c', name: 'activity_log.created_at'},
                    {data: 'properties', name: 'properties'},
                ],
                initComplete: function (res, json) {

                    // $(".create").on('click', function(e) {
                    //     let url = $(this).data('url');
                    //     $(".dynamic-title").html('Create Company');
                    //     getContent({
                    //         beforeSend: modalLoader,
                    //         "url": url,
                    //         success: function(data) {
                    //             $(".dynamic-body").html(data);
                    //             runScript();
                    //         }
                    //     });
                    // });
                }
            });
        });
    </script>
@endsection
