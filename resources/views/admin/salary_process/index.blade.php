@extends('layouts.app')
@section('title', 'Process Salary')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

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
                                <h5 class="card-title mb-0">Process Salary</h5>
                            </div>
                            <div class="card-body">
                                <!-- Nav tabs -->
                                <div class="container-fluid">
                                    <!-- Page Heading -->
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- 'qu code uniqur id , name , guid' -->
                                            <form id="filter-form">
                                                <div class="row  align-items-center">
                                                    <div class="col-md-3  mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <select class="form-control" id="month" name="month">
                                                                <option value="">Select Month</option>
                                                                <option value="1" {{ $month == 1 ? 'selected' : '' }}>January</option>
                                                                <option value='2' {{ $month == 2 ? 'selected' : '' }}>February</option>
                                                                <option value='3' {{ $month == 3 ? 'selected' : '' }}>March</option>
                                                                <option value='4' {{ $month == 4 ? 'selected' : '' }}>April</option>
                                                                <option value='5' {{ $month == 5 ? 'selected' : '' }}>May</option>
                                                                <option value='6' {{ $month == 6 ? 'selected' : '' }}>June</option>
                                                                <option value='7' {{ $month == 7 ? 'selected' : '' }}>July</option>
                                                                <option value='8' {{ $month == 8 ? 'selected' : '' }}>August</option>
                                                                <option value='9' {{ $month == 9 ? 'selected' : '' }}>September</option>
                                                                <option value='10' {{ $month == 10 ? 'selected' : '' }}>October</option>
                                                                <option value='11' {{ $month == 11 ? 'selected' : '' }}>November</option>
                                                                <option value='12' {{ $month == 12 ? 'selected' : '' }}>December</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3  mb-2">
                                                        <div class="d-flex align-items-center">
                                                           <select name="year" id="year" class="form-select" required>
                                                                <option value="">Select Year</option>
                                                                @for ($i = date('Y'); $i >= 2024; $i--)
                                                                    <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                                @endfor
                                                            </select>
                                                        </div>
                                                    </div>  
                                                    <div class="col-md-4  mb-2">
                                                        <div class="input-group d-flex justify-content-right">
                                                            <!-- <button type="button" id="search" class="btn btn-primary mx-2">
                                                                Search
                                                            </button>  -->
                                                            <button type="button" id="createsalary" class="btn btn-primary mx-2">
                                                                Create
                                                            </button>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="message-container" style="display: none; padding: 10px; margin-bottom: 10px;"></div>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content text-muted">
                                    <div class="tab-pane active" id="PendingOrder" role="tabpanel">
                                        <div class="row" id="salary_data">
                                             <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                                <thead>
                                
                                                    <tr>
                                                        <th class="all">Sr.No</th>
                                                        <th class="all">Month</th>
                                                        <th class="all">Year</th>
                                                        <th class="all">Action</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $srNo = 1; 
                                                    $Total = 0;
                                                    ?>
                                
                                                    @foreach($missingMonths as $data)
                                                    <!--@if($data['confirm_date'] == null)-->
                                                        <tr>
                                                        <td>{{ $srNo++ }}</td>
                                                        </td>
                                                        <td>{{ date("F", mktime(0, 0, 0, (int) $data['month'], 1)) }}</td> <!-- Convert month number to name -->
                                                        <td>{{ $data['year'] }}</td>
                                                         <td>
                                                             @if($data['is_process'] == 1 && $data['confirm_date'] == null)
                                                             <a class="" href="{{route('salary_process.view',$data['sid'])}}" 
                                                                title="View"><span class="btn btn-primary btn-sm mx-1">Edit</span>
                                                            </a>
                                                            <input type="hidden" id="salaryId" value="{{ $data['sid'] }}">
                                                            <button type="button" id="Regenerate" onclick="Regenerate('{{ $data['sid'] ?? 'N/A' }}', '{{ $data['month'] }}', '{{ $data['year'] }}')" class="btn btn-primary btn-sm mx-1"> 
                                                                Regenerate
                                                            </button>
                                                            <button onclick="deleteData()" type="button" class="btn btn-primary btn-sm mx-2"> 
                                                                Delete
                                                            </button>
                                
                                                            <button type="button" id="confirm" onclick="confirmSalary(<?= $data['sid'] ?>)" class="btn btn-primary btn-sm mx-1"> 
                                                                Confirm Salary
                                                            </button>
                                                             <button onclick="genrateToexcel('{{ $data['month'] }}', '{{ $data['year'] }}')" type="button" class="btn btn-primary btn-sm mx-2"> 
                                                                Export to Excel
                                                            </button>
                                                           
                                                             @elseif($data['sid'] != null )
                                                            <a title="Process Salary" href="#" data-bs-toggle="modal" data-bs-target="#ConfirmationModal" class="btn btn-primary btn-sm mx-1"         
                                                            onclick="Data('{{ $data['sid'] ?? 'N/A' }}', '{{ $data['month'] }}', '{{ $data['year'] }}')">
                                                            process
                                                            </a>
                                                            @else
                                                                <form action="{{route('salary_process.create_salary')}}" method="post">
                                                                    @csrf
                                                                    <input type="hidden" name="month" value="{{ $data['month'] }}">
                                                                    <input type="hidden" name="year" value="{{ $data['year'] }}">
                                                                    <input type="hidden"  name="directcreate" value="1">
                                                                    <button type="submit"  class="btn btn-primary mx-2 btn-sm">Create</button>
                                                                </form>
                                                            @endif
                                                        </td>   
                                                        <!--@endif-->
                                                        @endforeach             
                                                     </tr>
                                                </tbody>
                                
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!------clear device token ------>
                <div class="modal fade zoomIn" id="ConfirmationModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="btn-close"></button>
                            </div>

                            <div class="modal-body">
                                <div class="mt-2 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop"
                                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                                    </lord-icon>

                                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                        <h4>Are you Sure ?</h4>
                                        <p class="text-muted mx-4 mb-0">Are you Sure You want to Process Salary ?</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                 <form id="filter-form">
                                    <input type="hidden" name="salaryId" id="salaryId">
                                    <input type="hidden" name="month" id="smonth">
                                    <input type="hidden" name="year" id="syear">
                                    <button type="submit" class="btn btn-primary mx-2"
                                    id="confirmSubmit">Submit</button>
                                <button type="button" class="btn btn-primary mx-2"
                                    data-bs-dismiss="modal">Cancel</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!------clear device token ------>
