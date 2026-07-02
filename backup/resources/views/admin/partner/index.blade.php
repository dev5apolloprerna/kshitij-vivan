@extends('layouts.app')
@section('title', 'Partner List')
@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-body">
                                <div class="row">

                                    <div class="col-lg-5">

                                        <div class="d-flex justify-content-between card-header">
                                            <h5 class="card-title mb-0">Add Partner </h5>
                                        </div>

                                        <div class="live-preview">
                                            <form method="POST" action="{{ route('partner.store') }}" autocomplete="off"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <div class="modal-body">

                                                    <div class="mt-4 mb-3">
                                                        <span style="color:red;">*</span>Name
                                                        <input type="text" class="form-control" name="name"
                                                            placeholder="Enter Name" maxlength="100" autocomplete="off"
                                                            autofocus required>
                                                    @if($errors->has('name'))
                                                    <span class="text-danger">
                                                        {{ $errors->first('name') }}
                                                    </span>
                                                    @endif

                                                    </div>
                                                    <div class="mt-4 mb-3">
                                                        <span style="color:red;">*</span>Photo
                                                        <input type="file" class="form-control" name="Image" required>
                                                    </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <button type="submit" class="btn btn-primary mx-2"
                                                            id="add-btn">Submit</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="col-lg-1">
                                    </div>

                                    <div class="col-lg-5">
                                        <div class="d-flex justify-content-between card-header">
                                            <h5 class="card-title mb-0">Partner List</h5>

                                        </div>
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
                                                @foreach ($partner as $data)
                                                    <tr class="text-center">
                                                        <td>{{ $i + $partner->perPage() * ($partner->currentPage() - 1) }}
                                                        </td>
                                                        <td>{{ $data->name }}</td>
                                                        <td> @if($data->photo == '')
                                                            <img src="{{ asset('assets/images/noImage.png')}}" width="50px" height="50px">
                                                        @else
                                                            <img src="{{ asset('/Partner/').'/'.$data->photo}}" width="50px" height="50px" >
                                                        @endif</td>


                                                        <td>
                                                            <div class="gap-2">
                                                                
                                                                <a class="mx-1" title="Edit" href="#"
                                                                    onclick="getEditData(<?= $data->partnerId ?>)"
                                                                    data-bs-toggle="modal" data-bs-target="#showModal">
                                                                    <i class="far fa-edit"></i>
                                                                </a>

                                                                <a class="" href="#" data-bs-toggle="modal"
                                                                    title="Delete" data-bs-target="#deleteRecordModal"
                                                                    onclick="deleteData(<?= $data->partnerId ?>);">
                                                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                                                </a>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $i++; ?>

                                   
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="d-flex justify-content-center mt-3">
                                            {{ $partner->links() }}
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>


                <!--Edit Modal Start-->
                <div class="modal fade flip" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header bg-light p-3">
                                <h5 class="modal-title" id="exampleModalLabel">Edit Partner</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                                    id="close-modal"></button>
                            </div>
                            <form method="POST" action="{{ route('partner.update') }}" autocomplete="off"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="partnerId" id="partnerId" value="">

                                <div class="modal-body">

                                    <div class="mb-3">
                                        <span style="color:red;">*</span>Name
                                        <input type="text" class="form-control" name="name" id="EditstrName"
                                            placeholder="Enter Name" maxlength="50" autocomplete="off" required>
                                    </div>
                                    <div class="mb-3">
                                        <span style="color:red;"></span>Image
                                        <input type="file" id="editImage" name="Image" class="form-control"  accept="image/*">
                                        <input type="hidden" name="hiddenImage" id="hiddenImage"  class="form-control">
                                        <p id="error" style="color:red"></p>
                                        <img src="" width="50px" height="50px" id="editphoto">
                                        
                                        <div id="error"></div>
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
                <!--Edit Modal End -->

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
                                    <form id="user-delete-form" method="POST" action="{{ route('partner.delete') }}">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="partnerId" id="deleteid" value="">

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
        function getEditData(id) {
            var url = "{{ route('partner.edit', ':id') }}";
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
                        console.log(obj);
                        if(obj.photo == null)
                        {
                            var imageUrl="https://platinumhrsolutions.in/assets/images/noImage.png";
                        }else{

                            var imageUrl="/Partner/"+obj.photo;
                        }

                        $("#EditstrName").val(obj.name);
                        $('#partnerId ').val(id);
                        $("#hiddenImage").val(obj.photo);
                        $('#editphoto').attr('src', imageUrl);

                    }
                });
            }
        }
    </script>

    <script>
        function deleteData(id) {
            $("#deleteid").val(id);
        }
    </script>

@endsection
