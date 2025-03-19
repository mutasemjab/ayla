@extends('layouts.admin')
@section('title')
    {{ __('messages.wallets') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }} {{ __('messages.wallets') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('wallets.store') }}" method="post" enctype='multipart/form-data'>
                @csrf
                <div class="row">


                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Deposit') }}</label>
                            <input name="deposit" id="deposit" class="form-control" value="{{ old('deposit') }}">
                            @error('deposit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Withdrawal') }}</label>
                            <input name="withdrawal" id="withdrawal" class="form-control" value="{{ old('withdrawal') }}">
                            @error('withdrawal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Note') }}</label>
                            <textarea name="note" id="note" class="form-control" rows="8">{{ old('note') }}</textarea>
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">{{ __('messages.Submit') }}</button>
                            <a href="{{ route('wallets.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection


