@extends('dashboard.layouts.app')

@section('title')
    Notifications
@endsection

@section('content')
    <ul class="breadcrumb">
        <li><a href="{{ route('admin.home') }}">Dashboard</a></li>
        <li class="active"><span class='fa fa-bell'></span> Notifications</li>
    </ul>
@endsection