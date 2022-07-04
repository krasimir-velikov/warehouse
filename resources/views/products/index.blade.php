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
                <h1><strong>Product Availability</strong> @if(request('cat')): {{$categories->where('id',request('cat'))->first()->name}}
                    @elseif(request('subcat')): {{$subcategories->where('id',request('subcat'))->first()->category->name}}/{{$subcategories->where('id',request('subcat'))->first()->name}}@endif
                @if(request('name'))-> {{$products->where('id',request('name'))->first()->name}}
                    @elseif(request('namelike'))-> {{request('namelike')}} @endif @if(!request('cat')&&!request('subcat')&&!request('name')&&!request('namelike')): All Products @endif</h1>
            </div>
            @if(in_array(Auth::user()->level, [1,2])) <div class="col">
{{--                <button class="addproduct btn my-1 mx-2 float-right">Add product</button>--}}
{{--                <button class="categories btn my-1 mx-2 float-right">Manage Categories</button>--}}
                <form method="GET" action="{{route('products.create')}}">
                    <button class="btn addproduct my-1 mx-2 float-right" title="Add New Product" type="submit">Add products</button>
                </form>
                <form method="GET" action="{{route('categories')}}">
                    <button class="btn categories my-1 mx-2 float-right" title="Add New Product" type="submit">Manage categories</button>
                </form>
            </div> @endif

        </div>
        <div class="row my-5">
            <h3 class="col-xl-1 text-center my-1">Search:</h3>
            <div class="d-inline col-xl-2 mx-3 my-1">
                <form>
                    <select name="name" class="form-control selectName" onchange="this.form.submit()" style="height: 35px">
                        <option value="" @if(!request('name'))selected @endif disabled="">Filter by name</option>

                        @foreach($searchproducts as $product)
                            <option @if($product->id == request('name')) selected @endif value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach

                    </select>
                </form>
            </div>
            <h3 class="col-xl-1 text-center my-1">Filters:</h3>




            <div class="d-inline col-xl-2 mx-3 my-1">
                <form>
                <select name="cat" class="form-control selectCat" onchange="this.form.submit()" style="height: 35px">
                    <option value="" @if(!request('cat'))selected @endif disabled="">Filter by category</option>
                    @foreach($categories as $cat)
                        <option @if($cat->id == request('cat')) selected @endif value="{{$cat->id}}">{{$cat->name}}</option>
                    @endforeach
                </select>
            </form>
        </div>

            @if(request('cat') || request('subcat'))
            <div class="d-inline col-xl-2 mx-3 my-1">
                <form>

                <select name="subcat" class="form-control selectSubCat" onchange="this.form.submit()" style="height: 35px">
                    <option value="" @if(!request('subcat'))selected @endif disabled="">Filter by subcategory</option>
                    @foreach($subcategories as $subcat)
                        <option @if($subcat->id == request('subcat'))selected @endif value="{{$subcat->id}}">{{$subcat->name}}</option>
                    @endforeach
                </select>
                </form>
            </div>
            @endif
            <div class="d-inline col-xl-2 mx-3 my-1">
                <form>
                    <input name="namelike" class="form-control" value="{{request('namelike')}}" placeholder="Name" onfocusout="this.form.submit()">
                    @if(request('cat'))<input type="hidden" name="cat" value="{{request('cat')}}">
                    @elseif(request('subcat'))<input type="hidden" name="subcat" value="{{request('subcat')}}">@endif
                </form>
            </div>

            <div class="col my-1 my-1">
                <a href="{{route('products')}}" class="btn btn-sm btn-outline-dark float-right">Clear Filters</a>
            </div>

{{--            <div class="d-inline col-2 mx-3">--}}
{{--                <form>--}}
{{--                <select name="suppplier" class="form-control selectSup" onchange="this.form.submit()" style="height: 35px">--}}
{{--                    <option value="" @if(!request('supplier'))selected @endif disabled="">Filter by supplier</option>--}}
{{--                    <option value="1">1</option>--}}
{{--                    <option value="2">2</option>--}}
{{--                    <option value="3">3</option>--}}
{{--                </select>--}}
{{--                </form>--}}
{{--            </div>--}}



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
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" class="text-center">{{$categories->where('id', $product->category_id)->first()->name}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" class="text-center">{{$subcategories->where('id', $product->subcategory_id)->first()->name}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" class="text-center">{{$product->bought_for}} lv/{{$product->unit}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" class="text-center">{{$product->sold_for}} lv/{{$product->unit}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" id="amount{{$product->id}}" class="text-center" style="color: @if($product->amount == 0)red @else green @endif">{{$product->amount}} {{$product->unit}}</td>
                        <td onclick="window.location.href='{{route('products.show', ['id'=>$product->id])}}'" class="text-center">@if(count($suppliers) && $suppliers->where('id', $product->supplier_id)->first()){{$suppliers->where('id',$product->supplier_id)->first()->name}}@else - @endif</td>
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
