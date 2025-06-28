@extends('frontend.layouts.master')

@php
    $defualt = get_default_language_code()??'en';
    $default_lng = App\Constants\LanguageConst::NOT_REMOVABLE;
    $gallety_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::GALLERY_SCTION);
    $gallery = App\Models\Admin\SiteSections::getData($gallety_slug)->first();
@endphp


@section('content')
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@include('frontend.partials.breadcrumb')
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Gallery
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<div class="gallery-section two ptb-120">
    <div class="container">
        <div class="row justify-content-center mb-30-none">
            @if(isset($gallery->value->items))
            @foreach($gallery->value->items ?? [] as $key => $item)
            <div class="col-xl-4 col-lg-4 mb-30">
                <div class="gallery-item">
                    <div class="thumb">
                        <a class="img-popup" data-rel="lightcase:myCollection" href="{{ get_image(@$item->image,'site-section') }}">
                            <img src="{{ get_image(@$item->image,'site-section') }}" alt="gallery">
                            <div class="gallery-thumb-overlay">
                               <div class="text-center">
                                <div class="icon">
                                    <i class="las la-plus-circle"></i>
                                </div>
                                <h2>{{ @$item->language->$defualt->title ?? @$item->language->$default_lng->title }}</h2>
                                <span>#{{ @$item->language->$defualt->tag ?? @$item->language->$default_lng->tag }}</span>
                               </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
            @endif


        </div>
    </div>
</div>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Gallery
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@endsection

