<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use App\Subcategory;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        $searchproducts = Product::where('deleted', 0)->orderBy('name', 'ASC');
        $products = Product::where('deleted', 0)->orderBy('name', 'ASC');
        $subcategories = Subcategory::where('deleted',0)->orderBy('name', 'ASC');

        if($request->cat){
            $products = $products->where('category_id',$request->cat);
            $searchproducts = $searchproducts->where('category_id',$request->cat);
            $subcategories = $subcategories->where('category_id', $request->cat);
        }
        if($request->subcat){
            if(Subcategory::where('id',$request->subcat)->first()->category_id != $request->cat){
                $request->request->remove('subcat');
            }else{
                $products = $products->where('subcategory_id',$request->subcat);
                $searchproducts = $searchproducts->where('subcategory_id',$request->subcat);
            }

        }
        if($request->namelike){
            $products = $products->where('name','like','%'.$request->namelike.'%');
            $searchproducts = $searchproducts->where('name','like','%'.$request->namelike.'%');
        }
        if($request->name){
            $products = $products->where('id',$request->name);
        }
        if($request->available){
            if($request->available == 1){
                $products = $products->where('amount','>',0);
                $searchproducts = $searchproducts->where('amount','>',0);
            }
            elseif($request->available == 2){
                $products = $products->where('amount',0);
                $searchproducts = $searchproducts->where('amount',0);
            }
        }
        if($request->supplier){
            $products = $products->where('supplier_id',$request->supplier);
            $searchproducts = $searchproducts->where('supplier_id',$request->supplier);
        }
        $products = $products->paginate(15);
        $searchproducts = $searchproducts->get();
        $subcategories = $subcategories->get();
        $categories = Category::where('deleted',0)->orderBy('name', 'ASC')->get();
        $suppliers = Supplier::where('deleted',0)->orderBy('name', 'ASC')->get();



        return view('products.index', compact('products', 'categories', 'subcategories', 'suppliers','searchproducts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2])) {


            $categories = Category::where('deleted',0)->orderBy('name', 'ASC')->get();
            $suppliers = Supplier::orderBy('name', 'ASC')->where('deleted',0)->get();


            if($request->temp){
                $temp=$request->temp;
                return view('products.create', compact('categories', 'suppliers', 'temp'));

            }
            else{
                return view('products.create', compact('categories', 'suppliers'));

            }

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

            $this->validatorName(["name"=>$request->name, "id"=>$request->id, "subcat"=>$request->subcat, "supp"=>$request->supplier])->validate();

            $product = new Product();
            $product->name = $request->name;
            $product->category_id = $request->cat;
            $product->subcategory_id = $request->subcat;
            $product->unit = $request->unit;
            $product->bought_for = $request->buyprice;
            $product->sold_for = $request->sellprice;
            $product->supplier_id = $request->supplier;

            if($request->description){
                $product->description = $request->description;
            }

            $product->save();



            return redirect(route('products.create', ['temp'=> 1]));


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

        $product = Product::where('id',$request->id)->first();

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
        if(in_array(Auth::user()->level, [1,2])){

            $product = Product::where('id', $request->id)->first();
            $categories = Category::where('deleted',0)->orderBy('name', 'ASC')->get();
            $suppliers = Supplier::orderBy('name', 'ASC')->where('deleted',0)->get();
            $subcategories = Subcategory::where('deleted',0)->where('category_id', $product->category_id)->get();

            if($request->temp){
                $temp = $request->temp;
                return view('products.edit', compact('product','categories','suppliers', 'subcategories','temp'));
            }
            else{
                return view('products.edit', compact('product','categories','suppliers', 'subcategories'));
            }

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
        if(in_array(Auth::user()->level, [1,2])){


            $this->validatorName(["name"=>$request->name, "id"=>$request->id, "subcat"=>$request->subcat, "supp"=>$request->supplier])->validate();


            $product = Product::where('id', $request->id)->first();
            $product->name = $request->name;
            $product->category_id = $request->cat;
            $product->subcategory_id = $request->subcat;
            $product->unit = $request->unit;
            $product->bought_for = $request->buyprice;
            $product->sold_for = $request->sellprice;
            $product->supplier_id = $request->supplier;

            if($request->description){
                $product->description = $request->description;
            }
            else{
                $product->description = null;
            }

            $product->save();



            return redirect(route('products.edit', ['temp'=> 1, 'id'=>$request->id]));


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

protected function validatorName(array $data)
{
    return Validator::make($data, [
        'name' => Rule::unique('products')->where('deleted',0)->where('subcategory_id',$data['subcat'])->where('supplier_id',$data['supp'])->whereNot('id', $data['id'])

    ]);
}
}
