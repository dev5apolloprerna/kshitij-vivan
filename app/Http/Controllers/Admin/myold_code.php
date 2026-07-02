						$basic_salary = $SalaryDetail->basic_salary;
						$balance_cl = $SalaryDetail->balance_cl; // Available leaves

						$total_leave = $request->total_absent + $request->total_half_day;

						// Determine used leave and unpaid leave
						$used_leave = min($balance_cl, $request->total_absent); // Use available leaves first
						$unpaid_leave = max(0, $request->total_absent - $used_leave); // Remaining unpaid days

						// Salary deduction calculations
						$deduction_for_absent = ($basic_salary / 30) * $unpaid_leave; // Only deduct for unpaid leaves
						$deduction_for_half_day = ($basic_salary / 30) * 0.5 * $request->total_half_day;

						// Net Pay Calculation
						$net_pay = $basic_salary - ($deduction_for_absent + $deduction_for_half_day);

						// Incentives, Bonus, Deductions
						$incentive = $request->Incentive ?? $SalaryDetail->Incentive;
						$Bonus = $request->Bonus ?? $SalaryDetail->Bonus;
						$PT = $request->PT ?? $SalaryDetail->PT;
						$TDS = $request->TDS ?? $SalaryDetail->TDS;
						$Loan_Advance = $request->Loan_Advance ?? $SalaryDetail->Loan_Advance;

						// Total Additions and Deductions
						$Total_A = $basic_salary + $incentive + $Bonus + $request->Others;
						$Total_B = $deduction_for_absent + $deduction_for_half_day + $PT + $TDS + $Loan_Advance;

						// Final Net Salary
						$net_pay = $Total_A - $Total_B;

						// Update Leave Balance
						$new_balance_cl = max(0, $balance_cl - $used_leave);


            	$SalaryDetail->center=$request->center;
            	$SalaryDetail->net_pay=$net_pay;
            	$SalaryDetail->basic_salary=$basic_salary;
            	$SalaryDetail->Incentive=$request->Incentive;
            	$SalaryDetail->Bonus=$request->Bonus;
            	$SalaryDetail->Others=$request->Others;
            	$SalaryDetail->Total_A=$Total_A;
            	$SalaryDetail->WDIM=$request->WDIM;
            	$SalaryDetail->HDIM=$HDIM;
            	$SalaryDetail->Leave_ded=$Leave_ded;
            	$SalaryDetail->PT=$request->PT;
            	$SalaryDetail->TDS=$request->TDS;
            	$SalaryDetail->Loan_Advance=$request->Loan_Advance;
            	$SalaryDetail->Total_B=$Total_B;
            	$SalaryDetail->Rem=$request->Rem;
			    $SalaryDetail->Accumlated=$request->Accumlated ?? 0;
	        	$SalaryDetail->Used=$used_leave ?? 0;
	        	$SalaryDetail->Leave_taken=$total_leave ?? 0;
	        	$SalaryDetail->half_day_leave=$request->total_half_day ?? 0;
	        	$SalaryDetail->full_day_leave=$request->total_absent ?? 0;
            	$SalaryDetail->save();
		        