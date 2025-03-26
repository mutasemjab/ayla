@extends('layouts.admin')
@section('title')
{{ __('messages.cardPackages') }}
@endsection



@section('content')



<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.cardPackages') }} </h3>
        <a href="{{ route('cardPackages.create') }}" class="btn btn-sm btn-success"> {{ __('messages.New') }} {{
            __('messages.cardPackages') }}</a>

    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">


            </div>

        </div>
        <div class="clearfix"></div>

        <div id="ajax_responce_serarchDiv" class="col-md-12">
            @can('unit-table')
            @if (@isset($data) && !@empty($data) && count($data) > 0)
            <table id="example2" class="table table-bordered table-hover">
                <thead class="custom_thead">


                    <th>{{ __('messages.Name_en') }}</th>
                    <th>{{ __('messages.Name_ar') }}</th>
                    <th>{{ __('messages.Activate') }}</th>

                    <th></th>
                </thead>
                <tbody>
                    @foreach ($data as $info)
                    <tr>


                        <td>{{ $info->name_en }}</td>
                        <td>{{ $info->name_ar }}</td>
                        <td>{{ $info->activate == 1 ? 'Active' : 'Not Active' }}</td>


                        <td>
                            @can('unit-edit')
                            <a href="{{ route('cardPackages.edit', $info->id) }}" class="btn btn-sm  btn-primary">{{
                                __('messages.Edit') }}</a>
                            @endcan
                            @can('unit-delete')
                            <form action="{{ route('cardPackages.destroy', $info->id) }}" method="POST" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">{{ __('messages.Delete') }}</button>
                            </form>
                            @endcan

                        </td>


                    </tr>
                    @endforeach



                </tbody>
            </table>
            <br>
            {{ $data->links() }}
            @else
            <div class="alert alert-danger">
                {{ __('messages.No_data') }} </div>
            @endif
            @endcan

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
