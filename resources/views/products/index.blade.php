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

        .addproduct{
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
                <h1><strong>Product Availability</strong>
{{--                    @if(request('subcat')): {{$subcategories->where('id',request('subcat'))->first()->category->name}}/{{$subcategories->where('id',request('subcat'))->first()->name}}--}}
{{--                    @elseif(request('cat')): {{$categories->where('id',request('cat'))->first()->name}}@endif--}}
                    @if(request('cat')): {{$categories->where('id',request('cat'))->first()->name}}@endif
                    @if(request('subcat'))/{{$subcategories->where('id',request('subcat'))->first()->name}}@endif
                    @if(request('name'))-> {{$products->where('id',request('name'))->first()->name}}
                    @elseif(request('namelike'))-> {{request('namelike')}} @endif
                    @if(request('supplier')) by {{$suppliers->where('id',request('supplier'))->first()->name}} @endif
                    @if(request('available')==1) (Available)@elseif(request('available')==2) (Unavailable)@endif
                    @if(!request('cat')&&!request('subcat')&&!request('name')&&!request('namelike')&&!request('supplier')&&!('available')): All Products @endif</h1>
            </div>
            @if(in_array(Auth::user()->level, [1,2])) <div class="col">

                <form method="GET" action="{{route('products.create')}}">
                    <button class="btn addproduct my-1 mx-2 float-right" title="Add New Product" type="submit">Add products</button>
                </form>
                <form method="GET" action="{{route('categories')}}">
                    <button class="btn categories my-1 mx-2 float-right" title="Manage Categories" type="submit">Manage categories</button>
                </form>
            </div> @endif

        </div>
        <div class="row my-5">
            <form class="form-inline">
                <h3 class="mx-3 my-1">Search:</h3>
                <div class="m-2">

                        <select name="name" class="form-control selectName" onchange="this.form.submit()" style="height: 35px">
                            <option value="" @if(!request('name'))selected @endif disabled="">Filter by name</option>

                            @foreach($searchproducts as $product)
                                <option @if($product->id == request('name')) selected @endif value="{{$product->id}}">{{$product->name}}: {{$product->category->name}} -> {{$product->subcategory->name}}</option>
                            @endforeach

                        </select>

                </div>
                <h3 class="mx-3 my-1">Filters:</h3>




                <div class="m-2">
                    <label for="cat" class="form-label ">{{ __('Category: ') }}</label>
                    <select name="cat" class="form-control selectCat" onchange="this.form.submit()" style="height: 35px">
                        <option value="" @if(!request('cat'))selected @endif disabled="">Filter by category</option>
                        @foreach($categories as $cat)
                            <option @if($cat->id == request('cat')) selected @endif value="{{$cat->id}}">{{$cat->name}}</option>
                        @endforeach
                    </select>

                </div>

                @if(request('cat') || request('subcat'))
                <div class="m-2">

                    <label for="subcat" class="form-label ">{{ __('Subcategory: ') }}</label>

                    <select name="subcat" class="form-control selectSubCat" onchange="this.form.submit()" style="height: 35px">
                        <option value="" @if(!request('subcat'))selected @endif disabled="">Filter by subcategory</option>
                        @foreach($subcategories as $subcat)
                            <option @if($subcat->id == request('subcat'))selected @endif value="{{$subcat->id}}">{{$subcat->name}}</option>
                        @endforeach
                    </select>

                </div>
                @endif
                <div class="m-2">
                        <label for="namelike" class="form-label ">{{ __('Name: ') }}</label>
                        <input name="namelike" class="form-control" value="{{request('namelike')}}" placeholder="Name" onfocusout="this.form.submit()">


                </div>

                <div class="m-2">
                    <label for="supplier" class="form-label ">{{ __('Supplier: ') }}</label>
                    <select name="supplier" class="form-control selectSup" onchange="this.form.submit()" style="height: 35px">
                        <option value="" @if(!request('supplier'))selected @endif disabled="">Filter by supplier</option>
                        @foreach($suppliers as $supplier)
                            <option @if($supplier->id == request('supplier')) selected @endif value="{{$supplier->id}}">{{$supplier->name}}</option>
                        @endforeach
                    </select>

                </div>

                <div class="m-2">
                    <label for="available" class="form-label ">Status:</label>
                    <select name="available" class="form-control " onchange="this.form.submit()" style="height: 35px">
                        <option value="" disabled @if(!request('available')) selected @endif >Select Availability</option>
                        <option value="1" @if(request('available') == 1) selected @endif>Available</option>
                        <option value="2" @if(request('available') == 2) selected @endif>Unavailable</option>
                    </select>
                </div>
            </form>





            <div class="col m-2">
                <a href="{{route('products')}}" class="btn btn-sm btn-outline-dark float-right">Clear Filters</a>
            </div>
        </div>


    </div>
{{--    <div class="overflow-auto">--}}
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="border-right " width="20%">Product Name</th>
                    <th class="text-center border-right" width="10%">Category</th>
                    <th class="text-center border-right" width="15%">Subcategory</th>
                    <th class="text-center border-right " width="10%">Buy price</th>
                    <th class="text-center border-right " width="10%">Sell price</th>
                    <th class="text-center border-right" width=10%">Availability</th>
                    <th class="text-center border-right " width="15%">Supplier</th>
                    @if(in_array(Auth::user()->level, [1,2])) <th class="text-center" width="10%">Actions</th> @endif

                </tr>
            </thead>
            <tbody>
            @if(count($products))
                @foreach($products as $product)
                    <tr title="Click for Details" id="{{$product->id}}">
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" id="name{{$product->id}}">{{$product->name}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" class="text-center">{{$product->category->name}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" class="text-center">{{$product->subcategory->name}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" class="text-center">{{$product->bought_for}} lv/{{$product->unit}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" class="text-center">{{$product->sold_for}} lv/{{$product->unit}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" id="amount{{$product->id}}" class="text-center" style="color: @if($product->amount == 0)red @else green @endif">{{$product->amount}} {{$product->unit}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" class="text-center">{{$product->supplier->name}}</td>
                        @if(in_array(Auth::user()->level, [1,2])) <td class="text-center">
                            <form method="GET" class="text-center d-inline" action="{{route('products.edit')}}">
                                <input type="hidden" name="id" value="{{$product->id}}">
                                <button class="btn btn-sm edit"  type="submit" title="Edit the product">Edit</button>
                            </form>
                            <div class="d-inline text-center">
                                <button class="btn  my-1 btn-sm delete" value="{{$product->id}}" title="Delete the product" onclick="deleteIt(this.value)">Delete</button>
                            </div>
                        </td> @endif

                    </tr>
                @endforeach
            @else
                <tr><td colspan="8" class="text-center">0 Products found</td> </tr>
            @endif
            </tbody>
        </table>
    <div class="col-12  d-flex justify-content-center">
            {{ $products->appends(request()->all())->links() }}
    </div>
{{--    </div>--}}

@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('.selectName').select2({
                placeholder: 'Search by name'
            });

        });


        $(document).ready(function() {
            $('.selectCat').select2({
                placeholder: 'Category'
            });

        });


        $(document).ready(function() {
            $('.selectSubCat').select2({
                placeholder: 'Subcategory'
            });

        });


        $(document).ready(function() {
            $('.selectSup').select2({
                placeholder: 'Supplier'
            });

        });
        function deleteIt(value){

            var amount = $("#amount"+value).text();
            var int = parseInt(amount);
            var name = $("#name"+value).text();


            if(confirm(((int != 0) ? "ATTENTION! "+amount+" of "+name+" are listed in the warehouse." : "") +" Are you sure, you want to delete the product "+name+"?"))
            {
                $.get("{{route('products.delete')}}", {id: value}, function (data) {

                    $("#" + data).remove();


                })
            }
        }
    </script>

@endsection
