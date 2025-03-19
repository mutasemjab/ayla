@extends('layouts.admin')
@section('title')
    {{ __('messages.questions') }}
@endsection



@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center">{{ __('messages.questions') }}</h3>

        <a href="{{ route('questions.create') }}" class="btn btn-sm btn-success">{{ __('messages.New') }} {{ __('messages.questions') }}</a>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="clearfix"></div>

        <div class="col-md-12">
            @if(isset($questions) && !empty($questions) && count($questions) > 0)
                <table id="example2" class="table table-bordered table-hover">
                    <thead class="custom_thead">
                        <th>{{ __('messages.question') }}</th>
                        <th>{{ __('messages.answer') }}</th>
                        <th></th>
                    </thead>
                    <tbody>
                        @foreach ($questions as $question)
                            <tr>
                                <td>{{ $question->question }}</td>
                                <td>{{ $question->answer }}</td>
                                <td>
                                    <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-sm btn-primary">{{ __('messages.Edit') }}</a>
                                    <form action="{{ route('questions.destroy', $question->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-danger">{{ __('messages.No_data') }}</div>
            @endif
        </div>
    </div>
</div>
@endsection


