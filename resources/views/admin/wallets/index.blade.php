@extends('layouts.admin')
@section('title')
{{ __('messages.wallets') }}
@endsection



@section('content')



<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.wallets') }} </h3>


    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">


            </div>

        </div>
        <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            @can('banner-table')
            @if (@isset($wallets) && !@empty($wallets) && count($wallets) > 0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">


                    <th>{{ __('messages.User') }}</th>
                    <th>{{ __('messages.Total') }}</th>
                    <th>{{ __('messages.Action') }}</th>

                </thead>
                <tbody>
                    @foreach($wallets as $wallet)
                    <tr>
                        <td>{{ $wallet->user ? $wallet->user->name : $wallet->admin->name }}</td>
                        <td>{{ $wallet->total }}</td>
                        <td>
                            <a href="{{ route('wallets.show', $wallet->id) }}" class="btn btn-primary">
                                {{ __('messages.View_Transactions') }}</a>
                        </td>
                    </tr>
                    @endforeach



                </tbody>
            </table>
            <br>
            {{ $wallets->links() }}
            @else
            <div class="alert alert-danger">
                {{ __('messages.No_wallets') }} </div>
            @endif
            @endcan

        </div>



    </div>

</div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/sliderss.js') }}"></script>
@endsection
