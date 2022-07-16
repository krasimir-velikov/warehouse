@extends('layouts.app')
@section('head')
    <!--jQuery-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>

    <!--Select2-->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"  ></script>
@endsection

@section('content')
    <button class="btn mx-3 btn-primary" onclick="window.location='{{route('products')}}'">Back to Products</button>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center"><h3>Add New Product</h3></div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('products.store') }}">
                            @csrf


                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Product Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror " name="name" required autofocus>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="cat" class="col-md-4 col-form-label text-md-right">{{ __('Category') }}</label>

                                <div class="col-md-5">
                                    <select id="cat" class="form-control selectCat" name="cat" onchange="subcategory(this.value)" required autofocus>

                                        <option disabled selected></option>
                                    @foreach($categories as $cat)
                                                <option value="{{$cat->id}}">{{$cat->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="form-group row sub">

                            </div>

                            <div class="form-group row">
                                <label for="unit" class="col-md-4 col-form-label text-md-right">{{ __('Unit') }}</label>

                                <div class="col-md-6">
                                    <select id="unit" class="form-control select" name="unit" required autofocus>
                                        <option disabled selected></option>
                                        <option value="pcs">Piece</option>
                                        <option value="kg">Kilogram</option>
                                        <option value="g">Gram</option>
                                        <option value="m">Meter</option>
                                        <option value="m2">Square Meter</option>
                                        <option value="m3">Cubic Meter</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="buyprice" class="col-md-4 col-form-label text-md-right">{{ __('Buy Price') }}</label>

                                <div class="col-md-4 col-10">
                                    <input id="buyprice" type="number" step="0.01" min="0" class="form-control" name="buyprice" required autofocus>

                                </div>
                                <p class="col-2 col-form-label">lv</p>
                            </div>

                            <div class="form-group row">
                                <label for="sellprice" class="col-md-4 col-form-label text-md-right">{{ __('Sell Price') }}</label>

                                <div class="col-md-4 col-10">
                                    <input id="sellprice" type="number" step="0.01" min="0" class="form-control" name="sellprice" required autofocus>

                                </div>
                                <p class="col-2 col-form-label">lv</p>
                            </div>
                            <div class="form-group row">
                                <label for="supplier" class="col-md-4 col-form-label text-md-right">{{ __('Supplier') }}</label>

                                <div class="col-md-5">
                                    <select id="supplier" class="form-control selectCat" name="supplier" autofocus required>

                                        <option disabled selected></option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <textarea rows="4" id="description" type="text" class="form-control" name="description" autofocus></textarea>

                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save new product') }}
                                    </button>
                                </div>
                            </div>
                            @if(isset($temp))
                            <div id="successMessage"></div>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

    <script>
        $(document).ready(function() {
            $('.selectCat').select2({
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('.selectSup').select2({
            });

        });
    </script>

    <script>
        $(document).ready(function() {
            $('.selectSubCat').select2({
            });

        });
    </script>
    <script>

        function subcategory(value){
            $.get("{{route('products.subcats')}}", {id: value}, function(data) {

                $(".sub").html(data);
                $('.selectSubCat').select2({
                });

            })
        }
    </script>
    <script>
        $(document).ready(function(){
            if(document.getElementsByClassName("invalid-feedback").length){
                alert('Product add failed!')
            }
            else if(document.getElementById('successMessage')) {
                alert('Successfully added new product.')
            }
        })
    </script>



@endsection

