@extends('layouts.front')
@section('content')
@section('opTag')
@section('title', $seo->metaTitle ?? 'Blog')
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
                    <h1 class="text-anime-style-3">Career</h1>
                    <nav class="wow fadeInUp" data-wow-delay="0.25s">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">home</a></li>

                            <li class="breadcrumb-item active" aria-current="page">Career</li>
                        </ol>
                    </nav>
                </div>
                <!-- Page Header Box End -->
            </div>
        </div>
    </div>
</div>
<!-- Page Header End -->


<!-- Why Choose Us Section Start -->
<div class="why-choose-us pt-5 ">
    <div class="container">
        <div class="row section-row align-items-center">
            <div class="col-lg-6">
                <!-- Section Title Start -->
                <div class="section-title">
                    <h3 class="wow fadeInUp">Career</h3>
                    <h2 class="text-anime-style-3">Join our team and make an impact!</h2>
                </div>
                <!-- Section Title End -->
            </div>

            <div class="col-lg-6">
                <!-- Section Title Content Start -->
                <div class="section-title-content wow fadeInUp" data-wow-delay="0.25s">
                    <p> we're committed to innovation and creating opportunities for growth. Our team is made up of
                        passionate professionals
                        who are dedicated to pushing the boundaries and delivering outstanding results. If you're ready
                        to take your career to the next level,
                        explore our job openings below.
                    </p>
                </div>
                <!-- Section Title Content End -->
            </div>
        </div>



        <div class="row">
            @foreach($Jobs as $job)
            <div class="col-lg-4">
                <!-- Why Choose Item Start -->
                <div class="career-item wow fadeInUp" data-wow-delay="0.25s">

                    <div class="career-item-content">
                        <h3>{{ $job->jobTitle }}</h3>
                        <p><strong>Openings:</strong> {{ $job->noofcandidateRequired ?? 0 }}</p>
                        <p><strong>Experience:</strong> {{ $job->experience ?? '-' }} Years</p>
                        <!-- <p>{!! Str::limit($job->jobDescription, 30, '....') !!}</p> -->
                        <a href="#" class="btn-default btn-highlighted" data-toggle="modal" data-target="#jobdetail_{{$job->jobId}}"><strong>Detail</strong></a>

                    </div>
                </div>
                <!-- Why Choose Item End -->
            </div>

            <!-- Why Choose Us Section End -->
<div class="modal " id="jobdetail_{{$job->jobId}}">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">{{$job->jobTitle}}</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
        <p><strong>Openings:</strong> 5</p>
                        <p><strong>Experience:</strong> {{$job->experience}} years</p>
                            <p><strong>Responsibilities:</strong></p>
                <p>
                    {!! $job->jobDescription !!}
                </p>

                
                <div class="apply-form contact-form">
            <h2>Apply Now</h2>
            <form action="{{route('submitCV')}}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="jobId" value="{{$job->jobId}}">
                <div class="row">
                    <div class="col-lg-4 form-group"> <label for="name" >Full Name</label></div>
                    <div class="col-lg-6 form-group"><input type="text" id="name" name="name" class="form-control" required></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 form-group">  <label for="email">Email Address</label></div>
                    <div class="col-lg-6 form-group"><input type="email" id="email" name="email" class="form-control" required></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 form-group">  <label for="resume">Upload Resume</label></div>
                    <div class="col-lg-6 form-group"><input type="file" name="cv" id="resume" placeholder="Upload CV" class="form-control" onchange="return validateFile()" required></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 form-group">   <label for="message">Cover Letter</label></div>
                    <div class="col-lg-6 form-group"> <textarea id="message" name="coverLetter" rows="5" class="form-control" placeholder="Tell us why you're a good fit for this role..." required></textarea></div>
                </div>
                <button type="submit" value="Submit Application" class="btn-default">Apply</button>
            </form>
        </div>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn-default" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
            @endforeach

            
        </div>
    </div>
</div>

 <!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS for Bootstrap -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

@endsection
@section('scripts')
<script type="text/javascript">
    function validateFile() {
            var allowedExtension = ['pdf'];
            var fileExtension = document.getElementById('resume').value.split('.').pop().toLowerCase();
            var isValidFile = false;

            for (var index in allowedExtension) {

                if (fileExtension === allowedExtension[index]) {
                    isValidFile = true;
                    break;
                }
            }
            if (!isValidFile) {
                alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
                $('#resume').val("");
            }
            return isValidFile;
        }
</script>
@endsection
