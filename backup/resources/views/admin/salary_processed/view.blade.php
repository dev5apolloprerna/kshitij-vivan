@extends('layouts.app')

@section('title', 'Processed Salary')

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
                                <h5 class="card-title mb-0">Processed Salary</h5>
                                <button onclick="genrateToexcel('{{ $data->salary_month }}', '{{ $data->salary_year }}')" type="button" class="btn btn-primary btn-sm mx-2"> 
                                    Export to Excel
                                </button>
                            </div>
                            <div class="card-body">
                                <!-- Nav tabs -->

                                

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

                                                             <!--<a class="mx-1" title="Update Salry Details" href="#"
                                                                    onclick="editData(<?= $salary->sDetailId ?>)"
                                                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                                                    <i class="fa-solid fa-edit"></i>
                                                                </a>                            
                                                                <a class="mx-1" title="Update Leave" href="#"
                                                                    onclick="editleaveData(<?= $salary->sDetailId ?>)"
                                                                    data-bs-toggle="modal" data-bs-target="#editleaveModal">
                                                                    <i class="fa-solid fa-plus"></i>
                                                                </a>-->
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
                <h5 class="modal-title"> Salary Details </h5>
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
                                    <td class="fw-bold" style="padding: 5px;">Employee Name:</td>
                                    <td class="text-center" id="empName"></td>
                                </tr>

                                <tr>
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
                                    <td class="fw-bold" style="padding: 5px;">Full Day Leave:</td>
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
                                <td>
                                    <a class="mx-1" target="_blank" id="salaryPdfLink" href="#" title="Pdf Details">
                                        <i class="fa-solid fa-file-pdf fa-lg"></i>
                                    </a>

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
    
    
       function genrateToexcel(getmonth = null,getyear = null)
    {
        alert(getyear);
        let month = getmonth;
        let year = getyear;

        var Url = "{{route('salary_processed.export_salary',[":month",":year"])}}";
        Url = Url.replace(':month', month);
        Url = Url.replace(':year', year);
        window.location.href = Url;
    }
    function viewData(employeeId) {
        //let baseUrl = window.location.origin+"/attendance_system"; // Gets "http://127.0.0.1:8000"
        let baseUrl = window.location.origin; // Gets "http://127.0.0.1:8000"

        $.ajax({
        
        url: baseUrl + '/admin/salary_processed/getSalaryDetails/' + employeeId, // Correct full URL
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            console.log(response.sid);
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
            $('#vtotalhalfdayleave').text(response.totalhalfdayleave);
            $('#vfulldayleave').text(response.fulldayleave);
            $('#vfulldayleave1').text(response.fulldayleave);
            $('#Rem').text(response.Rem);
            $('#Rem1').text(response.Rem);
            // Show modal
                let pdfUrl = baseUrl + '/admin/salary_process/pdf/' + response.sid;
                $('#salaryPdfLink').attr('href', pdfUrl);
            
            

            $('#ViewModal').modal('show');
        },
        error: function() {
            alert('Failed to fetch salary details.');
        }
    });
}

    </script>
@endsection
