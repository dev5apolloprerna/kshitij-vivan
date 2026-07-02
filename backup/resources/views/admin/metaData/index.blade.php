@extends('layouts.app')

@section('title', 'Meta Data List')

@section('content')
    
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Alert Messages --}}
                @include('common.alert')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Meta Data List</h5>
                            </div>
                            <div class="card-body">
                                <?php //echo date('ymd');
                                ?>
                                <table id="scroll-horizontal" class="table nowrap align-middle" style="width:100%">
                             <thead>
                        <tr>
                            <th>
                                Id
                            </th>
                            <th>
                                Page Name
                            </th>
                            <th>
                                Meta Title
                            </th>
                            <th>
                                Meta Keyword
                            </th>
                            <th>
                                Meta Description
                            </th>

                            <th>
                                {{ 'Action' }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data as $datas)
                            <tr data-entry-id="{{ $datas->id }}">
                                <td>
                                    {{ $datas->id ?? '' }}
                                </td>
                                <td>
                                    {{ $datas->pagename ?? '' }}
                                </td>
                                <td>
                                    {{ $datas->metaTitle ?? '' }}
                                </td>
                                <td>
                                    {{ $datas->metaKeyword ?? '' }}
                                </td>
                                <td>
                                    {{ $datas->metaDescription ?? '' }}
                                </td>
                                <td>
                                    <a class="btn btn-link p-0" title="Edit" href="{{ route('metaData.edit', $datas->id) }}">
                                        <span class="text-500 fas fa-edit"></span>
                                    </a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                   </tbody>
               </table>
           </div>
       </div>
   </div>
</div>
</div>
</div>
</div>

@endsection
