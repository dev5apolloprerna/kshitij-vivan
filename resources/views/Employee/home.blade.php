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
                                        <div id="loaderText" style="display:none;">
                                            <span class="text-white">Fetching location...</span>
                                        </div>
                                        <button type="submit"  id="startDayBtn"   class="card card-animate bg-primary text-white"
                                                style="border: none; width: 100%; padding: 0;" >
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
<!-- Fullscreen loader -->
<!-- Fullscreen loader -->
<div id="geoLoader" style="
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background-color: rgba(255, 255, 255, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    font-size: 1.2rem;
    color: #333;
">
    <div>
        <div class="spinner-border text-primary mb-3" role="status"></div>
        <div>Fetching your location, please wait...</div>
    </div>
</div>


@endsection
@section('scripts')
<script type="text/javascript">
document.addEventListener('DOMContentLoaded', () => {
    // Show loader when page loads
    const loader = document.getElementById('geoLoader');
    loader.style.display = 'flex';

    // Attempt to get location when page loads
    getUserLocation();

    // Fetch and display live work time info
    fetch('/employee/get-live-work-time-data')
        .then(response => response.json())
        .then(data => {
            const defaultMessage = '<div class="alert alert-danger alert-dismissible alert-solid alert-label-icon fade show" style="width: 23%;" role="alert"><i class="ri-error-warning-line label-icon"></i><strong>Error !</strong> Please Start Your Day</div>';
            const dayStartMessage = '<div class="alert alert-primary alert-dismissible alert-solid alert-label-icon fade show" style="width: 23%;" role="alert"><i class="ri-user-smile-line label-icon"></i><strong>Success !</strong> Day started.</div>';
            const dayEndMessage = '<div class="alert alert-primary alert-dismissible alert-solid alert-label-icon fade show" style="width: 23%;" role="alert"><i class="ri-user-smile-line label-icon"></i><strong>Success !</strong> Day ended.</div>';

            const liveWorkingTimeElement = document.getElementById('live-working-time1');

            if (data.error || !data.start_date_time) {
                liveWorkingTimeElement.innerHTML = defaultMessage;
                return;
            }

            liveWorkingTimeElement.innerHTML = data.day_ended ? dayEndMessage : dayStartMessage;
        })
        .catch(error => {
            console.error('Error fetching working time data:', error);
            document.getElementById('live-working-time1').innerHTML =
                '<div class="alert alert-danger alert-dismissible alert-solid alert-label-icon fade show" role="alert"><i class="ri-error-warning-line label-icon"></i><strong>Error !</strong> Unable to fetch attendance data.</div>';
        });
});

function getUserLocation() {
    const loader = document.getElementById('geoLoader');

    if (!navigator.geolocation) {
        alert("Geolocation is not supported by your browser.");
        loader.style.display = 'none';
        return;
    }

    navigator.geolocation.getCurrentPosition(
        function (position) {
            let lat = position.coords.latitude;
            let long = position.coords.longitude;

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = long;
            document.getElementById('endlatitude').value = lat;
            document.getElementById('endlongitude').value = long;

            getAddressFromCoordinates(lat, long, () => {
                // Hide loader after address is fetched
                loader.style.display = 'none';
            });
        },
        function (error) {
            console.error("Location error:", error);
            loader.style.display = 'none';
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("Location permission denied. Please allow location access.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Location unavailable.");
                    break;
                case error.TIMEOUT:
                    alert("Location request timed out.");
                    break;
                default:
                    alert("Unknown location error.");
            }
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}

function getAddressFromCoordinates(lat, long, callback) {
    const apiKey = "AIzaSyDJDm56GxJQyzh8fa7dmsdEA1CVPeZBno8"; // Replace with your actual API key
    const geocodeAPI = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${long}&key=${apiKey}`;

    fetch(geocodeAPI)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'OK' && data.results.length > 0) {
                const address = data.results[0].formatted_address;
                document.getElementById('address').value = address;
                document.getElementById('endaddress').value = address;
            } else {
                console.error("No address found.");
            }
            if (callback) callback();
        })
        .catch(error => {
            console.error("Error during reverse geocoding:", error);
            if (callback) callback();
        });
}
</script>
@endsection