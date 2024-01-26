@extends('dashboard.layouts.app')

@section('title')
    Messages
@endsection

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="active"><span class='fa fa-bell'></span> Messages</li>
    </ul>
@endsection