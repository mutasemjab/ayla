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
                <h3 class="card-title card_title_center">Import Note Vouchers from Excel</h3>

            </div>
            <div class="card-body">
                <a href="{{ route('noteVouchers.downloadTemplate') }}" class="btn btn-info float-right">
                    <i class="fas fa-download"></i> Download Template
                </a>

                <form action="{{ route('noteVouchers.importExcel') }}" method="POST" enctype="multipart/form-data">
                    @csrf

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
