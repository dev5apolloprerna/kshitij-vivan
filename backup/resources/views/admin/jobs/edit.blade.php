
@extends('layouts.app')

@section('title', 'Edit Job')

@section('content')

    <!--<script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>-->

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">Edit User</h4>
                            <div class="page-title-right">
                                <a href="{{ route('jobs.index') }}"
                                    class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                                    Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="live-preview">
                                <form method="POST"  action="{{ route('jobs.update', $job->jobId) }}" enctype="multipart/form-data" id="myForm">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-lg-6 col-md-6">
                                                <span style="color:red;">*</span><label>Job Title</label>
                                                <input type="text" class="form-control"
                                                placeholder="Enter Name" name="jobTitle" id="jobTitle"
                                                autocomplete="off" value="{{ $job->jobTitle }}" maxlength="50" required>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;">*</span>Experience
                                            <input type="text" class="form-control" name="experience" value="{{$job->experience}}" required>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;">*</span>Number of candidates required
                                            <input type="text" class="form-control" name="noofcandidateRequired" value="{{$job->noofcandidateRequired}}" required>
                                        </div>
                                        
                                        <div class="col-lg-12 col-md-6">
                                            <label>Job Description</label>
                                            <textarea  class="form-control ckeditor" name="jobDescription" id="jobDescription" cols="4">{{ $job->jobDescription }}</textarea>
                                        </div>

                                        <div class="card-footer mt-5" style="float: right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3 mx-2">Update</button>
                                            <a class="btn btn-primary float-right mr-3 mb-3 mx-2"
                                                href="{{ route('jobs.index') }}">Cancel</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
    <script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
@section('scripts')
<script type="text/javascript">
     $(document).ready(function() {
            $('#state').change(function() {
                var stateID = $(this).val();
                if(stateID) {
                    $.ajax({
                        url: '../get-cities/' + stateID,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            $('#city').empty();
                            $('#city').append('<option value="">Select City</option>');
                            $.each(data, function(key, value) {
                                $('#city').append('<option value="'+ value.cityId +'">'+ value.cityName +'</option>');
                            });
                        }
                    });
                } else {
                    $('#city').empty();
                    $('#city').append('<option value="">Select City</option>');
                }
            });
        });
</script>
@endsection