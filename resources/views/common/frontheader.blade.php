 <!-- Magic Cursor Start -->
 <div id="magic-cursor">
        <div id="ball">
            <div class="circle"></div>
        </div>
</div>
<!-- Magic Cursor End -->
 
 <!-- Header Start -->
 <header class="main-header ">
        <div class="header-sticky header">
            <nav class="navbar navbar-expand-lg">
                <div class="container">
                    <!-- Logo Start -->
                    <a class="navbar-brand" href="index-2.html">
                        <img src="{{asset('assets/frontassets/images/logo.png')}}" alt="Logo">
                    </a>
                    <!-- Logo End -->

                    <!-- Main Menu Start -->
                    <div class="collapse navbar-collapse main-menu">
                        <div class="nav-menu-wrapper">
                            <ul class="navbar-nav mr-auto" id="menu">



                                <li class="nav-item"><a class="nav-link" href="{{route('FrontIndex')}}">Home</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{route('FrontAbout')}}">about us</a></li>
                                <li class="nav-item submenu"><a class="nav-link" href="#">services</a>
                                    <ul>
                                        <?php
                                          $Service = App\Models\Service::orderBy('serviceName', 'asc')
                                              ->where(['iStatus' => 1, 'isDelete' => 0])
                                              ->get();
                                         ?>
                                        @foreach ($Service as $s)
                                        <li class="nav-item"><a class="nav-link" href="{{route('FrontServices',$s->serviceId)}}">{{ $s->serviceName }}</a></li>
                                        @endforeach                                   
                                    </ul>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{route('FrontCareer')}}"> Career</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{route('FrontNews')}}">News</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{route('FrontPartner')}}">Partners</a></li>
                                
                                <li class="nav-item"><a class="nav-link" href="{{route('FrontContact')}}">Contact Us</a></li>
                                
                                <li class="nav-item highlighted-menu"><a class="nav-link" href="{{route('FrontContact')}}">Get a Quote</a></li>
                            </ul>
                        </div>
                        <!-- Let’s Start Button Start -->
                        <!--<div class="header-btn d-inline-flex">-->
                        <!--    <a href="#" class="btn-default">book now</a>-->
                        <!--</div>-->
                        <!-- Let’s Start Button End -->
                    </div>
                    <!-- Main Menu End -->

                    <div class="navbar-toggle"></div>
                </div>
            </nav>
            <div class="responsive-menu"></div>
        </div>
    </header>
    <!-- Header End -->