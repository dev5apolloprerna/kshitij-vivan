    <div class="col-md-4  mb-2">
        <div class="input-group d-flex justify-content-right">
            <input type="hidden" name="mnt" id="mnt" value="{{ $month }}">
            <input type="hidden" name="yr"  id="yr" value="{{ $year }}">
            
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
                        <th class="all" width="30%">Month</th>
                        <th class="all" width="30%">Year</th>
                        <th class="all" width="40%">Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $Total = 0;
                    ?>

                    @foreach ($attendance as  $index=> $salary)

                        <tr>
                        <td>
                            {{ $i + $attendance->perPage() * ($attendance->currentPage() - 1) }}
                        </td>
                        <td>{{ date("F", mktime(0, 0, 0, $salary->salary_month, 10)) }}</td>
                        <td>{{ $salary->salary_year ?? '-' }}</td>
                         <td>
                            @if($salary->is_process == 1 && $create == 1 && $salary->confirm_date == null)
                            <a class="" href="{{route('salary_process.view',[$salary->sid])}}" 
                                title="View">
                                <span class="btn btn-primary btn-sm mx-1">Edit</span>
                            </a>
                            <input type="hidden" id="salaryId" value="{{ $salary->sid }}">
                            <button type="button" id="Regenerate" onclick="Regenerate(<?= $salary->sid ?>)" class="btn btn-primary btn-sm mx-1"> 
                                Regenerete
                            </button>
                            <button onclick="deleteData()" type="button" class="btn btn-primary btn-sm mx-2"> 
                                Delete
                            </button>

                            <button type="button" id="confirm" onclick="confirmSalary(<?= $salary->sid ?>)" class="btn btn-primary btn-sm mx-1"> 
                                Confirm Salary
                            </button>
                             <button onclick="genrateToexcel()" type="button" class="btn btn-primary btn-sm mx-2"> 
                                Export to Excel
                            </button>
                            @elseif($salary->is_process == 1 && $search == 1 && $salary->confirm_date != null) 
                            <a class="" href="{{route('salary_process.view',[$salary->sid])}}" 
                                title="View">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            @elseif($salary->is_process == null && $salary->confirm_date == null) 
                            <a title="Process Salary" href="#" data-bs-toggle="modal" data-bs-target="#ConfirmationModal" class="btn btn-primary btn-sm mx-1" onclick="Data(<?= $salary->sid ?>,<?= $salary->salary_month ?>,<?= $salary->salary_year ?>)">  
                            Process
                            </a>
                            @else
                            <a class="" href="{{route('salary_process.view',[$salary->sid])}}" 
                                title="View">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </a>
                            @endif   
                
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



        