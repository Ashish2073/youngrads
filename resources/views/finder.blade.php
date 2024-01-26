@extends('layouts.contentLayoutMaster')

@section('title', 'Course Finder')

@section('page-style')
    <style>
        body {
            /* background: white!important; */
        }

    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <table class='table' id='finder'>
            <thead>
                <th>ID</th>
                <th>Duration</th>
                <th>Country</th>
                {{-- <th>Application Fee</th> --}}
                @foreach(config('fee_types') as $fee_type)
                    <th>{{ $fee_type->name }}</th>
                @endforeach
            </thead>
        </table>
    </div>
@endsection

@section('page-script')
    <script>
        
        $(document).ready(function() {
            $("#finder").DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 100,
                ajax: {
                    url: route('finder').url(),

                    beforeSend: function() {},
                    complete: function() {}
                },
                columns: [
                    {
                        data: 'id',
                        name: 'campus_programs.id',
                    },
                    {
                        data: 'duration',
                        name: 'programs.duration'
                    },
                    {
                        data: 'country',
                        name: 'countries.name'
                    },
                    @foreach (config('fee_types') as $fee_type)
                      {
                        data:"{{ \Str::slug($fee_type->name, "_") }}",
                        name:"{{ \Str::slug($fee_type->name, "_") }}",
                      },
                    @endforeach
                    // {
                    //     data: 'application_fee',
                    //     name: 'fees.price'
                    // }
                ]
            });
        })

    </script>
@endsection
