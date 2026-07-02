@extends('layouts.front')
@section('content')
@section('opTag')
@section('title', $seo->metaTitle ?? 'Home')
 <meta name="description" content="{{ $seo->metaDescription }}" />
 <meta name="keywords" content="{{ $seo->metaKeyword }}" />
{!! $seo->head !!}
{!! $seo->body !!}
@endsection



    <!-- Hero Section Start -->
    <div class="hero">
        <div class="container-fluid">
            <div id="mainBanner" class="carousel slide" data-bs-ride="carousel" data-bs-pause="false">
                <div class="carousel-inner">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#mainBanner" data-bs-slide-to="0" class="active"
                            aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#mainBanner" data-bs-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#mainBanner" data-bs-slide-to="2"
                            aria-label="Slide 3"></button>

                    </div>
                    <div class="carousel-item active" data-bs-interval="7000">
                        <img src="{{asset('assets/frontassets/images/slide-1.jpg')}}" class="d-block w-100" alt="...">
                        <div class="carousel-caption px-md-5">
                            <h5>Nagadiya Auditing Of Accounts L.L.C</h5>
                            <hr class="bg-light border-4 border-top border-light">
                            <p>CHARTERED ACCOUNTANTS. INTERNAL & EXTERNAL AUDIT. </p>
                            <div class="banner-btn">
                                <a href="{{route('FrontContact')}}" class="btn btn-default ">Contact Us</a>
                                
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item" data-bs-interval="7000">
                        <img src="{{asset('assets/frontassets/images/slide-2.jpg')}}" class="d-block w-100" alt="...">
                        <div class="carousel-caption px-md-5">
                            <h5>Nagadiya Auditing Of Accounts L.L.C</h5>
                            <hr class="bg-light border-4 border-top border-light">
                            <p>TAX. MANAGEMENT CONSULTING. 
                                </p>
                            <div class="banner-btn">
                                <a href="{{route('FrontContact')}}" class="btn btn-default ">Contact Us</a>
                                
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item" data-bs-interval="7000">
                        <img src="{{asset('assets/frontassets/images/slide-3.jpg')}}" class="d-block w-100" alt="...">
                        <div class="carousel-caption px-md-5">
                            <h5>Nagadiya Auditing Of Accounts L.L.C</h5>
                            <hr class="bg-light border-4 border-top border-light">
                            <p>COMPANY INCORPORATION.</p>
                            <div class="banner-btn">
                                 <a href="{{route('FrontContact')}}" class="btn btn-default ">Contact Us</a>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section End -->

    <!-- About Section Start -->
    <div class="about-us">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-6">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">about us</h3>
                        <h2 class="text-anime-style-3">Nagadiya Auditing Of Accounts L.L.C is a firm of Chartered
                            Accountants, established as Audit firm in Emirate of Dubai UAE</h2>
                    </div>
                    <!-- Section Title End -->
                </div>

                <div class="col-lg-6">
                    <!-- Section Title Content Start -->
                    <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                        <p>We provide expert, exceptional and credible audit services and data-driven business growth
                            solutions to our clients, with an up-to-date understanding and alignment under the laws of
                            the local government bodies in UAE, and a focus on providing international standards of
                            quality in our services.
                        </p>
                        <p><br></p>
                        <p>
                            Mr. Hardik Nagadiya, founder of Nagadiya Auditing Of Accounts L.L.C is a Chartered Accountant
                            who has experience of more than 15 years’ in Auditing, Accounting, taxation, company
                            incorporation and management consultancy. </p>
                    </div>
                    <!-- Section Title Content End -->
                </div>
            </div>



            <div class="row">
                <div class="col-md-4">
                    <!-- About Company Item Start -->
                    <div class="about-company-item wow fadeInUp" data-wow-delay="0.25s">
                        <div class="about-company-head">
                            <div class="icon-box">
                                <img src="{{asset('assets/frontassets/images/icon-about-company-1.svg')}}" alt="">
                            </div>
                            <h3>
                                Affiliations
                            </h3>
                        </div>
                        <div class="about-company-content">
                            <p>We are affiliated with all <br> major Free Zones as authorized agents.</p>
                        </div>
                    </div>
                    <!-- About Company Item End -->
                </div>

                <div class="col-md-4">
                    <!-- About Company Item Start -->
                    <div class="about-company-item wow fadeInUp" data-wow-delay="0.5s">
                        <div class="about-company-head">
                            <div class="icon-box">
                                <img src="{{asset('assets/frontassets/images/icon-about-company-2.svg')}}" alt="">
                            </div>
                            <h3>
                                Our Mission</h3>
                        </div>
                        <div class="about-company-content">
                            <p>Our priority is for the individual partners/managers to give personal attention to client’s needs.</p>
                        </div>
                    </div>
                    <!-- About Company Item End -->
                </div>

                <div class="col-md-4">
                    <!-- About Company Item Start -->
                    <div class="about-company-item wow fadeInUp" data-wow-delay="0.75s">
                        <div class="about-company-head">
                            <div class="icon-box">
                                <img src="{{asset('assets/frontassets/images/vision.svg')}}" alt="">
                            </div>
                            <h3>
                                Our Vision</h3>
                        </div>
                        <div class="about-company-content">
                            <p>To take the lead for our clients through commercial advice, value for money and complete support.</p>
                        </div>
                    </div>
                    <!-- About Company Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- About Section End -->

    <!-- Our Services Start -->
    <div class="our-service light-bg-section">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-5">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">our services</h3>
                        <h2 class="text-anime-style-3">Your business goals are our priority</h2>
                    </div>
                    <!-- Section Title End -->
                </div>

                <div class="col-lg-7">
                    <!-- Section Title Content Start -->
                    <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                        <p>We deliver top-notch audit, outsourced accounting & payroll, consulting and tax services with a focus on client-focused, business-centric solutions.</p>
                    </div>
                    <!-- Section Title Content End -->
                </div>
            </div>

            <div class="row">
                @foreach($service as $ser)
                <div class="col-md-3">
                    <!-- Service Item Start -->
                    <div class="service-item wow fadeInUp" data-wow-delay="0.25s">
                        <div class="service-image">
                            <figure class="image-anime">
                                <a href="{{route('FrontServices',$ser->serviceId)}}"><img src="{{ asset('/Service/').'/'.$ser->photo}}" alt=""></a>
                            </figure>
                        </div>
                        <div class="service-content">
                            <h3>{{ $ser->serviceName }}</h3>
                            <div class="service-readmore-btn">
                                <a href="{{route('FrontServices',$ser->serviceId)}}" class="btn-default">read more</a>
                            </div>
                        </div>
                    </div>
                    <!-- Service Item End -->
                </div>
                @endforeach
                

                <!-- Services Footer Btn Start -->
                <!-- <div class="service-footer-btn wow fadeInUp" data-wow-delay="0.5s">
                    <a href="#" class="btn-default">view all services</a>
                </div> -->
                <!-- Services Footer Btn End -->
            </div>
        </div>
    </div>
    <!-- Our Services End -->

    <!-- State Counter Section Start -->
    <div class="state-counter">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">state of affairs</h3>
                        <h2 class="text-anime-style-3">Keeping you informed on tax laws and financial trends
                        </h2>
                    </div>
                    <!-- Section Title End -->

                    <!-- Counter Contact Btn Start -->
                    <div class="counter-contact-btn wow fadeInUp" data-wow-delay="0.25s">
                        <a href="{{route('FrontContact')}}" class="btn-default">contact us</a>
                    </div>
                    <!-- Counter Contact Btn End -->
                </div>

                <div class="col-lg-7">
                  

                    <!-- State Image Counter Start -->
                    <div class="state-image-counter-2">
                        <!-- Counter Box Image Start -->
                        <div class="counter-box counter-image wow fadeInUp" data-wow-delay="0.75s">
                            <figure class="image-anime">
                                <img src="{{asset('assets/frontassets/images/counter-img-2.jpg')}}" alt="">
                            </figure>
                            
                                <div class="counter-content wow fadeInUp">
                                    <h3><span class="counter">50</span>+</h3>
                                    <p>Financial Audits Conducted Annually</p>
                                </div>
                            
                        </div>
                        <!-- Counter Box Image End -->
                       
                       
                    </div>
                    <!-- State Image Counter End -->
                </div>
            </div>
        </div>
    </div>
    <!-- State Counter Section End -->

    <!-- Why Choose Us Section Start -->
    <div class="why-choose-us light-bg-section">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-6">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">why choose us</h3>
                        <h2 class="text-anime-style-3">Why should you choose Nagadiya Auditing Of Accounts L.L.C ?</h2>
                    </div>
                    <!-- Section Title End -->
                </div>

                <div class="col-lg-6">
                    <!-- Section Title Content Start -->
                    <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                        <p>By choosing Nagadiya Auditing Of Accounts L.L.C, businesses can ensure that their financial operations are secure, compliant, and optimized for growth.</p>
                    </div>
                    <!-- Section Title Content End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <!-- Why Choose Image Box Start -->
                    <div class="why-choose-image-box wow fadeInUp" data-wow-delay="0.25s">
                        <!-- Why Choose Image Start -->
                        <div class="why-choose-image">
                            <img src="{{asset('assets/frontassets/images/why-choose-us-img.jpg')}}" alt="">
                        </div>
                        <!-- Why Choose Image End -->

                        <!-- Why Choose Content Start -->
                        <div class="why-choose-content">
                            <!-- Why Choose Title Start -->
                            <div class="why-choose-title">
                                <h2 class="text-anime-style-3">Experience seamless, secure, and efficient
                                    account management</h2>
                            </div>
                            <!-- Why Choose Title End -->

                            <!-- Why Choose Counter Start -->
                            <div class="why-choose-counter">
                                <h3><span class="counter">13</span>  Years</h3>
                                <p>Experience in auditing accounting taxation and advisory</p>
                            </div>
                            <!-- Why Choose Counter End -->
                        </div>
                        <!-- Why Choose Content End -->
                    </div>
                    <!-- Why Choose Image Box End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <!-- Why Choose Item Start -->
                    <div class="why-choose-item wow fadeInUp" data-wow-delay="0.25s">
                        <div class="icon-box">
                            <img src="{{asset('assets/frontassets/images/icon-why-choose-1.svg')}}" alt="">
                        </div>
                        <div class="why-choose-body">
                            <h3>Affiliations</h3>
                            <p>We are affiliated with major Free Zones as authorized agents.</p>
                        </div>
                    </div>
                    <!-- Why Choose Item End -->
                </div>

                <div class="col-lg-4">
                    <!-- Why Choose Item Start -->
                    <div class="why-choose-item wow fadeInUp" data-wow-delay="0.5s">
                        <div class="icon-box">
                            <img src="{{asset('assets/frontassets/images/icon-why-choose-2.svg')}}" alt="">
                        </div>
                        <div class="why-choose-body">
                            <h3>Experienced</h3>
                            <p>We have helped dozens of companies to incorporate across different countries.</p>
                        </div>
                    </div>
                    <!-- Why Choose Item End -->
                </div>

                <div class="col-lg-4">
                    <!-- Why Choose Item Start -->
                    <div class="why-choose-item wow fadeInUp" data-wow-delay="0.75s">
                        <div class="icon-box">
                            <img src="{{asset('assets/frontassets/images/icon-why-choose-3.svg')}}" alt="">
                        </div>
                        <div class="why-choose-body">
                            <h3>Affordable</h3>
                            <p>Our packages are affordable and economic.</p>
                        </div>
                    </div>
                    <!-- Why Choose Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Why Choose Us Section End -->

   <!-- Our Clients Section Start -->
   <div class="our-clients">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-6">
                <!-- Section Title Start -->
                <div class="section-title">
                    <h3 class="wow fadeInUp">our clients</h3>
                    <h2 class="text-anime-style-3">Our Happy Clients From Different Industries. </h2>
                </div>
                <!-- Section Title End -->
            </div>

            <div class="col-lg-6">
                <!-- Section Title Content Start -->
                <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                    <p>We are proud to have earned the trust and partnership of clients across diverse sectors. We take pride in providing value-driven services, and our clients' success is our success. Join the growing number of satisfied partners who have chosen us to meet their unique business needs.</p>
                </div>
                <!-- Section Title Content End -->
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12 clients-slider">
                <!-- Clients Logo Start -->
                <div class="client-logo owl-carousel owl-theme wow fadeInUp" data-wow-delay="0.5s">
                    <!-- client Item Start -->
                    <div class="client-item image-anime">
                        <img src="{{asset('assets/frontassets/images/client-logo-1.jpg')}}" alt="">
                    </div>
                    <!-- client Item End -->

                    <!-- client Item Start -->
                    <div class="client-item image-anime">
                        <img src="{{asset('assets/frontassets/images/client-logo-2.jpg')}}" alt="">
                    </div>
                    <!-- client Item End -->

                    <!-- client Item Start -->
                    <div class="client-item image-anime">
                        <img src="{{asset('assets/frontassets/images/client-logo-3.jpg')}}" alt="">
                    </div>
                    <!-- client Item End -->

                    <!-- client Item Start -->
                    <div class="client-item image-anime">
                        <img src="{{asset('assets/frontassets/images/client-logo-4.jpg')}}" alt="">
                    </div>
                    <!-- client Item End -->

                    <!-- client Item Start -->
                    <div class="client-item image-anime">
                        <img src="{{asset('assets/frontassets/images/client-logo-5.jpg')}}" alt="">
                    </div>
                    <!-- client Item End -->

                </div>
                <!-- Clients Logo End -->
            </div>
        </div>
    </div>
