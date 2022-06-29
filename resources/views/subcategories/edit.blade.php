@extends('layouts.app')

@section('content')
    <button class="btn mx-3 btn-primary" onclick="window.location='{{route('categories')}}'">Back to Categories</button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center"><h3>Edit Subcategory in {{$subcategory->category->name}}</h3></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('subcategories.update') }}">
                            @csrf


                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Subcategory Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" value="{{$subcategory->name}}" class="form-control @error('name') is-invalid @enderror" name="name" required autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <input type="hidden" name="cat" value="{{$subcategory->category->id}}">



                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save changes') }}
                                    </button>
                                </div>
                            </div>
                            @if(isset($temp))
                                <div id="successMessage"></div>
                            @endif
                            <input type="hidden" name="id" value="{{$subcategory->id}}">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

    <script>
        $(document).ready(function(){
            if(document.getElementsByClassName("invalid-feedback").length){
                alert('Subcategory edit failed!')
                console.log(1);

            }else if(document.getElementById('successMessage')) {
                alert('Successfully edited subcategory.')
            }
        })
    </script>



@endsection

