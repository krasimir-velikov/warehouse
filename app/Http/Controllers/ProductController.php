<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Subcategory;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){


        if($request->cat){
            if($request->namelike){
                $subcategories = Subcategory::where('deleted',0)->orderBy('name', 'ASC')->get();
                $products = Product::where('deleted', 0)->where('category_id',$request->cat)->where('name','like','%'.$request->namelike.'%')->orderBy('name', 'ASC')->paginate(13);
                $searchproducts = Product::where('deleted', 0)->where('category_id',$request->cat)->where('name','like','%'.$request->namelike.'%')->orderBy('name', 'ASC')->get();
            }
            else{
                $products = Product::where('deleted', 0)->where('category_id',$request->cat)->orderBy('name', 'ASC')->paginate(13);
                $searchproducts = Product::where('deleted', 0)->where('category_id',$request->cat)->orderBy('name', 'ASC')->get();
                $subcategories = Subcategory::where('deleted',0)->where('category_id', $request->cat)->orderBy('name','ASC')->get();

            }

        }
        elseif($request->subcat){
            $subcategories = Subcategory::where('deleted',0)->orderBy('name', 'ASC')->get();

            if($request->namelike){
                $products = Product::where('deleted', 0)->where('subcategory_id',$request->subcat)->where('name','like','%'.$request->namelike.'%')->orderBy('name', 'ASC')->paginate(13);
                $searchproducts = Product::where('deleted', 0)->where('subcategory_id',$request->subcat)->where('name','like','%'.$request->namelike.'%')->orderBy('name', 'ASC')->get();

            }
            else{
                $products = Product::where('deleted', 0)->where('subcategory_id',$request->subcat)->orderBy('name', 'ASC')->paginate(13);
                $searchproducts = Product::where('deleted', 0)->where('subcategory_id',$request->subcat)->orderBy('name', 'ASC')->get();

            }
        }
        else{
            $subcategories = Subcategory::where('deleted',0)->orderBy('name', 'ASC')->get();

            if($request->name){
                $products = Product::where('deleted', 0)->where('id',$request->name)->orderBy('name', 'ASC')->paginate(13);
                $searchproducts = Product::where('deleted', 0)->orderBy('name', 'ASC')->get();

            }
            elseif($request->namelike){
                $products = Product::where('deleted', 0)->where('name','like','%'.$request->namelike.'%')->orderBy('name', 'ASC')->paginate(13);
                $searchproducts = Product::where('deleted', 0)->where('name','like','%'.$request->namelike.'%')->orderBy('name', 'ASC')->get();

            }
            else{
                $products = Product::where('deleted', 0)->orderBy('name', 'ASC')->paginate(13);
                $searchproducts = Product::where('deleted', 0)->orderBy('name', 'ASC')->get();

            }
        }

        $categories = Category::where('deleted',0)->orderBy('name', 'ASC')->get();
        $suppliers = Supplier::all();



        return view('products.index', compact('products', 'categories', 'subcategories', 'suppliers', 'searchproducts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2,4])) {


            $categories = Category::where('deleted',0)->orderBy('name', 'ASC')->get();
            $suppliers = Supplier::orderBy('name', 'ASC')->get();

            return view('products.create', compact('categories', 'suppliers'));

        }
        else{
            return $this->index($request);
        }
    }

    public function subcats(Request $request){

        if(in_array(Auth::user()->level, [1,2])) {

            $subcategories = Subcategory::where('deleted',0)->where('category_id', $request->id)->orderBy('name', 'ASC')->get();

            $subcat = "<label for=\"subcat\" class=\"col-md-4 col-form-label text-md-right\">Subcategory</label><div class=\"col-md-5\">
                                <select id=\"subcat\" class=\"form-control selectSubCat\" name=\"subcat\" required autofocus>
                                    <option disabled selected></option>\"";
            foreach ($subcategories as $subcategory) {
                $subcat .= "<option value=\"" . $subcategory->id . "\">" . $subcategory->name . "</option>";
            }
            $subcat .= "</select></div>";

            return $subcat;
        }
        else{
            return $this->index($request);
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2])){
            $product = new Product();
            $product->name = $request->name;
            $product->category_id = $request->cat;
            $product->subcategory_id = $request->subcat;
            $product->unit = $request->unit;
            $product->bought_for = $request->buyprice;
            $product->sold_for = $request->sellprice;
            if($request->supplier) {
                $product->supplier_id = $request->supplier;
            }
            if($request->description){
                $product->description = $request->description;
            }

            $product->save();

            $temp = 1;

            $categories = Category::where('deleted',0)->orderBy('name', 'ASC')->get();
            $suppliers = Supplier::orderBy('name', 'ASC')->get();


                return view('products.create', compact('temp', 'categories', 'suppliers'));


        }
        else{
            return "Unauthorized action!";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {

        $product = Product::where('id',$request->id)->where('deleted',0)->first();

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2,4])){
            $product = Product::where('id', $request->id)->first();
            $categories = Category::where('deleted',0)->orderBy('name', 'ASC')->get();
            $suppliers = Supplier::orderBy('name', 'ASC')->get();
            $subcategories = Subcategory::where('deleted',0)->where('category_id', $product->category_id)->get();

            return view('products.edit', compact('product','categories','suppliers', 'subcategories'));
        }
        else{
            return $this->index($request);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2,4])){

            $product = Product::where('id', $request->id)->first();
            $product->name = $request->name;
            $product->category_id = $request->cat;
            $product->subcategory_id = $request->subcat;
            $product->unit = $request->unit;
            $product->bought_for = $request->buyprice;
            $product->sold_for = $request->sellprice;
            if($request->supplier) {
                $product->supplier_id = $request->supplier;
            }
            else{
                $product->supplier_id = null;
            }
            if($request->description){
                $product->description = $request->description;
            }
            else{
                $product->description = null;
            }

            $product->save();

            $temp = 1;

            $product = Product::where('id', $request->id)->first();
            $categories = Category::where('deleted',0)->orderBy('name', 'ASC')->get();
            $suppliers = Supplier::orderBy('name', 'ASC')->get();
            $subcategories = Subcategory::where('deleted',0)->where('category_id', $product->category_id)->get();

            return view('products.edit', compact('temp', 'product', 'categories', 'suppliers','subcategories'));


        }
        else{
            return "Unauthorized action!";
        }
    }
    public function delete(Request $request){

        if(in_array(Auth::user()->level, [1,2])){
            $product = Product::where('id', $request->id)->first();

            $product->deleted = 1;
            $product->save();

            return $product->id;
        }
        else{
            return "Unauthorized action!";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
