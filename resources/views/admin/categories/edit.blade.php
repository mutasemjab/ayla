@extends('layouts.admin')
@section('title')
{{ __('messages.Edit') }} {{ __('messages.categories') }}
@endsection



@section('contentheaderlink')
<a href="{{ route('categories.index') }}"> {{ __('messages.categories') }} </a>
@endsection

@section('contentheaderactive')
{{ __('messages.Edit') }}
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title text-center">{{ __('messages.Edit') }} {{ __('messages.categories') }}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('categories.update', $data['id']) }}" method="post" enctype='multipart/form-data'>
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

                <div class="col-md-6">
                    <div class="form-group">
                        <label> {{ __('messages.is_game') }}</label>
                        <select name="is_game" id="is_game" class="form-control">
                            <option value="">Select</option>
                            <option value="1" {{ $data->is_game == 1 ? 'selected' : '' }}>Yes</option>
                            <option value="2" {{ $data->is_game == 2 ? 'selected' : '' }}>No</option>
                        </select>
                        @error('is_game')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="category_id">Parent Category</label>
                        <select class="form-control" name="category_id" id="category_id">
                            <option value="">Select Parent Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" @if($data->category_id == $cat->id) selected @endif>{{
                                $cat->name_ar }}</option>
                            @endforeach
                            <option value="0" @if($data->category_id === null) selected @endif>No Parent Category
                            </option>
                        </select>
                        @error('category_id')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <div class="col-md-12">
                    <div class="form-group">
                        <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px"
                            style="display: none;">
                        <input type="file" id="Item_img" name="photo" class="form-control-file"
                            onchange="previewImage()">
                        @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-12 text-center">
                    <div class="form-group">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">{{
                            __('messages.Update') }}</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-danger btn-sm">{{ __('messages.Cancel')
                            }}</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    function previewImage() {
            var preview = document.getElementById('image-preview');
            var input = document.getElementById('Item_img');
            var file = input.files[0];
            if (file) {
                preview.style.display = "block";
                var reader = new FileReader();
                reader.onload = function() {
                    preview.src = reader.result;
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = "none";
            }
        }
</script>
@endsection
