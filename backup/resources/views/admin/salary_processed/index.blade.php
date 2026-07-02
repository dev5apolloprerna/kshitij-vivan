@extends('layouts.app')

@section('title', 'Processed Salary')

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
                                <h5 class="card-title mb-0">Processed Salary</h5>
                            </div>
                            <div class="card-body">
                                <!-- Nav tabs -->

                                <div class="container-fluid">
                                    <!-- Page Heading -->
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- 'qu code uniqur id , name , guid' -->
                                            <form method="post" action="{{route('salary_processed.index')}}" id="form1">
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
                                                                Get
                                                            </button> 
                                                            <button type="submit" onclick="myFunction()" class="btn btn-primary mx-2">
                                                                Reset
                                                            </button> 
                                                            <button onclick="genrateToexcel()" type="button" class="btn btn-primary btn-sm mx-2"> 
                                                                Export to Excel
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
                                        <div class="row" >
                                            <div class="col-lg-12">
                                                <div class="card">
                                                    <div class="card-body">
                                                        <div class="table-responsive">

                                                            <table id="scroll-horizontal" class="table nowrap align-middle"
                                                            style="width:100%">
                                                            <thead>

                                                                <tr>
                                                                    <th class="all" width="2%">Sr.No</th>
                                            <!--                         <th class="all" width="5%">Emp Id</th>
                                                                    <th class="all" width="10%">Emp Name</th> -->
                                                                    <th class="all" width="30%">Month</th>
                                                                    <th class="all" width="30%">Year</th>
                                            <!--                         <th class="all" width="10%">Basic Salary</th>
                                                                    <th class="all" width="10%">Net Pay</th>
                                                                    <th class="all" width="10%">Accumlated</th>
                                                                    <th class="all" width="10%">Used</th>
                                                                    <th class="all" width="10%">Leave Taken</th>
                                             -->                        <th class="all" width="30%">Action</th>
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
                                                                        <a class="" href="{{route('salary_processed.view',[$salary->sid])}}" 
                                                                            title="View">
                                                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                                                        </a>
                                                                        
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
    @endsection
@section('scripts')
<script type="text/javascript">
        function genrateToexcel()
    {
        var month = $('#month').val();
        var year = $('#year').val();
        var Url = "{{route('salary_processed.export_salary',[":month",":year"])}}";
        Url = Url.replace(':month', month);
        Url = Url.replace(':year', year);
        window.location.href = Url;
    }
    function myFunction() 
        {
            $('#month').val('');
            $('#year').val('');
            $('#form1').submit(); // Automatically submits after reset

        }
</script>
@endsection


                                                    