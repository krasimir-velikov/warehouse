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

{{--            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('imports')}}">--}}
{{--                <img src="{{asset('io.png')}}" style=" margin-top: 1px; float: right; width: 120px">--}}
{{--                <h1>Transfers</h1>--}}
{{--                <p>Detailed views. Imports. Exports</p>--}}
{{--            </a>--}}
            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('imports')}}">
                <img src="{{asset('imports.png')}}" style=" margin-top: 20px; float: right; width: 120px">
                <h1>Imports</h1>
                <p>
                    @if(in_array(Auth::user()->level, [1,2]))Manage and review imports.<br>Confirm import payments
                    @elseif(Auth::user()->level == 3)Review imports.<br>Confirm import payments
                    @elseif(Auth::user()->level == 4)Manage and review imports
                        @endif
                </p>
            </a>
            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('exports')}}">
                <img src="{{asset('exports.png')}}" style=" margin-top: 20px; float: right; width: 120px">
                <h1>Exports</h1>
                <p>
                    @if(in_array(Auth::user()->level, [1,2]))Manage and review exports.<br>Confirm export payments
                    @elseif(Auth::user()->level == 3)Review exports.<br>Confirm export payments
                    @elseif(Auth::user()->level == 4)Manage and review exports
                    @endif
                </p>
            </a>
            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('clients')}}">
                <img src="{{asset('clients.png')}}" style=" margin-top: 23px; float: right; width: 120px">
                <h1>Clients</h1>
                <p>
                    @if(in_array(Auth::user()->level, [1,2]))Manage and review clients
                    @elseif(in_array(Auth::user()->level, [3,4]))Review clients
                    @endif
                </p>
            </a>
            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('suppliers')}}">
                <img src="{{asset('suppliers.png')}}" style=" margin-top: 23px; float: right; width: 120px">
                <h1>Suppliers</h1>
                <p>
                    @if(in_array(Auth::user()->level, [1,2]))Manage and review suppliers
                    @elseif(in_array(Auth::user()->level, [3,4]))Review suppliers
                    @endif
                </p>
            </a>
            @if(Auth::user()->level<=3)
{{--            <a class="col-xl-4 col-md-7 col-sm-9 m-5 boxes" href="{{route('finances')}}">--}}
{{--                <img src="{{asset('finances.png')}}" style=" margin-top: 23px; float: right; width: 120px">--}}
{{--                <h1>Finances</h1>--}}
{{--                <p>Money issues. Say no more!</p>--}}
{{--            </a>--}}
            @endif
            <a class="col-xl-4 col-md-7 m-5 col-sm-9 boxes" href="{{route('products')}}">
                {{--                <img src="shelves.png" style=" margin-top: 23px; float: right; width: 7vw">--}}
                <img src="{{asset('products.png')}}" style=" margin-top: 23px; float: right; width: 120px">
                <h1>Products</h1>
                <p>
                    @if(in_array(Auth::user()->level, [1,2]))Manage and review products.<br>Manage categories
                    @elseif(in_array(Auth::user()->level, [3,4]))Review products
                    @endif
                </p>
            </a>
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