@endsection

@section('scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script>
$('#filter-form').on('submit', function(e) {
    e.preventDefault();
    //alert('Are You Sure You Want to Process This Salary ?')
    $('#ConfirmationModal').modal('show');
});
$('#search').on('click', function (e) 
{
    let month= $('#month').val();
    let year= $('#year').val();

    // AJAX request if all fields are valid
    $.ajax({
        url: 'create_salary',
        type: 'POST',
        data: {
            month: month,
            year: year,
            search: 1,
            _token: '{{ csrf_token() }}' // CSRF token for Laravel
        },
        success: function (response) {
                $('#salary_data').html(response);
            
        }
    });
});
$('#createsalary').on('click', function (e) 
{ 
     e.preventDefault(); // Prevent default form submission

    // Clear previous messages
    let messageContainer = $('#message-container');
    messageContainer.hide();

    let month= $('#month').val();
    let year= $('#year').val();
    
    // AJAX request if all fields are valid
    $.ajax({
        url: 'create_salary',
        type: 'POST',
        data: {
            month: month,
            year: year,
            create:1,
            _token: '{{ csrf_token() }}' // CSRF token for Laravel
        },
        success: function (response) {
                $('#salary_data').html(response);
                messageContainer.show(); // Hide the message on success
            
        },
        error: function (xhr) {
            let errorMessages = '<ul>';
            if (xhr.status === 422) { // Laravel validation error
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    errorMessages += `<li>${value[0]}</li>`;
                });
                errorMessages += '</ul>';
                messageContainer.html(errorMessages)
                    .css({ 'color': 'red', 'background': '#ffdddd', 'border': '1px solid red' })
                    .fadeIn();
            } else {
                messageContainer.html('Error: ' + xhr.responseText)
                    .css({ 'color': 'red', 'background': '#ffdddd', 'border': '1px solid red' })
                    .fadeIn();
            }
        }
    });

});
$('#confirmSubmit').on('click', function (e) 
{ 
    e.preventDefault(); // Prevent default form submission

    // Clear previous messages
    let messageContainer = $('#message-container');
    messageContainer.hide();

let month= $('#smonth').val();
let year= $('#syear').val();
let salaryId= $('#salaryId').val();

    // AJAX request if all fields are valid
    $.ajax({
        url: 'process_salary',
        type: 'POST',
        data: {
            month: month,
            year: year,
            salaryId: salaryId,
            is_process: 1,
            is_attendace: 1,
            create:1,
            _token: '{{ csrf_token() }}' // CSRF token for Laravel
        },
        success: function (response) {
            if (response == 1) {
                messageContainer.html('Please insert the salary of all employees.')
                    .css({ 'color': 'red', 'background': '#ffdddd', 'border': '1px solid red' })
                    .fadeIn();
            }else if (response == 2) 
            {
                messageContainer.html('Salary Alredy Process.')
                    .css({ 'color': 'red', 'background': '#ffdddd', 'border': '1px solid red' })
                    .fadeIn();
            } else {
                $('#salary_data').html(response);
                messageContainer.show(); // Hide the message on success
            }
        },
        error: function (xhr) {
            let errorMessages = '<ul>';
            if (xhr.status === 422) { // Laravel validation error
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    errorMessages += `<li>${value[0]}</li>`;
                });
                errorMessages += '</ul>';
                messageContainer.html(errorMessages)
                    .css({ 'color': 'red', 'background': '#ffdddd', 'border': '1px solid red' })
                    .fadeIn();
            } else {
                messageContainer.html('Error: ' + xhr.responseText)
                    .css({ 'color': 'red', 'background': '#ffdddd', 'border': '1px solid red' })
                    .fadeIn();
            }
        }
    });

    $('#ConfirmationModal').modal('hide'); // Close modal after submission
});

