@extends('layouts.app')
@section('title', 'Service List')
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
                                <h5 class="card-title mb-0">Service List</h5>
                                <a href="{{route('service.create')}}" class="btn btn-sm btn-primary">
                                    <i data-feather="plus"></i> Add New
                                </a>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                         <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                            <thead>
                                                <tr class="text-center">
                                                    <th width="1%">No</th>
                                                    <th width="2%"> Name</th>
                                                    <th width="2%"> Photo</th>
                                                    <th width="1%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 1; ?>
                                                @foreach ($service as $servicedata)
                                                    <tr class="text-center">
                                                        <td>{{ $i + $service->perPage() * ($service->currentPage() - 1) }}
                                                        </td>
                                                        <td>{{ $servicedata->serviceName }}</td>
                                                        <td> @if($servicedata->photo == '')
                                                            <img src="{{ asset('assets/images/noImage.png')}}" width="50px" height="50px">
                                                        @else
                                                            <img src="{{ asset('/Service/').'/'.$servicedata->photo}}" width="50px" height="50px" >
                                                        @endif</td>

                                                        <td>
                                                            <div class="gap-2">
                                                                <a class="mx-1"  href="#" data-bs-toggle="modal" data-bs-target="#viewModal_{{$servicedata->serviceId}}" title="View">
                                                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                                                </a>
                                                                <a class="mx-1" title="Edit" href="{{route('service.edit',$servicedata->serviceId)}}">
                                                                    <i class="far fa-edit"></i>
                                                                </a>

                                                                <a class="" href="#" data-bs-toggle="modal"
                                                                    title="Delete" data-bs-target="#deleteRecordModal"
                                                                    onclick="deleteData(<?= $servicedata->serviceId ?>);">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </a>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>

                                    <div class="modal fade flip" id="viewModal_{{$servicedata->serviceId}}" tabindex="-1" aria-labelledby="exampleModalLabel"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header bg-light p-3">
                                                    <h5 class="modal-title" id="exampleModalLabel">{{ $servicedata->serviceName }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                                        id="close-modal"></button>
                                                </div>

                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <p >{!! $servicedata->serviceDescription !!}</p>
                                                            
                                                        </div>
                                                    </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $service->links() }}
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
                                    <form id="user-delete-form" method="POST" action="{{ route('service.destroy') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="serviceId" id="deleteid" value="">

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
            $('#state').change(function() {
                var stateID = $(this).val();
                if(stateID) {
                    $.ajax({
                        url: 'jobs/get-cities/' + stateID,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                            $('#city').empty();
                            $('#city').append('<option value="">Select City</option>');
                            $.each(data, function(key, value) {
                                $('#city').append('<option value="'+ value.cityId +'">'+ value.cityName +'</option>');
                            });
                        }
                    });
                } else {
                    $('#city').empty();
                    $('#city').append('<option value="">Select City</option>');
                }
            });
        });
        
    </script>

@endsection
