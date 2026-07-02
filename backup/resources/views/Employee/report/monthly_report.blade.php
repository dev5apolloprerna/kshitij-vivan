@extends('layouts.app')

@section('title', 'Monthly Report')

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
                                <h5 class="card-title mb-0">Monthly Report</h5>
                            </div>
                            <div class="card-body">
                                <!-- Nav tabs -->

                                <div class="container-fluid">
                                    <!-- Page Heading -->
                                    <div class="card">
                                        <div class="card-body">
                                            <!-- 'qu code uniqur id , name , guid' -->
                                            <form method="post" id="form" action="{{ route('empreport.monthly') }}">
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
                                                    <div class="col-md-4  mb-2">
                                                        <div class="input-group d-flex justify-content-right">
                                                            <button type="submit" class="btn btn-primary mx-2">
                                                                Search
                                                            </button>
                                                            <a class="btn btn-primary mx-2" href="{{ route('empreport.monthly') }}">
                                                                Reset
                                                            </a>
                                                            <button onclick="genrateToexcel()" type="button" class="btn btn-primary btn-sm mx-2"> 
                                                                Export to Excel
                                                            </button>

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
                                                                    <td>{{ $monthly_report['empId'] }}</td>
                                                                    
                                                                    <td>{{ $monthly_report['employee_name'] ?? '-' }}</td>
                                                                    <td>@if($monthly_report['start_date_time'] != NULL )
                                                                        {{ date('d-m-Y h:i A',strtotime($monthly_report['start_date_time']))?? '-' }}
                                                                        @else
                                                                            {{ date('d-m-Y',strtotime($monthly_report['date'])) }}
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ number_format($monthly_report['start_latitude'], 2); }}</td>
                                                                    <td>{{ number_format($monthly_report['start_longitude'], 2); }}</td>
                                                                    <td>{{ $monthly_report['start_address'] }}</td>
                                                                    <td>@if($monthly_report['end_date_time'] != NULL && $monthly_report['end_date_time'] != '30-11--0001')
                                                                        {{ date('d-m-Y h:i A',strtotime($monthly_report['end_date_time'])) ?? '-' }}
                                                                        @else
                                                                            {{ '-' }}
                                                                        @endif
                                                                        </td>
                                                                    <td>{{  number_format($monthly_report['end_latitude'],2) ?? '-' }}</td>
                                                                    <td>{{  number_format($monthly_report['end_longitude'],2) ?? '-' }}</td>
                                                                    <td>{{ $monthly_report['end_address'] ?? '-' }}</td>
                                                                    <td>
                                                                        
                                                                        @if ($monthly_report['day'] == 1 && $monthly_report['status'] === 'P')
                                                                            <span>P</span>
                                                                        @elseif ($monthly_report['day'] == 2)
                                                                            <span>H</span>
                                                                        @elseif ($monthly_report['status'] == 'Sunday')
                                                                            <h6>Sunday</h6>
                                                                        @elseif ($monthly_report['holiday_name'])
                                                                            <span>{{ $monthly_report['holiday_name'] }}</span>
                                                                        @elseif($monthly_report['status'] == 'A')
                                                                            <span>A</span>
                                                                        @else
                                                                        <span>A</span>
                                                                        @endif

                                                                    </td>
                                                                    <td>{{ $monthly_report['comment'] ?? '-' }}</td>
                                                                    </tr>
                                                                    <?php 
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
           function genrateToexcel()
    {
        var fromdate = $('#startdatepicker').val();
        var todate = $('#enddatepicker').val();
        var empid = $('#empid').val();
        var Url = "{{route('empreport.export_monthly',[":fromdate",":todate",":empid"])}}";
        Url = Url.replace(':fromdate', fromdate);
        Url = Url.replace(':todate', todate);
        Url = Url.replace(':empid', empid);
        window.location.href = Url;
    }

    </script>
@endsection
