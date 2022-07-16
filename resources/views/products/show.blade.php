@extends('layouts.app')
@section('content')
    <button class="btn btn-primary mx-3" onclick="window.location='{{route('products')}}'">Back to Products</button>

    <div class="container-fluid ">
        <div class="justify-content-center row ">
            <div class="col-lg-8">
                <div class=" card">
                    <div class="card-header text-center">
                        <h1>Product Details: {{$product->name}}</h1>
                    </div>
                    <div class="text-center card-body">
                        <table class="my-3 table">
                            <thead>


                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%" style="background-color: #f2f2f2; " class="border text-right"><h5>Product Name</h5></td>
                                    <td class="border"><h5>{{$product->name}}</h5></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20%" style="background-color: #f2f2f2; " class="border text-right"><h5>Category</h5></td>
                                    <td class="border"><h5>{{$product->category->name}}</h5></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20%" style="background-color: #f2f2f2; " class="border text-right"><h5>Subcategory</h5></td>
                                    <td class="border" class="border"><h5>{{$product->subcategory->name}}</h5></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                <tr>
                                    <td width="20%" style="background-color: #f2f2f2; " class="border text-right"><h5>Unit</h5></td>
                                    @switch($product->unit)
                                        @case('pcs')
                                            <td class="border"><h5>Piece</h5></td>
                                            @break
                                        @case('kg')
                                            <td class="border"><h5>Kilogram</h5></td>
                                        @break
                                        @case('g')
                                            <td class="border"><h5>Gram</h5></td>
                                            @break
                                        @case('m')
                                            <td class="border"><h5>Meter</h5></td>
                                            @break
                                        @case('m2')
                                            <td class="border"><h5>Square Meter</h5></td>
                                            @break
                                        @case('m3')
                                            <td class="border"><h5>Cubic Meter</h5></td>
                                            @break
                                    @endswitch

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20%" style="background-color: #f2f2f2; " class="border text-right"><h5>Availability</h5></td>
                                    <td class="border"><h5 @if($product->amount == 0) style="color: red" @else style="color: green" @endif >{{$product->amount}} {{$product->unit}}</h5></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                <tr>
                                    <td width="20%" style="background-color: #f2f2f2; " class="border text-right"><h5>Buy Price</h5></td>
                                    <td class="border"><h5>{{$product->bought_for}} lv/{{$product->unit}}</h5></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20%" style="background-color: #f2f2f2; " class="border text-right"><h5>Sell Price</h5></td>
                                    <td class="border"><h5>{{$product->sold_for}} lv/{{$product->unit}}</h5></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20%" style="background-color: #f2f2f2; " class="border text-right"><h5>Supplier</h5></td>
                                    <td class="border"><h5>{{$product->supplier->name}}</h5></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td width="20%" style="background-color: #f2f2f2; " class="border text-right"><h5>Description</h5></td>
                                    <td class="border text-left"><p>{{$product->description}}</p></td>

                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                </tr>



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
