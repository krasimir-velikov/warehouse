@extends('layouts.app')
@section('head')
    <!--jQuery-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>

    <!--Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"  ></script>


@endsection

@section('content')
    <button class="btn mx-3 my-1 btn-primary" onclick="window.location='{{route('exports')}}'">Back to All Exports</button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header text-center"><h1>New Import</h1></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('exports.store') }}">
                            @csrf
                            <div class="form-group row">
                                <label for="client" class="col-md-4 col-form-label text-md-right">{{ __('Client') }}</label>

                                <div class="col-md-5">
                                    <select id="client" class="form-control selectClient" onchange="client1(this.value)" name="client" autofocus>

                                        <option disabled selected></option>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}">{{$client->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="all">

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

        function client1(value){

            $.get("{{route('exports.client')}}", {id: value}, function(data) {

                $("#all").html(data);
                $('.selectName').select2({
                });

            })
        }

        function insert(product, idraw){
            $('#'+idraw+' :not(:selected)').attr('disabled','disabled');

            var id = idraw.replace('name','');

            $.get("{{route('exports.insert')}}", {product:product, id:id }, function(data){
                $("#insert"+id).html(data);
            })

            $("#addproduct").html("<div id='add' class=\"form-group text-center row mb-0 m-3\">" +
            "<div class=\"col\">" +
            "<button title=\"Add Export Product\" type='button' onclick='addproduct()' class=\"btn btn-success\">Add Export Product </button>" +
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

            var exportprice = 0;
            for(var i=1; i<101; i++){
                if($("#price"+i).length){
                    exportprice += parseFloat($("#price"+i).text().replace(' lv',''));
                }
            }
            $("#exportprice").html("Export Price: "+exportprice.toFixed(2)+" lv")


        }

        function addproduct(){
            if($("#addproduct").prevAll('div').length){
                var id = parseInt($("#addproduct").prevAll('div').attr("id").replace('p',''))+1;
            }
            else{
                var id = 1;
            }
            var supplier = $("#client").val();

            var taken = [-3];

            var j = 0;
            for(var i =1;i<101;i++){

                if($("#name"+i).length){
                    taken[j] = $("#name"+i).val();
                    j++;
                }
            }



            $.get("{{route('exports.addproduct')}}", {id: id, supplier:supplier, taken:taken}, function(data){
                $("#addproduct").replaceWith(data);
                $('.selectName').select2({
                    // placeholder: 'Product Name'
                });
            })


        }

        function deleteIt(value){
            if(confirm("Are you sure, you want to delete this Export Product?")){


                if(!$("#priceper"+value).length){

                    $("#addproduct").html("<div id='add' class=\"form-group text-center row mb-0 m-3\">" +
                        "<div class=\"col\">" +
                        "<button title=\"Add Export Product\" type='button' onclick='addproduct()' class=\"btn btn-success\">Add Export Product </button>" +
                        "</div>" +
                        "</div>")

                }

                $("#p"+value).remove();

                var exportprice = 0;
                for(var i=1; i<101; i++){
                    if($("#price"+i).length){
                        exportprice += parseFloat($("#price"+i).text().replace(' lv',''));
                    }
                }
                $("#exportprice").html("Export Price: "+exportprice.toFixed(2)+" lv")



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
            $('.selectClient').select2({
            });

        });
    </script>







@endsection

