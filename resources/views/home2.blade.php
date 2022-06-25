@extends('layouts.app')
@section('head')
    <style>
        html, body{
            font-family: 'Nunito', sans-serif;
        }
        .boxes{
            background-color: white;
            /*align-items: center;*/
            padding: 15px;
            font-size: 20px;
            border: 1px solid gray;
            height: 200px;
            border-radius: 25px;
        }
        .boxes > h{
            font-size: 35px;
            color: black;
        }
        .boxes > p{
            font-size: 20px;
            color: grey;
        }
        .boxes:hover{
            border: 3px solid gray;

        }

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
    <div class="container">
        <div class="row justify-content-around align-content-center">
            <a class="col-xl-4 col-md-7 m-5 col-sm-9 boxes" href="{{route('products')}}">
{{--                <img src="shelves.png" style=" margin-top: 23px; float: right; width: 7vw">--}}
                <img src="{{asset('products.png')}}" style=" margin-top: 23px; float: right; width: 120px">
                <h1>Products</h1>
                <p>Check product availability. Overview of import/exports</p>
            </a>
            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('transfers')}}">
                <img src="{{asset('io.png')}}" style=" margin-top: 1px; float: right; width: 120px">
                <h1>Transfers</h1>
                <p>Detailed views. Imports. Exports</p>
            </a>
            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('clients')}}">
                <img src="{{asset('clients.png')}}" style=" margin-top: 23px; float: right; width: 120px">
                <h1>Clients</h1>
                <p>Manage clients</p>
            </a>
            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('suppliers')}}">
                <img src="{{asset('suppliers.png')}}" style=" margin-top: 23px; float: right; width: 120px">
                <h1>Suppliers</h1>
                <p>Manage suppliers</p>
            </a>
            @if(Auth::user()->level<=3)
            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('finances')}}">
                <img src="{{asset('finances.png')}}" style=" margin-top: 23px; float: right; width: 120px">
                <h1>Finances</h1>
                <p>Money issues. Say no more!</p>
            </a>
            @endif
            @if(Auth::user()->level<=2)
            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('employees')}}">
{{--                <img src="empl.png" style=" margin-top: 23px; float: right; width: 7vw">--}}
                <img src="{{asset('employees.png')}}" style=" margin-top: 23px; float: right; width: 120px">
                <h1>Employees</h1>
                <p>Manage employee accounts</p>
            </a>
            @endif
        </div>
    </div>
@endsection
