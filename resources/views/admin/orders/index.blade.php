@extends('layouts.admin')
@section('title')
{{ __('messages.Orders') }}
@endsection


@section('contentheaderactive')
{{ __('messages.Show') }}
@endsection



@section('content')



      <div class="card">
        <div class="card-header">
          <h3 class="card-title card_title_center"> {{ __('messages.Orders') }} </h3>
          <input type="hidden" id="token_search" value="{{csrf_token() }}">


        </div>
        <!-- /.card-header -->
        <div class="card-body">
          <div class="row">
          <div class="col-md-4">

            {{-- <input  type="radio" name="searchbyradio" id="searchbyradio" value="name"> name --}}

            {{-- <input autofocus style="margin-top: 6px !important;" type="text" id="search_by_text" placeholder=" name" class="form-control"> <br> --}}

                      </div>

                          </div>
               <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">

            @if (isset($data) && !$data->isEmpty())

            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">
                    <th>{{ __('messages.Status') }}</th>
                    <th>{{ __('messages.Note Voucher Id') }}</th>
                    <th>{{ __('messages.Pin Number') }}</th>
                    <th>{{ __('messages.selling_price') }}</th>
                    <th>{{ __('messages.User') }}</th>
                    <th>{{ __('messages.product') }}</th>
                    <th>{{ __('messages.created_at') }}</th>
                    <th>{{ __('messages.Action') }}</th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>

                        <td>@if($info->order_status==1) Accepted @elseif($info->order_status==2) Failed @endif</td>
                         <td>
                            @foreach($info->voucherProductDetails as $detail)
                                {{ $detail->noteVoucher->id }}<br>
                            @endforeach
                        </td>
                        <td>
                            {{ $info->binNumber->bin_number ?? 'N/A' }}
                        </td>
                        <td>{{ $info->price }}</td>
                        <td>{{ $info->user->name }}</td>
                        <td>{{ $info->product->name_ar }}</td>
                        <td>{{ $info->created_at }}</td>

                        <td>
                            <a href="{{ route('orders.edit', $info->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            {{-- <a href="{{ route('orders.show', $info->id) }}" class="btn btn-sm btn-primary">Show</a> --}}
                            <form action="{{ route('orders.destroy', $info->id) }}" method="POST"  onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
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

        </div>



      </div>

        </div>

</div>

@endsection

@section('script')
<script>
    function confirmDelete(event) {
        event.preventDefault(); // Prevent form from submitting immediately
        if (confirm("Are you sure you want to delete this category?")) {
            event.target.submit(); // Submit form if confirmed
        }
    }
</script>

@endsection


