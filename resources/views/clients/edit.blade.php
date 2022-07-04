@extends('layouts.app')

@section('content')
    <button class="btn mx-3 btn-primary" onclick="window.location='{{route('clients')}}'">Back to All Clients</button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center"><h3>Edit Client</h3></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('clients.update') }}">
                            @csrf


                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Client Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" value="{{$client->name}}" class="form-control @error('name') is-invalid @enderror" name="name" required autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Information') }}</label>

                                <div class="col-md-6">
                                    <textarea rows="4" id="info" type="text" class="form-control" name="info" autofocus>{{$client->information}}</textarea>

                                </div>
                            </div>
                            <input type="hidden" name="id" value="{{$client->id}}">



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
                            <input type="hidden" name="id" value="{{$client->id}}">
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
                alert('Client edit failed!')

            }else if(document.getElementById('successMessage')) {
                alert('Successfully edited client.')
            }
        })
    </script>



@endsection

