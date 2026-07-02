@extends('layouts.front')
@section('content')
   <!-- Page Header Start -->
   <div class="page-header light-bg-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- Page Header Box Start -->
                    <div class="page-header-box">
                        <h1 class="text-anime-style-3">Our Partners</h1>
                        <nav class="wow fadeInUp" data-wow-delay="0.25s">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">home</a></li>
                               
                                <li class="breadcrumb-item active" aria-current="page">Our Partners</li>
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
                <div class="client-logo non-slider wow fadeInUp" data-wow-delay="0.5s">
                    <!-- client Item Start -->
                    @foreach($partner as $p)
                    <div class="client-item image-anime">
                        <img src="{{ asset('/Partner/').'/'.$p->photo}}" alt="">
                        <!-- <p>{{$p->name}}</p> -->
                    </div>
                    <!-- client Item End -->
                    @endforeach
                </div>
                <!-- Clients Logo End -->
            </div>
        </div>
    </div>
</div>
<!-- Our Clients Section End -->


@endsection