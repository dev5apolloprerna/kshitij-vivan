@extends('layouts.front')
@section('opTag')
@section('title', $seo->metaTitle ?? 'About Us')
 <meta name="description" content="{{ $seo->metaDescription }}" />
 <meta name="keywords" content="{{ $seo->metaKeyword }}" />
{!! $seo->head !!}
{!! $seo->body !!}
@endsection


@section('content')

<!-- Page Header Start -->
<div class="page-header light-bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Page Header Box Start -->
                <div class="page-header-box">
                    <h1 class="text-anime-style-3">About us</h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">about us</li>
                        </ol>
                    </nav>
                </div>
                <!-- Page Header Box End -->
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

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
                <div class="col-lg-12">
                    <p><br><br>
                    Mr. Hardik Nagadiya started his journey as an entrepreneur in 2017 when he established an accounting, financial advisory, CFO Service, company incorporation and taxation company viz. <a href="http://goodwillconsultancy.net/"> “Goodwill Consultancy FZ LLC” (http://goodwillconsultancy.net/) </a> registered in Sharjah, UAE.
</p><p>
We deliver top-notch audit, outsourced accounting & payroll, consulting and tax services with a focus on client-focused, business-centric solutions. Organizations trust our team of professional advisers for high-value advice grounded on global expertise and local insight.
</p><p>
We have 2 offices, one in Dubai and one in Sharjah.
                    </p>
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







@endsection