@extends('layouts.admin')
@section('title')
{{ __('messages.dealers') }}
@endsection





@section('content')



<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.dealers') }} </h3>
        {{-- <input type="hidden" id="token_search" value="{{ csrf_token() }}"> --}}
        {{-- <input type="hidden" id="ajax_search_url" value="{{ route('admin.dealers.ajax_search') }}"> --}}
        <a href="{{ route('admin.dealers.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }} {{
            __('messages.dealers') }}</a>

    </div>
    <!-- /.card-header -->
    <div class="card-body">


        {{-- <form method="get" action="{{ route('admin.dealers.index') }}" enctype="multipart/form-data">
            @csrf
            <div class="row my-3">
                <div class="col-md-6 ">
                    <input autofocus type="text" placeholder="" name="search" class="form-control">
                </div>
                <div class="col-md-6">
                    <button class="btn btn-success "> {{ __('messages.Search') }} </button>
                </div>
            </div>
        </form> --}}


        <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            @can('dealer-table')
            @if (@isset($data) && !@empty($data) && count($data) > 0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">

                    <th>{{ __('messages.Name') }} </th>
                    <th> {{ __('messages.Email') }} </th>
                    <th>{{ __('messages.Phone') }} </th>
                    <th>{{ __('messages.Activate') }} </th>
                    <th></th>

                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>

                        <td>{{ $info->name }}</td>
                        <td>{{ $info->email }}</td>
                        <td>{{ $info->phone }}</td>
                        <td>
                            {{ $info->activate == 1 ? "Yes" : "No" }}
                        </td>


                        <td>
                            {{-- <a href="{{ route('admin.dealers.show', $info->id) }}" class="btn btn-sm  btn-primary">
                                {{ __('messages.Show') }}</a> --}}
                            @can('dealer-edit')
                            <a href="{{ route('admin.dealers.edit', $info->id) }}" class="btn btn-sm  btn-primary"> {{
                                __('messages.Edit') }}</a>
                            @endcan
                            <a href="{{ route('admin.dealers.export', ['search' => $searchQuery]) }}"
                                class="btn btn-sm btn-success">Export to Excel</a>
                        </td>


                    </tr>
                    @endforeach

                </tbody>
            </table>
            <br>
            {{ $data->appends(['search' => $searchQuery])->links() }}
            @else
            <div class="alert alert-danger">
                {{ __('messages.No_data') }} </div>
            @endif

        </div>
        @endcan

    </div>

</div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/dealers.js') }}"></script>
@endsection
