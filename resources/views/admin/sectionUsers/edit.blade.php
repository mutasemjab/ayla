@extends('layouts.admin')
@section('title')
{{ __('messages.Edit') }} {{ __('messages.sectionUsers') }}
@endsection



@section('contentheaderlink')
<a href="{{ route('sectionUsers.index') }}"> {{ __('messages.sectionUsers') }} </a>
@endsection

@section('contentheaderactive')
{{ __('messages.Edit') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title text-center">{{ __('messages.Edit') }} {{ __('messages.sectionUsers') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('sectionUsers.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name_en">{{ __('messages.Name_en') }}</label>
                        <input type="text" name="name_en" id="name_en" class="form-control"
                            value="{{ old('name_en', $data['name_en']) }}">
                        @error('name_en')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name_ar">{{ __('messages.Name_ar') }}</label>
                        <input type="text" name="name_ar" id="name_ar" class="form-control"
                            value="{{ old('name_ar', $data['name_ar']) }}">
                        @error('name_ar')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                


        
                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">{{
                            __('messages.Update') }}</button>
                        <a href="{{ route('sectionUsers.index') }}" class="btn btn-danger btn-sm">{{ __('messages.Cancel')
                            }}</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

