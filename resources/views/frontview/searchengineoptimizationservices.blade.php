@extends('layouts.front')
@section('content')
@section('opTag')
    @section('title', $seo->metaTitle ?? 'SEO')
     <meta name="description" content="{{ $seo->metaDescription }}" />
     <meta name="keywords" content="{{ $seo->metaKeyword }}" />
    {!! $seo->head !!}
    {!! $seo->body !!}
    @endsection




<!-- Page Header Start -->
<div class="page-header light-bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Page Header Box Start -->
                <div class="page-header-box">
                    <h1 class="text-anime-style-3">SEO Services in India & UAE </h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">home</a></li>
                            <li class="breadcrumb-item"><a href="#">services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Search Engine Optimization (SEO)</li>
                        </ol>
                    </nav>
                </div>
                <!-- Page Header Box End -->
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Single Service Page Start -->
<div class="page-service-single">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="service-single-content">
                    <!-- Service Featured Image Start -->
                    <!-- <div class="service-featured-image">
                        <figure class="image-anime reveal">
                            <img src="{{asset('assets/frontassets/images/service-img-1.jpg')}}" alt="">
                        </figure>
                    </div> -->
                    <!-- Service Featured Image End -->

                    <!-- Service Entry Content Start -->
                    <div class="service-entry">
                        <h2 class="text-anime-style-3">SEO Services in India & UAE </h2>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <!-- Customer Benefits Image Start -->
                                <div class="customer-benefit-image">
                                    <figure class="image-anime reveal">
                                        <img src="{{asset('assets/frontassets/images/Affordable-SEo.webp')}}" alt="">
                                    </figure>
                                </div>
                                <!-- Customer Benefits Image End -->
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-anime-style-3">From Zero to Hero: Transforming Your Business with our Expert SEO Services</h5>
                                <div class="customer-Benefit-content wow fadeInUp">
                                    <br>
                                    <p>Apollo Infotech is a leading SEO services provider in UAE & India.  We are a team of experienced digital marketing professionals dedicated to helping businesses to grow their online presence through effective search engine optimization strategies.

At Apollo Infotech, we understand that having a strong online presence is crucial to the success of any business. That’s why we offer comprehensive SEO services that include website audits, keyword research, on-page optimization, off-page optimization, link building and content creation. We also provide regular reports and analytics to help our clients track their progress and make data- driven decisions.

Whether you’re a small business looking to increase your local visibility or a large corporation looking to dominate your industry, we have the expertise and experience to help you succeed. Contact us today to know more about how our SEO services in UAE & India can help take your business to the next level.</p>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                
                                <div class="col-md-6">
                                    <h3 class="text-anime-style-3">Our Search Engine Optimization (SEO) Services  in India & UAE </h3>
                                    <div class="customer-Benefit-content wow fadeInUp">
                                        <br>
                                        <ul>
                                            <li> Website Audits</li>
                                            <li>
                                            Keyword Research</li>
                                            <li>
                                            On- Page Optimization</li>
                                            <li>
                                            Off-Page Optimization</li>

<li>
Content Creation</li><li>

Reporting and Analytics</li>
                                         
                                           
                                           
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Customer Benefits Image Start -->
                                    <div class="customer-benefit-image">
                                        <figure class="image-anime reveal">
                                            <img src="{{asset('assets/frontassets/images/Seo-audit.webp')}}" alt="">
                                        </figure>
                                    </div>
                                    <!-- Customer Benefits Image End -->
                                </div>
                            </div>
</div>
                            <!-- Service Benefits Start -->



                        </div>
                        <!-- Service Entry Content End -->
                    </div>
                </div>

                <div class="col-lg-4">
                    <!-- Service Sidebar Start -->
                    <div class="service-sidebar">
                        <!-- Service Catagery List Start -->
                        <div class="service-catagery-list wow fadeInUp">
                            <h3>our services</h3>
                            <ul>
                                <li><a href="{{route('FrontWebsitedevelopmentservices')}}">Website Development</a></li>
                                <li><a href="{{route('FrontMobileapplicationdevelopmentservices')}}">Mobile Application Development</a></li>
                                <li><a href="{{route('FrontSearchengineoptimizationservices')}}">Search Engine Optimization (SEO)</a></li>
                                <li><a href="{{route('FrontSocialmediamarketingservices')}}">Social Media Marketing (SMO)</a></li>
                                <li><a href="{{route('FrontGraphicdesignservices')}}">Graphic Design Services </a></li>
                                

                            </ul>
                        </div>
                        <!-- Service Catagery List End -->

                        <!-- Sidebar Cta Box Start -->
                        <div class="sidebar-cta-box wow fadeInUp" data-wow-delay="0.5s">
                            <!-- Sidebar Cta Image Start -->
                            <div class="sidebar-cta-image">
                                <figure class="image-anime">
                                    <img src="{{asset('assets/frontassets/images/service-cta-bg.jpg')}}" alt="">
                                </figure>
                            </div>
                            <!-- Sidebar Cta Image End -->

                            <!-- Sidebar Cta Content Start -->
                            <div class="sidebar-cta-content">
                                <h3>Need Help? We Are Here To Help You</h3>
                                <a href="#" class="btn-default btn-highlighted">contact us</a>
                            </div>
                            <!-- Sidebar Cta Content End -->
                        </div>
                        <!-- Sidebar Cta Box End -->
                    </div>
                    <!-- Service Sidebar End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Single Service Page End -->




    @endsection