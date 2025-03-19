@extends('layouts.admin')
@section('title')
{{ __('messages.categories') }}
@endsection


@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title card_title_center"> {{ __('messages.Add_New') }} {{ __('messages.categories') }} </h3>
    </div>
    <!-- /.card-header -->
    <div class="card-body">


        <form action="{{ route('categories.store') }}" method="post" enctype='multipart/form-data'>
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


                <div class="form-group col-md-6">
                    <label for="is_game"> {{ __('messages.is_game') }}</label>
                    <select name="is_game" id="is_game" class="form-control">
                        <option value="">Select</option>
                        <option @if(old('is_game')==1 || old('is_game')=="") selected="selected" @endif value="1">Yes</option>
                        <option @if(old('is_game')==2 and old('is_game')!="") selected="selected" @endif value="2">No</option>
                    </select>
                    @error('is_game')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group col-md-6">
                    <label for="category_id">Parent Category</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <option value="">Select Parent Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name_ar }}</option>
                        @endforeach
                    </select>
                    @error('category_id')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>




                <div class="col-md-12">
                    <div class="form-group">
                        <img src="" id="image-preview" alt="Selected Image" height="50px" width="50px"
                            style="display: none;">
                        <button class="btn"> photo</button>
                        <input type="file" id="Item_img" name="photo" class="form-control" onchange="previewImage()">
                        @error('photo')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>




                <div class="col-md-12">
                    <div class="form-group text-center">
                        <button id="do_add_item_cardd" type="submit" class="btn btn-primary btn-sm">
                            {{__('messages.Submit')}}</button>
                        <a href="{{ route('categories.index') }}"
                            class="btn btn-sm btn-danger">{{__('messages.Cancel')}}</a>

                    </div>
                </div>

            </div>
        </form>



    </div>




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
