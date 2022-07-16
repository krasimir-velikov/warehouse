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
                    <div class="card-header text-center"><h1>Edit Export</h1></div>

                    <div class="card-body">
                            @csrf
                            <div class="form-group row">
                                <label for="client" class="col-md-4 col-form-label text-md-right">{{ __('Client') }}</label>

                                <div class="col-md-5 form-control">
                                    <p>{{$export->client->name}}@if($export->client->deleted) <strong>Deleted Client</strong>@endif</p>
                                </div>
                            </div>
                                <div class="form-group row">


                                <label for="created" class="col-md-4  col-form-label text-md-right">{{ __('Created') }}</label>

                                <div class="col-md-5 form-control">
                                    <p>{{date('d.m.Y H:i:s', strtotime($export->created_at))}}</p>
                                </div>
                                </div>
                            <div class="form-group row">


                            <label for="creator" class="col-md-4  col-form-label text-md-right">{{ __('Created by') }}</label>

                                <div class="col-md-5 form-control">
                                    <p>{{$export->user->name}} ({{$export->user->email}})@if($export->user->status==2)<strong> Deleted Employee</strong>@elseif($export->user->status==0)<strong> Blocked Employee</strong>@endif</p>
                                </div>
                            </div>

                                @if($export->updated_by)
                                <div class="form-group row">

                                <label for="edited" class="col-md-4  col-form-label text-md-right">{{ __('Last edited') }}</label>

                                    <div class="col-md-5 form-control">
                                        <p>{{date('d.m.Y H:i:s', strtotime($export->updated_at))}}</p>
                                    </div>
                                </div>
                                <div class="form-group row">


                                <label for="editor" class="col-md-4  col-form-label text-md-right">{{ __('Last edited by') }}</label>

                                    <div class="col-md-5 form-control">
                                        <p>{{App\User::where('id', $export->updated_by)->first()->name}} ({{App\User::where('id', $export->updated_by)->first()->email}})@if(App\User::where('id', $export->updated_by)->first()->status==2)<strong> Deleted Employee</strong>@elseif(App\User::where('id', $export->updated_by)->first()->status==0)<strong> Blocked Employee</strong>@endif</p>
                                    </div>
                                </div>
                                @endif
                            <div class="col-12  text-center">
                                <h4 id="exportprice" name="exportprice">Export Price: {{$export->price}} lv</h4>
                            </div>



                                @foreach($export->export_product->where('amount', '!=', 0) as $key=>$exproduct)
                                    <div id='p{{$key+1}}' class="card my-3">
                                        <div class="card-header text-center"><h3>Export Product</h3></div>

                                        <div class="card-body">



                                            <div class="form-group row">
                                                <label for="name{{$key+1}}" class="col-md-4  text-md-right">Product</label>

                                                <div name="name{{$key+1}}" class="col-md-5 col-form-control">
                                                    <p>{{$exproduct->product->name}}: ({{$exproduct->product->category->name}} -> {{$exproduct->product->subcategory->name}})@if($exproduct->product->deleted) <strong>Deleted Product</strong>@endif</p>

                                                </div>
                                            </div>
                                            <div id='insert{{$key+1}}'>
                                                <div class="form-group row">
                                                    <label for="availability{{$key+1}}" class="col-md-4 col-form-label text-md-right">Availability</label>
                                                    <div class="col-md-6">
                                                        <p id="availability{{$key+1}}" style='color: {{($exproduct->product->amount > 0)? "green" : "red"}}' name="availability{{$key+1}}" class="col-form-label">{{$exproduct->product->amount}} {{$exproduct->product->unit}}</p>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="priceper{{$key+1}}" class="col-md-4 col-form-label text-md-right">Price per {{$exproduct->product->unit}}</label>

                                                    <div class="col-10 col-md-5">
                                                        <input id="priceper{{$key+1}}" type="number" step="0.01" min="0.01" class="form-control" onchange="price(this.id)" value="{{$exproduct->price}}" name="priceper{{$key+1}}" required autofocus>

                                                    </div>
                                                    <p class="col-2 col-md-1 col-form-label">lv</p>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="amount" {{$key+1}} class="col-md-4 col-form-label text-md-right">Amount</label>

                                                    <div class="col-10 col-md-5">
                                                        <input onchange="price(this.id)" id="amount{{$key+1}}" value="{{$exproduct->amount}}" type="number" step="1" min="1" max="{{$exproduct->amount_before}}" class="form-control" name="amount{{$key+1}}" required autofocus>

                                                    </div>
                                                    <p class="col-2 col-md-1 col-form-label">{{$exproduct->product->unit}}</p>
                                                </div>
                                                <div id='insertprice{{$key+1}}'>
                                                    <div class="form-group row">
                                                        <label for="price{{$key+1}}" class="col-md-4 col-form-label text-md-right">Price</label>
                                                        <div class="col-md-6">
                                                            <p id="price{{$key+1}}" name="price{{$key+1}}" class="col-form-label">{{round($exproduct->price*$exproduct->amount, 2)}} lv</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <input type="hidden" value="{{$exproduct->id}}" name="id{{$key+1}}" id="id{{$key+1}}">


                                            <div class="form-group text-center row mb-0">
                                                <div class="col">
                                                    <button value="{{$key+1}}" type="button" class="btn btn-primary" onclick="edit(this.value)">
                                                        Save
                                                    </button>
                                                    <button value='{{$key+1}}' name="{{$exproduct->id}}" type='button' class="btn btn-danger" onclick='deleteIt(this.value, this.name)'>
                                                        Remove
                                                    </button>

                                                </div>
                                            </div>



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

        function price(idraw){
            var id = idraw.replace('amount','');
            id = id.replace('priceper','');

            var price = $("#priceper"+id).val();
            var amount = parseInt($("#amount"+id).val());

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



        function deleteIt(value, name){


            var count = 0;
            for(var i = 1; i<101; i++){
                if($("#p"+i).length){
                    count++;
                }
            }
            if(count > 1){
                if(confirm("Are you sure, you want to delete this Export Product?")){





                    $.post("{{route('exports.delete')}}", {_token: "{{ csrf_token() }}", id: name}, function(data){
                        alert(data);

                        if(data == "Successfully deleted export product."){
                            $("#p"+value).remove();
                        }


                    })

                    var importprice = 0;
                    for(var i=1; i<101; i++){
                        if($("#price"+i).length){
                            importprice += parseFloat($("#price"+i).text().replace(' lv',''));
                        }
                    }
                    $("#exportprice").html("Export Price: "+exportprice.toFixed(2)+" lv")



                }
            }
            else{
                alert("Empty exports are not allowed!\nThere must be at least one export product.\nIf you want to delete the whole export, use the delete button on the All Exports page.")
            }

        }

        function edit(value){
            var id = $("#id"+value).val();
            var amount = parseInt($("#amount"+value).val())
            var priceper = parseFloat($("#priceper"+value).val());
            priceper = priceper.toFixed(2);
            var price = parseFloat(($("#exportprice").text()).slice(14));



            $.post("{{route('exports.update')}}", {_token: "{{ csrf_token() }}", id: id, amount: amount, priceper:priceper, price:price  }, function(data){
                if(data[0]=="Successfully edited export product."){
                    $("#availability"+value).text(data[1]+" "+data[2]);
                    if(data[1]==0){
                        $("#availability"+value).css('color','red')
                    }
                    else{
                        $("#availability"+value).css('color','green')

                    }
                }
                alert(data[0]);

            })
        }
    </script>










@endsection

