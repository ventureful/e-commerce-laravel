@extends('layouts.app')
@section('page-title', __('front.500'))

{{--@section('header', '')--}}
@section('content')
    <div class="utility-page-wrap background">
        <div class="utility-page-content w-form">
            <h2 class="display-heading-2 not-found">Internal server error</h2>
            <div>Please contact the administrator.</div>
            <div class="button-container">
                <a href="/" class="button">Go back to Homepage</a>
            </div>
        </div>
    </div>
@endsection
