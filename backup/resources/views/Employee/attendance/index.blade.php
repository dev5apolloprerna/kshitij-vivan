@extends('layouts.app')

@section('title', 'Attendance')

@section('content')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css' rel='stylesheet' />
<div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-xxl-12">
                        <h5 class="mb-3"></h5>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Monthly Report</h5>
                            </div>
                            <div class="card-body">
                                <!-- Nav tabs -->

                                <div class="container-fluid">
                                    <!-- Page Heading -->
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- 'qu code uniqur id , name , guid' -->
                                            <form id="filter-form">
                                                @csrf
                                                <div class="row  align-items-center">
                                                    <div class="col-md-3  mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <input placeholder="Enter From Date" type="date"
                                                                class="form-control" id="start_date" name="start_date"
                                                                autocomplete="off"
                                                                value="<?= isset($FromDate) ? $FromDate : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3  mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <input placeholder="Enter To Date" type="date"
                                                                class="form-control" name="end_date" autocomplete="off"
                                                                id="end_date"
                                                                value="<?= isset($ToDate) ? $ToDate : '' ?>">
                                                        </div>
                                                    </div>  
                                                
                                                    <div class="col-md-4  mb-2">
                                                        <div class="input-group d-flex justify-content-right">
                                                            <button type="submit" class="btn btn-primary mx-2">
                                                                Search
                                                            </button>
                                                            <a class="btn btn-primary mx-2" href="{{ route('attendance.index') }}">
                                                                Reset
                                                            </a>
                                                            <!-- <button onclick="genrateToexcel()" type="button" class="btn btn-primary btn-sm mx-2"> 
                                                                Export to Excel
                                                            </button> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                    <div id="calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal for marking attendance -->
<div class="modal fade zoomIn" id="attendanceModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="attendanceModalLabel">Mark Attendance</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
      </div>
        <div class="modal-body">
        <form id="attendance-form">
            <input type="hidden" id="attendance_date" name="attendance_date">
            <input type="hidden" id="attendance_empid" name="empid">
            
            <div class="form-group">
                <label for="attendance_status">Attendance Status:</label>
                <select id="attendance_status" name="attendance_status" class="form-control" required>
                    <option value="" disabled selected>Select Status</option>
                    <option value="3">Absent</option>
                    <option value="1">Present</option>
                    <option value="2">Half-day</option>
                </select>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="save-attendance-btn">Save Attendance</button>
    </div>
   </div>
  </div>
</div>

@endsection


    <!-- jQuery, FullCalendar, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js'></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


@section('scripts')
    <script>
  $(document).ready(function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: function (fetchInfo, successCallback, failureCallback) {
            var start_date = fetchInfo.startStr;
            var end_date = fetchInfo.endStr;

            var search_start_date = $('#start_date').val();
            var search_end_date = $('#end_date').val();

            if (search_start_date && search_end_date) {
                start_date = search_start_date;
                end_date = search_end_date;
            }

            if (!start_date || !end_date) {
                successCallback([]);
                return;
            }

            $.ajax({
                url: '{{ route('empattendance.events') }}',
                method: 'GET',
                data: { start: start_date, end: end_date },
                success: function (data) {
                    var events = data.map(function (item) {
                        if (item.title && item.allDay) {
                            // Holiday Event
                            return {
                                title: item.title,
                                start: item.start,
                                allDay: true,
                                backgroundColor: item.backgroundColor,
                                borderColor: item.borderColor,
                                textColor: item.textColor
                            };
                        } else {
                            // Attendance Event
                            var days;
                            var backgroundColor = ''; // Default color

                            if (item.day == 1 && item.role_id == 0) 
                            {
                                days = "P";
                            }else if (item.day == 1 && item.role_id == 1) 
                            {
                                days = "P";
                                backgroundColor = 'lightseagreen'; // Green
                            }else if (item.day == 2) {
                                days = "H";
                                backgroundColor = '#008000'; // Green
                            } else if (item.day == 3) 
                            {
                                days = "Absent";
                                backgroundColor = 'orange'; // Yellow

                            } else if(item.day== null || item.end == null)
                            {
                                days = "A";
                                backgroundColor = 'orange'; // Yellow
                            }else{
                                days="L";
                            }
                        
                            var startDatee = new Date(item.start);
                            var endDatee = item.end ? new Date(item.end) : null;
                            
                            var startTime = startDatee.toLocaleTimeString('en-IN', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            });
                            
                            var endTime = endDatee ? endDatee.toLocaleTimeString('en-IN', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: false
                            }) : "-"; // Display "-" if end time is missing
                            
                            return {
                                title: days + ' : ' + startTime + ' - ' + endTime,
                                start: item.start,
                                end: item.end,
                                backgroundColor: backgroundColor, // Assign color
                                borderColor: backgroundColor, // Border color same as background
                                textColor: '#FFFFFF', // White text for better visibility
                                allDay: true,
                                extendedProps: {
                                    tooltip: days + ' : ' + startTime + ' - ' + endTime,
                                    comment: item.comment || ''
                                }
                            };
                        
                        }
                    });
                    successCallback(events);
                },
                error: function () {
                    failureCallback();
                }
            });
        },
        eventDidMount: function (info) {
            // Native browser tooltip
            if (info.event.extendedProps.tooltip) {
                info.el.setAttribute('title', info.event.extendedProps.tooltip);
            }

            // Append comment below the title
            if (info.event.extendedProps.comment) {
                const commentEl = document.createElement('div');
                commentEl.innerText = info.event.extendedProps.comment;
                commentEl.style.fontSize = '0.75em';
                commentEl.style.color = '#ffffff';
                commentEl.style.marginTop = '2px';
                info.el.appendChild(commentEl);
            }
        },
        dayCellDidMount: function(info) {
            var date = new Date(info.date);
            if (date.getDay() === 0) 
            { // 0 means Sunday
                info.el.innerHTML = `            
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%;"><h5 style="color: red;">Sunday</h5></div>`;
            }
        }
    });

    calendar.render();

        $('#filter-form').on('submit', function (e) {
        e.preventDefault();
        var startDate = $('#start_date').val();
        if (startDate) {
            calendar.gotoDate(startDate); // Navigate to the selected start date
        }
        calendar.refetchEvents();
    });

});

    function genrateToexcel()
    {
        var fromdate = $('#enddatepicker').val();
        var todate = $('#enddatepicker').val();
        var Url = "{{route('empreport.export_monthly',[":fromdate",":todate"])}}";
        Url = Url.replace(':fromdate', fromdate);
        Url = Url.replace(':todate', todate);
        window.location.href = Url;
    }
    </script>
@endsection