</div>
<!-- Our Clients Section End -->

    <!-- Cta Box Section Start -->
    <div class="cta-box">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h2 class="text-anime-style-3">Looking for auditing, accounting taxation advisory get started?</h2>
                    </div>
                    <!-- Section Title End -->
                </div>

                <div class="col-lg-4">
                    <!-- Section Btn Start -->
                    <div class="section-btn wow fadeInUp" data-wow-delay="0.25s">
                        <a href="#" class="btn-default btn-highlighted btn-large">get started</a>
                    </div>
                    <!-- Section Btn End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Cta Box Section End -->

   


   

    <!-- Our blog Section start -->
    <div class="our-blog">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-6">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">Our News</h3>
                        <h2 class="text-anime-style-3">Latest Updates</h2>
                    </div>
                    <!-- Section Title End -->
                </div>

                <div class="col-lg-6">
                    <!-- Section Title Content Start -->
                    <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                        <!-- <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
                            voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint
                            occaecati cupiditate.</p> -->
                    </div>
                    <!-- Section Title Content End -->
                </div>
            </div>

            <div class="row">
                @foreach($news as $n)
                <div class="col-lg-4 col-md-6">
                    <!-- Blog Item Start -->
                    <div class="blog-item wow fadeInUp" data-wow-delay="0.25s">
                        <!-- Post Featured Image Start-->
                        <div class="post-featured-image">
                            <figure class="image-anime">
                                <a href="{{route ('FrontNewsdetail',$n->id)}}"><img src="{{ asset('/News/').'/'.$n->photo}}" alt=""></a>
                            </figure>
                        </div>
                        <!-- Post Featured Image End -->

                        <!-- post Item Body Start -->
                        <div class="post-item-body">
                            <h2><a href="{{route ('FrontNewsdetail',$n->id)}}">{{ $n->title }}</a></h2>
                            <p>{{ $n->description }}</p>
                        </div>
                        <!-- Post Item Body End-->

                        <!-- Post Item Footer Start-->
                        <div class="post-item-footer">
                            <a href="{{route ('FrontNewsdetail',$n->id)}}" class="btn-default">read more</a>
                        </div>
                        <!-- Post Item Footer End-->
                    </div>
                    <!-- Blog Item End -->
                </div>
                @endforeach
        </div>
    </div>
    <!-- Our blog Section End -->
</div>



@endsection