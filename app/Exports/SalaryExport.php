<?php

namespace App\Exports;

use App\Models\SalaryMaster;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Support\Facades\DB;

class SalaryExport implements FromCollection, WithHeadings, WithStyles
{
    protected $month, $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
{
    return SalaryMaster::select(
        'salary_detail.employeeId',
        DB::raw('(SELECT name FROM employee_master WHERE employee_master.empid = salary_detail.employeeId LIMIT 1) AS employeeName'),
        'salary_master.salary_year',
        'salary_detail.center',
        'salary_detail.net_pay',
        'salary_detail.basic_salary',
        'salary_detail.Incentive',
        'salary_detail.Bonus',
        'salary_detail.Others',
        'salary_detail.Total_A',
        'salary_detail.WDIM',
        'salary_detail.HDIM',
        'salary_detail.Leave_ded',
        'salary_detail.PT',
        'salary_detail.TDS',
        'salary_detail.Loan_Advance',
        'salary_detail.Total_B',
        'salary_detail.Accumlated',
        'salary_detail.Used',
        'salary_detail.Leave_taken',
        'salary_detail.Rem'
    )
    ->join('salary_detail', 'salary_detail.salaryId', '=', 'salary_master.sid')
    ->leftJoin('employee_master', 'employee_master.empid', '=', 'salary_detail.employeeId')
    ->when($this->month, fn ($query) => $query->where('salary_month', $this->month))
    ->when($this->year, fn ($query) => $query->where('salary_year', $this->year))
    ->get()
    ->map(function ($row) {
        return [
            $row->employeeId ?? 0,
            $row->employeeName ?? 'N/A',
            date("M, Y", mktime(0, 0, 0, $row->salary_month, 1, $row->salary_year)),
            $row->salary_year ?? 0,
            $row->center ?? 'N/A',
            $row->net_pay ?? 0,
            $row->basic_salary ?? 0,
            $row->Incentive ?? 0,
            $row->Bonus ?? 0,
            $row->Others ?? 0,
            $row->Total_A ?? 0,
            $row->WDIM ?? 0,
            $row->HDIM ?? 0,
            $row->Leave_ded ?? 0,
            $row->PT ?? 0,
            $row->TDS ?? 0,
            $row->Loan_Advance ?? 0,
            $row->Total_B ?? 0,
            $row->Accumlated ?? 0,
            $row->Used ?? 0,
            $row->Leave_taken ?? 0,
            $row->Rem ?? 0,
        ];
    });
}


    public function headings(): array
    {
        return [
            "Emp ID", "Name", "Month", "Center", "NET Pay (A-B)", "Basic Salary",
            "Incentive", "Bonus", "Others", "Total (A)", "WDIM", "HDIM", "Leave Ded",
            "PT", "TDS", "Loan/Advance", "Total (B)", "Accumulated", "Used",
            "Leave Taken", "Rem"
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Apply Green Background to Headers
        $sheet->getStyle('B1:Q1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '00B050']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER
            ]
        ]);

        // Center align all columns
        $sheet->getStyle('A:V')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
}
