@extends('layouts.admin')
@section('title')
    {{ __('messages.Receivables') }}
@endsection



@section('content')

<div class="container">
    <h1>Receivables</h1>
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Total Amount</th>
                <th>Remaining Amount</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receivables as $receivable)
            <tr>
                <td>{{ $receivable->user->name }}</td>
                <td>{{ $receivable->total_amount }}</td>
                <td>{{ $receivable->remaining_amount }}</td>
                <td>
                    <a href="{{ route('receivables.edit', $receivable->id) }}" class="btn btn-primary">Edit</a>
                    <a href="{{ route('receivables.show', $receivable->user_id) }}" class="btn btn-info">Show</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection

