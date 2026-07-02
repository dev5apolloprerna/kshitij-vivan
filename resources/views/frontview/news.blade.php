@extends('layouts.front')
@section('content')

<!-- Page Header Start -->
<div class="page-header light-bg-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- Page Header Box Start -->
                <div class="page-header-box">
                    <h1 class="text-anime-style-3">News</h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">News</li>
                        </ol>
                    </nav>
                </div>
                <!-- Page Header Box End -->
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->

<!-- Our blog Section start -->
<div class="page-blog">
    <div class="container">
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

        <div class="row">
            <div class="col-md-12">
                <!-- Post Pagination Start -->
                <div class="post-pagination wow fadeInUp" data-wow-delay="0.75s">
                        {{ $news->links() }}
                </div>
                <!-- Post Pagination End -->
            </div>
        </div>
    </div>
</div>
<!-- Our blog Section End -->

@endsection