@extends('layouts.app')

@section('title', 'Salary Detail')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')
                <div id="message-container" style="display: none; padding: 10px; margin-bottom: 10px;"></div>

                <div class="row">
                    <div class="col-xxl-12">
                        <h5 class="mb-3"></h5>
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h5 class="card-title mb-0">Salary Detail</h5>
                                    <div class="page-title-right">
                                        <a href="{{ route('salary_process.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Back</a>
                                    </div>

                            </div>
                            <div id="message-container" style="display: none; padding: 10px; margin-bottom: 10px;"></div>
 
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="table-responsive">

                                                <table id="scroll-horizontal" class="table nowrap align-middle"
                                                style="width:100%">
                                                <thead>

                                                 <tr>
                                                     <th class="all" width="2%">Sr.No</th>
                                                     <th class="all" >Emp Id</th>
                                                     <th class="all" >Emp Name</th> 
                                                     <th class="all" >Month</th>
                                                     <th class="all" >Year</th>
                                                     <th class="all" >Basic Salary</th>
                                                     <th class="all" >Net Pay</th>
                                                     <th class="all" >Accumlated</th>
                                                     <th class="all" >Used</th>
                                                     <th class="all" >Leave Taken</th>
                                                     <th class="all" >Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $i = 1;
                                                    $Total = 0;
                                                    ?>

                                                    @foreach ($salaryData as $salary)
                                                        <tr>
                                                        <td>
                                                            {{ $i + $salaryData->perPage() * ($salaryData->currentPage() - 1) }}
                                                        </td>
                                                         <td>{{ $salary->employeeId }}</td>
                                                        <td>{{ $salary->employeeName }}</td> 
                                                        <td>{{ date("F", mktime(0, 0, 0, $salary->salary_month, 10)) }}</td>
                                                        <td>{{ $salary->salary_year ?? '-' }}</td>
                                                         <td>{{ $salary->basic_salary  ?? '-' }}</td>
                                                        <td>{{ $salary->net_pay  ?? '-' }}</td>
                                                        <td>{{ $salary->Accumlated  ?? '-' }}</td>
                                                        <td>{{ $salary->Used  ?? '-' }}</td>
                                                        <td>{{ $salary->Leave_taken  ?? '-' }}</td>
                                                        <td>
                                                            <a class="mx-1" title="View" href="#"
                                                                onclick="viewData(<?= $salary->sDetailId ?>)"
                                                                data-bs-toggle="modal" data-bs-target="#ViewModal">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </a>  
                                                                <a class="mx-1" target="_blank"
                                                                    href="{{ route('salary_process.pdf', $salary->sDetailId) }}"
                                                                    title="Pdf Details">
                                                                    <i class="fa-solid fa-file-pdf fa-lg"></i>
                                                                </a>  
                                                            @if($salary->confirm_date == null)     
                                                             <a class="mx-1" title="Update Salry Details" href="#"
                                                                    onclick="editData(<?= $salary->sDetailId ?>)"
                                                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                                                    <i class="fa-solid fa-edit"></i>
                                                                </a> 
                                                                <button type="button" id="RegenerateEMP" onclick="RegenerateEMP(<?= $salary->sDetailId ?>)" class="btn btn-primary btn-sm mx-1"> 
                                                                    Regenerete
                                                                </button>
                                                               <!--  <a class="mx-1" title="Update Leave" href="#"
                                                                    onclick="editleaveData(<?= $salary->sDetailId ?>)"
                                                                    data-bs-toggle="modal" data-bs-target="#editleaveModal">
                                                                    <i class="fa-solid fa-plus"></i>
                                                                </a> -->
                                                                @endif
                                                        </td>
                                                     </tr>
                                                        <?php $i++; ?>
                                                    @endforeach
                                                </tbody>

                                            </table>
                                            <div class="d-flex justify-content-center mt-3">
                                                {{ $salaryData->appends(request()->except('page'))->links() }}
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
    </div>

        <div class="modal fade flip" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" style="overflow-y: auto;"> 
                    <div class="modal-header bg-light p-3">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Salary Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="close-modal"></button>
                    </div>

                    <form  method="post" action="{{route('salary_process.updateSalaryDetailData')}}">
                        @csrf
                        <input type="hidden" name="sid" id="sid" value="">
                        <input type="hidden" name="month" id="editmonth" value="">
                        <input type="hidden" name="year" id="edityear" value="">
                        <div class="modal-body">
                            <div class="row">

                            <!-- <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Center
                                <input type="text" class="form-control" name="center" id="center"
                                    placeholder="Enter Center" maxlength="50" autocomplete="off" required>
                            </div> -->
                            
                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Incentive
                                <input type="text" class="form-control" name="Incentive" id="Incentive" required>
                            </div>

                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Bonus
                                <input type="text" class="form-control" name="Bonus" id="Bonus" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Others
                                <input type="text" class="form-control" name="Others" id="Others" required>
                            </div>

                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>WDIM
                                <input type="text" class="form-control" name="WDIM" id="WDIM" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>HDIM
                                <input type="text" class="form-control" name="HDIM" id="HDIM" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>PT
                                <input type="text" class="form-control" name="PT" id="PT" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>TDS
                                <input type="text" class="form-control" name="TDS" id="TDS" required>
                            </div>
                             <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Loan_Advance
                                <input type="text" class="form-control" name="Loan_Advance" id="Loan_Advance" required>
                            </div>
                             <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Rem
                                <input type="text" class="form-control" name="Rem" id="Rem" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Accumulated
                                <input type="text" class="form-control" name="Accumlated" id="accumulatedLeave"
                                    placeholder="Enter Accumulated Leabe" maxlength="50" autocomplete="off" readonly>
                            </div>
                            
                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Used Leave
                                <input type="text" class="form-control" name="Used" id="usedLeave" readonly>
                            </div>

                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Leave Taken
                                <input type="text" class="form-control" name="Leave_taken" id="leaveTaken" readonly>
                            </div>
                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Total Full Day
                                <input type="text" class="form-control" name="total_absent" id="total_absent" required>
                            </div>
                            <div class="mb-3 col-md-4">
                                <span style="color:red;">*</span>Total Half Day
                                <input type="text" class="form-control" name="total_half_day" id="total_half_day" required>
                            </div>

                        </div>
                        </div>
                        <div class="modal-footer">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="submit" class="btn btn-primary mx-2"
                                    id="updateSubmit1">Submit</button>
                                <button type="button" class="btn btn-primary mx-2"
                                    data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--Edit Modal End -->

       
