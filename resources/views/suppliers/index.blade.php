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
                <h1><strong>Suppliers:</strong> @if(request('name')) {{$suppliers->first()->name }} @else All Suppliers @endif</h1>
           </div>

            @if(in_array(Auth::user()->level, [1,2])) <form method="GET" action="{{route('suppliers.create')}}">
                    <button class="btn add my-1 mx-3 float-right" title="Add New Suppliers" type="submit">Add suppliers</button>
                </form> @endif

            </div>


        <div class="row my-5">
            <h3 class="col-xl-1 text-center my-1">Search:</h3>
            <div class="d-inline col-xl-2 mx-3 my-1">
                <form>
                    <select name="name" class="form-control selectName" onchange="this.form.submit()" style="height: 35px">
                        <option value="" @if(!request('name'))selected @endif disabled=""></option>

                        @foreach($searchsuppliers as $supplier)
                            <option @if($supplier->id == request('name')) selected @endif value="{{$supplier->id}}">{{$supplier->name}}</option>
                        @endforeach

                    </select>
                </form>
            </div>



            <div class="col my-1 my-1">
                <a href="{{route('suppliers')}}" class="btn btn-sm btn-outline-dark">Clear Search</a>
            </div>

        </div>


    </div>
    <table class="table table-hover">
        <thead>
        <tr>
            <th class="border-right " width="15%">Supplier Name</th>
            <th class="text-center border-right" width="40%">Information</th>
            <th class="text-center border-right" width="10%">Balance</th>
            <th class="text-center border-right " width="20%">Present since</th>
           @if(in_array(Auth::user()->level, [1,2])) <th class="text-center" width="15%">Actions</th> @endif

        </tr>
        </thead>
        <tbody>
        @if(count($suppliers))
            @foreach($suppliers as $supplier)
                <tr id="{{$supplier->id}}">
                    <td id="name{{$supplier->id}}">{{$supplier->name}}</td>
                    <td class="text-center">{{$supplier->information}}</td>
                    <td id="bal{{$supplier->id}}" style="color: @if($supplier->balance>0) red @else green @endif " class="text-center">{{$supplier->balance}} lv</td>
                    <td class="text-center">{{date('d.m.Y', strtotime($supplier->created_at))}}</td>
                    @if(in_array(Auth::user()->level, [1,2])) <td class="text-center">
                        <form method="GET" class="text-center d-inline" action="{{route('suppliers.edit')}}">
                            <input type="hidden" name="id" value="{{$supplier->id}}">
                            <button class="btn btn-sm edit"  type="submit" title="Edit supplier information">Edit</button>
                        </form>
                        <div class="d-inline text-center">
                            <button class="btn  my-1 btn-sm delete" value="{{$supplier->id}}" title="Delete supplier" onclick="deleteIt(this.value)">Delete</button>
                            @if($supplier->product->where('deleted',0)->first() )
                                <input type="hidden" id="suphas{{$supplier->id}}" value="@foreach($supplier->product->where('deleted',0) as $product){{$product->name}}({{$product->category->name}}->{{$product->subcategory->name}}): {{$product->amount}} {{$product->unit}}$@endforeach ">
                            @endif
                        </div>
                    </td> @endif

                </tr>
            @endforeach
        @else
            <tr><td colspan="8" class="text-center">0 Suppliers found</td> </tr>
        @endif
        </tbody>
    </table>
    <div class="col-12  d-flex justify-content-center">
        {{ $suppliers->appends(request()->all())->links() }}
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
                if(confirm("Supplier "+name+" is owed "+balance+" lv.\nAre you sure, you want to delete this supplier?")){
                    if($("#suphas"+value).length){
                        var p = $("#suphas" + value).val().replaceAll("$", "\n");
                        if(confirm("ATTENTION!\nThe following products are assigned to this supplier:\n\n" + p + "\nIf you to delete the supplier, all above products will be deleted as well!\nAre you sure, you want to delete this supplier?")){
                            $.get("{{route('suppliers.delete')}}", {id: value}, function (data) {

                                $("#" + value).remove();


                            })
                        }
                    }
                    else{
                        if(confirm("There are no products assigned to this supplier.\nAre you sure, you want to delete this supplier?")){
                            $.get("{{route('suppliers.delete')}}", {id: value}, function (data) {

                                $("#" + value).remove();


                            })
                        }
                    }

                }
            }
            else if(balance < 0){
                balance *= -1;
                if(confirm("Supplier "+name+" owes "+balance+" lv. Are you sure, you want to delete this supplier?")){
                    if($("#suphas"+value).length){
                        var p = $("#suphas" + value).val().replaceAll("$", "\n");
                        if(confirm("ATTENTION!\nThe following products are assigned to this supplier:\n\n" + p + "\nIf you to delete the supplier, all above products will be deleted as well!\nAre you sure, you want to delete this supplier?")){
                            $.get("{{route('suppliers.delete')}}", {id: value}, function (data) {

                                $("#" + value).remove();


                            })
                        }
                    }
                    else{
                        if(confirm("There are no products assigned to this supplier.")){
                            $.get("{{route('suppliers.delete')}}", {id: value}, function (data) {

                                $("#" + value).remove();


                            })
                        }
                    }
                }
            }
            else{
                if(confirm("Supplier "+name+" is not owed anything.\nAre you sure, you want to delete this supplier?")){
                    if($("#suphas"+value).length){
                        var p = $("#suphas" + value).val().replaceAll("$", "\n");
                        if(confirm("ATTENTION!\nThe following products are assigned to this supplier:\n\n" + p + "\nIf you to delete the supplier, all above products will be deleted as well!\nAre you sure, you want to delete this supplier?")){
                            $.get("{{route('suppliers.delete')}}", {id: value}, function (data) {

                                $("#" + value).remove();


                            })
                        }
                    }
                    else{
                        if(confirm("There are no products assigned to this supplier.\nAre you sure, you want to delete this supplier?")){
                            $.get("{{route('suppliers.delete')}}", {id: value}, function (data) {

                                $("#" + value).remove();


                            })
                        }
                    }
                }
            }

        }

    </script>

@endsection
