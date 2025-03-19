@extends('layouts.admin')
@section('title')
    {{ __('messages.Edit') }} {{ __('messages.Receivables') }}
@endsection



@section('contentheaderlink')
    <a href="{{ route('receivables.index') }}"> {{ __('messages.Receivables') }} </a>
@endsection

@section('contentheaderactive')
    {{ __('messages.Edit') }}
@endsection


@section('content')
<div class="container">
    <h1>Edit Receivable</h1>
    <form action="{{ route('receivables.update', $receivable->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="payment_amount">Payment Amount</label>
            <input type="number" class="form-control" id="payment_amount" name="payment_amount" step="any" required>
        </div>
        <div class="form-group">
            <label for="payment_date">Payment Date</label>
            <input type="date" class="form-control" id="payment_date" name="payment_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
@endsection
