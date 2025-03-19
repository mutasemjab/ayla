@extends('layouts.admin')
@section('title')
    {{ __('messages.transfers') }}
@endsection


@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }} {{ __('messages.transfers') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('transfers.store') }}" method="post" enctype='multipart/form-data'>
                @csrf
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="user">{{ __('messages.User') }}</label>
                        <select class="form-control" name="user" id="user_select">
                            <option value="">Select user</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('user')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="dealer">{{ __('messages.dealers') }}</label>
                        <select class="form-control" name="dealer" id="dealer_select">
                            <option value="">Select dealer</option>
                            @foreach($dealers as $dealer)
                                <option value="{{ $dealer->id }}">{{ $dealer->name }}</option>
                            @endforeach
                        </select>
                        @error('dealer')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

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
                            <a href="{{ route('transfers.index') }}" class="btn btn-sm btn-danger">{{ __('messages.Cancel') }}</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const userSelect = document.getElementById('user_select');
        const dealerSelect = document.getElementById('dealer_select');

        userSelect.addEventListener('change', function () {
            if (userSelect.value) {
                dealerSelect.disabled = true;
            } else {
                dealerSelect.disabled = false;
            }
        });

        dealerSelect.addEventListener('change', function () {
            if (dealerSelect.value) {
                userSelect.disabled = true;
            } else {
                userSelect.disabled = false;
            }
        });
    });
</script>
@endsection
