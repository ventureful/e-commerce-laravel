@extends('layouts.admin.master')
@section('page-title', __a('dashboard'))

@section('breadcrumbs')
    {!! Breadcrumbs::render(ADMIN_PREFIX) !!}
@endsection

@section('content')
    <h2>Welcome!</h2>
@endsection
