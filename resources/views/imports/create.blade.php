@extends('layouts.app')
@section('head')
    <!--jQuery-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>

    <!--Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"  ></script>


@endsection

@section('content')
    <button class="btn mx-3 my-1 btn-primary" onclick="window.location='{{route('imports')}}'">Back to All Imports</button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header text-center"><h1>New Import</h1></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('imports.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="supplier" class="col-md-4 col-form-label text-md-right">{{ __('Supplier') }}</label>

                                <div class="col-md-5">
                                    <select id="supplier" class="form-control selectSup" onchange="supp(this.value)" name="supplier" autofocus>

                                        <option disabled selected></option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="all">
{{--                        <div class="card my-3">--}}
{{--                            <div class="card-header text-center"><h3>Import Product</h3></div>--}}

{{--                            <div class="card-body">--}}



{{--                                    <div class="form-group row">--}}
{{--                                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Product') }}</label>--}}

{{--                                        <div class="col-md-5">--}}
{{--                                            <select name="name" class="form-control selectName" onchange="" style="height: 35px">--}}
{{--                                                <option value="" selected disabled></option>--}}

{{--                                                @foreach($products as $product)--}}
{{--                                                    <option value="{{$product->id}}">{{$product->name}} : {{$product->category->name}} -> {{$product->subcategory->name}}</option>--}}
{{--                                                @endforeach--}}

{{--                                            </select>--}}

{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group row">--}}
{{--                                        <label for="availability" class="col-md-4 col-form-label text-md-right">{{ __('Availability') }}</label>--}}

{{--                                        <div class="col-md-6">--}}
{{--                                            <p id="availability" name="availability" class="col-form-label">27 kg</p>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group row">--}}
{{--                                        <label for="priceper" class="col-md-4 col-form-label text-md-right">{{ __('Price per kg') }}</label>--}}

{{--                                        <div class="col-10 col-md-5">--}}
{{--                                            <input id="priceper" type="number" step="0.01" min="0" class="form-control" value="1.5" name="priceper" required autofocus>--}}

{{--                                        </div>--}}
{{--                                        <p class="col-2 col-md-1 col-form-label">lv</p>--}}
{{--                                    </div>--}}

{{--                                    <div class="form-group row">--}}
{{--                                        <label for="amount" class="col-md-4 col-form-label text-md-right">{{ __('Amount') }}</label>--}}

{{--                                        <div class="col-md-6">--}}
{{--                                            <input id="amount" type="number" step="1" min="1" max="27" class="form-control" name="amount" required autofocus>--}}

{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                <div class="form-group row">--}}
{{--                                    <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price') }}</label>--}}

{{--                                    <div class="col-md-6">--}}
{{--                                        <p id="price" name="price" class="col-form-label">270 lv</p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}




{{--                                <div class="form-group text-center row mb-0">--}}
{{--                                    <div class="col">--}}
{{--                                        <button class="btn btn-danger">--}}
{{--                                            {{ __('Remove') }}--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}



{{--                            </div>--}}
{{--                        </div>--}}


{{--                            <div class="form-group text-center row mb-0 m-3">--}}
{{--                                <div class="col">--}}
{{--                                    <button title="Add Import Product" class="btn btn-success">--}}
{{--                                        {{ __('+') }}--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}

{{--                            <div class="form-group justify-content-center row m-5">--}}

{{--                                    <div class="col-4  text-center">--}}
{{--                                        <h4 id="importprice" name="importprice">Import Price: 169.9 lv</h4>--}}
{{--                                    </div>--}}


{{--                            <div class="col-12 text-center">--}}
{{--                                <button type="submit" class="btn btn-primary">--}}
{{--                                    {{ __('Save Import') }}--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                            </div>
                    </form>
                    </div>



                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>

        function supp(value){

            $.get("{{route('imports.supplier')}}", {id: value}, function(data) {

                $("#all").html(data);
                $('.selectName').select2({
                });

            })
        }

        function insert(product, idraw){
            $('#'+idraw+' :not(:selected)').attr('disabled','disabled');

            var id = idraw.replace('name','');

            $.get("{{route('imports.insert')}}", {product:product, id:id }, function(data){
                $("#insert"+id).html(data);
            })

            $("#addproduct").html("<div id='add' class=\"form-group text-center row mb-0 m-3\">" +
            "<div class=\"col\">" +
            "<button title=\"Add Import Product\" type='button' onclick='addproduct()' class=\"btn btn-success\">Add Import Product </button>" +
            "</div>" +
            "</div>")
        }

        function price(idraw){
            var id = idraw.replace('amount','');
            id = id.replace('priceper','');

            var price = $("#priceper"+id).val();
            var amount = $("#amount"+id).val();

            if(amount<0){
                amount = 0;
            }
            if(price<0){
                price = 0;
            }
            var data = "<div class=\"form-group row\">" +
                "<label for=\"price"+id+"\" class=\"col-md-4 col-form-label text-md-right\">Price</label>"+
                "<div class=\"col-md-6\">" +
                "<p id=\"price"+id+"\" name=\"price"+id+"\" class=\"col-form-label\">"+(price*amount).toFixed(2)+" lv</p>" +
                "</div>" +
                "</div>";

            $("#insertprice"+id).html(data);

            var importprice = 0;
            for(var i=1; i<101; i++){
                if($("#price"+i).length){
                    importprice += parseFloat($("#price"+i).text().replace(' lv',''));
                }
            }
            $("#importprice").html("Import Price: "+importprice.toFixed(2)+" lv")


        }

        function addproduct(){
            if($("#addproduct").prevAll('div').length){
                var id = parseInt($("#addproduct").prevAll('div').attr("id").replace('p',''))+1;
            }
            else{
                var id = 1;
            }
            var supplier = $("#supplier").val();

            var taken = [-3];

            var j = 0;
            for(var i =1;i<101;i++){

                if($("#name"+i).length){
                    taken[j] = $("#name"+i).val();
                    j++;
                }
            }



            $.get("{{route('imports.addproduct')}}", {id: id, supplier:supplier, taken:taken}, function(data){
                $("#addproduct").replaceWith(data);
                $('.selectName').select2({
                    // placeholder: 'Product Name'
                });
            })


        }

        function deleteIt(value){
            if(confirm("Are you sure, you want to delete this Import Product?")){


                if(!$("#priceper"+value).length){

                    $("#addproduct").html("<div id='add' class=\"form-group text-center row mb-0 m-3\">" +
                        "<div class=\"col\">" +
                        "<button title=\"Add Import Product\" type='button' onclick='addproduct()' class=\"btn btn-success\">Add Import Product </button>" +
                        "</div>" +
                        "</div>")

                }

                $("#p"+value).remove();

                var importprice = 0;
                for(var i=1; i<101; i++){
                    if($("#price"+i).length){
                        importprice += parseFloat($("#price"+i).text().replace(' lv',''));
                    }
                }
                $("#importprice").html("Import Price: "+importprice.toFixed(2)+" lv")



            }
        }
    </script>



    <script>
        $(document).ready(function() {
            $('.selectName').select2({
                // placeholder: 'Product Name'
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('.selectSup').select2({
            });

        });
    </script>







@endsection

