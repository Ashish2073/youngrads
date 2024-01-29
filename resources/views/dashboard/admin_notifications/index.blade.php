@extends('layouts/contentLayoutMaster')

@section('title', 'Notifcations')

@section('page-style')
    <link rel="stylesheet" href="{{ asset(mix('css/pages/data-list-view.css')) }}">
    <style>
        .modal-dialog-aside {
            width: 44% !important;
        }

        .status {
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

    <section id="basic-datatable">
        <div class="row w-50 m-auto">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">

                        <h4 class="card-title">Notifications</h4>

                    </div>
                    <div class="card-content">
                        <div class="card-body card-dashboard">

                            <div class="table-responsive">
                                <table id="admin-application-table"
                                    class="table table-hover table-bordered w-100 zero-configuration">
                                    <thead class="d-none">
                                        <tr>
                                            <th>Notification</th>
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

            var dataTable;
            dataTable = $("#admin-application-table").DataTable({
                "processing": true,
                "serverSide": true,
                //"pageLength": 100,
                ajax: {
                    url: "{{ route('admin.notifications') }}",
                    data: function(d) {

                    }
                },
                dom: '<"top"<"actions action-btns"><"action-filters">><"clear">rt<"bottom"<"actions">p>',
                columns: [{
                    name: 'notifications',
                    data: 'notifications'
                }, ],
                responsive: false,

                drawCallback: function(setting, data) {
                    // let setTime;
                    // clearTimeout(setTime);
                    // setTime = setTimeout(()=>{
                    //     dataTable.draw('page');
                    //   },10000);

                },
                bInfo: false,
                pageLength: 100,
                initComplete: function(settings, json) {
                    $(".dt-buttons .btn").removeClass("btn-secondary");
                    $(".table-img").each(function() {
                        $(this).parent().addClass('product-img');
                    });

                    // setTimeout(()=>{
                    //   dataTable.ajax.reload(null,false);
                    // },5000);

                }
            });

            $(document).on('click', '.read', function() {
                id = $(this).data('id');

                that = $(this);
                $.ajax({
                    url: "{{ route('admin.mark-read') }}",
                    type: 'post',
                    beforeSend: function() {
                        that.attr('disabled', true).html(
                            "<i class='text-danger fa fa-spinner fa-spin'></i> ");
                    },
                    data: {
                        _token: "{{ csrf_token() }}",
                        id: id
                    },
                    success: function(data) {
                        dataTable.draw('page');
                        if (data != 0) {
                            $('#notification').text(data);
                        } else {
                            $('#notification').text('');
                        }
                    }
                });
            });
        });
    </script>
@endsection
