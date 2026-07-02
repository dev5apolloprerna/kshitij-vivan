@extends('layouts.front')
@section('content')
@section('opTag')
    @section('title', $seo->metaTitle ?? 'Website Development')
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
                    <h1 class="text-anime-style-3">Web Development Services in India & UAE </h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">home</a></li>
                            <li class="breadcrumb-item"><a href="#">services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Web Development Services</li>
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
                        <h2 class="text-anime-style-3">Web Development Services in India & UAE </h2>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <!-- Customer Benefits Image Start -->
                                <div class="customer-benefit-image">
                                    <figure class="image-anime reveal">
                                        <img src="{{asset('assets/frontassets/images/website-development.webp')}}" alt="">
                                    </figure>
                                </div>
                                <!-- Customer Benefits Image End -->
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-anime-style-3">Get a ultrafast, secured, user engaging website that yields you ROI.</h5>
                                <div class="customer-Benefit-content wow fadeInUp">
                                    <br>
                                    <p>Apollo Infotech is a leading web development company in India. Our Comapny is known for delivering high-quality Website Design & Development services provider in UAE & India that guarantees you a great gains. 

Our customer centric approach help our client's to share their clear business ideas. With their perspective and our experience we develop a unique user engaging product that drives traffic and converts customers.

We've a team of expert developers who stays up-to-date with the latest technology to give you the best technical support. So, if you want a website for your business which is super functional, secured and responsive then you need not look further.</p>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                
                                <div class="col-md-6">
                                    <h3 class="text-anime-style-3">Our Web Development Services in India & UAE  </h3>
                                    <div class="customer-Benefit-content wow fadeInUp">
                                        <br>
                                        <ul>
                                            <li> Static Website Development</li>
                                            <li>
                                            Dynamic Website Development</li>
                                            <li>
                                            CMS Development</li>
                                            <li>
                                            Website Maintenance</li>
                                            <li>
                                            eCommerce Website Development</li>
                                            <li>Web Portal Development</li>
                                           
                                           
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Customer Benefits Image Start -->
                                    <div class="customer-benefit-image">
                                        <figure class="image-anime reveal">
                                            <img src="{{asset('assets/frontassets/images/website-development-2.webp')}}" alt="">
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