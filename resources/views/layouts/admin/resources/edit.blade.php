{{-- Extends Layout --}}
@extends('layouts.admin.master')

<?php
$_pageTitle = (isset($addVarsForView['_pageTitle']) && !empty($addVarsForView['_pageTitle']) ? $addVarsForView['_pageTitle'] : ucwords($resourceTitle));
$_pageSubtitle = (isset($addVarsForView['_pageSubtitle']) && !empty($addVarsForView['_pageSubtitle']) ? $addVarsForView['_pageSubtitle'] : __('pages.edit'));
$_formFiles = isset($formFiles) ? $formFiles : false;
$_listLink = route($resourceRoutesAlias . '.index');
$_createLink = route($resourceRoutesAlias . '.create');

$_updateLink = route($resourceRoutesAlias . '.update', $record->id);

?>

{{-- Breadcrumbs --}}
@section('breadcrumbs')
    {!! Breadcrumbs::render($resourceRoutesAlias.'.edit', $record->id, __('messages.edit_data', ['resource' => $record->getRecordTitle()])) !!}
@endsection

{{-- Page Title --}}
@section('page-title', $_pageTitle)

{{-- Page Subtitle --}}
@section('page-subtitle', $_pageSubtitle)

{{-- Header Extras to be Included --}}
@section('head-extras')

@endsection

@section('content')

    <div class="row">
        <div class="col-xs-12">

            <!-- Edit Form -->
            <div class="box box-info" id="wrap-edit-box">

                <form class="form" role="form" method="POST" action="{{ $_updateLink }}"
                        {{ $_formFiles === true ? 'enctype=multipart/form-data' : ''}}>
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="box-header with-border">
                        <h3 class="box-title">{{ __('messages.edit_data', ['resource' => $record->getRecordTitle()]) }}</h3>

                        <div class="box-tools">
                            <a href="{{ $_listLink }}" class="btn btn-sm btn-default margin-r-5 margin-l-5">
                                <i class="fa fa-search"></i> <span>{{__('pages.list')}}</span>
                            </a>
                            <button class="btn btn-sm btn-warning margin-r-5 margin-l-5">
                                <i class="fa fa-save"></i> <span>{{__('pages.save')}}</span>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->

                    <div class="box-body">
                        @yield('resource_form')
                    </div>
                    <!-- /.box-body -->

                    <div class="box-footer clearfix">
                        <div class="row">
                            <div class="col-md-8">
                                @if($record->creator_id && $record->creator)
                                    <p>
                                        Tạo bởi: {{$record->creator->name}}
                                    </p>
                                @endif
                                @if($record->updater_id && $record->updater)
                                    <p>
                                        Sửa lần cuối bởi: {{$record->updater->name}}
                                    </p>
                                @endif
                            </div>
                            <div class="col-md-4">
                                <div class="text-right margin-b-5 margin-t-5">
                                    <a href="{{ $_listLink }}" class="btn btn-default" style="margin-right: 10px">
                                        <i class="fa fa-ban"></i> <span>{{__('pages.cancel')}}</span>
                                    </a>
                                    <button class="btn btn-warning">
                                        <i class="fa fa-save"></i> <span>{{__('pages.update')}}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-footer -->
                </form>
            </div>
            <!-- /.box -->
            <!-- /End Edit Form -->
        </div>
    </div>
    <!-- /.row -->
@endsection

{{-- Footer Extras to be Included --}}
@section('footer-extras')

@endsection
