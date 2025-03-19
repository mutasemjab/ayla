@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.transfers') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('transfers.index') }}"> {{ __('messages.transfers') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title card_title_center"> {{ __('messages.Edit') }} {{ __('messages.transfers') }} </h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('transfers.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-md-6">
                        <label for="user_select">{{ __('messages.User') }}</label>
                        <select class="form-control" name="user" id="user_select">
                            <option value="">Select users</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $user->id == $data->user_id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-6">
                        <label for="dealer_select">{{ __('messages.dealers') }}</label>
                        <select class="form-control" name="dealer" id="dealer_select">
                            <option value="">Select dealers</option>
                            @foreach($dealers as $dealer)
                                <option value="{{ $dealer->id }}" {{ $dealer->id == $data->user_id ? 'selected' : '' }}>
                                    {{ $dealer->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('dealer')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Deposit') }}</label>
                            <input name="deposit" id="deposit" class="form-control"
                                value="{{ old('deposit', $data['deposit']) }}" />
                            @error('deposit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Withdrawal') }}</label>
                            <input name="withdrawal" id="withdrawal" class="form-control"
                                value="{{ old('withdrawal', $data['withdrawal']) }}" />
                            @error('withdrawal')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{ __('messages.Note') }}</label>
                            <textarea name="note" id="note" class="form-control" rows="8">{{ old('note', $data['note']) }}</textarea>
                            @error('note')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">{{ __('messages.Update') }}</button>
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

            function toggleSelects() {
                if (userSelect.value) {
                    dealerSelect.disabled = true;
                } else {
                    dealerSelect.disabled = false;
                }

                if (dealerSelect.value) {
                    userSelect.disabled = true;
                } else {
                    userSelect.disabled = false;
                }
            }

            userSelect.addEventListener('change', toggleSelects);
            dealerSelect.addEventListener('change', toggleSelects);

            // Initial toggle state
            toggleSelects();
        });
    </script>
@endsection

