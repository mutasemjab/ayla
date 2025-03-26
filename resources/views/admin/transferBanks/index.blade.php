@extends('layouts.admin')
@section('title')
{{ __('messages.transferBanks') }}
@endsection

@section('content')

<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.transferBanks') }} </h3>
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
            @can('transferBank-table')
            @if (@isset($data) && !@empty($data) && count($data) > 0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <tr>
                        <th>{{ __('messages.User') }}</th>
                        <th>{{ __('messages.User Phone') }}</th>
                        <th>{{ __('messages.Amount') }}</th>
                        <th>{{ __('messages.Name of wallet') }}</th>
                        <th>{{ __('messages.Number of wallet') }}</th>
                        <th>{{ __('messages.Created at') }}</th>
                        <th>{{ __('messages.Status') }}</th>
                        <th>{{ __('messages.Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>
                        <td>{{ $info->user->name }}</td>
                        <td>{{ $info->user->phone }}</td>
                        <td>{{ $info->amount }}</td>
                        <td>{{ $info->name_of_wallet }}</td>
                        <td>{{ $info->number_of_wallet }}</td>
                        <td>{{ $info->created_at }}</td>
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
                            <a href="{{ route('transferBanks.approve', $info->id) }}" class="btn btn-success">{{ __('messages.Approve') }}</a>
                            <a href="{{ route('transferBanks.reject', $info->id) }}" class="btn btn-danger">{{ __('messages.Reject') }}</a>
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
