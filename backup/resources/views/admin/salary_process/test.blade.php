$basic_salary = $SalaryDetail->basic_salary;
						$balance_cl = $SalaryDetail->balance_cl; // Get available leave balance

						$total_leave = $request->total_absent + ($request->total_half_day * 0.5);

						// Determine how many leaves are covered by balance
						$used_leave = min($balance_cl, $total_leave);
						$unpaid_leave = max(0, $total_leave - $used_leave);

						// Calculate salary deductions
						$deduction_for_absent = ($basic_salary / 30) * $unpaid_leave;
						$deduction_for_half_day = ($basic_salary / 30) * 0.5 * $request->total_half_day;

						$net_pay = $basic_salary - ($deduction_for_absent + $deduction_for_half_day);

						// Other Salary Components
						$incentive = $request->Incentive ?? $SalaryDetail->Incentive;
						$Bonus = $request->Bonus ?? $SalaryDetail->Bonus;
						$PT = $request->PT ?? $SalaryDetail->PT;
						$TDS = $request->TDS ?? $SalaryDetail->TDS;
						$Loan_Advance = $request->Loan_Advance ?? $SalaryDetail->Loan_Advance;

						$Total_A = $basic_salary + $incentive + $Bonus + $request->Others;
						$Leave_ded = ($basic_salary > 0) ? ($basic_salary / 30) * $unpaid_leave : 0;
						$Total_B = $Leave_ded + $PT + $TDS + $Loan_Advance;
						$net_pay = $Total_A - $Total_B;

						// Update Salary Details
						$SalaryDetail->center = $request->center;
						$SalaryDetail->net_pay = $net_pay;
						$SalaryDetail->basic_salary = $basic_salary;
						$SalaryDetail->Incentive = $request->Incentive;
						$SalaryDetail->Bonus = $request->Bonus;
						$SalaryDetail->Others = $request->Others;
						$SalaryDetail->Total_A = $Total_A;
						$SalaryDetail->WDIM = $request->WDIM;
						$SalaryDetail->HDIM = $unpaid_leave;  // Corrected calculation
						$SalaryDetail->Leave_ded = $Leave_ded;
						$SalaryDetail->PT = $request->PT;
						$SalaryDetail->TDS = $request->TDS;
						$SalaryDetail->Loan_Advance = $request->Loan_Advance;
						$SalaryDetail->Total_B = $Total_B;
						$SalaryDetail->Rem = $request->Rem;
						$SalaryDetail->Accumlated = $request->Accumlated ?? 0;
						$SalaryDetail->Used = $used_leave ?? 0;
						$SalaryDetail->Leave_taken = $total_leave ?? 0;
						$SalaryDetail->half_day_leave = $request->total_half_day ?? 0;
						$SalaryDetail->full_day_leave = $request->total_absent ?? 0;
						$SalaryDetail->save();