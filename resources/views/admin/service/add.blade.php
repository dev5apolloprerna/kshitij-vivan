@extends('layouts.app')

@section('title', 'Add Service')

@section('content')


<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            {{-- Alert Messages --}}
            @include('common.alert')

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0">Add Service</h4>
                        <div class="page-title-right">
                            <a href="{{ route('service.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Back</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form method="POST"  action="{{ route('service.store') }}" enctype="multipart/form-data" id="myForm">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-lg-6 col-md-6">
                                    <span style="color:red;">*</span>Name
                                            <input type="text" class="form-control" name="serviceName"
                                                placeholder="Enter Name" maxlength="100" autocomplete="off"
                                                autofocus required>
                                        @if($errors->has('serviceName'))
                                        <span class="text-danger">
                                            {{ $errors->first('serviceName') }}
                                        </span>
                                        @endif

                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;">*</span>Photo
                                            <input type="file" class="form-control" name="Image" required>
                                        </div>
                                         <div class="col-lg-12 col-md-12">
                                            <span style="color:red;"></span>Description
                                            <textarea  class="form-control ckeditor" name="serviceDescription"></textarea>
                                        </div>
                                        
                                        <div class="col-lg-6 col-md-6">
                                            <label>Meta Title</label>
                                            <textarea  class="form-control" name="metatitle" id="metatitle" cols="4">{{ old('metatitle') }}</textarea>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <label>Meta Description</label>
                                            <textarea  class="form-control" name="metadescription" id="metadescription" cols="4">{{ old('metadescription') }}</textarea>
                                        </div>

                                        <div class="col-lg-6 col-md-6">
                                            <label>Meta Keywork</label>
                                            <textarea  class="form-control" name="metakeyword" id="metakeyword" cols="4">{{ old('metakeyword') }}</textarea>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <label>Header Content</label>
                                            <textarea  class="form-control" name="headercontent" id="headercontent" cols="4">{{ old('headercontent') }}</textarea>
                                        </div>
                                        
                                    <div class="card-footer mt-5" style="text-align: right;">
                                        <button type="submit" name="save" value="save" class="btn btn-primary btn-user float-right mb-3 mx-2">Save</button>
                                        <!-- <button type="submit" name="saveAdd" value="saveAdd" class="btn btn-primary btn-user float-right mb-3 mx-2">Save & Add New</button> -->
                                        <a class="btn btn-primary float-right mr-3 mb-3 mx-2" href="{{ route('service.index') }}">Cancel </a>
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
<script>

    function validateFile() 
    {
        var allowedExtension = ['jpeg', 'jpg', 'png', 'webp'];
        var fileExtension = document.getElementById('strPhoto').value.split('.').pop().toLowerCase();
        var isValidFile = false;
        var image = document.getElementById('strPhoto').value;

        for (var index in allowedExtension) {

            if (fileExtension === allowedExtension[index]) {
                isValidFile = true;
                break;
            }
        }
        if (image != "") {
            if (!isValidFile) {
                alert('Allowed Extensions are : *.' + allowedExtension.join(', *.'));
                $('#strPhoto').val("");
            }
            return isValidFile;
        }

        return true;
    }
    $(document).ready(function() {
            $('#state').change(function() {
                var stateID = $(this).val();
                if(stateID) {
                    $.ajax({
                        url: './get-cities/' + stateID,
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

</script>
@endsection
