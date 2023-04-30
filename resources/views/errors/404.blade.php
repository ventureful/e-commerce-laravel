@extends('layouts.app')
@section('page-title', __('front.404'))

{{--@section('header', '')--}}
@section('content')
    <div class="utility-page-wrap background">
        <div class="utility-page-content w-form">
            <h2 class="display-heading-2 not-found">Page Not Found</h2>
            <div>The page you are looking for doesn&#x27;t exist or has been moved.</div>
            <div class="button-container">
                <a href="/" class="button">Go back to Homepage</a>
            </div>
        </div>
    </div>
@endsection