<div class="modal fade flip" id="ViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title"> Salary & Leave Details </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                <div class="row">
                    <div class="col-lg-12">
                        <table width="100%" class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td class="fw-bold" style="width: 30%; padding: 5px;"></td>
                                    <td class="text-center" id="monthYear"></td>
                                </tr><!-- 
                                <tr>
                                    <td class="text-end fw-bold" style="width: 30%; padding: 5px;">Employee ID:</td>
                                    <td class="text-center" id="empId"></td>
                                </tr> -->
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Employee Name:</td>
                                    <td class="text-center" id="empName"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Basic Salary:</td>
                                    <td class="text-center" id="vbasicSalary"></td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Incentive:</td>
                                    <td class="text-center" id="vIncentive"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Bonus:</td>
                                    <td class="text-center" id="vBonus"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Others:</td>
                                    <td class="text-center" id="vOthers"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Total A:</td>
                                    <td class="text-center" id="vTotal_A"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">WDIM:</td>
                                    <td class="text-center" id="vWDIM"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">HDIM:</td>
                                    <td class="text-center" id="vHDIM"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Leave Ded:</td>
                                    <td class="text-center" id="vLeave_ded"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">PT:</td>
                                    <td class="text-center" id="vPT"></td>
                                </tr>
                                 <tr>
                                    <td class="fw-bold" style="padding: 5px;">TDS:</td>
                                    <td class="text-center" id="vTDS"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Loan/Advance:</td>
                                    <td class="text-center" id="vLoan_Advance"></td>
                                </tr>
                                 <tr>
                                    <td class="fw-bold" style="padding: 5px;">Total B:</td>
                                    <td class="text-center" id="vTotal_B"></td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Accumulated Leave:</td>
                                    <td class="text-center" id="vaccumulatedLeave"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Full Day Leave:</td>
                                    <td class="text-center" id="vtotal_absent"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Balance CF:</td>
                                    <td class="text-center" id="vRem"></td>
                                </tr>
                                <!--<tr>
                                    <td class="fw-bold" style="padding: 5px;">Rem:</td>
                                    <td class="text-center" id="vRem1"></td>
                                </tr>-->
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Half Day Marked:</td>
                                    <td class="text-center" id="vhalf_day"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Total Half Day Marked:</td>
                                    <td class="text-center" id="vtotal_half_day"></td>
                                </tr>
                                 <tr>
                                    <td class="fw-bold" style="padding: 5px;">Full Day Marked:</td>
                                    <td class="text-center" id="vtotal_absent1"></td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Leave:</td>
                                    <td class="text-center" id="vleaveTaken"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Used Leave:</td>
                                    <td class="text-center" id="vusedLeave"></td>
                                </tr>
                                
                                
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Net Pay:</td>
                                    <td class="text-center" id="vnetPay"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>                                                
