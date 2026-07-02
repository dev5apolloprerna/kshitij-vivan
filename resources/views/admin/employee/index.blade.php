@extends('layouts.app')
@section('title', 'Employee List')
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
                                <h5 class="card-title mb-0">Employee List</h5>
                                <a href="{{route('employee.create')}}" class="btn btn-sm btn-primary">
                                    <i data-feather="plus"></i> Add New
                                </a>
                            </div>
                             <div class="card-body">
                              <form method="post" action="{{ route('employee.index') }}" id="myForm">
                                    @csrf
                                     <div class="row"> 
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="name">Search By Name</label>
                                                <input type="text" name="search" id="search" class="form-control" value="{{ old('search', isset($search) ? $search : '') }}">
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
                                                    <th width="10%">No</th>
                                                    <th width="10%">Name</th>
                                                    <th width="10%">Mobile</th>
                                                    <th width="20%">Email</th>
                                                    <th width="10%">User Name</th>
                                                    <th width="10%">Role</th>
                                                    <th >Salary</th>
                                                    <th width="10%">Report To</th>
                                                    <th width="10%">Status</th>
                                                    <th width="10%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($employee as $data)
                                                    <tr class="text-center">
                                                        <td>{{ $i + $employee->perPage() * ($employee->currentPage() - 1) }}
                                                        </td>
                                                        <td>{{ $data->name }}</td>
                                                        <td>{{ $data->mobile }}</td>
                                                        <td>{{ $data->email }}</td>
                                                        <td>{{ $data->username }}</td>
                                                        <td>@if($data->role_id == 2) 
                                                            {{ 'Employee' ?? '-'}}
                                                            @elseif($data->role_id == 3)
                                                            {{ 'Manager' ?? '-' }}
                                                            @endif                                                            
                                                        </td>
                                                        <td>{{ $data->salary ?? '-' }}</td>
                                                        
                                                        <td>
                                                            {{ $data->report_name ?? '-' }}
                                                        </td>
                                                        <td>
                                                        <a class="mx-1" href="#" data-bs-toggle="modal"
                                                            title="Active or Inactive" data-bs-target="#editStatusModal"
                                                            onclick="statusData(<?= $data->empid ?>);">
                                                         @if($data->iStatus == 1)
                                                         <i class="fa fa-lock-open"></i>
                                                        @else
                                                        <i class="fa fa-lock"></i>
                                                        @endif
                                                        </a>
                                                        </td>
                                                        <td>
                                                            <div class="gap-2">
                                                                <a class="mx-1" title="Edit" href="{{ route('employee.edit', $data->empid) }}">
                                                                    <i class="far fa-edit"></i>
                                                                </a>
                                                                <a class="" href="#" data-bs-toggle="modal"
                                                                    title="Clear Device Token" data-bs-target="#tokenRecordModal"
                                                                    onclick="tokenData(<?= $data->empid ?>);">
                                                                    <i class="fas fa-unlink" aria-hidden="true"></i>
                                                                </a>
                                                                <a class="" href="#" data-bs-toggle="modal"
                                                                    title="Delete" data-bs-target="#deleteRecordModal"
                                                                    onclick="deleteData(<?= $data->empid ?>);">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </a>
                                                                <a class="mx-1" title="change password"
                                                                    href="{{ route('employee.changepassword', $data->empid) }}">
                                                                    <i class="fa-solid fa-key"></i>
                                                                </a>                                                                


                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>
                                                        <div class="modal fade zoomIn" id="editStatusModal" tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                                                        id="btn-close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="mt-2 text-center">
                                                                        <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                                                            <h4>Are you Sure ?</h4>
                                                                            <p class="text-muted mx-4 mb-0">Are you Sure You want to 
                                                                                <?php
                                                                                $status="";
                                                                                if(sizeof($employee) != 0)
                                                                                {
                                                                                    if($data->iStatus == 1)
                                                                                    {
                                                                                        $status='Inactive';
                                                                                        
                                                                                    }else{
                                                                                        $status='Active';
                                                                                    }    
                                                                                }
                                                                                
                                                                                echo $status;
                                                                                ?>
                                                                            this Record
                                                                                ?</p>
                                                                        </div>
                                                                    </div>
                                                                    <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                                                        <a class="btn btn-primary mx-2" href="{{ route('logout') }}"
                                                                            onclick="event.preventDefault(); document.getElementById('user-status-form').submit();">
                                                                            Yes
                                                                        </a>
                                                                        <button type="button" class="btn w-sm btn-primary mx-2" data-bs-dismiss="modal">Close</button>
                                                                        <form id="user-status-form" method="POST"
                                                                            action="{{ route('employee.editStatus', $data->empid) }}">
                                                                            @csrf
                                                                            <input type="hidden" name="id" id="empid" value="">

                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!---- edit status ---->
                                                    
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $employee->links() }}
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!------clear device token ------>
                <div class="modal fade zoomIn" id="tokenRecordModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="btn-close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mt-2 text-center">
                                    <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop"
                                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                                    </lord-icon>

                                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                        <h4>Are you Sure ?</h4>
                                        <p class="text-muted mx-4 mb-0">Are you Sure You want to Clear Device Token ?</p>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                                    <a class="btn btn-primary mx-2" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('user-token-form').submit();">
                                        Yes,
                                        Clear It!
                                    </a>
                                    <button type="button" class="btn w-sm btn-primary mx-2"
                                        data-bs-dismiss="modal">Close</button>
                                    <form id="user-token-form" method="POST" action="{{ route('employee.clear_device_token', $data->empid ?? '') }}">
                                        @csrf
                                        <input type="hidden" name="empid" id="tokenempid" value="">

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!------clear device token ------>
                
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
                                    <form id="user-delete-form" method="POST" action="{{ route('employee.destroy') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="empid" id="deleteid" value="">

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
        function tokenData(id) {
            $("#tokenempid").val(id);
        }
        function statusData(id) {
            $("#empid").val(id);
        }
        function deleteData(id) {
            $("#deleteid").val(id);
        }
        function myFunction() 
        {
            $('#search').removeAttr('value');
        }

    </script>

@endsection
