@extends('layouts.app')
@section('head')
    <style>
        a{
            color: black;
            text-decoration: none;
        }
        a:hover{
            text-decoration: none;
        }

        .edit{
            background-color: orange;
            color: white;
        }
        .create{
            color: white;
            font-weight: bold;
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
    <button class="mx-3 btn btn-primary" onclick="window.location='{{route('products')}}'">Back to Products</button>

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h1>Categories</h1>
                    </div>

                    <div class="card-body">
                        <div class="text-center">
                            <form method="GET" action="{{route('categories.create')}}">
                                <button class="btn-primary btn create" title="New category" type="submit">Add Category</button>
                            </form>
                        </div>

                            @foreach($categories as $category)
                            <div id="cat{{$category->id}}" class="my-5 card">
                                <div class="card-header text-center">
                                <a role="button" href="#coll{{$category->id}}" data-toggle="collapse">
                                    <h3 class="">{{$category->name}}</h3>

                                </a>

                                    <form method="GET"  action="{{route('categories.edit')}}">
                                        <input type="hidden" name="id" value="{{$category->id}}">
                                        <button class="btn btn-sm edit"  type="submit" title="Edit category">Edit</button>
                                    </form>
                                    <button class="btn btn-sm delete" value="{{$category->id}}" title="Delete category" onclick="deleteCat(this.value)">Delete</button>
                                    @if($category->product->where('deleted',0)->first() )
                                        <input type="hidden" id="cathas{{$category->id}}" value="@foreach($category->product->where('deleted',0) as $product){{$product->name}}: {{$product->amount}} {{$product->unit}}$@endforeach ">
                                    @endif

                                </div>




                                <div class="card-body collapse" id="coll{{$category->id}}">
                                    <h6 class="text-center">Subcategories:</h6>
                                    <table class="my-3 table">
                                        <thead>



                                        </thead>
                                        <tbody>
                                        <div class="text-center">
                                        <form method="GET" action="{{route('subcategories.create')}}">
                                            <input type="hidden" name="cat" value="{{$category->id}}">
                                            <button class="btn btn-primary btn-sm create" title="New subcategory" type="submit">Add Subcategory</button>
                                        </form>
                                        </div>
                                        @foreach($category->subcategory->where('deleted',0)->sortBy('name') as $subcategory)
                                        <tr id="subcat{{$subcategory->id}}">
                                            <td style="background-color: #f2f2f2; " class="border text-center">
                                                <h5>{{$subcategory->name}}</h5>
                                                <div class="text-center">
                                                    <form method="GET"  action="{{route('subcategories.edit')}}">
                                                        <input type="hidden" name="id" value="{{$subcategory->id}}">
                                                        <button class="btn btn-sm edit"  type="submit" title="Edit subcategory">Edit</button>
                                                    </form>
                                                    <button class="btn btn-sm delete" value="{{$subcategory->id}}" title="Delete subcategory" onclick="deleteSubcat(this.value)">Delete</button>
                                                    @if($subcategory->product->where('deleted',0)->first() )
                                                        <input type="hidden" id="subhas{{$subcategory->id}}" value="@foreach($subcategory->product->where('deleted',0) as $product){{$product->name}}: {{$product->amount}} {{$product->unit}}$@endforeach ">
                                                    @endif
                                                </div>

                                            </td>


                                        </tr>
                                        <tr>
                                            <td></td>

                                        </tr>
                                            @endforeach




                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach


                    </div>
                </div>
            </div>
        </div>



        @endsection

        @section('scripts')

            <script>

                function deleteSubcat(value) {

                    if ($("#subhas" + value).length) {
                        var p = $("#subhas" + value).val().replaceAll("$", "\n");
                        if (confirm("ATTENTION!\nThe following products are listed in this subcategory:\n\n" + p + "\nIf you want to delete the subcategory, all above products will be deleted as well!")) {

                            $.get("{{route('subcategories.delete')}}", {id: value}, function (data) {
                                // alert("Subcategory " + data + " successfully deleted.");
                                $("#subcat" + value).remove();


                            })
                        }

                    } else {
                        if (confirm("There are no products listed in this subcategory.\nAre you sure, you want to delete it?")) {

                            $.get("{{route('subcategories.delete')}}", {id: value}, function (data) {
                                // alert("Subcategory " + data + " successfully deleted.");
                                $("#subcat" + value).remove();


                            })
                        }
                    }
                }
                function deleteCat(value){

                    if($("#cathas"+value).length){
                        var p = $("#cathas"+value).val().replaceAll("$", "\n");
                        if(confirm("ATTENTION!\nThe following products are listed in this category:\n\n"+p+"\nIf you want to delete the category, all above products and all corresponding subcategories will be deleted as well!")){

                            $.get("{{route('categories.delete')}}", {id: value}, function (data) {
                                // alert("Category "+data+" successfully deleted.");
                                $("#cat"+value).remove();


                            })
                        }

                    }
                    else{
                        if(confirm("There are no products listed in this category.\nAre you sure, you want to delete it?")){

                            $.get("{{route('categories.delete')}}", {id: value}, function (data) {
                                // alert("Category "+data+" successfully deleted.");
                                $("#cat"+value).remove();


                            })
                        }
                    }




                    {{--var email = $("#"+value+"E").text();--}}
                    {{--if(confirm("Are you sure, you want to delete "+email+"?")) {--}}


                    {{--    $.get("{{route('employees.delete')}}", {id: value}, function (data) {--}}

                    {{--        $("#row" + data).remove();--}}


                    {{--    })--}}
                    {{--}--}}
                }

            </script>

@endsection

