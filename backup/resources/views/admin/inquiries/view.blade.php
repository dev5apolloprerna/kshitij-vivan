@extends('layouts.app')

@section('title', 'Inquiry View')

@section('content')

    {{-- Alert Messages --}}
    @include('common.alert')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0"></h4>
                                <div class="page-title-right">
                                    <a href="{{ route('Inquiry.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Back</a>
                                </div>

                        </div>
                    </div>
                </div> 

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">

                            <div class="card-header">
                                <h5 class="card-title mb-0">Inquiry View</h5>

                            </div>
                            <div class="card-body">
                                <?php //echo date('ymd');
                                ?>
                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                                        <tr>
                                            <td scope="col" width="10%">Name</th>
                                            <td>{{ $inquiry->firstname }} {{ $inquiry->lastname }}</td>
                                        </tr>
                                        <tr>
                                            <td scope="col" width="10%">Mobile</th>
                                            <td>{{ $inquiry->mobileNumber }}</td>
                                        </tr>
                                        <tr>
                                            <td scope="col" width="10%">Email</th>
                                                <td>{{ $inquiry->email }}</td>
                                        </tr>
                                        <tr>
                                            <td scope="col" width="10%">Message</th>
                                                <td>{{ $inquiry->message }}</td>
                                        </tr>
                                    
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
