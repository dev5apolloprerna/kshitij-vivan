@extends('layouts.front')
@section('content')
@section('opTag')
@section('title', $seo->metaTitle ?? 'Contact Us')
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
                        <h1 class="text-anime-style-3">Contact us</h1>
                        <nav class="wow fadeInUp" data-wow-delay="0.25s">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">contact us</li>
                            </ol>
                        </nav>
                    </div>
                    <!-- Page Header Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header End -->
                @include('common.alert')

    <!-- Contact Information Section Start -->
    <div class="contact-information">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.25s">
                        <!-- Contact Image Start -->
                        <div class="contact-image">
                            <figure class="image-anime">
                                <img src="{{asset('assets/frontassets/images/location-img.jpg')}}" alt="">
                            </figure>
                        </div>
                        <!-- Contact Image End -->

                        <!-- Contact Info Box Start -->
                        <div class="contact-info-box">
                            <div class="icon-box">
                                <img src="{{asset('assets/frontassets/images/icon-location.svg')}}" alt="">
                            </div>
                            <div class="contact-info-content">
                                <p><a href="https://maps.app.goo.gl/s9XN5PyqaNZaCDuU7" target="_blank"> Sharjah Media City, Sharjah, UAE </a></p>
                            </div>
                        </div>
                        <!-- Contact Info Box End -->
                    </div>
                    <!-- Contact Info Item End -->
                </div>

                <div class="col-md-4">
                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.5s">
                        <!-- Contact Image Start -->
                        <div class="contact-image">
                            <figure class="image-anime">
                                <img src="{{asset('assets/frontassets/images/email-img.jpg')}}" alt="">
                            </figure>
                        </div>
                        <!-- Contact Image End -->

                        <!-- Contact Info Box Start -->
                        <div class="contact-info-box">
                            <div class="icon-box">
                                <img src="{{asset('assets/frontassets/images/icon-email.svg')}}" alt="">
                            </div>
                            <div class="contact-info-content">
                                <p><a href="mailto:hardik@nagadiya-auditors.com">hardik@nagadiya-auditors.com</a></p>
                               
                            </div>
                        </div>
                        <!-- Contact Info Box End -->
                    </div>
                    <!-- Contact Info Item End -->
                </div>

                <div class="col-md-4">
                    <!-- Contact Info Item Start -->
                    <div class="contact-info-item wow fadeInUp" data-wow-delay="0.75s">
                        <!-- Contact Image Start -->
                        <div class="contact-image">
                            <figure class="image-anime">
                                <img src="{{asset('assets/frontassets/images/phone-img.jpg')}}" alt="">
                            </figure>
                        </div>
                        <!-- Contact Image End -->

                        <!-- Contact Info Box Start -->
                        <div class="contact-info-box">
                            <div class="icon-box">
                                <img src="{{asset('assets/frontassets/images/icon-phone.svg')}}" alt="">
                            </div>
                            <div class="contact-info-content">
                                <p><a href="tel:971528320122">+971-528320122</a></p>
                               
                            </div>
                        </div>
                        <!-- Contact Info Box End -->
                    </div>
                    <!-- Contact Info Item End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Information Section End -->

    <!-- Contact Us Section Start -->
    <div class="contact-us light-bg-section">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-6">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">get in touch</h3>
                        <h2 class="text-anime-style-3"> let's get in touch</h2>
                    </div>
                    <!-- Section Title End -->
                </div>

                <div class="col-lg-6">
                    <!-- Section Title Content Start -->
                    <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                        <!-- <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
                            voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati
                            cupiditate.</p> -->
                    </div>
                    <!-- Section Title Content End -->
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <!-- Contact Form Start -->
                    <div class="contact-form wow fadeInUp" data-wow-delay="0.25s">
                        <form class="form"  id="contactForm" action="{{ route('FrontContactstore') }}" method="post" novalidate="novalidate">
                                    @csrf  
                            <div class="row">
                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="fname" class="form-control" id="fname" maxlength="50"
                                        placeholder="first name" required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="lname" class="form-control" id="lname" maxlength="50"
                                        placeholder="last name" required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <input type="email" name="email" class="form-control" id="email" placeholder="email"
                                        required="" maxlength="50">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-6 mb-4">
                                    <input type="text" name="phone" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')" maxlength="10" minlength="10" class="form-control" id="phone" placeholder="Phone"
                                        required="">
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="form-group col-md-12 mb-4">
                                    <textarea name="msg" class="form-control" id="msg" rows="7"
                                        placeholder="write a message" required=""></textarea>
                                    <div class="help-block with-errors"></div>
                                </div>

                                <div class="col-md-12">
                                    <button type="submit" class="btn-default">submit now</button>
                                    <div id="msgSubmit" class="h3 hidden"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Contact Form End -->
                </div>
                <div class="col-lg-4 py-4">
                    <div class="groupcompanybox">
                        <h4>Our Group Company</h4>
                        <a href="http://goodwillconsultancy.net/" target="blank" class="groupcompany">
                            <img src="{{asset('assets/frontassets/images/logo-goodwill.png')}}" />
                            
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Us Section End -->

    <!-- Location Map Section Start -->
    <div class="location-map">
        <div class="container">
            <div class="row section-row align-items-center">
                <div class="col-lg-6">
                    <!-- Section Title Start -->
                    <div class="section-title">
                        <h3 class="wow fadeInUp">location</h3>
                        <h2 class="text-anime-style-3">How to reach our location</h2>
                    </div>
                    <!-- Section Title End -->
                </div>

                <div class="col-lg-6">
                    <!-- Section Title Content Start -->
                    <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                        <!-- <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium
                            voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati
                            cupiditate.</p> -->
                    </div>
                    <!-- Section Title Content End -->
                </div>
            </div>
</div>
<div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Google Map Start -->
                    <div class="">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d671016.6394377027!2d55.23165257473733!3d25.362003086645593!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ef5929a229f1c75%3A0xb28ae30eb6bb813a!2sSharjah%20Media%20City%20(Shams)!5e0!3m2!1sen!2sin!4v1725617980288!5m2!1sen!2sin" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                    <!-- Google Map End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Location Map Section End -->






@endsection