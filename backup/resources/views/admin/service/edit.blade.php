
@extends('layouts.app')

@section('title', 'Edit Service')

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
                            <h4 class="mb-sm-0">Edit Service</h4>
                            <div class="page-title-right">
                                <a href="{{ route('service.index') }}"
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
                                <form method="POST"  action="{{ route('service.update', $data->serviceId) }}" enctype="multipart/form-data" id="myForm">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-lg-6 col-md-6">
                                                <span style="color:red;">*</span><label>Service Title</label>
                                                <input type="text" class="form-control"
                                                placeholder="Enter Name" name="serviceName" id="serviceName"
                                                autocomplete="off" value="{{ $data->serviceName }}" maxlength="50" required>
                                        </div>
                                       <div class="col-lg-6 col-md-6">
                                            <span style="color:red;">*</span>Photo
                                            <input type="file" class="form-control" name="Image">
                                            <input type="hidden" name="hiddenImage" id="hiddenImage"  class="form-control" value="{{$data->photo}}">
                                                <img src="{{ asset('/Service/') . '/' . $data->photo }}" width="50px" height="50px" id="editphoto">
                                        </div>
                                         <div class="col-lg-12 col-md-12">
                                            <span style="color:red;"></span>Description
                                            <textarea  class="form-control ckeditor" name="serviceDescription">{{$data->serviceDescription}}</textarea>
                                        </div>
                                        
                                        <div class="col-lg-6 col-md-6">
                                            <label>Meta Title</label>
                                            <textarea  class="form-control" name="metatitle" id="metatitle" cols="4">{{ $data->metatitle }}</textarea>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <label>Meta Description</label>
                                            <textarea  class="form-control" name="metadescription" id="metadescription" cols="4">{{ $data->metadescription }}</textarea>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <label>Meta Keywork</label>
                                            <textarea  class="form-control" name="metakeyword" id="metakeyword" cols="4">{{ $data->metakeyword }}</textarea>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <label>Header Content</label>
                                            <textarea  class="form-control" name="headercontent" id="headercontent" cols="4">{{ $data->headercontent }}</textarea>
                                        </div>
                                        
                                        <div class="card-footer mt-5" style="text-align: right;">
                                            <button type="submit"
                                                class="btn btn-primary btn-user float-right mb-3 mx-2">Update</button>
                                            <a class="btn btn-primary float-right mr-3 mb-3 mx-2"
                                                href="{{ route('service.index') }}">Cancel</a>
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