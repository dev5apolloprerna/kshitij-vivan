@extends('layouts.front')
@section('content')

<!-- Page Header Start -->
<div class="page-header light-bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Page Header Box Start -->
                <div class="page-header-box">
                    <h1 class="text-anime-style-3">Other Services</h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">home</a></li>

                            <li class="breadcrumb-item active" aria-current="page">Other Services</li>
                        </ol>
                    </nav>
                </div>
                <!-- Page Header Box End -->
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->
<!-- Our Clients Section Start -->
<div class="our-clients">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-6">
                <!-- Section Title Start -->
                <div class="section-title">
                    <h3 class="wow fadeInUp">Other Services</h3>
                    <h2 class="text-anime-style-3">Introducing Our Creative Partnership with Apollo Infotech </h2>
                </div>
                <!-- Section Title End -->
            </div>

            <div class="col-lg-6">
                <!-- Section Title Content Start -->
                <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                    <p>We are thrilled to announce an exciting new collaboration with Apollo Infotech, a pioneering
                        force in technology and innovation. Our creative venture is designed to fuse cutting-edge
                        technology with imaginative solutions that push the boundaries of what’s possible in the digital
                        world.</p>
                </div>
                <!-- Section Title Content End -->
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 clients-slider">
                <!-- Clients Logo Start -->
                <div class="service-logo non-slider wow fadeInUp" data-wow-delay="0.5s">
                    <!-- client Item Start -->
                    <div class="col-lg-2 service-block">
                        <div class="client-item image-anime w-100">

                            <img src="{{asset('assets/frontassets/images/web-development.webp')}}" alt=""></a>
                        </div>
                        <a href="{{route('FrontWebsitedevelopmentservices')}}">
                            <p>Web Development</p>
                        </a>
                    </div>
                    <!-- client Item End -->
                     <!-- client Item Start -->
                    <div class="col-lg-2 service-block">
                        <div class="client-item image-anime w-100">

                            <img src="{{asset('assets/frontassets/images/Banner-Mobile.webp')}}" alt=""></a>
                        </div>
                        <a href="{{route('FrontMobileapplicationdevelopmentservices')}}">
                            <p>Mobile App Development</p>
                        </a>
                    </div>
                    <!-- client Item End -->
                     <!-- client Item Start -->
                    <div class="col-lg-2 service-block">
                        <div class="client-item image-anime w-100">

                            <img src="{{asset('assets/frontassets/images/seo.webp')}}" alt=""></a>
                        </div>
                        <a href="{{route('FrontSearchengineoptimizationservices')}}">
                            <p>Search Engine Optimization (SEO)</p>
                        </a>
                    </div>
                    <!-- client Item End -->
                     <!-- client Item Start -->
                    <div class="col-lg-2 service-block">
                        <div class="client-item image-anime w-100">

                            <img src="{{asset('assets/frontassets/images/smo.webp')}}" alt=""></a>
                        </div>
                        <a href="{{route('FrontSocialmediamarketingservices')}}">
                            <p>Social Media Marketing (SMO)</p>
                        </a>
                    </div>
                    <!-- client Item End -->
                     <!-- client Item Start -->
                    <div class="col-lg-2 service-block">
                        <div class="client-item image-anime w-100">

                            <img src="{{asset('assets/frontassets/images/graphics.webp')}}" alt=""></a>
                        </div>
                        <a href="{{route('FrontGraphicdesignservices')}}">
                            <p>Graphic Design Services </p>
                        </a>
                    </div>
                    <!-- client Item End -->

                    


                </div>
                <!-- Clients Logo End -->
            </div>
        </div>
    </div>
</div>
<!-- Our Clients Section End -->

@endsection