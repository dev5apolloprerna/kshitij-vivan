<html>
  <head>
    <style>
    td {
      border: 1px solid #d8d8d8;
      padding:0 10px;
      height:28px;
      font-family:'Calibri';
      line-height:normal;
    }
    p{
        padding:0;
        margin:0;
        font-family:'Calibri';
    }
    </style>
  </head>
  <body>
 <table width="100%" class="table table-bordered">
    <tbody>
        <tr><th  colspan="2">This is to certify that {{ $salary->employeeName }} has withdrawn salary as per mentioned below.</th></tr>
        <tr>
            <td class="text-end fw-bold" style="width: 30%; padding: 5px;"></td>
            <td class="text-center" id="monthYear">{{ date('M',strtotime($salary->salary_month)) .' '.date('Y',strtotime($salary->salary_year))."'" }}</td>
        </tr><!-- 
        <tr>
            <td class="text-end fw-bold" style="width: 30%; padding: 5px;">Employee ID:</td>
            <td class="text-center" id="empId"></td>
        </tr> -->
        <tr>
            <td class=" text-center fw-bold" style="padding: 5px;">Employee Name:</td>
            <td class="text-center" id="empName">{{ $salary->employeeName }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Basic Salary:</td>
            <td class="text-center" id="vbasicSalary">{{ $salary->basic_salary }}</td>
        </tr>
        
        <tr>
            <td class="fw-bold" style="padding: 5px;">Incentive:</td>
            <td class="text-center" id="vIncentive">{{ $salary->Incentive }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Bonus:</td>
            <td class="text-center" id="vBonus">{{ $salary->Bonus }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Others:</td>
            <td class="text-center" id="vOthers">{{ $salary->Others }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Total A:</td>
            <td class="text-center" id="vTotal_A">{{ $salary->Total_A }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">WDIM:</td>
            <td class="text-center" id="vWDIM">{{ $salary->WDIM }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">HDIM:</td>
            <td class="text-center" id="vHDIM">{{ $salary->HDIM }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Leave Ded:</td>
            <td class="text-center" id="vLeave_ded">{{ $salary->Leave_ded }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">PT:</td>
            <td class="text-center" id="vPT">{{ $salary->PT }}</td>
        </tr>
         <tr>
            <td class="fw-bold" style="padding: 5px;">TDS:</td>
            <td class="text-center" id="vTDS">{{ $salary->TDS }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Loan/Advance:</td>
            <td class="text-center" id="vLoan_Advance">{{ $salary->Loan_Advance }}</td>
        </tr>
         <tr>
            <td class="fw-bold" style="padding: 5px;">Total B:</td>
            <td class="text-center" id="vTotal_B">{{ $salary->Total_B }}</td>
        </tr>

        <tr>
            <td class="fw-bold" style="padding: 5px;">Accumulated Leave:</td>
            <td class="text-center" id="vaccumulatedLeave">{{ $salary->Accumlated }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Used Leave:</td>
            <td class="text-center" id="vusedLeave">{{ $salary->Used }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Leave Taken:</td>
            <td class="text-center" id="vleaveTaken">{{ $salary->Leave_taken }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Rem:</td>
            <td class="text-center" id="vRem">{{ $salary->Rem }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Half Day Leave:</td>
            <td class="text-center" id="vtotal_half_day">{{ $salary->total_half_day }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Full Day Leave:</td>
            <td class="text-center" id="vtotal_absent">{{ $salary->full_day_leave }}</td>
        </tr>
        <tr>
            <td class="fw-bold" style="padding: 5px;">Net Pay:</td>
            <td class="text-center" id="vnetPay">{{ $salary->net_pay }}</td>
        </tr>
    </tbody>

  </body>
</html>