@extends('layouts.master')

@section('body')
<style>
    .bg-form {
        background-color: #e9ecef;
    }
    .form-control:disabled, .form-control[readonly] {
        background-color: #ffffff;
        opacity: 1;
    }
    .form-control-sm {
        height: calc(1.5125rem + 2px);
        padding: .15rem .5rem;
        font-size: .750rem;
        line-height: 1.5;
        border-radius: .2rem;
        background-color: #ffffff !important;
    }
    .btn-sm {
        font-size: 10px !important;
        height: 25px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }
    .bb {
        border-bottom: 1px solid rgb(145, 138, 138);
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title text-gray">
                        <b>INVENTORY</b>
                    </h2>
                    @if(!$checkinv)
                        <a href="#" id="startInventoryBtn" class="btn bg-main text-light btn-sm" style="float: right;">
                            <i class="fas fa-plus fa-xs"></i> START INVENTORY
                        </a>
                    @endif
                </div>
                <div class="card-body"> 
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="table-responsive" style="height: 300px !important;">
                                <table id="example3" class="table table-bordered table-hover" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="30" class="text-center">#</th>
                                            <th class="text-center">Start Date</th>
                                            <th class="text-center">End Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inventories as $index => $inv)
                                            <tr id="row-{{ $inv->id }}">
                                                <td class="text-center align-middle">{{ $index + 1 }}</td>
                                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($inv->start_date)->format('M d, Y') }}</td>
                                                <td class="text-center align-middle">{{ \Carbon\Carbon::parse($inv->end_date)->format('M d, Y') }}</td>
                                                <td class="text-center align-middle">
                                                    @if($inv->status == 1)
                                                        <span class="badge badge-success">Ongoing</span>
                                                    @else
                                                        <span class="badge badge-success">Done</span>
                                                    @endif
                                                </td>
                                                <td class="text-center align-middle">
                                                    <a class="btn btn-info btn-sm" href="{{ route('inventoryForm', $inv->id) }}">
                                                        <i class="fas fa-info-circle"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm delete-row" data-model="Inventory" data-id="{{ $inv->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
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
</div>
@endsection
