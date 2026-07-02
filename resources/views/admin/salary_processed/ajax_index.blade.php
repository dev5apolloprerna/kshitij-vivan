<div class="col-lg-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">

                <table id="scroll-horizontal" class="table nowrap align-middle"
                style="width:100%">
                <thead>
                    <tr>
                        <th class="all" width="2%">Sr.No</th>
                        <th class="all" width="5%">Emp Id</th>
                        <th class="all" width="10%">Emp Name</th>
                        <th class="all" width="10%">Month</th>
                        <th class="all" width="10%">Year</th>
                        <th class="all" width="10%">Basic Salary</th>
                        <th class="all" width="10%">Net Pay</th>
                        <th class="all" width="10%">Accumlated</th>
                        <th class="all" width="10%">Used</th>
                        <th class="all" width="10%">Leave Taken</th>
                        <th class="all" width="10%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $Total = 0;
                    ?>
                    @foreach ($attendance as $salary)

                        <tr>
                        <td>
                            {{ $i + $attendance->perPage() * ($attendance->currentPage() - 1) }}
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
                        <td><a class="mx-1" title="View" href="#"
                                onclick="viewData(<?= $salary->sDetailId ?>)"
                                data-bs-toggle="modal" data-bs-target="#ViewModal">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                        <!-- <a class="mx-1" title="View" href="#"
                                onclick="editData(<?= $salary->sDetailId ?>)"
                                data-bs-toggle="modal" data-bs-target="#attendanceModal">
                                <i class="fa-solid fa-edit"></i>
                            </a> -->
                        </td>                      
                     </tr>
                        <?php $i++; ?>
                    @endforeach
                </tbody>

            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $attendance->appends(request()->except('page'))->links() }}
            </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade zoomIn" id="attendanceModal" tabindex="-1" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="attendanceModalLabel">Mark Attendance</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="btn-close"></button>
      </div>
        <div class="modal-body">
        <form id="attendance-form-update" >
            <table>
                <input type="hidden" value="" name="salaryId" id="salaryId">
                    <tr>
                        <td class="text-end fw-bold" style="padding: 5px;">Accumulated Leave:</td>
                        <td class="text-start">
                        <input type="text" name="accumulatedLeave" id="eaccumulatedLeave" readonly></td>
                    </tr>
                    <tr>
                        <td class="text-end fw-bold" style="padding: 5px;">Used Leave:</td>
                        <td class="text-start">
                            <input type="text"  name="usedLeave" id="eusedLeave" readonly>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-end fw-bold" style="padding: 5px;">Leave Taken:</td>
                        <td class="text-start" >
                           <input type="text" name="leaveTaken" id="eleaveTaken" value="" > 
                        </td>
                    </tr>
                    <tr>
                        <td class="text-end fw-bold" style="padding: 5px;">Half Day Leave:</td>
                        <td class="text-start" >
                                 <input type="text" name="halfdayleave" id="ehalfdayleave">

                        </td>
                    </tr>
                    <tr>
                        <td class="text-end fw-bold" style="padding: 5px;">Full Day Leave:</td>
                        <td class="text-start">
                            <input type="text" name="fulldayleave" id="efulldayleave">
                        </td>
                    </tr>
            </table>

        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="update-attendance-btn">Save Attendance</button>
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
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <table width="100%" class="table table-bordered">
                            <tbody >
                                <tr>
                                    <td class="text-end fw-bold" style="width: 30%; padding: 5px;">Basic Salary:</td>
                                    <td class="text-start" id="basicSalary"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Net Pay:</td>
                                    <td class="text-start" id="netPay"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Incentive:</td>
                                    <td class="text-start" id="Incentive"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Bonus:</td>
                                    <td class="text-start" id="Bonus"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Others:</td>
                                    <td class="text-start" id="Others"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Total A:</td>
                                    <td class="text-start" id="Total_A"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">WDIM:</td>
                                    <td class="text-start" id="WDIM"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">HDIM:</td>
                                    <td class="text-start" id="HDIM"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Leave Ded:</td>
                                    <td class="text-start" id="Leave_ded"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">PT:</td>
                                    <td class="text-start" id="PT"></td>
                                </tr>
                                 <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">TDS:</td>
                                    <td class="text-start" id="TDS"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Loan/Advance:</td>
                                    <td class="text-start" id="Loan_Advance"></td>
                                </tr>
                                 <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Total B:</td>
                                    <td class="text-start" id="Total_B"></td>
                                </tr>

                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Accumulated Leave:</td>
                                    <td class="text-start" id="vaccumulatedLeave"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Used Leave:</td>
                                    <td class="text-start" id="vusedLeave"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Leave Taken:</td>
                                    <td class="text-start" id="vleaveTaken"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Half Day Leave:</td>
                                    <td class="text-start" id="vhalfdayleave"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Full Day Leave:</td>
                                    <td class="text-start" id="vfulldayleave"></td>
                                </tr>
                                <tr>
                                    <td class="text-end fw-bold" style="padding: 5px;">Rem:</td>
                                    <td class="text-start" id="Rem"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

