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

    <!--DateRangePicker-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="{{asset("js/daterange.js")}}"></script>


@endsection

@section('content')
    <div class="container-fluid">
        <div class="row my-2 justify-content-between">
            <div class="col-8 mx-5">
{{--                <h1><strong>Imports</strong> @if(request('cat')): {{$categories->where('id',request('cat'))->first()->name}}--}}
{{--                    @elseif(request('subcat')): {{$subcategories->where('id',request('subcat'))->first()->category->name}}/{{$subcategories->where('id',request('subcat'))->first()->name}}@endif--}}
{{--                @if(request('name'))-> {{$products->where('id',request('name'))->first()->name}}--}}
{{--                    @elseif(request('namelike'))-> {{request('namelike')}} @endif @if(!request('cat')&&!request('subcat')&&!request('name')&&!request('namelike')): All Products @endif</h1>--}}
                <h1><strong>Imports</strong></h1>

            </div>
            <div class="col">

                <form method="GET" action="{{route('imports.create')}}">
                    <button class="btn addproduct my-1 mx-2  float-right" title="Add new import" type="submit">New Import</button>
                </form>
                <form method="GET" action="{{route('exports')}}">
                    <button class="btn categories my-1 mx-2  float-right" title="See all exports" type="submit">Exports</button>
                </form>
            </div>

        </div>
        <div class="row my-5">
            <h3 class="my-1 mx-3">Filters:</h3>

            <form id="filters" class="form-inline">

                <div class="m-2">
                <label for="daterange" class="form-label ">{{ __('Date: ') }}</label>
                <input  name="daterange" id="daterange" class="form-control " autocomplete="off" @if(request('daterange'))  value="{{request('daterange')}}" @else value="{{"01.01.2022 - ".date('d.m.Y')}}" @endif />
                </div>
                <div class="m-2">
                <label for="supplier" class="form-label ">{{ __('Supplier: ') }}</label>
                <select name="supplier" class="form-control  selectSup" onchange="this.form.submit()" style="height: 35px">
                    <option value="" @if(!request('supplier'))selected @endif disabled="">Filter by supplier</option>
                    @foreach($suppliers as $supplier)
                        <option @if($supplier->id == request('supplier')) selected @endif value="{{$supplier->id}}">{{$supplier->name}}@if($supplier->deleted) *Deleted Supplier*@endif</option>
                    @endforeach
                </select>
                </div>
                <div class="m-2">
                <label for="created_by" class="form-label ">{{ __('Added by: ') }}</label>
                <select name="created_by" class="form-control  selectCreator" onchange="this.form.submit()" style="height: 35px">
                    <option value="" @if(!request('created_by'))selected @endif disabled="">Employee</option>
                    @foreach($employees as $employee)
                        <option @if($employee->id == request('created_by')) selected @endif value="{{$employee->id}}">{{$employee->name}} ({{$employee->email}})@if($employee->status==2) *Deleted Employee*@elseif($employee->status==0) *Blocked Employee*@endif</option>
                    @endforeach
                </select>
                </div>
                <div class="m-2">
                    <label for="product" class="form-label ">{{ __('Product: ') }}</label>
                    <select name="product" class="form-control  selectProduct" onchange="this.form.submit()" style="height: 35px">
                        <option value="" @if(!request('product'))selected @endif disabled="">Product</option>
                        @foreach($products as $product)
                            <option @if($product->id == request('product')) selected @endif value="{{$product->id}}">{{$product->name}}: {{$product->category->name}} -> {{$product->subcategory->name}}@if($product->deleted==0)*Deleted Employee*@endif</option>
                        @endforeach
                    </select>
                </div>


                <div class="m-2">
                <label for="status" class="form-label ">Status:</label>
                <select name="status" class="form-control " onchange="this.form.submit()" style="height: 35px">
                    <option value="" disabled @if(!request('status')) selected @endif >Select payment status</option>
                    <option value="1" @if(request('status') == 1) selected @endif>Not payed</option>
                    <option value="2" @if(request('status') == 2) selected @endif>Payed</option>
                </select>
                </div>









            </form>

            <div class="col my-1 my-1">
                <a href="{{route('imports')}}" class="btn btn-sm btn-outline-dark float-right">Clear Filters</a>
            </div>




        </div>


    </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th class="text-center border-right " width="20%">From</th>
                    <th class="text-center border-right" width="20%">Supplier</th>
                    <th class="text-center border-right" width="15%">Price</th>
                    <th class="text-center border-right " width="20%">Created by</th>
                    <th class="text-center border-right" width=15%">Payment Status</th>
                    @if(in_array(Auth::user()->level, [1,2,4]))<th class="text-center" width="10%">Actions</th>@endif

                </tr>
            </thead>
            <tbody>
            @if(count($imports))
                @foreach($imports as $import)
                    <tr id="tr{{$import->id}}" title="Click for Details" id="{{$import->id}}">
                        <td onclick="window.location.href='{{route('imports.show', ['id'=>$import->id])}}'"  class="text-center">{{date('d.m.Y H:i:s', strtotime($import->created_at))}}</td>
                        <td onclick="window.location.href='{{route('imports.show', ['id'=>$import->id])}}'"  class="text-center">{{$import->supplier->name}}@if($import->supplier->deleted)<br><strong>Deleted Supplier</strong>@endif</td>
                        <td onclick="window.location.href='{{route('imports.show', ['id'=>$import->id])}}'"  class="text-center">{{$import->price}} lv</td>
                        <td onclick="window.location.href='{{route('imports.show', ['id'=>$import->id])}}'"  class="text-center">{{$import->user->name}} ({{$import->user->email}})<br>
                            @switch($import->user->level)
                                @case(1)
                                    --SuperAdmin--
                                    @break
                                @case(2)
                                    --Admin--
                                    @break
                                @case(4)
                                    --Worker--
                                    @break
                            @endswitch
                        </td>
                        @if($import->status==1) <td id="pstatus{{$import->id}}" onclick="window.location.href='{{route('imports.show', ['id'=>$import->id])}}'" class="text-center" style="color: red">Not payed</td> @endif
                        @if($import->status==2) <td onclick="window.location.href='{{route('imports.show', ['id'=>$import->id])}}'" class="text-center" style="color: green">Payed</td> @endif


                    <td class="text-center">
                        @if($import->status != 2)
                            <div id="actions{{$import->id}}">
                                @if(in_array(Auth::user()->level, [1,2,3]))
                                <div class="d-inline text-center">
                                    <button class="btn  my-1 btn-sm addproduct " value="{{$import->id}}" title="Confirm import payment" onclick="payment(this.value)">Confirm Payment</button>
                                    <br>
                                </div>
                                @endif
                                @if(in_array(Auth::user()->level, [1,2]) || Auth::user()->level==4 && $import->created_by == Auth::user()->id)
                                <form method="GET" class="text-center d-inline" action="{{route('imports.edit')}}">
                                    <input type="hidden" name="id" value="{{$import->id}}">
                                    <button class="btn btn-sm edit"  type="submit" title="Edit the import">Edit</button>
                                </form>
                                <div class="d-inline text-center">
                                    <button class="btn  my-1 btn-sm delete" value="{{$import->id}}" title="Delete the import" onclick="deleteIt(this.value)">Delete</button>
                                </div>
                                @endif
                            </div>
                        @endif
                    </td>

                    </tr>
                @endforeach
            @else
                <tr><td colspan="8" class="text-center">0 Imports found</td> </tr>
            @endif
            </tbody>
        </table>
    <div class="col-12  d-flex justify-content-center">
            {{ $imports->appends(request()->all())->links() }}
    </div>

@endsection

@section('scripts')
    <script>



        $(document).ready(function() {
            $('.selectSup').select2({
                placeholder: 'Supplier'
            });

        });

        $(document).ready(function() {
            $('.selectCreator').select2({
                placeholder: 'Employee'
            });

        });
        $(document).ready(function() {
            $('.selectProduct').select2({
                placeholder: 'Contains Product'
            });

        });
        function deleteIt(value){


            if(confirm("Are you sure, you want to delete this import?"))
            {
                $.post("{{route('imports.deletewhole')}}", {_token: "{{ csrf_token() }}", id: value}, function (data) {


                    if(data=="Successfully deleted import."){
                        $("#tr" + value).remove();
                    }
                    alert(data);





                })
            }
        }

        function payment(value){
            if(confirm("Are you sure, you want to mark this export as payed? This action can not be undone!")){
                $.post("{{route('imports.payment')}}", {_token: "{{ csrf_token() }}", id: value}, function (data) {
                    $("#actions" + value).remove();

                    $("#pstatus"+value).text('Payed')
                    $("#pstatus"+value).css('color','green');
                })
            }


        }



        $('#daterange').on('apply.daterangepicker', (e, picker) => {
            $("#filters").submit();
        });

    </script>

@endsection
