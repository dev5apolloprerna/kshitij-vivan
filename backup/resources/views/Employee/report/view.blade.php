@extends('layouts.app')

@section('title', 'Salary')

@section('content')


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
                                <h5 class="card-title mb-0">Salary Detail</h5>
                            </div>
                            <div class="card-body">
                                <!-- Nav tabs -->

                                <div class="container-fluid">
                                    <!-- Page Heading -->
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- 'qu code uniqur id , name , guid' -->
                                            <form action="{{ route('empreport.empSalary') }}" method="POST">
                                                @csrf
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
                                                            <button type="submit" id="search" class="btn btn-primary mx-2">
                                                                Search
                                                            </button> 
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
                                                     <th class="all" width="5%">Month</th>
                                                     <th class="all" width="5%">Year</th>
                                                     <th class="all" width="8%">Basic Salary</th>
                                                     <th class="all" width="8%">Net Pay</th>
                                                     <th class="all" width="8%">Leave Ded</th>
                                                     <th class="all" width="7%">Total A</th>
                                                     <th class="all" width="7%">Total B</th>
                                                     <th class="all" width="8%">Accumlated</th>
                                                     <th class="all" width="4%">Used</th>
                                                     <th class="all" width="5%">Leave Taken</th>
                                                     <th class="all" width="7%">Half Day Leave</th>
                                                     <th class="all" width="7%">Full Day Leave</th>
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
                                                        <td>{{ $salary->Leave_ded  ?? '-' }}</td>
                                                        <td>{{ $salary->Total_A  ?? '-' }}</td>
                                                        <td>{{ $salary->Total_B  ?? '-' }}</td>
                                                        <td>{{ $salary->Accumlated  ?? '-' }}</td>
                                                        <td>{{ $salary->Used  ?? '-' }}</td>
                                                        <td>{{ $salary->Leave_taken  ?? '-' }}</td>
                                                        <td>{{ $salary->half_day_leave  ?? '-' }}</td>
                                                        <td>{{ $salary->full_day_leave  ?? '-' }}</td>
                                                        <td>
                                                           <a class="mx-1 view-btn" title="View" href="#"     
                                                           data-id="<?= $salary->sDetailId ?>" 
                                                           data-name="<?= $salary->employeeName ?>"
                                                           data-month="<?=  date("M", mktime(0, 0, 0, $salary->salary_month, 10))  ?>"
                                                           data-year="<?= $salary->year ?>"
                                                           data-basic-salary="<?= $salary->basic_salary ?>"
                                                           data-incentive="<?= $salary->Incentive ?? '0' ?>"
                                                           data-bonus="<?= $salary->Bonus ?? '0' ?>"
                                                           data-others="<?= $salary->Others ?? '0' ?>"
                                                           data-wdim="<?= $salary->WDIM ?? '0' ?>"
                                                           data-hdim="<?= $salary->HDIM ?? '0' ?>"
                                                           data-net-pay="<?= $salary->net_pay ?? '0' ?>"
                                                           data-leave-ded="<?= $salary->Leave_ded ?? '0' ?>"
                                                           data-pt="<?= $salary->PT ?? '0' ?>"
                                                           data-tds="<?= $salary->TDS ?? '0' ?>"
                                                           data-advance="<?= $salary->Locan_advance ?? '0' ?>"
                                                           data-total-a="<?= $salary->Total_A ?>"
                                                           data-total-b="<?= $salary->Total_B ?>"
                                                           data-accumulated-leave="<?= $salary->Accumlated ?>"
                                                           data-used-leave="<?= $salary->Used ?>"
                                                           data-leave-taken="<?= $salary->Leave_taken ?>"
                                                           data-half-day-leave="<?= $salary->half_day_leave ?>"
                                                           data-total-half-day="<?= $salary->total_half_day ?>"
                                                           data-full-day-leave="<?= $salary->full_day_leave ?>"
                                                           data-rem="<?= $salary->Rem ?? '0' ?>"
             
                                                                data-bs-toggle="modal" data-bs-target="#ViewModal">
                                                                <i class="fa-solid fa-eye"></i>
                                                            </a>
                                                            <a class="mx-1" target="_blank"
                                                                    href="{{ route('empreport.pdf', $salary->sDetailId) }}"
                                                                    title="Pdf Details">
                                                                    <i class="fa-solid fa-file-pdf fa-lg"></i>
                                                                </a>  

                                                            
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
        </div>
    </div>
    <div class="modal fade flip" id="ViewModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title"><span  id="empName"></span> Salary & Leave Details </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                <div class="row">
                    <div class="col-lg-12">
                        <table width="100%" class="table table-bordered">
                            <tbody >
                                <tr>
                                    <td class="fw-bold" style="width: 30%; padding: 5px;"></td>
                                    <td class="text-center" id="monthYear"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="width: 30%; padding: 5px;">Basic Salary:</td>
                                    <td class="text-center" id="basicSalary"></td>
                                </tr>
                               
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Incentive:</td>
                                    <td class="text-center" id="Incentive"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Bonus:</td>
                                    <td class="text-center" id="Bonus"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Others:</td>
                                    <td class="text-center" id="Others"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Total A:</td>
                                    <td class="text-center" id="Total_A"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">WDIM:</td>
                                    <td class="text-center" id="WDIM"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">HDIM:</td>
                                    <td class="text-center" id="HDIM"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Leave Ded:</td>
                                    <td class="text-center" id="Leave_ded"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">PT:</td>
                                    <td class="text-center" id="PT"></td>
                                </tr>
                                 <tr>
                                    <td class="fw-bold" style="padding: 5px;">TDS:</td>
                                    <td class="text-center" id="TDS"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Loan/Advance:</td>
                                    <td class="text-center" id="Loan_Advance"></td>
                                </tr>
                                 <tr>
                                    <td class="fw-bold" style="padding: 5px;">Total B:</td>
                                    <td class="text-center" id="Total_B"></td>
                                </tr>
                                
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Accumulated Leave:</td>
                                    <td class="text-center" id="vaccumulatedLeave"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Full Day Marked:</td>
                                    <td class="text-center" id="vfulldayleave"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Balance Cf:</td>
                                    <td class="text-center" id="Rem1"></td>
                                </tr>
                                <!--<tr>
                                    <td class="fw-bold" style="padding: 5px;">Rem:</td>
                                    <td class="text-center" id="Rem"></td>
                                </tr>-->
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Half Day Marked:</td>
                                    <td class="text-center" id="vhalfdayleave"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Total Half Day Marked:</td>
                                    <td class="text-center" id="vtotalhalfdayleave"></td>
                                </tr>
                                <tr>
                                    <td class="fw-bold" style="padding: 5px;">Full Day Marked:</td>
                                    <td class="text-center" id="vfulldayleave1"></td>
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
                                    <td class="text-center" id="netPay"></td>
                                </tr>
                                <tr>
                                <td>
                                    
                                </td> 
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
    
    
    function genrateToexcel()
    {
        var month = $('#month').val();
        var year = $('#year').val();
        var Url = "{{route('salary_processed.export_salary',[":month",":year"])}}";
        Url = Url.replace(':month', month);
        Url = Url.replace(':year', year);
        window.location.href = Url;
    }
