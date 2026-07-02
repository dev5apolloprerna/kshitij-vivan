@extends('layouts.front')
@section('content')
@section('opTag')
    @section('title', $seo->metaTitle ?? 'SMO')
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
                    <h1 class="text-anime-style-3"> Social Media Marketing in India & UAE</h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">home</a></li>
                            <li class="breadcrumb-item"><a href="#">services</a></li>
                            <li class="breadcrumb-item active" aria-current="page"> Social Media Marketing</li>
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
                            <img src="{{asset('assets/images/service-img-1.jpg')}}" alt="">
                        </figure>
                    </div> -->
                    <!-- Service Featured Image End -->

                    <!-- Service Entry Content Start -->
                    <div class="service-entry">
                        <h2 class="text-anime-style-3"> Social Media Marketing in India & UAE</h2>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <!-- Customer Benefits Image Start -->
                                <div class="customer-benefit-image">
                                    <figure class="image-anime reveal">
                                        <img src="{{asset('assets/frontassets/images/Social-media-advertising.webp')}}" alt="">
                                    </figure>
                                </div>
                                <!-- Customer Benefits Image End -->
                            </div>
                            <div class="col-md-6">
                                <h5 class="text-anime-style-3">Get Noticed on Social Media With Our Social Media Marketing Services</h5>
                                <div class="customer-Benefit-content wow fadeInUp">
                                    <br>
                                    <p>Are you struggling to make your mark on social media in UAE & India? Do you want to supercharge your business's growth and reach a larger and more engaged audience?

Look no further than Apollo Infotech. Our team of dedicated social media experts provides the best social media marketing services in UAE and Indian clients for their business branding on  social media. We understand the challenges that come with navigating the ever-changing social media landscape and have the experience and expertise to help businesses of all sizes succeed on popular platforms like Facebook, Instagram, and LinkedIn.

From developing personalized strategies to creating engaging content, we are committed to helping our clients achieve their goals and drive business growth. Let us help you supercharge your business on social media.</p>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                
                                <div class="col-md-6">
                                    <h3 class="text-anime-style-3">Our Social Media Marketing Services  in India & UAE</h3>
                                    <div class="customer-Benefit-content wow fadeInUp">
                                        <br>
                                        <ul>
                                            <li>Social media strategy development</li>
                                            <li>
                                            Social media content creation</li>
                                            <li>
                                            Social media analytics and reporting</li>
                                            <li>
                                            Social media advertising</li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <!-- Customer Benefits Image Start -->
                                    <div class="customer-benefit-image">
                                        <figure class="image-anime reveal">
                                            <img src="{{asset('assets/frontassets/images/Start.webp')}}" alt="">
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