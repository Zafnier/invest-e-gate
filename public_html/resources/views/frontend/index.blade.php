@extends('frontend.layouts.master')

@php
    $defualt = get_default_language_code()??'en';
    // $default_lng = 'en';
    $default_lng = App\Constants\LanguageConst::NOT_REMOVABLE;
    $about_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::ABOUT_SECTION);
    $about = App\Models\Admin\SiteSections::getData( $about_slug)->first();
    $download_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::DOWNLOAD_SECTION);
    $download = App\Models\Admin\SiteSections::getData( $download_slug)->first();
    $video_slug = Illuminate\Support\Str::slug(App\Constants\SiteSectionConst::VIDEO_SECTION);
    $video = App\Models\Admin\SiteSections::getData( $video_slug)->first();
    $app_settings = App\Models\Admin\AppSettings::first();

@endphp
@section('content')
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="banner-section">
    <div class="banner-bg">
        <img src="{{ get_image(@$homeBanner->value->images->banner_image,'site-section') }}" alt="banner">
    </div>
    <div class="banner-shape">
        <img src="{{ asset('public/frontend/') }}/images/map.jpg" alt="banner-shape">
    </div>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-7 col-lg-7 col-md-12">
                <div class="banner-content">
                    <h1 class="title">{{__( @$homeBanner->value->language->$defualt->heading ?? @$homeBanner->value->language->$default_lng->heading ) }}</h1>
                    <p>{{ __(@$homeBanner->value->language->$defualt->sub_heading ?? @$homeBanner->value->language->$default_lng->sub_heading) }}</p>
                    <div class="banner-btn">
                        <a href="{{ url($homeBanner->value->language->$defualt->button_link ?? '') }}" class="btn--base"><i class="las la-heart"></i> {{ __(@$homeBanner->value->language->$defualt->button_name) }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Banner
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start About
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="about-section ptb-120">
    <div class="about-shape">
        <img src="{{ asset('public/frontend/') }}/images/about/right-shape.png" alt="shape">
    </div>
    <div class="container">
        <div class="row justify-content-center align-items-center mb-30-none">
            <div class="col-xl-6 col-lg-6 mb-30">
                <div class="about-thumb">
                    <img src="{{ get_image(@$about->value->images->first_section_image,'site-section') }}" alt="about">
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 mb-30">
                <div class="about-content">
                    <div class="sub-title">{{ __(@$about->value->language->$defualt->fitst_section_title ?? @$about->value->language->$default_lng->fitst_section_title) }}</div>
                    <h2 class="title">{{ __(@$about->value->language->$defualt->fitst_section_heading ?? @$about->value->language->$default_lng->fitst_section_heading) }}</h2>
                    <p>{{ __(@$about->value->language->$defualt->first_section_sub_heading ??@$about->value->language->$default_lng->first_section_sub_heading ) }}</p>
                    <div class="about-btn">
                        <a href="{{ $about->value->language->$default_lng->first_section_button_link ?? '' }}" class="btn--base">{{ $about->value->language->$defualt->first_section_button_name ?? $about->value->language->$default_lng->first_section_button_name}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End About
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Campaign
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@include('frontend.partials.campaigns', ['campaigns' => $campaigns])
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Campaign
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Gallery
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="gallery-section pt-120">
    <div class="container-fluid p-0">
        <div class="row g-0">
            @if(isset($gallery->value->items))
                @php
                    $count = 0;
                @endphp
                @foreach($gallery->value->items ?? [] as $key => $item)
                <div class="col-xl-4 col-lg-4 col-md-6">
                    <div class="gallery-item">
                        <div class="thumb">
                            <img src="{{ get_image(@$item->image,'site-section') }}" alt="gallery">
                            <div class="gallery-shape">
                                <img src="{{ asset('public/frontend/') }}/images/gallery/gallery-shape.png" alt="shape">
                            </div>
                            <div class="content">
                                <h2 class="title">{{ @$item->language->$defualt->title ?? @$item->language->$default_lng->title }}</h2>
                                <div class="gallery-btn">
                                    <a href="javascript:void()">#{{ @$item->language->$defualt->tag ?? @$item->language->$default_lng->tag }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $count++;
                    if ($count == 3) {
                        break;
                    }
                @endphp
                @endforeach
            @endif
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Gallery
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->


<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Testimonial
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@include('frontend.partials.testimonial')
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Testimonial
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Blog
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
<section class="blog-section pt-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-12 text-center">
                <div class="section-header">
                    @php
                        $header = explode('|', @$event_head_data->value->language->$defualt->heading ?? @$event_head_data->value->language->$default_lng->heading);
                    @endphp
                    <span class="section-sub-title">{{ $event_head_data->value->language->$defualt->title ?? 'Events' }}</span>
                    <h2 class="section-title">@isset($header[0]) {{ $header[0] }} @endisset <span>@isset($header[1]) {{ $header[1] }} @endisset</span></h2>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mb-30-none">
            @foreach ($recent_events as $item)
            <div class="col-xl-6 col-lg-6 col-md-6 mb-30">
                <div class="blog-item">
                    <div class="blog-thumb">
                        <a href="{{ setRoute('events.details',[$item->id, $item->slug])}}"><img src="{{ get_image($item->image,'events') }}" alt="{{ @$item->title->language->$defualt->title }}"></a>
                    </div>
                    <div class="blog-content">
                        <div class="blog-date">
                            <h6 class="title">{{ dateFormat('d M',$item->created_at) }}</h6>
                            <span class="sub-title">{{ dateFormat('Y',$item->created_at) }}</span>
                        </div>
                        <span class="category">{{ $item->category->name }}</span>
                        <h3 class="title"><a href="{{ setRoute('events.details',[$item->id, $item->slug])}}">{{ @$item->title->language->$defualt->title ?? @$item->title->language->$default_lng->title }}</a></h3>
                        <p>{!! Str::limit(@$item->details->language->$defualt->details ?? @$item->details->language->$default_lng->details, 150); !!}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Blog
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    Start Brand
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
@include('frontend.partials.brand')
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    End Brand
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->



@endsection


@push("script")

@endpush
