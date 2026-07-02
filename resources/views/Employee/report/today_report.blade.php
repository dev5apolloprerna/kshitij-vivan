@extends('layouts.app')

@section('title', 'Today Report')

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
                            <h5 class="card-title mb-0">Today Report</h5>
                        </div>
                        <div class="card-body">
                            <!-- Nav tabs -->
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
                                                                    <!--<th class="all" width="1%">Action</th>-->
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php
                                                                $i = 1;
                                                                $Total = 0;
                                                                ?>
                                                                @foreach ($today as $today_report)
                                                                    <tr class="text-center">
                                                                    <td>
                                                                        {{ $i + $today->perPage() * ($today->currentPage() - 1) }}
                                                                    </td>
                                                                    <td>{{ $today_report->empId }}</td>
                                                                    
                                                                    <td>{{ $today_report->name }}</td>
                                                                    <td>@if($today_report->start_date_time != NULL )
                                                                        {{ date('d-m-Y h:i A',strtotime($today_report->start_date_time))?? '-' }}
                                                                        @else
                                                                            {{ '-' }}
                                                                        @endif
                                                                    </td>

                                                                    <td>{{ $today_report->start_latitude }}</td>
                                                                    <td>{{ $today_report->start_longitude }}</td>
                                                                    <td>{{ $today_report->start_address }}</td>
                                                                    <td>@if($today_report->end_date_time != NULL && $today_report->end_date_time != '30-11--0001')
                                                                        {{ date('d-m-Y h:i A',strtotime($today_report->end_date_time)) ?? '-' }}
                                                                        @else
                                                                            {{ '-' }}
                                                                        @endif
                                                                        </td>
                                                                    <td>{{ $today_report->end_latitude ?? '-' }}</td>
                                                                    <td>{{ $today_report->end_longitude?? '-' }}</td>
                                                                    <td>{{ $today_report->end_address ?? '-' }}</td>
                                                                    <td>@if($today_report->day == 1)
                                                                            {{ 'P' }}
                                                                        @elseif($today_report->day == 2)
                                                                         {{ 'H' }}
                                                                         @elseif($today_report->day == 3)
                                                                         {{ 'A' }}
                                                                         @elseif($today_report->day == 4)
                                                                         {{ 'L' }}
                                                                        @else
                                                                        {{ '-' }}
                                                                        @endif
                                                                        </td>
                                                                    <td>{{ $today_report->comment ?? '-' }}</td>
                                                                    <!-- <td>-->
                                                                    <!--    <a class="mx-1" title="Attendance" href="#"-->
                                                                    <!--        onclick="getEditData(<?= $today_report->empId ?>)"-->
                                                                    <!--        data-bs-toggle="modal" data-bs-target="#showModal">-->
                                                                    <!--        <i class="fa fa-calendar-check"></i>-->
                                                                    <!--    </a>-->
                                                                    <!--</td>-->
                                                                    </tr>
                                                                    <?php 
                                                                    $i++; ?>
                                                                @endforeach

                                                            </tbody>

                                                        </table>
                                                        <div class="d-flex justify-content-center mt-3">
                                                            {{ $today->appends(request()->except('page'))->links() }}
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
<!---------------- add leave or half day---------------------->
                <div class="modal fade flip" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Attendance</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="close-modal"></button>
                            </div>
                            <form method="POST" action="{{ route('report.update_attendance') }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="empid" id="empid" value="">

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <span style="color:red;">*</span>Name
                                        <input type="text" class="form-control" name="name" id="Editname"
                                            placeholder="Enter Name" maxlength="50" autocomplete="off" readonly>
                                    </div>
                                    <div class="mb-3">
                                        <span style="color:red;">*</span>Day
                                        <select name="day" id="Editday" class="form-control" required>
                                            <option value="1">Full Day</option>
                                            <option value="2">Half Day</option>
                                            <option value="3">Leave</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class="hstack gap-2 justify-content-end">
                                        <button type="submit" class="btn btn-primary mx-2"
                                            id="add-btn">Update</button>
                                        <button type="button" class="btn btn-primary mx-2"
                                            data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
@endsection

@section('scripts')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

<script>
    function getEditData(id) {
            var url = "{{ route('empreport.edit_attendance', ':id') }}";
            url = url.replace(":id", id);
            if (id) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id,
                        id
                    },
                    success: function(data) 
                    {
                        var obj = JSON.parse(data);
                        $('#empid').val(id);
                        $('#Editname').val(obj.empname);
                        $("#Editday").val(obj.day);
                    }
                });
            }
        }
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
