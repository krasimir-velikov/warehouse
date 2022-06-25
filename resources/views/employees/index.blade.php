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

        .edit{
            background-color: orange;
            color: white;
        }
        .block{
            background-color: dimgrey;
            color: white;
        }
        .create{
            color: white;
            font-weight: bold;
            background-color: #1f6fb2;
        }
        .activate{
            background-color: #2fa360;
            color: white;

        }
        .delete{
            background-color: red;
            color: white;
        }
        form{
            display: inline;
        }
    </style>
@endsection

@section('content')
    @if (session()->has('message'))
        <div style="position:fixed;  z-index:10; bottom:20px; right:20px;" class="alert alert-success" role="alert">
            {{ session('message') }}
        </div>
    @endif


    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <img src="{{asset('employees.png')}}" style=" margin-top: 23px; width: 120px">
                        <h1>Employees</h1>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif



                        {{--ADMINS--}}

                        @if(Auth::user()->level==1)
                        <div class="my-5 card">
                            <a class="card-header text-center" role="button" href="#admins" data-toggle="collapse">
                                <h4 class="">Administrators</h4>
                            </a>


                            <div class="card-body collapse" id="admins">
                                <table class="container">
                                    <tr class="row my-5">
                                        <th class="col-3 border-right border-bottom text-center">Name</th>
                                        <th class="col-3 border-right border-bottom text-center">Email</th>
                                        <th class="col-3 border-right border-bottom text-center">Status
                                        </th>
                                        <th class="col-3 border-bottom text-center">Actions</th>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="col text-center">
                                            <form method="GET" action="{{route('employees.create')}}">
                                                <input type="hidden" name="level" value="2">
                                                <button class="btn create" title="New Administrator Account" type="submit">Add account</button>
                                            </form>
                                        </td>
                                    </tr>

                                    @foreach($admins as $admin)
                                        <tr id="row{{$admin->id}}" class="row my-5">
                                            <td class="col-3 border-right text-center">{{$admin->name}}</td>
                                            <td class="col-3 border-right text-center">{{$admin->email}}</td>
                                            @if($admin->status==1)<td class="col-3 border-right text-center" id="{{$admin->id}}S" style="color: forestgreen">Active</td>
                                            @elseif($admin->status==0)<td class="col-3 border-right text-center" id="{{$admin->id}}S" style="color: red">Blocked</td>@endif
                                            <td class="col-3 text-center">
                                                <form method="GET" action="{{route('employees.edit')}}">
                                                    <input type="hidden" name="id" value="{{$admin->id}}">
                                                    <button class="btn edit"  type="submit" title="Redakciq">Edit</button>
                                                </form>
                                                @if($admin->status)

                                                    <button id="{{$admin->id}}B" class="btn block" value="{{$admin->id}}" title="Block the account" onclick="block(this.value)">Block</button>


                                                @else
                                                    <button id="{{$admin->id}}A" class="btn activate" value="{{$admin->id}}" title="Activate the account" onclick="activate(this.value)">Activate</button>

                                                @endif

                                                <button id="{{$admin->id}}D" class="btn delete" value="{{$admin->id}}" title="Delete the account" onclick="deleteIt(this.value)">Delete</button>

                                            </td>
                                        </tr>


                                    @endforeach
                                </table>
                            </div>
                        </div>
                            @endif

                            {{--ACCOUNTANTS--}}

                            <div class="my-5 card">
                                <a class="card-header text-center" role="button" href="#accountants" data-toggle="collapse">
                                    <h4 class="">Accountants</h4>
                                </a>


                                <div class="card-body collapse" id="accountants">
                                    <table class="container">
                                        <tr class="row my-5">
                                            <th class="col-3 border-right border-bottom text-center">Name</th>
                                            <th class="col-3 border-right border-bottom text-center">Email</th>
                                            <th class="col-3 border-right border-bottom text-center">Status
                                            </th>
                                            <th class="col-3 border-bottom text-center">Actions</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="col text-center">
                                                <form method="GET" action="{{route('employees.create')}}">
                                                    <input type="hidden" name="level" value="3">
                                                    <button class="btn create" title="New Accountant Account" type="submit">Add account</button>
                                                </form>
                                            </td>
                                        </tr>

                                        @foreach($accountants as $accountant)
                                            <tr id="row{{$accountant->id}}" class="row my-5">
                                                <td class="col-3 border-right text-center">{{$accountant->name}}</td>
                                                <td class="col-3 border-right text-center">{{$accountant->email}}</td>
                                                @if($accountant->status==1)<td class="col-3 border-right text-center" id="{{$accountant->id}}S" style="color: forestgreen">Active</td>
                                                @elseif($accountant->status==0)<td class="col-3 border-right text-center" id="{{$accountant->id}}S" style="color: red">Blocked</td>@endif
                                                <td class="col-3 text-center">
                                                    <form method="GET" action="{{route('employees.edit')}}">
                                                        <input type="hidden" name="id" value="{{$accountant->id}}">
                                                        <button class="btn edit"  type="submit" title="Edit the account">Edit</button>
                                                    </form>
                                                    @if($accountant->status)

                                                        <button id="{{$accountant->id}}B" class="btn block" value="{{$accountant->id}}" title="Block the account" onclick="block(this.value)">Block</button>


                                                    @else
                                                        <button id="{{$accountant->id}}A" class="btn activate" value="{{$accountant->id}}" title="Activate the account" onclick="activate(this.value)">Activate</button>

                                                    @endif
                                                </td>
                                            </tr>


                                        @endforeach
                                    </table>
                                </div>
                            </div>



                            {{--WORKERS--}}

                            <div class="my-5 card">
                                <a class="card-header text-center" role="button" href="#workers" data-toggle="collapse">
                                    <h4 class="">Workers</h4>
                                </a>


                                <div class="card-body collapse" id="workers">
                                    <table class="container">
                                        <tr class="row my-5">
                                            <th class="col-3 border-right border-bottom text-center">Name</th>
                                            <th class="col-3 border-right border-bottom text-center">Email</th>
                                            <th class="col-3 border-right border-bottom text-center">Status
                                            </th>
                                            <th class="col-3 border-bottom text-center">Actions</th>
                                        </tr>
                                        <tr>
                                            <td colspan="2" class="col text-center">
                                                <form method="GET" action="{{route('employees.create')}}">
                                                    <input type="hidden" name="level" value="4">
                                                    <button class="btn create" title="New Worker Account" type="submit">Add account</button>
                                                </form>
                                            </td>
                                        </tr>

                                        @foreach($workers as $worker)
                                            <tr id="row{{$worker->id}}" class="row my-5">
                                                <td class="col-3 border-right text-center">{{$worker->name}}</td>
                                                <td class="col-3 border-right text-center">{{$worker->email}}</td>
                                                @if($worker->status==1)<td class="col-3 border-right text-center" id="{{$worker->id}}S" style="color: forestgreen">Active</td>
                                                @elseif($worker->status==0)<td class="col-3 border-right text-center" id="{{$worker->id}}S" style="color: red">Blocked</td>@endif
                                                <td class="col-3 text-center">
                                                    <form method="GET" action="{{route('employees.edit')}}">
                                                        <input type="hidden" name="id" value="{{$worker->id}}">
                                                        <button class="btn edit"  type="submit" title="Edit the account">Edit</button>
                                                    </form>
                                                    @if($worker->status)

                                                        <button id="{{$worker->id}}B" class="btn block" value="{{$worker->id}}" title="Block the account" onclick="block(this.value)">Block</button>


                                                    @else
                                                        <button id="{{$worker->id}}A" class="btn activate" value="{{$worker->id}}" title="Activate the account" onclick="activate(this.value)">Activate</button>

                                                    @endif
                                                </td>
                                            </tr>


                                        @endforeach
                                    </table>
                                </div>
                            </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('scripts')

    <script>

        function block(value){

            $.get("{{route('employees.block')}}", {id: value}, function(data){
                $("#"+value+"S").replaceWith("<td class=\"col-3 border-right text-center\" id=\""+value+"S\" style=\"color: red\">Blocked</td>");
                $("#"+value+"B").replaceWith(data);

            })
        }

        function activate(value){

            $.get("{{route('employees.activate')}}", {id: value}, function(data){
                $("#"+value+"S").replaceWith("<td class=\"col-3 border-right text-center\" id=\""+value+"S\" style=\"color: forestgreen\">Active</td>");
                $("#"+value+"A").replaceWith(data);



            })
        }
        function deleteIt(value){
            alert("U SURE?");
                $.get("{{route('employees.delete')}}", {id: value}, function(data) {

                    $("#row"+data).remove();



                })
            }

    </script>

@endsection
