@extends('front.layouts.main')

@push('head')
    <div class="container-fluid bg-main py-5 bg-header" style="margin-bottom: 90px;">
        <div class="row py-5">
            <div class="col-12 pt-lg-5 mt-lg-5 text-center">
                <h1 class="display-4 text-white animated zoomIn">@yield('code') @yield('title')</h1>
                <a href="{{ route('web.index') }}" class="h5 text-white">Home</a>
                <i class="far fa-circle text-white px-2"></i>
                <a href="javscript:void(0)" class="h5 text-main">@yield('title')</a>
            </div>
        </div>
    </div>
@endpush

@section('content')
    <!-- About Start -->
    <div class="container-fluid py-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5" id="aboutSection">
                <div class="col-12">
                    <div class="section-title position-relative pb-3 mb-5">
                        <h5 class="fw-bold text-main text-uppercase">@yield('message').</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->
@endsection
