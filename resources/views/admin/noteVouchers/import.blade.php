@extends('layouts.admin')

@section('title')
Import Note Vouchers
@endsection

@section('contentheader')
Note Vouchers
@endsection

@section('contentheaderlink')
<a href="{{ route('noteVouchers.index') }}">Note Vouchers</a>
@endsection

@section('contentheaderactive')
Import
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Import Note Vouchers from Excel</h3>
                <a href="{{ route('noteVouchers.downloadTemplate') }}" class="btn btn-info float-right">
                    <i class="fas fa-download"></i> Download Template
                </a>
            </div>
            <div class="card-body">
            

                <form action="{{ route('noteVouchers.importExcel') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Voucher Type</label>
                                <select name="note_voucher_type_id" class="form-control" required>
                                    <option value="">Select Type</option>
                                    @foreach($note_voucher_types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('note_voucher_type_id')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="date_note_voucher" class="form-control" value="{{ date('Y-m-d') }}" required>
                                @error('date_note_voucher')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>From Warehouse</label>
                                <select name="fromWarehouse" class="form-control" required>
                                    <option value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('fromWarehouse')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>To Warehouse</label>
                                <select name="toWarehouse" class="form-control">
                                    <option value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                                @error('toWarehouse')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Excel File</label>
                        <input type="file" name="excel_file" class="form-control" required>
                        <small class="text-muted">Upload Excel file with products data</small>
                        @error('excel_file')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>After Saving</label>
                        <select name="redirect_to" class="form-control">
                            <option value="index">Return to List</option>
                            <option value="show">View Created Voucher</option>
                        </select>
                    </div>

                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-file-import"></i> Import
                        </button>
                        <a href="{{ route('noteVouchers.index') }}" class="btn btn-secondary btn-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