@endsection
@section('scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
    function fetchEmployeeData(employeeId, mode) {
     //let baseUrl = window.location.origin+"/attendance_system"; // Gets "http://127.0.0.1:8000"
   let baseUrl = window.location.origin; // Gets "http://127.0.0.1:8000"

    $.ajax({
        url: baseUrl + '/admin/salary_process/getSalaryDetails/' + employeeId,
        type: 'GET',
        dataType: 'json',
        success: function(response) {

            // Common fields population
            $('#monthYear').text(response.monthYear);
            $('#empId').text(response.employeeId);
            $('#empName').text(response.employeeName);
            $('#vbasicSalary').text(response.basic_salary);
            $('#vnetPay').text(response.net_pay);
            $('#vcenter').text(response.center);
            $('#vIncentive').text(response.Incentive);
            $('#vBonus').text(response.Bonus);
            $('#vOthers').text(response.Others);
            $('#vTotal_A').text(response.Total_A);
            $('#vWDIM').text(response.WDIM);
            $('#vHDIM').text(response.HDIM);
            $('#vLeave_ded').text(response.Leave_ded);
            $('#vPT').text(response.PT);
            $('#vTDS').text(response.TDS);
            $('#vLoan_Advance').text(response.Loan_Advance);
            $('#vTotal_B').text(response.Total_B);
            $('#vaccumulatedLeave').text(response.Accumlated);
            $('#usedLeave').text(response.Used);
            $('#vleaveTaken').text(response.leave_taken);
            $('#vRem').text(response.Rem);
            $('#vRem1').text(response.Rem);
            $('#vaccumulatedLeave').text(response.Accumlated);
            $('#vusedLeave').text(response.Used);
            $('#vleaveTaken').text(response.leave_taken);
            $('#vtotal_absent').text(response.total_absent);
            $('#vtotal_absent1').text(response.total_absent);
            $('#vtotal_half_day').text(response.total_half_day);
            $('#vhalf_day').text(response.half_day);

            if (mode === "edit") 
            {
                $('#sid').val(response.sid);
                $('#editmonth').val(response.month);
                $('#edityear').val(response.year);
                $('#empId').val(response.employeeId);
                $('#empName').val(response.employeeName);
                $('#basicSalary').val(response.basic_salary);
                $('#netPay').val(response.net_pay);
                $('#center').val(response.center);
                $('#Incentive').val(response.Incentive);
                $('#Bonus').val(response.Bonus);
                $('#Others').val(response.Others);
                $('#Total_A').val(response.Total_A);
                $('#WDIM').val(response.WDIM);
                $('#HDIM').val(response.HDIM);
                $('#Leave_ded').val(response.Leave_ded);
                $('#PT').val(response.PT);
                $('#TDS').val(response.TDS);
                $('#Loan_Advance').val(response.Loan_Advance);
                $('#Total_B').val(response.Total_B);
                $('#Rem').val(response.Rem);                
                // Show the Edit modal
                $('#EditModal').modal('show');
            }
            if(mode = "editleaveData") {
                $('#editsid').val(response.sid);
                $('#editmonth1').val(response.month);
                $('#edityear1').val(response.year);
                $('#accumulatedLeave').val(response.Accumlated);
                $('#usedLeave').val(response.Used);
                $('#leaveTaken').val(response.leave_taken);
                $('#total_absent').val(response.total_absent);
                $('#total_half_day').val(response.total_half_day);

            }
            else 
            {
                // Show the View modal
                $('#ViewModal').modal('show');
            }
        },
        error: function() {
            alert('Failed to fetch salary details.');
        }
    });
}

// Usage:
// View mode
function viewData(employeeId) {
    fetchEmployeeData(employeeId, "view");
}

// Edit mode
function editData(employeeId) {
    fetchEmployeeData(employeeId, "edit");
}

function editleaveData(employeeId) {
    fetchEmployeeData(employeeId, "editleave");
}

function RegenerateEMP(salaryId) {
    
    // let baseUrl = window.location.origin+"/attendance_system"; // Gets "http://127.0.0.1:8000"
     let baseUrl = window.location.origin; // Gets "http://127.0.0.1:8000"


    event.preventDefault(); // Prevent default form submission

    // Clear previous messages
    let messageContainer = $('#message-container');
    messageContainer.hide();

    // AJAX request if all fields are valid
    $.ajax({
        url: baseUrl +`/admin/salary_process/ReGenerateEMP`,  // Pass month and year in URL
        type: 'POST',
        data: {
            salaryId: salaryId,
            _token: '{{ csrf_token() }}' // CSRF token for Laravel
        },
        success: function (response) {
            if (response == 1) {
                messageContainer.html('Please insert the salary of all employees.')
                    .css({ 'color': 'red', 'background': '#ffdddd', 'border': '1px solid red' })
                    .fadeIn();
            } else {
                messageContainer.show(); // Show the message on success
              location.reload();
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
</script>
@endsection