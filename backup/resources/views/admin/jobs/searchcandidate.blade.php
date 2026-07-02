@extends('layouts.app')
@section('title', 'Applied Job List')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                             <div class="card-header" style="display: flex;justify-content: space-between;">
                                <h5 class="card-title mb-0">Applied Job List</h5>
                               

                            </div>
                             <div class="card-body">
                              <form method="post" action="{{ route('jobs.searchcandidate') }}" id="myForm">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Search By Name</label>
                                                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', isset($name) ? $name : '') }}">
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-2">
                                            <div class="form-group">
                                            <input class="btn btn-primary" style="margin-top: 15%;" type="submit" value="{{'Search'}}">
                                            <input class="btn btn-primary" style="margin-top: 15%;" type="submit" onclick="myFunction()" value="{{'Reset'}}">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div> 
                        </div>
                       
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                           

                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-12">
                                        
                                        <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="1%"><input type="checkbox" id="selectAll"></th>
                                                    <th width="1%">No</th>
                                                    <th width="2%">Job Title</th>
                                                    <th width="2%">Name</th>
                                                    <th width="2%">Email</th>
                                                    <th width="2%">Cover Letter</th>
                                                    <th width="2%">Resume</th>
                                                    <th width="1%">Action</th>
                                                </tr>
                                            </thead>
                                            @if(sizeof($appliedJob) != 0)
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($appliedJob as $data)
                                                    <tr class="text-center">
                                                        <td><input type="checkbox" name="memberId[]" id="memberId" value="{{$data->candidateId}}"></td>
                                                        <td>{{ $i + $appliedJob->perPage() * ($appliedJob->currentPage() - 1) }}
                                                        </td>
                                                        <td>{{ $data->jobTitle }}</td>
                                                        <td>{{ $data->name }}</td>
                                                        <td>{{ $data->Email }}</td>
                                                        <td>{{ Str::limit($data->coverLetter, 80, '....') }}</td>
                                                        <td>
                                                           <?php
                                                                $root = $_SERVER['DOCUMENT_ROOT'];
                                                                $jobDate=date('Y/m/d', strtotime($data->jobDate));
                                                                $destinationpath = $root . '/'.$jobDate;
                                                            ?>  
                                                        @if(file_exists($destinationpath.'/'.$data->resume))
                                                        <a class="mx-2" target="_blank"
                                                            href="{{ asset($jobDate) . '/' . $data->resume }}"
                                                            title="Pdf Details">
                                                            <i class="fa-solid fa-file-pdf fa-lg"></i>
                                                        </a>
                                                        @else
                                                        <a class="mx-2"
                                                            href="#" onclick="return alert('File Not Exist!');"

                                                            title="Pdf Details">
                                                            <i class="fa-solid fa-file-pdf fa-lg"></i>
                                                        </a>
                                                        @endif
                                                    </td>
                                                        <td>
                                                            <div class="gap-2">
                                                                 <a class="mx-1"  href="#" data-bs-toggle="modal" data-bs-target="#viewModal_{{$data->jobId}}" title="View">
                                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                                </a>
                                                                <!-- <a class="mx-1" title="Edit" href="{{ route('applied-job.edit', $data->jobId) }}">
                                                                    <i class="far fa-edit"></i>
                                                                </a>

                                                                <a class="" href="#" data-bs-toggle="modal"
                                                                    title="Delete" data-bs-target="#deleteRecordModal"
                                                                    onclick="deleteData(<?= $data->jobId ?>);">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </a> -->


                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <!------model for view description ------------------->

                                                        <div class="modal fade flip" id="viewModal_{{$data->jobId}}" tabindex="-1" aria-labelledby="exampleModalLabel"  aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header bg-light p-3">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Cover Letter</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"  id="close-modal"></button>
                                                                </div>

                                                                    <div class="modal-body">
                                                                        <div class="mb-3">
                                                                            <p >{{$data->coverLetter}}</p>
                                                                            
                                                                        </div>
                                                                    </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php $i++; ?>
                                                @endforeach
                                            </tbody>
                                             @else
                                            <div>
                                                <tr><td colspan="10">
                                                <h5 style="text-align: center;">No data found</h5></td></tr>
                                            </div>
                                            @endif
                                        </table>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $appliedJob->appends(request()->except('page'))->links() }}

                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!--Delete Modal Start -->
                <div class="modal fade zoomIn" id="deleteRecordModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="btn-close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mt-2 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                                    </lord-icon>
                                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                        <h4>Are you Sure ?</h4>
                                        <p class="text-muted mx-4 mb-0">Are you Sure You want to Remove this Record
                                            ?</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                    <a class="btn btn-primary mx-2" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('user-delete-form').submit();">
                                        Yes,
                                        Delete It!
                                    </a>
                                    <button type="button" class="btn w-sm btn-primary mx-2"
                                        data-bs-dismiss="modal">Close</button>
                                    <form id="user-delete-form" method="POST" action="{{ route('applied-job.destroy') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="jobId" id="deleteid" value="">

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Delete modal End -->

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function deleteData(id) {
            $("#deleteid").val(id);
        }
                 function myFunction() 
        {
            $('#jobTitle').removeAttr('value');
            $('#experience').val('');
            $('#industry').val('');
            $('#salary').val('');
            $('#fuction').val('');
            $('#state').val('');
            $('#city').val('');

        }
        $(document).ready(function() {
            // Select/Deselect all checkboxes
            $('#selectAll').click(function() {
                $('input[name="memberId[]"]').prop('checked', this.checked);
            });

            // If all checkboxes are checked, check the 'select all' checkbox
            $('input[name="memberId[]"]').click(function() {
                if ($('input[name="memberId[]"]:checked').length == $('input[name="memberId[]"]').length) {
                    $('#selectAll').prop('checked', true);
                } else {
                    $('#selectAll').prop('checked', false);
                }
            });

            // Handle form submission
            $('#addButton').click(function() 
            {
                var employeeId=$('#employeeId').val();
                var selectedmemberId = $('input[name="memberId[]"]:checked').map(function() 
                {
                    return this.value;
                }).get();

                if (selectedmemberId.length > 0) 
                {
                    $.ajax({
                        url: "{{ route('bucket.addMember') }}",
                        method: 'POST',
                        data: {
                            memberId: selectedmemberId,
                            employeeId: employeeId,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) 
                        {
                            alert(response.success);
                        },
                        error: function(error) {
                            alert('An error occurred.');
                        }
                    });
                } else {
                    alert('Please select at least one item.');
                }
            });
        });

    </script>

@endsection
