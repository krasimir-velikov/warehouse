@extends('layouts.app')
@section('head')
    <style>
        a{
            color: black;
            text-decoration: none;
        }
        a:hover{
            color: black;
            text-decoration: none;
        }
    </style>

@endsection

@section('content')
    <button class="btn mx-3 btn-primary" onclick="window.location='{{route('employees')}}'">Back to Employees</button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center"><h3>Edit Employee Account: <b>{{$user->name}}</b></h3></div>

                    <div class="card-body my-2">
                        <form method="POST" action="{{ route('employees.update') }}">
                            @csrf
                            <input type="hidden" value="{{$user->id}}" name="id">
                        <div class="form-group row">

                            <label for="level" class="col-md-4 col-form-label text-md-right">{{ __('Account Type') }}</label>

                            <div class="col-md-6">
                                <select id="level" class="form-control select" name="level" required autofocus>
                                    @if(Auth::user()->level==1)<option value="2" @if($user->level==2) selected @endif>Admin</option>@endif
                                    <option value="3" @if($user->level==3) selected @endif>Accountant</option>
                                    <option value="4" @if($user->level==4) selected @endif>Worker</option>
                                </select>
                            </div>
                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Save') }}
                                        </button>
                                    </div>
                                </div>
                        </div>
                            </form>
                        </div>



                        <div class="my-3 card">
                            <a class="card-header text-center" role="button" href="#info" data-toggle="collapse">
                                <h4>Personal Information</h4>
                            </a>


                            <div class="card-body collapse" id="info">

                                <form method="POST" action="{{ route('employees.update') }}">
                                    @csrf
                                    <input type="hidden" value="{{$user->id}}" name="id">



                                    <div class="form-group row">
                                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                        <div class="col-md-6">
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{$user->name}}" required autocomplete="name" autofocus>

                                            @error('name')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row my-3 mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Save') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>

                                    <form method="POST" action="{{ route('employees.update') }}">
                                        @csrf
                                        <input type="hidden" value="{{$user->id}}" name="id">




                                        <div class="form-group row">
                                        <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                                        <div class="col-md-6">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" required autocomplete="email">

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <div class="col-md-6 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('Save') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>




                        <div class="my-5 card">
                                        <a class="card-header text-center" role="button" href="#password" data-toggle="collapse">
                                            <h4>Change Password</h4>
                                        </a>
                            <div class="card-body collapse" id="password">

                            <form method="POST" action="{{ route('employees.update') }}">
                                            @csrf
                                <input type="hidden" value="{{$user->id}}" name="id">



                                <div class="form-group row">
                                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('New Password') }}</label>

                                            <div class="col-md-6">
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                                @error('password')
                                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                                @enderror
                                            </div>
                                        </div>



                                        <div class="form-group row">
                                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                                            <div class="col-md-6">
                                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                            </div>
                                        </div>


                                        <div class="form-group row mb-0">
                                            <div class="col-md-6 offset-md-4">
                                                <button type="submit" class="btn btn-primary">
                                                    {{ __('Save') }}
                                                </button>
                                            </div>
                                        </div>
                                </form>


                                    </div>

                        </div>






                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(isset($temp))
        <div id="successMessage"></div>
    @endif
@endsection
@section('scripts')
    <script>
        $(document).ready(function(){
            if(document.getElementsByClassName("invalid-feedback").length){
                alert('Employee account edit failed!')
                console.log(1);

            }else if(document.getElementById('successMessage')) {
                alert('Successfully edited employee account.')
            }
        })


    </script>

@endsection