/*$(document).ready(function () {
    $(".view-btn").click(function () {

         var employeeId = $(this).data("name");
        //let baseUrl = window.location.origin+"/attendance_system"; // Gets "http://127.0.0.1:8000"
        let baseUrl = window.location.origin; // Gets "http://127.0.0.1:8000"

        $.ajax({
        
        url: baseUrl + '/employee/employee-report/getSalaryDetails/' + employeeId, // Correct full URL
        type: 'GET',
        dataType: 'json',
        success: function(response) 
        {
            // Populate modal fields with response data
            $('#monthYear').text(response.monthYear);
            $('#empName').text(response.employeeName);
            $('#basicSalary').text(response.basic_salary);
            $('#netPay').text(response.net_pay);
            $('#Incentive').text(response.Incentive);
            $('#Bonus').text(response.Bonus);
            $('#Others').text(response.Others);
            $('#Total_A').text(response.Total_A);
            $('#WDIM').text(response.WDIM);
            $('#HDIM').text(response.HDIM);
            $('#Leave_ded').text(response.Leave_ded);
            $('#PT').text(response.PT);
            $('#TDS').text(response.TDS);
            $('#Loan_Advance').text(response.Loan_Advance);
            $('#Total_B').text(response.Total_B);
            $('#vaccumulatedLeave').text(response.Accumlated);
            $('#vusedLeave').text(response.Used);
            $('#vleaveTaken').text(response.leave_taken);
            $('#vhalfdayleave').text(response.halfdayleave);
            $('#vfulldayleave').text(response.fulldayleave);
            $('#Rem').text(response.Rem);

            // Show modal
            $('#ViewModal').modal('show');
        },
        error: function() {
            alert('Failed to fetch salary details.');
        }
     });
    });
});*/

$(document).ready(function () {
    $(".view-btn").click(function () {
        var empName = $(this).data("name");
        var monthYear = $(this).data("month") + " " + $(this).data("year");
        var basicSalary = $(this).data("basic-salary");
        var netPay = $(this).data("net-pay");
        var leaveDed = $(this).data("leave-ded");
        var totalA = $(this).data("total-a");
        var totalB = $(this).data("total-b");
        var accumulatedLeave = $(this).data("accumulated-leave");
        var usedLeave = $(this).data("used-leave");
        var leaveTaken = $(this).data("leave-taken");
        var halfDayLeave = $(this).data("half-day-leave");
        var totalhalfDayLeave = $(this).data("total-half-day");
        var fullDayLeave = $(this).data("full-day-leave");
        var incentive = $(this).data("incentive");
        var bonus = $(this).data("bonus");
        var others = $(this).data("others");
        var wdim = $(this).data("wdim");
        var hdim = $(this).data("hdim");
        var pt = $(this).data("pt");
        var tds = $(this).data("tds");
        var advance = $(this).data("advance");
        var rem = $(this).data("rem");

        $("#empName").text(empName);
        $("#monthYear").text(monthYear);
        $("#basicSalary").text(basicSalary);
        $("#netPay").text(netPay);
        $("#Leave_ded").text(leaveDed);
        $("#Total_A").text(totalA);
        $("#Total_B").text(totalB);
        $("#vaccumulatedLeave").text(accumulatedLeave);
        $("#vusedLeave").text(usedLeave);
        $("#vleaveTaken").text(leaveTaken);
        $("#vhalfdayleave").text(halfDayLeave);
        $("#vtotalhalfdayleave").text(totalhalfDayLeave);
        $("#vfulldayleave").text(fullDayLeave);
        $("#vfulldayleave1").text(fullDayLeave);
        $('#Incentive').text(incentive);
        $('#Bonus').text(bonus);
        $('#Others').text(others);
        $('#WDIM').text(wdim);
        $('#HDIM').text(hdim);
        $('#PT').text(pt);
        $('#TDS').text(tds);
        $('#Loan_Advance').text(advance);
        $('#Rem').text(rem);
        $('#Rem1').text(rem);

    });
});
    </script>
@endsection
