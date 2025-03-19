@extends('layouts.admin')

@section('title')
    {{ __('messages.Receivables') }}
@endsection

@section('content')
<div class="container">
    <h1>Receivables for {{ $user->name }}</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Total Amount</th>
                <th>Remaining Amount</th>
                <th>Payments</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receivables as $receivable)
            <tr>
                <td>{{ $receivable->total_amount }}</td>
                <td>{{ $receivable->remaining_amount }}</td>
                <td>
                    <ul>
                        @foreach($receivable->receivableTransactions as $transaction)
                        <li>{{ $transaction->payment_date }}: {{ $transaction->payment_amount }} JD</li>
                        @endforeach
                    </ul>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
