@extends('layouts.app')

@section('title', 'Attendance')

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
                                <h5 class="card-title mb-0">Attendance</h5>
                            </div>
                            <div class="card-body">
                                <!-- Nav tabs -->

                                <div class="container-fluid">
                                    <!-- Page Heading -->
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- 'qu code uniqur id , name , guid' -->
                                            <form method="post" id="form" action="{{ route('report.monthly') }}">
                                                @csrf
                                                <div class="row  align-items-center">
                                                    <div class="col-md-3  mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <input placeholder="Enter From Date" type="text"
                                                                class="form-control" id="startdatepicker" name="fromdate"
                                                                autocomplete="off"
                                                                value="<?= isset($FromDate) ? $FromDate : '' ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3  mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <input placeholder="Enter To Date" type="text"
                                                                class="form-control" name="todate" autocomplete="off"
                                                                id="enddatepicker"
                                                                value="<?= isset($ToDate) ? $ToDate : '' ?>">
                                                        </div>
                                                    </div>  
                                                    <div class="col-md-3  mb-2">
                                                        <div class="d-flex align-items-center">
                                                            <select name="empid" class="form-control" id="empid">
                                                                <option value="">Select Employee</option>
                                                                @foreach($emp as $e)
                                                                <option value="{{ $e->empid }}" {{ old('empid', isset($empid) && $empid == $e->empid  ? 'selected' : "") }}>{{ $e->name }}</option>
                                                                @endforeach
                                                            </select>

                                                        </div>
                                                    </div> 
                                                    
                                                    <div class="col-md-3  mb-2">
                                                        <div class="input-group d-flex justify-content-right">
                                                            <button type="submit" class="btn btn-primary mx-2">
                                                                Search
                                                            </button>
                                                            <a class="btn btn-primary mx-2" href="{{ route('report.monthly') }}">
                                                                Reset
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-content text-muted">
                                    <div class="tab-pane active" id="PendingOrder" role="tabpanel">
                                        <div class="row">

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
                                                                    <th class="all" width="10%">Start Date & Time</th>
                                                                    <th class="all" width="5%">Start Latitude </th>
                                                                    <th class="all" width="5%">Start Longitude </th>
                                                                    <th class="all" width="20%">Start Location </th>
                                                                    <th class="all" width="10%">End Date & Time</th>
                                                                    <th class="all" width="5%">End Latitude</th>
                                                                    <th class="all" width="5%">End Longitude</th>
                                                                    <th class="all" width="20%">End Location</th>
                                                                    <th class="all" width="5%">Attendance Status</th>
                                                                    <th class="all" width="5%">Comment</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                $Total = 0;
                                                                ?>
                                                                @foreach ($monthly as $monthly_report)
                                                                    <tr class="text-center">
                                                                    <td>
                                                                        {{ $i + $monthly->perPage() * ($monthly->currentPage() - 1) }}
                                                                    </td>
                                                                    <td>{{ $monthly_report->empId }}</td>
                                                                    
                                                                    <td>{{ $monthly_report->name }}</td>
                                                                    <td>@if($monthly_report->start_date_time != NULL )
                                                                        {{ date('d-m-Y h:i A',strtotime($monthly_report->start_date_time))?? '-' }}
                                                                        @else
                                                                            {{ '-' }}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $monthly_report->start_latitude }}</td>
                                                                    <td>{{ $monthly_report->start_longitude }}</td>
                                                                    <td>{{ $monthly_report->start_address }}</td>
                                                                    <td>@if($monthly_report->end_date_time != NULL && $monthly_report->end_date_time != '30-11--0001')
                                                                        {{ date('d-m-Y h:i A',strtotime($monthly_report->end_date_time)) ?? '-' }}
                                                                        @else
                                                                            {{ '-' }}
                                                                        @endif
                                                                        </td>
                                                                    <td>{{ $monthly_report->end_latitude ?? '-' }}</td>
                                                                    <td>{{ $monthly_report->end_longitude?? '-' }}</td>
                                                                    <td>{{ $monthly_report->end_address ?? '-' }}</td>
                                                                    <td>
                                                                        @if($monthly_report->day == 1)
                                                                            {{ 'P' }}
                                                                        @elseif($monthly_report->day == 2)
                                                                         {{ 'H' }}
                                                                         @elseif($monthly_report->day == 3)
                                                                         {{ 'A' }}
                                                                         @elseif($monthly_report->day == 4)
                                                                         {{ 'L' }}
                                                                        @else
                                                                        {{ '-' }}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $monthly_report->comment ?? '-' }}</td>
                                                                    </tr>
                                                                    <?php 
                                                                    $Total += $monthly_report->netAmount;
                                                                    $i++; ?>
                                                                @endforeach

                                                            </tbody>

                                                        </table>
                                                        <div class="d-flex justify-content-center mt-3">
                                                            {{ $monthly->appends(request()->except('page'))->links() }}
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
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

    <script>
        $(function() {
            $("#startdatepicker").datepicker({
                dateFormat: 'd-m-yy',
                defaultDate: new Date()
            });
        });

        $(function() {
            $("#enddatepicker").datepicker({
                dateFormat: 'd-m-yy',
                defaultDate: new Date()
            });
        });
    </script>
@endsection
