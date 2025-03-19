@extends('layouts.admin')
@section('title')
{{ __('messages.requestBalances') }}
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.requestBalances') }} </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <!-- You can add filters or other controls here if needed -->
            </div>
        </div>
        <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            @can('requestBalance-table')
            @if (@isset($data) && !@empty($data) && count($data) > 0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <tr>
                        <th>{{ __('messages.Photo') }}</th>
                        <th>{{ __('messages.User') }}</th>
                        <th>{{ __('messages.Amount') }}</th>
                        <th>{{ __('messages.Status') }}</th>
                        <th>{{ __('messages.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>
                        <td>
                            <div class="image">
                                <img class="custom_img" src="{{ asset('assets/admin/uploads') . '/' . $info->photo_of_request }}">

                            </div>
                        </td>
                        <td>{{ $info->user->name }}</td>
                        <td>{{ $info->amount }}</td>
                        <td>
                            @if($info->status == 1)
                            {{ __('messages.Approved') }}
                            @elseif($info->status == 2)
                            {{ __('messages.Pending') }}
                            @elseif($info->status == 3)
                            {{ __('messages.Rejected') }}
                            @endif
                        </td>
                        <td>
                            @if($info->status == 2) <!-- Only show approve/reject buttons if pending -->
                            <a href="{{ route('requestBalances.approve', $info->id) }}" class="btn btn-success">{{ __('messages.Approve') }}</a>
                            <a href="{{ route('requestBalances.reject', $info->id) }}" class="btn btn-danger">{{ __('messages.Reject') }}</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            {{ $data->links() }}
            @else
            <div class="alert alert-danger">
                {{ __('messages.No_data') }}
            </div>
            @endif
            @endcan
        </div>
    </div>
</div>

@endsection
