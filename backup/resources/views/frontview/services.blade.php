@extends('layouts.front')
@section('content')
@section('opTag')
@section('title', $service->metatitle ?? '')
<meta name="description" content="{{ $service->metadescription }}" />
<meta name="keywords" content="{{ $service->metakeyword }}" />
{!! $service->headercontent !!}
@endsection





<!-- Page Header Start -->
<div class="page-header light-bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Page Header Box Start -->
                <div class="page-header-box">
                    <h1 class="text-anime-style-3">{{$service->serviceName}}</h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">home</a></li>
                            <li class="breadcrumb-item"><a href="#">services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$service->serviceName}}</li>
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
                    <!-- Service Featured Image End -->

                    <!-- Service Entry Content Start -->
                    <div class="service-entry">
                        <h2 class="text-anime-style-3">{{$service->serviceName}}</h2>
                        <div class="row ">
                            <div class="col-md-12">
                                <!-- Customer Benefits Image Start -->
                                <div class="customer-benefit-image pt-4">
                                    <figure class="image-anime reveal">
                                        <img src="{{ asset('/Service/').'/'.$service->photo}}" alt="">
                                    </figure>
                                </div>
                                <!-- Customer Benefits Image End -->
                            </div>
                            @if($service->serviceDescription != null)
                            <div class="col-md-12">
                                <!-- <h5 class="text-anime-style-3">Corporate Tax:</h5> -->
                                <div class="customer-Benefit-content wow fadeInUp">
                                    <br>
                                    
                                        {!!  $service->serviceDescription !!}
                                    
                                </div>
                            </div>
                            @endif
                            <!-- <div class="row align-items-center">
                                
                                <div class="col-md-6">
                                    <h5 class="text-anime-style-3">VAT and Excise:</h5>
                                    <div class="customer-Benefit-content wow fadeInUp">
                                        <br>
                                        <ul>
                                            <li>{{ $service->serviceDescription }}</li>
                                            
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="customer-benefit-image">
                                        <figure class="image-anime reveal">
                                            <img src="{{ asset('/Service/').'/'.$service->photo}}" alt="">
                                        </figure>
                                    </div>
                                </div>
                            </div> -->
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
                                @foreach($serviceList as $ss)
                                <li><a href="{{route('FrontServices',$ss->serviceId)}}">{{$ss->serviceName}}</a></li>
                                @endforeach

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
                                <a href="{{route('FrontContact')}}" class="btn-default btn-highlighted">contact us</a>
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