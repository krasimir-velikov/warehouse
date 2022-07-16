@extends('layouts.app')

@section('content')
    <button class="btn mx-3 my-1 btn-primary" onclick="window.location='{{route('imports')}}'">Back to All Imports</button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header text-center"><h1>Import Overview</h1></div>

                    <div class="card-body">
                            @csrf
                            <div class="form-group row">
                                <label for="supplier" class="col-md-4 form-label text-md-right">{{ __('Supplier') }}</label>

                                <div name="supplier" class="col-md-5 col-form-control">
                                    <p>{{$import->supplier->name}}@if($import->supplier->deleted) <strong>Deleted Supplier</strong>@endif</p>
                                </div>
                            </div>
                                <div class="form-group row">


                                <label for="created" class="col-md-4  col-form-label text-md-right">{{ __('Created') }}</label>

                                <div class="col-md-5 form-control">
                                    <p>{{date('d.m.Y H:i:s', strtotime($import->created_at))}}</p>
                                </div>
                                </div>
                            <div class="form-group row">


                            <label for="creator" class="col-md-4  col-form-label text-md-right">{{ __('Created by') }}</label>

                                <div class="col-md-5 form-control">
                                    <p>{{$import->user->name}} ({{$import->user->email}})@if($import->user->status==2)<strong> Deleted Employee</strong>@elseif($import->user->status==0)<strong> Blocked Employee</strong>@endif</p>
                                </div>
                            </div>

                                @if($import->updated_by)
                                <div class="form-group row">

                                <label for="edited" class="col-md-4  col-form-label text-md-right">{{ __('Last edited') }}</label>

                                    <div class="col-md-5 form-control">
                                        <p>{{date('d.m.Y H:i:s', strtotime($import->updated_at))}}</p>
                                    </div>
                                </div>
                                <div class="form-group row">


                                    <label for="editor" class="col-md-4  col-form-label text-md-right">{{ __('Last edited by') }}</label>

                                    <div class="col-md-5 form-control">
                                        <p>{{App\User::where('id', $import->updated_by)->first()->name}} ({{App\User::where('id', $import->updated_by)->first()->email}})@if(App\User::where('id', $import->updated_by)->first()->status==2)<strong> Deleted Employee</strong>@elseif(App\User::where('id', $import->updated_by)->first()->status==0)<strong> Blocked Employee</strong>@endif</p>
                                    </div>
                                </div>
                                @endif


                                <div class="form-group row">

                                    <label for="accountant" class="col-md-4  col-form-label text-md-right">{{ __('Payment Status') }}</label>

                                    <div class="col-md-5 form-control">
                                        @if($import->status==1)<p style="color: red">Not payed</p>
                                        @elseif($import->status==2)<p style="color: green">Payed</p>
                                        @endif
                                    </div>
                                </div>

                                @if($import->accountant_id)
                                <div class="form-group row">


                                    <label for="accountant" class="col-md-4  col-form-label text-md-right">{{ __('Payment confirmed by') }}</label>

                                    <div class="col-md-5 form-control">
                                        <p>{{App\User::where('id', $import->accountant_id)->first()->name}} ({{App\User::where('id', $import->accountant_id)->first()->email}})@if(App\User::where('id', $import->accountant_id)->first()->status==2)<strong> Deleted Employee</strong>@elseif(App\User::where('id', $import->accountant_id)->first()->status==0)<strong> Blocked Employee</strong>@endif</p>
                                    </div>
                                </div>
                                @endif
                                <div class="col-12  text-center">
                                    <h4 id="importprice" name="importprice">Import Price: {{$import->price}} lv</h4>
                                </div>



                                @foreach($import->import_product->where('amount', '!=', 0) as $key=>$improduct)
                                    <div id='p{{$key+1}}' class="card my-5">
                                        <div class="card-header text-center"><h3>Import Product</h3></div>

                                        <div class="card-body">



                                            <div class="form-group row">
                                                <label for="name{{$key+1}}" class="col-md-4 text-md-right">Product</label>

                                                <div name="name{{$key+1}}" class="col-md-5 col-form-control">
                                                    <p>{{$improduct->product->name}}: ({{$improduct->product->category->name}} -> {{$improduct->product->subcategory->name}})@if($improduct->product->deleted) <strong>Deleted Product</strong>@endif</p>

                                                </div>
                                            </div>
                                            <div id='insert{{$key+1}}'>
                                                <div class="form-group row">
                                                    <label for="availability" class="col-md-4 col-form-label text-md-right">Availability</label>
                                                    <div class="col-md-6">
                                                        <p id="availability" style='color: {{($improduct->product->amount > 0)? "green" : "red"}}' name="availability" class="col-form-label">{{$improduct->product->amount}} {{$improduct->product->unit}}</p>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="priceper{{$key+1}}" class="col-md-4 col-form-label text-md-right">Price per {{$improduct->product->unit}}</label>

                                                    <div class="col-10 col-md-5 form-control">
                                                        <p id="priceper{{$key+1}}" name="priceper{{$key+1}}">{{$improduct->price}} lv</p>

                                                    </div>
                                                    <p class="col-2 col-md-1 col-form-label">lv</p>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="amount" {{$key+1}} class="col-md-4 col-form-label text-md-right">Amount</label>

                                                    <div class="col-10 col-md-5 form-control">
                                                        <p id="amount{{$key+1}}" name="amount{{$key+1}}">{{$improduct->amount}}</p>
                                                    </div>
                                                    <p class="col-2 col-md-1 col-form-label">{{$improduct->product->unit}}</p>

                                                </div>
                                                <div class="form-group row">
                                                    <label for="price{{$key+1}}" class="col-md-4 col-form-label text-md-right">Price</label>
                                                    <div class="col-md-6">
                                                        <p id="price{{$key+1}}" name="price{{$key+1}}" class="col-form-label">{{round($improduct->price*$improduct->amount, 2)}} lv</p>
                                                    </div>
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


