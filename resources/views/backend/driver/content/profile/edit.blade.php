@extends('backend.driver.layout.master')

@push('css')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.79/theme-default.min.css">
@endpush

@section('content')
  @include('backend.partial.laravelError')
  <div class="row align-items-center">
    <div class="col-md-12">
      <form action="{{ route('driver.profile.update', $driver->id) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method("PATCH")
        <div class="form-row">
          <div class="form-group col-md-4 col-12">
            <label for="">Name:</label>
            <input type="text" name="name" class="form-control" data-validation="required" value="{{ $driver->name }}">
          </div>
          <div class="form-group col-md-4 col-12">
            <label for="">Email:</label>
            <input type="email" name="email" class="form-control" data-validation="email" value="{{ $driver->email }}">
          </div>
          <div class="form-group col-md-4 col-12">
            <label for="">Phone:</label>
            <input type="text" name="contact" class="form-control" data-validation="required" value="{{ $driver->contact }}">
          </div>
          <div class="form-group col-md-4 col-12">
            <label for="">Vehicle Type:</label>
            <select name="vehicle_type" class="form-control">
              @foreach ($vehicleTypes as $item)
                <option value="{{ $item->id }}" @if ($driver->vehicle_type->id == $item->id) selected @endif>{{ $item->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group col-md-4 col-12">
            <label for="">Vehicle Name:</label>
            <input type="text" name="vehicle_name" class="form-control" data-validation="required" value="{{ $driver->vehicle_name}}">
          </div>
          <div class="form-group col-md-4 col-12">
            <label for="">Vehicle Color:</label>
            <input type="text" name="vehicle_color" class="form-control" data-validation="required" value="{{$driver->vehicle_color}}">
          </div>

          <div class="form-group col-md-12">
            <label for="">Profile Image:</label>
            <input type="file" id="selected_image" class="form-control" name="image">
            {{-- <img src="" id="category-img-tag" class="img-fluid"> --}}
            <img class="pt-2 img-fluid" id="category-img-tag" height="200" width="200" src="{{ Storage::url('driver_images/' . auth('driver')->user()->image) }}" alt="">
          </div>
          {{-- <div class="form-group col-md-3 col-12">
            <label for="">Current Profile Image:</label>
            <input type="file" class="form-control" name="image">
          </div> --}}
          <div class="form-group col-md-12">
            <button type="submit" class="btn btn-success btn-md float-right">submit</button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('js')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.79/jquery.form-validator.min.js"></script>
  <script>
    $(document).ready(function() {
      $.validate();
    });
  </script>
@endpush


@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.3.79/jquery.form-validator.min.js"></script>
    <script>
        $(document).ready(function() {
            $.validate();
        });

        function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#category-img-tag').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#selected_image").change(function(){
        readURL(this);
    });
    </script>
@endpush