function Regenerate(salaryId, month = null, year = null) 
{
    event.preventDefault(); // Prevent default form submission

    // Clear previous messages
    let messageContainer = $('#message-container');
    messageContainer.hide();

    if (month !== null) {
        $('#month').val(month);
    }

    if (year !== null) {
        $('#year').val(year);
    }

    let selectedMonth = $('#month').val();
    let selectedYear = $('#year').val();

    // AJAX request if all fields are valid
    $.ajax({
        url: 'ReGenerate',
        type: 'POST',
        data: {
            month: selectedMonth,
            year: selectedYear,
            salaryId: salaryId,
            is_process: 1,
            is_attendace: 1,
            _token: '{{ csrf_token() }}' // CSRF token for Laravel
        },
        success: function (response) {
            if (response == 1) {
                messageContainer.html('Please insert the salary of all employees.')
                    .css({ 'color': 'red', 'background': '#ffdddd', 'border': '1px solid red' })
                    .fadeIn();
            } else {
                $('#salary_data').html(response);
                messageContainer.show(); // Show the message on success
            }
        },
        error: function (xhr) {
            let errorMessages = '<ul>';
            if (xhr.status === 422) { // Laravel validation error
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    errorMessages += `<li>${value[0]}</li>`;
                });
                errorMessages += '</ul>';
                messageContainer.html(errorMessages)
                    .css({ 'color': 'red', 'background': '#ffdddd', 'border': '1px solid red' })
                    .fadeIn();
            } else {
                messageContainer.html('Error: ' + xhr.responseText)
                    .css({ 'color': 'red', 'background': '#ffdddd', 'border': '1px solid red' })
                    .fadeIn();
            }
        }
    });
}


function deleteData() {
 //let baseUrl = window.location.origin+"/attendance_system"; // Gets "http://127.0.0.1:8000"
 let baseUrl = window.location.origin; // Gets "http://127.0.0.1:8000"

    let month = $('#month').val();  // Get selected month
    let year = $('#year').val();    // Get selected year

    if (!month || !year) {
        alert('Please select a valid month and year.');
        return;
    }

    if (!confirm('Are you sure you want to delete all records for ' + month + ' ' + year + '?')) {
        return;
    }

    $.ajax({
        url: baseUrl +`/admin/salary_process/delete-salary/${month}/${year}`,  // Pass month and year in URL
        type: 'DELETE',  // DELETE request
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
        },
        success: function(response) {
            alert(response.message);
            location.reload();  // Reload to update records
        },
        error: function(xhr) {
            alert('Failed to delete salary records: ' + xhr.responseJSON.message);
        }
    });
}

function confirmSalary(id) 
{

 //let baseUrl = window.location.origin+"/attendance_system"; // Gets "http://127.0.0.1:8000"
 let baseUrl = window.location.origin; // Gets "http://127.0.0.1:8000"

 $.ajax({
        url: baseUrl +`/admin/salary_process/confirm-salary/${id}`,  // Pass month and year in URL
        type: 'get',  // DELETE request
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
        },
        success: function(response) {
            alert(response.message);
            location.reload();  // Reload to update records
        },
        error: function(xhr) {
            alert('Failed to Confirm salary records: ' + xhr.responseJSON.message);
        }
    });
}
function Data(id,month,year)
{
    
    $('#salaryId').val(id);
    $('#smonth').val(month);
    $('#syear').val(year);
}
   function genrateToexcel(getmonth = null,getyear = null)
    {
        if (getmonth !== null) {
            $('#month').val(getmonth);
        }
    
        if (getyear !== null) {
            $('#year').val(getyear);
        }

        let month = $('#month').val();
        let year = $('#year').val();

        var Url = "{{route('salary_processed.export_salary',[":month",":year"])}}";
        Url = Url.replace(':month', month);
        Url = Url.replace(':year', year);
        window.location.href = Url;
    }
    </script>
@endsection
