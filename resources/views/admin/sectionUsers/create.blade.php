@extends('layouts.admin')
@section('title')
{{ __('messages.sectionUsers') }}
@endsection


@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }} {{ __('messages.sectionUsers') }} </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">


        <form action="{{ route('sectionUsers.store') }}" method="post" enctype='multipart/form-data'>
            <div class="row">
                @csrf

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.Name_en') }}</label>
                        <input name="name_en" id="name_en" class="form-control" value="{{ old('name_en') }}">
                        @error('name_en')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.Name_ar') }}</label>
                        <input name="name_ar" id="name_ar" class="form-control" value="{{ old('name_ar') }}">
                        @error('name_ar')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


             

                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">
                            {{__('messages.Submit')}}</button>
                        <a href="{{ route('sectionUsers.index') }}"
                            class="btn btn-sm btn-danger">{{__('messages.Cancel')}}</a>

                    </div>
                </div>

            </div>
        </form>



    </div>




</div>
</div>
@endsection


