@extends('layouts/contentLayoutMaster')

@section('title', 'Contact Entries')

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
                                <table id="contact-table" class="table table-hover w-100 zero-configuration">
                                    <thead>
                                    <tr>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Message</th>
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
        var dataTable;
        $(document).ready(function () {

            //datatabls
            dataTable = $("#contact-table").DataTable({
                "processing": true,
                "serverSide": true,
                "bInfo": true,
                "pageLength": 50,
                "fixedHeader": true,
                ajax: {
                    url: route('contact-entries').url(),
                    data: function (d) {
                        // d.quiz_id = $('select[name="quiz_id"]').val();
                    }
                },
                columns: [{
                    data: 'name',
                    name: 'name'
                },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'message',
                        name: 'message'
                    },

                ],

                'createdRow': function (row, data, dataIndex) {
                    $(row).addClass('action-row');
                    let id = data['id'];
                    let editUrl = route('contact-show', id).url();
                    $(row).attr('data-url', editUrl);
                    $(row).attr('data-target', "#dynamic-modal");
                    $(row).attr('data-toggle', "modal");
                },
                initComplete: function (res, json) {

                }
            });

            $('body').on('click', '.action-row', function (e) {

                $('.dynamic-title').text('Show Message');
                getContent({
                    url: $(this).data('url'),
                    success: function (data) {

                        $('.dynamic-body').html(data);

                    }
                });
            });

        });


    </script>
@endsection
