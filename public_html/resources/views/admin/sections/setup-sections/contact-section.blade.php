@php
    $default_lang_code = language_const()::NOT_REMOVABLE;
    $system_default_lang = get_default_language_code();
    $languages_for_js_use = $languages->toJson();
@endphp

@extends('admin.layouts.master')

@push('css')
    <link rel="stylesheet" href="{{ asset('public/backend/css/fontawesome-iconpicker.min.css') }}">
    <style>
        .fileholder {
            min-height: 374px !important;
        }

        .fileholder-files-view-wrp.accept-single-file .fileholder-single-file-view,.fileholder-files-view-wrp.fileholder-perview-single .fileholder-single-file-view{
            height: 330px !important;
        }
    </style>
@endpush

@section('page-title')
    @include('admin.components.page-title',['title' => __($page_title)])
@endsection

@section('breadcrumb')
    @include('admin.components.breadcrumb',['breadcrumbs' => [
        [
            'name'  => __("Dashboard"),
            'url'   => setRoute("admin.dashboard"),
        ]
    ], 'active' => __("Setup Section")])
@endsection

@section('content')
    <div class="custom-card">
        <div class="card-header">
            <h6 class="title">{{ __($page_title) }}</h6>
        </div>
        <div class="card-body">
            <form class="card-form" action="{{ setRoute('admin.setup.sections.section.update',$slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row justify-content-center mb-10-none">


                    <div class="col-xl-12 col-lg-12">
                        <div class="product-tab">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link @if (get_default_language_code() == language_const()::NOT_REMOVABLE) active @endif" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab" aria-controls="english" aria-selected="false">English</button>
                                    @foreach ($languages as $item)
                                        <button class="nav-link @if (get_default_language_code() == $item->code) active @endif" id="{{$item->name}}-tab" data-bs-toggle="tab" data-bs-target="#{{$item->name}}" type="button" role="tab" aria-controls="{{ $item->name }}" aria-selected="true">{{ $item->name }}</button>
                                    @endforeach
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane @if (get_default_language_code() == language_const()::NOT_REMOVABLE) fade show active @endif" id="english" role="tabpanel" aria-labelledby="english-tab">
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                @include('admin.components.form.input',[
                                                    'label'     => __("Title")."*",
                                                    'name'      => $default_lang_code . "_title",
                                                    'value'     => old($default_lang_code . "_title",$data->value->language->$default_lang_code->title ?? "")
                                                ])
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                @include('admin.components.form.input',[
                                                    'label'     => __("Heading")."*",
                                                    'name'      => $default_lang_code . "_heading",
                                                    'value'     => old($default_lang_code . "_heading",$data->value->language->$default_lang_code->heading ?? "")
                                                ])
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                @include('admin.components.form.input',[
                                                    'label'     => __("Location")."*",
                                                    'name'      => $default_lang_code . "_location",
                                                    'value'     => old($default_lang_code . "_location",$data->value->language->$default_lang_code->location ?? "")
                                                ])
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                @include('admin.components.form.input',[
                                                    'label'     => __("Phone Number")."*",
                                                    'name'      => $default_lang_code . "_phone",
                                                    'value'     => old($default_lang_code . "_phone",$data->value->language->$default_lang_code->phone ?? "")
                                                ])
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                @include('admin.components.form.input',[
                                                    'label'     => __("Office Hours"),
                                                    'name'      => $default_lang_code . "_office_hours",
                                                    'value'     => old($default_lang_code . "_office_hours",$data->value->language->$default_lang_code->office_hours ?? "")
                                                ])
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6">
                                            <div class="form-group">
                                                @include('admin.components.form.input',[
                                                    'label'     => __("Email Address")."*",
                                                    'name'      => $default_lang_code . "_email",
                                                    'value'     => old($default_lang_code . "_email",$data->value->language->$default_lang_code->email ?? "")
                                                ])
                                            </div>
                                        </div>
                                    </div>



                                </div>

                                @foreach ($languages as $item)
                                    @php
                                        $lang_code = $item->code;
                                    @endphp
                                    <div class="tab-pane @if (get_default_language_code() == $item->code) fade show active @endif" id="{{ $item->name }}" role="tabpanel" aria-labelledby="english-tab">
                                        <div class="row">
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="form-group">
                                                    @include('admin.components.form.input',[
                                                        'label'     => __("Title")."*",
                                                        'name'      => $lang_code . "_title",
                                                        'value'     => old($lang_code . "_title",$data->value->language->$lang_code->title ?? "")
                                                    ])
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="form-group">
                                                    @include('admin.components.form.input',[
                                                        'label'     => __("Heading")."*",
                                                        'name'      => $lang_code . "_heading",
                                                        'value'     => old($lang_code . "_heading",$data->value->language->$lang_code->heading ?? "")
                                                    ])
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="form-group">
                                                    @include('admin.components.form.input',[
                                                        'label'     => __("Location")."*",
                                                        'name'      => $lang_code . "_location",
                                                        'value'     => old($lang_code . "_location",$data->value->language->$lang_code->location ?? "")
                                                    ])
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="form-group">
                                                    @include('admin.components.form.input',[
                                                        'label'     => __("Phone Number")."*",
                                                        'name'      => $lang_code . "_phone",
                                                        'value'     => old($lang_code . "_phone",$data->value->language->$lang_code->phone ?? "")
                                                    ])
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="form-group">
                                                    @include('admin.components.form.input',[
                                                        'label'     => __("Office Hours")."*",
                                                        'name'      => $lang_code . "_office_hours",
                                                        'value'     => old($lang_code . "_office_hours",$data->value->language->$lang_code->office_hours ?? "")
                                                    ])
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-lg-6">
                                                <div class="form-group">
                                                    @include('admin.components.form.input',[
                                                        'label'     => __("Email Address")."*",
                                                        'name'      => $lang_code . "_email",
                                                        'value'     => old($lang_code . "_email",$data->value->language->$lang_code->email ?? "")
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-12 col-lg-12 form-group">
                        @include('admin.components.button.form-btn',[
                            'class'         => "w-100 btn-loading",
                            'text'          => __("submit"),
                            'permission'    => "admin.setup.sections.section.update"
                        ])
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection
