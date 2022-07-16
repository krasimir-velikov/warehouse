@extends('layouts.app')

@section('head')

    <style>
        h1{
            font-size: 50px;
        }

        .categories{
            color: white;
            background-color: #1f6fb2;
        }

        .add{
            color: white;
            background-color: #2fa360;
        }
        .edit{
            background-color: orange;
            color: white;
        }
        .delete{
            background-color: red;
            color: white;
        }

    </style>

    <!--jQuery-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>

    <!--Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"  ></script>

@endsection

@section('content')
    <div class="container-fluid">
        <div class="row my-2 justify-content-between">
            <div class="col-8 mx-5">
                <h1><strong>Clients:</strong> @if(request('name')) {{$clients->first()->name }} @else All Clients @endif</h1>
            </div>

            @if(in_array(Auth::user()->level, [1,2])) <form method="GET" action="{{route('clients.create')}}">
                    <button class="btn add my-1 mx-3 float-right" title="Add New Clients" type="submit">Add clients</button>
                </form> @endif

            </div>


        <div class="row my-5">
            <h3 class="col-xl-1 text-center my-1">Search:</h3>
            <div class="d-inline col-xl-2 mx-3 my-1">
                <form>
                    <select name="name" class="form-control selectName" onchange="this.form.submit()" style="height: 35px">
                        <option value="" @if(!request('name'))selected @endif disabled=""></option>

                        @foreach($searchclients as $client)
                            <option @if($client->id == request('name')) selected @endif value="{{$client->id}}">{{$client->name}}</option>
                        @endforeach

                    </select>
                </form>
            </div>



            <div class="col my-1 my-1">
                <a href="{{route('clients')}}" class="btn btn-sm btn-outline-dark">Clear Search</a>
            </div>

        </div>


    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="border-right " width="15%">Client Name</th>
            <th class="text-center border-right" width="40%">Information</th>
            <th class="text-center border-right" width="10%">Balance</th>
            <th class="text-center border-right " width="20%">Present since</th>
           @if(in_array(Auth::user()->level, [1,2])) <th class="text-center" width="15%">Actions</th> @endif

        </tr>
        </thead>
        <tbody>
        @if(count($clients))
            @foreach($clients as $client)
                <tr id="{{$client->id}}">
                    <td id="name{{$client->id}}">{{$client->name}}</td>
                    <td class="text-center">{{$client->information}}</td>
                    <td id="bal{{$client->id}}" style="color: @if($client->balance<0) red @else green @endif " class="text-center">{{$client->balance}} lv</td>
                    <td class="text-center">{{date('d.m.Y', strtotime($client->created_at))}}</td>
                    @if(in_array(Auth::user()->level, [1,2])) <td class="text-center">
                        <form method="GET" class="text-center d-inline" action="{{route('clients.edit')}}">
                            <input type="hidden" name="id" value="{{$client->id}}">
                            <button class="btn btn-sm edit"  type="submit" title="Edit client information">Edit</button>
                        </form>
                        <div class="d-inline text-center">
                            <button class="btn  my-1 btn-sm delete" value="{{$client->id}}" title="Delete client" onclick="deleteIt(this.value)">Delete</button>
                        </div>
                    </td> @endif

                </tr>
            @endforeach
        @else
            <tr><td colspan="8" class="text-center">0 Clients found</td> </tr>
        @endif
        </tbody>
    </table>
    <div class="col-12  d-flex justify-content-center">
        {{ $clients->appends(request()->all())->links() }}
    </div>

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.selectName').select2({
                placeholder: 'Search by name'
            });

        });



        function deleteIt(value){

            var balance = parseFloat($("#bal"+value).text());
            var name = $("#name"+value).text();

            if(balance > 0){
                if(confirm("Client "+name+" is owed "+balance+" lv.\n Are you sure, you want to delete this client?")){
                    $.get("{{route('clients.delete')}}", {id: value}, function (data) {

                        $("#" + value).remove();


                    })
                }
            }
            else if(balance < 0){
                balance *= -1;
                if(confirm("Client "+name+" owes "+balance+" lv.\n Are you sure, you want to delete this client?")){
                    $.get("{{route('clients.delete')}}", {id: value}, function (data) {

                        $("#" + value).remove();


                    })
                }
            }
            else{
                if(confirm("Client "+name+" owes nothing.\nAre you sure, you want to delete client "+name+"?")){
                    $.get("{{route('clients.delete')}}", {id: value}, function (data) {

                        $("#" + value).remove();


                    })
                }
            }

        }

    </script>

@endsection
