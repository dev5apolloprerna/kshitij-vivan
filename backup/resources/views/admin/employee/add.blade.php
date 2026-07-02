@extends('layouts.app')

@section('title', 'Add Employee')

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
                        <h4 class="mb-sm-0">Add Employee</h4>
                        <div class="page-title-right">
                            <a href="{{ route('employee.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Back</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="live-preview">
                                <form method="POST"  action="{{ route('employee.store') }}" enctype="multipart/form-data" id="myForm">
                                @csrf
                                    <div class="row gy-4">
                                        <div class="col-lg-6 col-md-6">
                                                <span style="color:red;">*</span><label>Name</label>
                                                <input type="text" class="form-control"
                                                placeholder="Enter Name" name="name" id="name"
                                                autocomplete="off" value="{{ old('name') }}" maxlength="50" required>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;">*</span><label>Email</label>
                                            <input type="email" class="form-control"
                                                placeholder="Enter Email" name="email" id="email" autocomplete="off"
                                                value="{{ old('email') }}" maxlength="100" required>
                                                @error('email')
                                                        <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                        </div>
                                        
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;">*</span><label>Mobile</label>
                                            <input type="text" class="form-control"
                                                placeholder="Enter Mobile Number" name="mobile" id="mobile" autocomplete="off" value="{{ old('mobile') }}" onkeyup="if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,'')"  maxlength="10" minlength="10" required>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;">*</span><label>Location</label>
                                            <input type="text"  class="form-control" name="location" id="location" maxlength="150" value="{{ old('location') }}" required>
                                        </div>
                                         <div class="col-lg-6 col-md-6">
                                            <span style="color:red;"></span><label>In Time</label>
                                            <input type="Time"  class="form-control" name="in_time" id="in_time" maxlength="150" value="{{ old('in_time') }}">
                                        </div>
                                         <div class="col-lg-6 col-md-6">
                                            <span style="color:red;"></span><label>Out Time</label>
                                            <input type="Time"  class="form-control" name="out_time" id="out_time" maxlength="150" value="{{ old('out_time') }}">
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;"></span><label>Grace Period (Minutes)</label>
                                            <input type="number" class="form-control" name="grace_period" id="grace_period" maxlength="3" min="0" max="59" placeholder="Enter minutes" value="{{ old('grace_period') }}">
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;">*</span><label>Salary</label>
                                            <input type="number" class="form-control" name="salary" id="salary" maxlength="10" placeholder="Enter Salary" value="{{ old('salary') }}" required>
                                        </div>

                                         <div class="col-lg-6 col-md-6">
                                            <span style="color:red;"></span><label>Morning Half Day In Time</label>
                                            <input type="Time"  class="form-control" name="morning_half_day_in_time" id="morning_half_day_in_time" maxlength="150" value="{{ old('morning_half_day_in_time') }}" >
                                        </div>
                                         <div class="col-lg-6 col-md-6">
                                            <span style="color:red;"></span><label>Morning Half Day Out Time</label>
                                            <input type="Time"  class="form-control" name="morning_half_day_out_time" id="morning_half_day_out_time" maxlength="150" value="{{ old('morning_half_day_out_time') }}" >
                                        </div>

                                       <!-- <div class="col-lg-6 col-md-6">
                                            <span style="color:red;"></span><label>Evening Half Day In Time</label>
                                            <input type="Time"  class="form-control" name="evening_half_day_in_time" id="evening_half_day_in_time" maxlength="150" value="{{ old('evening_half_day_in_time') }}" >
                                        </div>
                                         <div class="col-lg-6 col-md-6">
                                            <span style="color:red;"></span><label>Evening Half Day Out Time</label>
                                            <input type="Time"  class="form-control" name="evening_half_day_out_time" id="evening_half_day_out_time" maxlength="150" value="{{ old('evening_half_day_out_time') }}" >
                                        </div>-->
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;"></span><label>CL</label>
                                            <input type="number"  class="form-control" name="balance_cl" id="balance_cl" maxlength="150" value="{{ old('balance_cl') }}" placeholder="Enter Balance CL">
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;"></span><label>Balance CF</label>
                                            <input type="number"  class="form-control" name="balance_cf" id="balance_cf" maxlength="150" value="{{ old('balance_cf') }}" placeholder="Enter Balance CF">
                                        </div>
                                    

                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;">*</span><label>Role</label>
                                                <select class="form-control" name="role_id" id="role_id" required>
                                                    <option value="">Select Role</option>
                                                    <option value="2">Employee</option>
                                                    <option value="3">Manager</option>
                                                </select>
                                        </div>

                                        <div class="col-lg-6 col-md-6" id="manager_div" style="display: none;">
                                            <span style="color:red;"></span><label>Report To</label>
                                                <select class="form-control" name="report_to">
                                                    <option value="">Select Role</option>
                                                    @foreach($employee as $e)
                                                    <option value="{{ $e->empid }}">{{ $e->name }}</option>
                                                    @endforeach
                                                </select>
                                        </div>
                                        
                                        <div class="col-lg-6 col-md-4">
                                            <div>
                                                <span style="color:red;">*</span><label>User Name</label>
                                                <input type="text" id="username" name="username" placeholder="Enter Username" onkeyup="validateUsername()" class="form-control" required/>
                                            </div>
                                            <p id="usernameError" style="color:red;"></p>

                                             
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <span style="color:red;">*</span><label>Password</label>
                                            <input type="password" class="form-control"
                                                placeholder="Enter Password" name="password" id="password" autocomplete="off"   minlength="4" maxlength="20" required>
                                        </div>
                                    <div class="card-footer mt-5" style="text-align: right;">
                                        <button type="submit" class="btn btn-primary btn-user float-right mb-3 mx-2">Save</button>
                                        <a class="btn btn-primary float-right mr-3 mb-3 mx-2" href="{{ route('employee.index') }}">Cancel </a>
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

@section('scripts')
<script>
function validateUsername() {
    const username = document.getElementById('username').value;
    const regex = /^[a-zA-Z0-9_]{3,20}$/;
    const errorMsg = document.getElementById('usernameError');

    if (!regex.test(username)) {
        errorMsg.textContent = 'Username must be 3-20 characters and can only include letters, numbers, and underscores.';
    } else {
        errorMsg.textContent = ''; // Clear error message if valid
    }
}
$(document).ready(function() {
    $('#role_id').change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == 2) 
        {
            // Show the div and make the input required
            $('#manager_div').show();
            $('#report_to').prop('required', true);
        } else {
            // Hide the div and remove the required attribute from the input
            $('#manager_div').hide();
            $('#report_to').prop('required', false);
        }
    });
});

</script>
@endsection
