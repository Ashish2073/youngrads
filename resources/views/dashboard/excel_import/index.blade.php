@extends('dashboard.layouts.app')
@section('title')
    Imports
@endsection

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="active"><span class='fa fa-plus'></span> Imorting Data</li>
    </ul>


    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h3 class="text-center text-dark">Importing Data</h3>

            <form id="page-create-form" action="{{ route('admin.storeform') }}" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="file" name="excel" class="form-control @error('excel') {{ errCls() }} @enderror"
                           value="{{ old('excel') }}">
                </div>
                @error('excel')
                <p class="text-danger">{{ $message }}</p>
                @enderror
                <div class="form-group">
                    <button type="submit" id="submit-btn" class="btn btn-primary">Add Page</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('foot_script')
    <script>

    </script>
@endsection
