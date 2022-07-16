@extends('layouts.app')

@section('content')
    <button class="btn mx-3 btn-primary" onclick="window.location='{{route('categories')}}'">Back to Categories</button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center"><h3>Add New Category</h3></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('categories.store') }}">
                            @csrf


                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Category Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" required autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                             <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save new category') }}
                                    </button>
                                </div>
                            </div>
                            @if(isset($temp))
                                <div id="successMessage"></div>
                            @endif
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
                alert('Category creation failed!')

            }else if(document.getElementById('successMessage')) {
                alert('Successfully created new category.')
            }
        })
    </script>



@endsection

