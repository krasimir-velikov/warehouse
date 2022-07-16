@extends('layouts.app')

@section('content')
    <button class="btn mx-3 my-1 btn-primary" onclick="window.location='{{route('exports')}}'">Back to All Exports</button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header text-center"><h1>Export Overview</h1></div>

                    <div class="card-body">
                            @csrf
                            <div class="form-group row">
                                <label for="client" class="col-md-4 form-label text-md-right">{{ __('Client') }}</label>

                                <div name="client" class="col-md-5 col-form-control">
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


                                <div class="form-group row">

                                    <label for="accountant" class="col-md-4  col-form-label text-md-right">{{ __('Payment Status') }}</label>

                                    <div class="col-md-5 form-control">
                                        @if($export->status==1)<p style="color: red">Not payed</p>
                                        @elseif($export->status==2)<p style="color: green">Payed</p>
                                        @endif
                                    </div>
                                </div>

                                @if($export->accountant_id)
                                <div class="form-group row">


                                    <label for="accountant" class="col-md-4  col-form-label text-md-right">{{ __('Payment confirmed by') }}</label>

                                    <div class="col-md-5 form-control">
                                        <p>{{App\User::where('id', $export->accountant_id)->first()->name}} ({{App\User::where('id', $export->accountant_id)->first()->email}})@if(App\User::where('id', $export->accountant_id)->first()->status==2)<strong> Deleted Employee</strong>@elseif(App\User::where('id', $export->accountant_id)->first()->status==0)<strong> Blocked Employee</strong>@endif</p>
                                    </div>
                                </div>
                                @endif
                                <div class="col-12  text-center">
                                    <h4 id="exportprice" name="exportprice">Export Price: {{$export->price}} lv</h4>
                                </div>



                                @foreach($export->export_product->where('amount', '!=', 0) as $key=>$exproduct)
                                    <div id='p{{$key+1}}' class="card my-5">
                                        <div class="card-header text-center"><h3>Export Product</h3></div>

                                        <div class="card-body">



                                            <div class="form-group row">
                                                <label for="name{{$key+1}}" class="col-md-4 text-md-right">Product</label>

                                                <div name="name{{$key+1}}" class="col-md-5 col-form-control">
                                                    <p>{{$exproduct->product->name}}: ({{$exproduct->product->category->name}} -> {{$exproduct->product->subcategory->name}})@if($exproduct->product->deleted) <strong>Deleted Product</strong>@endif</p>

                                                </div>
                                            </div>
                                            <div id='insert{{$key+1}}'>
                                                <div class="form-group row">
                                                    <label for="availability" class="col-md-4 col-form-label text-md-right">Availability</label>
                                                    <div class="col-md-6">
                                                        <p id="availability" style='color: {{($exproduct->product->amount > 0)? "green" : "red"}}' name="availability" class="col-form-label">{{$exproduct->product->amount}} {{$exproduct->product->unit}}</p>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="priceper{{$key+1}}" class="col-md-4 col-form-label text-md-right">Price per {{$exproduct->product->unit}}</label>

                                                    <div class="col-10 col-md-5 form-control">
                                                        <p id="priceper{{$key+1}}" name="priceper{{$key+1}}">{{$exproduct->price}} lv</p>

                                                    </div>
                                                    <p class="col-2 col-md-1 col-form-label">lv</p>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="amount" {{$key+1}} class="col-md-4 col-form-label text-md-right">Amount</label>

                                                    <div class="col-10 col-md-5 form-control">
                                                        <p id="amount{{$key+1}}" name="amount{{$key+1}}">{{$exproduct->amount}}</p>
                                                    </div>
                                                    <p class="col-2 col-md-1 col-form-label">{{$exproduct->product->unit}}</p>

                                                </div>
                                                <div class="form-group row">
                                                    <label for="price{{$key+1}}" class="col-md-4 col-form-label text-md-right">Price</label>
                                                    <div class="col-md-6">
                                                        <p id="price{{$key+1}}" name="price{{$key+1}}" class="col-form-label">{{round($exproduct->price*$exproduct->amount, 2)}} lv</p>
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


