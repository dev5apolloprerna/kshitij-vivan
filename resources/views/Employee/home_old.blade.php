@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">


        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col">

                        <div class="h-100">
                            <div class="row mb-3 pb-1">
                                <div class="col-12">
                                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                            
                                    </div><!-- end card header -->
                                </div>
                                <!--end col-->
                            </div>
                            <!--end row-->

                            <div class="row">
                                    @include('common.alert')
                                    <div id="live-working-time1" class="digital-clock"></div>
                                    <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <form action="{{route('empattendance.emp_start_day')}}" method="post">
                                        @csrf
                                        <button type="submit" class="card card-animate bg-primary text-white"
                                                style="border: none; width: 100%; padding: 0;">
                                        <!--<div class="card card-animate bg-primary">-->
                                            <div class="card-body">
                                                    <input type="hidden" name="start_latitude" id="latitude">
                                                    <input type="hidden" name="start_longitude" id="longitude">
                                                    <input type="hidden" name="start_address" id="address">

                                                <div class="d-flex align-items-end justify-content-between mt-4">
                                                    <div style="width: 170px">
                                                            <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                                                            <span class="counter-value"
                                                                data-target="">Start Day</span>
                                                        </h4>
                                                    </div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-soft-light rounded fs-3">
                                                            <i class="fa-solid fa-calendar-day"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        <!--</div>  -->
                                        </button>
                                    </form>                                 
                                </div>

                                <div class="col-xl-3 col-md-6">
                                    <!-- card -->
                                    <form action="{{route('empattendance.end_day')}}" method="post">
                                        @csrf
                                        <button type="submit" class="card card-animate bg-primary text-white"
                                                style="border: none; width: 100%; padding: 0;">
                                        <!--<div class="card card-animate bg-primary">-->
                                            <div class="card-body">
                                                    <input type="hidden" name="end_latitude" id="endlatitude">
                                                    <input type="hidden" name="end_longitude" id="endlongitude">
                                                    <input type="hidden" name="end_address" id="endaddress">

                                                <div class="d-flex align-items-end justify-content-between mt-4">
                                                    <div style="width: 170px">
                                                            <h4 class="fs-22 fw-bold ff-secondary text-white mb-4">
                                                            <span class="counter-value"
                                                                data-target="">End Day</span>
                                                        </h4>
                                                    </div>
                                                    <div class="avatar-sm flex-shrink-0">
                                                        <span class="avatar-title bg-soft-light rounded fs-3">
                                                            <i class="fas fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <!--</div>  -->
                                        </button>
                                    </form>                                 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>
                            document.write(new Date().getFullYear())
                        </script> © {{ env('APP_NAME') }}
                    </div>

                </div>
            </div>
        </footer>
    </div>
    <!-- end main content-->


@endsection
@section('scripts')
<script type="text/javascript">
        if (navigator.geolocation) {
        // Get the current position
        navigator.geolocation.getCurrentPosition(function(position) {
            // Extract latitude and longitude
            let lat = position.coords.latitude;
            let long = position.coords.longitude;

            // Send data to the backend via AJAX or form submission
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = long;
            document.getElementById('endlatitude').value = lat;
            document.getElementById('endlongitude').value = long;

            getAddressFromCoordinates(lat, long);

            // Optionally auto-submit the form or use AJAX
            // document.getElementById('locationForm').submit();
        }, function(error) {
            console.error("Error getting location: ", error);
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }

    function getAddressFromCoordinates(lat, long) 
    {
        // Replace YOUR_API_KEY with your actual Google Maps API key
        let geocodeAPI = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${long}&key=AIzaSyDJDm56GxJQyzh8fa7dmsdEA1CVPeZBno8`;
        // Fetch the address using the API
        fetch(geocodeAPI)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'OK' && data.results.length > 0) {
                let address = data.results[0].formatted_address;

                // Set address in a hidden input or display it
                document.getElementById('address').value = address;
                document.getElementById('endaddress').value = address;
            } else {
                console.error('No address found for these coordinates.');
            }
        })
        .catch(error => console.error('Error in reverse geocoding: ', error));
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetch('/employee/get-live-work-time-data')
            .then(response => response.json())
            .then(data => {
                const defaultMessage = '<div class="alert alert-danger alert-dismissible alert-solid alert-label-icon fade show" style="width: 23%;" role="alert" id="error-alert"><i class="ri-error-warning-line label-icon"></i><strong>Error !</strong> Please Start Your Day</div>';
                const dayStartMessage = '<div class="alert alert-primary alert-dismissible alert-solid alert-label-icon fade show" style="width: 23%;" role="alert" id="success-alert"><i class="ri-user-smile-line label-icon"></i><strong>Success !</strong> Day started.</div>';
                const dayEndMessage = '<div class="alert alert-primary alert-dismissible alert-solid alert-label-icon fade show" style="width: 23%;" role="alert" id="success-alert"><i class="ri-user-smile-line label-icon"></i><strong>Success !</strong> Day ended.</div>';
    
                const liveWorkingTimeElement = document.getElementById('live-working-time1');
    
                if (data.error || !data.start_date_time) {
                    console.error(data.error || 'No data found');
                    liveWorkingTimeElement.innerHTML = defaultMessage;
                    return;
                }
    
                // Check if the day has ended
                if (data.day_ended) {
                    liveWorkingTimeElement.innerHTML = dayEndMessage;
                } else {
                    liveWorkingTimeElement.innerHTML = dayStartMessage;
                }
            })
            .catch(error => {
                console.error('Error fetching working time data:', error);
                const defaultMessage = '<div class="alert alert-danger alert-dismissible alert-solid alert-label-icon fade show" role="alert"><i class="ri-error-warning-line label-icon"></i><strong>Error !</strong> Unable to fetch attendance data.</div>';
                document.getElementById('live-working-time1').innerHTML = defaultMessage;
            });
    });
</script>
@endsection