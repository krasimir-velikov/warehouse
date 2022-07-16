<?php

namespace App\Http\Controllers;

use App\Category;
use App\ExportProduct;
use App\Import;
use App\ImportProduct;
use App\Product;
use App\Supplier;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $imports = Import::whereNotIn('status',[0])->orderBy('created_at', 'DESC');

        if($request->daterange){
            $from = strtotime(substr($request->daterange,6,4)."-".substr($request->daterange,3,2)."-".substr($request->daterange,0,2));
            $to = strtotime(substr($request->daterange,19,4)."-".substr($request->daterange,16,2)."-".substr($request->daterange,13,2))+23*60*60+59;
            $imports = $imports->where('created_at','>=',Carbon::createFromTimestamp($from))->where('created_at','<=',Carbon::createFromTimestamp($to));

        }
        if($request->product){
            $product = $request->product;
            $imports = $imports->whereHas('import_product', function($q) use ($product){$q->where('product_id', $product);});
        }
        if($request->supplier){
            $imports = $imports->where('supplier_id',$request->supplier);
        }
        if($request->created_by){
            $imports = $imports->where('created_by',$request->created_by);
        }
        if($request->status){
            $imports = $imports->where('status', $request->status);
        }

        $products = Product::orderBy('name','ASC')->get();
        $suppliers = Supplier::orderBy('name','ASC')->get();
        $employees = User::orderBy('name','ASC')->get();
        $imports = $imports->paginate(15);

        return view('imports.index', compact('suppliers', 'imports','employees','products'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2,4])){
            $suppliers = Supplier::where('deleted', 0)->orderBy('name','ASC')->get();

            return view('imports.create', compact('suppliers'));

        }
        else{
            return $this->index($request);
        }



    }

    public function supplier(Request $request){
        if(in_array(Auth::user()->level, [1,2,4])){
            $products = Product::where('supplier_id', $request->id)->where('deleted',0)->orderBy('name','ASC')->get();
            $select = '';
            foreach($products as $product){
                $select.= "<option value=\"".$product->id."\">".$product->name." : ".$product->category->name." -> ".$product->subcategory->name."</option>";
            }

            return "<div id='p1' class=\"card my-3\">
                            <div class=\"card-header text-center\"><h3>Import Product</h3></div>

                            <div class=\"card-body\">



                                    <div class=\"form-group row\">
                                        <label for=\"name1\" class=\"col-md-4 col-form-label text-md-right\">Product</label>

                                        <div class=\"col-md-5\">
                                            <select name=\"name1\" id='name1' class=\"form-control selectName\" onchange=\"insert(this.value, this.id)\" style=\"height: 35px\">
                                                <option value=\"\" selected disabled></option>

                                                ".$select."


                                            </select>

                                        </div>
                                    </div>
                                 <div id='insert1'></div>


                                <div class=\"form-group text-center row mb-0\">
                                    <div class=\"col\">
                                        <button value='1' type='button' class=\"btn btn-danger\" onclick='deleteIt(this.value)'>
                                            Remove
                                        </button>
                                    </div>
                                </div>



                            </div>
                        </div>
                        <div id='addproduct'></div>


                            <div class=\"form-group justify-content-center row m-5\">

                                    <div class=\"col-4  text-center\">
                                        <h4 id=\"importprice\" name=\"importprice\">Import Price: 0.00 lv</h4>
                                    </div>


                            <div class=\"col-12 text-center\">
                                <button type=\"submit\" class=\"btn btn-primary\">
                                    Save Import
                                </button>
                            </div>";
        }
        else{
            return $this->index($request);
        }
    }

    public function insert(Request $request){
        if(in_array(Auth::user()->level, [1,2,4])){
            $product = Product::where('id', $request->product)->first();


            return "            <div class=\"form-group row\">
                                        <label for=\"availability\" class=\"col-md-4 col-form-label text-md-right\">Availability</label>
                                        <div class=\"col-md-6\">
                                            <p id=\"availability\" style='color:".(($product->amount==0)?'red':'green')."' name=\"availability\" class=\"col-form-label\">".$product->amount." ".$product->unit."</p>
                                        </div>
                                    </div>

                                    <div class=\"form-group row\">
                                        <label for=\"priceper".$request->id."\" class=\"col-md-4 col-form-label text-md-right\">Price per ".$product->unit."</label>

                                        <div class=\"col-10 col-md-5\">
                                            <input id=\"priceper".$request->id."\" type=\"number\" step=\"0.01\" min=\"0.01\" class=\"form-control\" onchange=\"price(this.id)\" value=\"".$product->bought_for."\" name=\"priceper".$request->id."\" required autofocus>

                                        </div>
                                        <p class=\"col-2 col-md-1 col-form-label\">lv</p>
                                    </div>

                                    <div class=\"form-group row\">
                                        <label for=\"amount".$request->id."\" class=\"col-md-4 col-form-label text-md-right\">Amount</label>

                                        <div class=\"col-10 col-md-5\">
                                            <input onchange=\"price(this.id)\" id=\"amount".$request->id."\" type=\"number\" step=\"1\" min=\"1\" class=\"form-control\" name=\"amount".$request->id."\" required autofocus>

                                        </div>
                                        <p class=\"col-2 col-md-1 col-form-label\">".$product->unit."</p>
                                    </div>
                                    <div id='insertprice".$request->id."'></div>";
        }
        else{
            return $this->index($request);
        }


    }

    public function addproduct(Request $request){
        if(in_array(Auth::user()->level, [1,2,4])){
            $products = Product::where('supplier_id', $request->supplier)->where('deleted',0)->whereNotIn('id',$request->taken)->orderBy('name','ASC')->get();
            $select = '';
            foreach($products as $product){
                $select.= "<option value=\"".$product->id."\">".$product->name." : ".$product->category->name." -> ".$product->subcategory->name."</option>";
            }

            return "<div id='p".$request->id."' class=\"card my-3\">
                            <div class=\"card-header text-center\"><h3>Import Product</h3></div>

                            <div class=\"card-body\">



                                    <div class=\"form-group row\">
                                        <label for=\"name".$request->id."\" class=\"col-md-4 col-form-label text-md-right\">Product</label>

                                        <div class=\"col-md-5\">
                                            <select name=\"name".$request->id."\" id='name".$request->id."' class=\"form-control selectName\" onchange=\"insert(this.value, this.id)\" style=\"height: 35px\">
                                                <option value=\"\" selected disabled></option>

                                                ".$select."


                                            </select>

                                        </div>
                                    </div>
                                 <div id='insert".$request->id."'></div>


                                <div class=\"form-group text-center row mb-0\">
                                    <div class=\"col\">
                                        <button value='".$request->id."' type='button' class=\"btn btn-danger\" onclick='deleteIt(this.value)'>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id='addproduct'></div>";
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
        if(in_array(Auth::user()->level, [1,2,4])){
            $import = new Import();
            $import->price = 0;
            $import->supplier_id = $request->supplier;
            $import->created_by = Auth::user()->id;

            $import->save();

            for($i = 1;$i < 101;$i++){
                if($request->{"amount".$i}){
                    $product = Product::where('id',$request->{"name".$i})->first();
                    $improduct = new ImportProduct();

                    $improduct->product_id = $request->{"name".$i};
                    $improduct->import_id = $import->id;
                    $improduct->amount = $request->{"amount".$i};
                    $improduct->price = $request->{"priceper".$i};
                    $improduct->amount_before = $product->amount;
                    $improduct->save();

                    $product->amount += $request->{"amount".$i};
                    $product->save();

                    $import->price += round($improduct->amount*$improduct->price,2);

                }
            }

            $import->save();

            $supplier = Supplier::where('id', $import->supplier_id)->first();
            $supplier->balance += $import->price;
            $supplier->save();

            return redirect(route('imports'));



        }
        else{
            return "Unauthorized action!";
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $import = Import::where('id',$request->id)->first();

        return view('imports.show', compact('import'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $import = Import::where('id',$request->id)->first();

        if((in_array(Auth::user()->level, [1, 2]) || (Auth::user()->level == 4 && $import->created_by == Auth::user()->id )) && $import->status != 2){


            return view('imports.edit', compact('import'));

        }
        else{
            return $this->index($request);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $improduct = ImportProduct::where('id', $request->id)->first();
        $import = $improduct->import;


        if ((in_array(Auth::user()->level, [1, 2]) || (Auth::user()->level == 4 && $import->created_by == Auth::user()->id )) && $import->status != 2) {


            if ($improduct->price == $request->priceper && $improduct->amount == $request->amount) {
                return "No changes have been made.";
            } else {
                $exproducts = ExportProduct::where('created_at', '>', $improduct->created_at)->where('product_id', $improduct->product_id)->where('amount','!=',0)->whereHas('export', function($q){$q->where('status','!=', 0);})->orderBy('created_at', 'DESC')->get();

                foreach ($exproducts as $ex) {

                    if ($ex->amount_before - $improduct->amount + $request->amount - $ex->amount >= 0) {

                    } else {
                        return "ATTENTION! If the import product is edited to the requested amount, a subsequent export of ".$ex->product->name." from ".date('d.m.Y H:i:s', strtotime($ex->created_at))." to client ".$ex->export->client->name." exceeds the product amount at that time and is thus impossible. If you want to edit this import product with the requested amount, fix the corresponding export!";
                    }

                }
                $exproducts = ExportProduct::where('created_at', '>', $improduct->created_at)->where('product_id', $improduct->product_id)->orderBy('created_at', 'DESC')->get();
                $improducts = ImportProduct::where('created_at', '>', $improduct->created_at)->where('product_id', $improduct->product_id)->orderBy('created_at', 'DESC')->get();

                foreach($improducts as $i){
                    $i->amount_before -= $improduct->amount;
                    $i->amount_before += $request->amount;
                    $i->save();
                }
                foreach($exproducts as $x){
                    $x->amount_before -= $improduct->amount;
                    $x->amount_before += $request->amount;
                    $x->save();
                }

                $product = Product::where('id', $improduct->product_id)->first();
                $product->amount -= $improduct->amount;
                $product->amount += $request->amount;
                $product->save();

                $supplier = $import->supplier;
                $supplier->balance -= $import->price;
                $supplier->balance += $request->price;
                $supplier->save();

                $import->updated_by = Auth::user()->id;
                $import->price = $request->price;
                $import->save();

                $improduct->amount = $request->amount;
                $improduct->price = $request->priceper;
                $improduct->save();

                return ["Successfully edited import product.",$product->amount,$product->unit];



            }
        }
        else{
            return "Unauthorized action!";
        }
    }


    public function delete(Request $request){
        $improduct = ImportProduct::where('id', $request->id)->first();
        $import = $improduct->import;

        if ((in_array(Auth::user()->level, [1, 2]) || (Auth::user()->level == 4 && $import->created_by == Auth::user()->id )) && $import->status != 2){


            $exproducts = ExportProduct::where('created_at', '>', $improduct->created_at)->where('product_id', $improduct->product_id)->where('amount','!=',0)->whereHas('export', function($q){$q->where('status','!=', 0);})->orderBy('created_at', 'DESC')->get();

            foreach ($exproducts as $ex) {

                if ($ex->amount_before - $improduct->amount - $ex->amount >= 0) {


                } else {
                    return "ATTENTION! If the import product is deleted, a subsequent export of ".$ex->product->name." from ".date('d.m.Y H:i:s', strtotime($ex->created_at))." to client ".$ex->export->client->name." exceeds the product amount at that time and is thus impossible. If you want to delete this import product, fix the corresponding export!";
                }

            }
            $exproducts = ExportProduct::where('created_at', '>', $improduct->created_at)->where('product_id', $improduct->product_id)->orderBy('created_at', 'DESC')->get();
            $improducts = ImportProduct::where('created_at', '>', $improduct->created_at)->where('product_id', $improduct->product_id)->orderBy('created_at', 'DESC')->get();

            foreach($improducts as $i){
                $i->amount_before -= $improduct->amount;
                $i->save();
            }
            foreach($exproducts as $x){
                $x->amount_before -= $improduct->amount;
                $x->save();
            }

            $product = Product::where('id', $improduct->product_id)->first();
            $product->amount -= $improduct->amount;
            $product->save();

            $supplier = $import->supplier;
            $supplier->balance -= $improduct->price * $improduct->amount;
            $supplier->save();

            $import->updated_by = Auth::user()->id;
            $import->price -= $improduct->price * $improduct->amount;
            $import->save();

            $improduct->amount = 0;
            $improduct->save();

            return "Successfully deleted import product.";

        }
        else{
            return "Unauthorized action!";
        }
    }

    public function deleteWhole(Request $request){
        $import = Import::where('id', $request->id)->first();

        if ((in_array(Auth::user()->level, [1, 2]) || (Auth::user()->level == 4 && $import->created_by == Auth::user()->id )) && $import->status != 2) {
            foreach ($import->import_product as $improduct) {
                $exproducts = ExportProduct::where('created_at', '>', $improduct->created_at)->where('product_id', $improduct->product_id)->where('amount','!=',0)->whereHas('export', function($q){$q->where('status','!=', 0);})->orderBy('created_at', 'DESC')->get();

                foreach ($exproducts as $ex) {

                        if ($ex->amount_before - $improduct->amount - $ex->amount >= 0) {


                        } else {
                            return "ATTENTION! If the import is deleted, a subsequent export of ".$ex->product->name." from ".date('d.m.Y H:i:s', strtotime($ex->created_at))." to client ".$ex->export->client->name." exceeds the product amount at that time and is thus impossible. If you want to delete this import, fix the corresponding export!";
                        }

                    }

            }
            foreach ($import->import_product as $improduct) {

                $exproducts = ExportProduct::where('created_at', '>', $improduct->created_at)->where('product_id', $improduct->product_id)->orderBy('created_at', 'DESC')->get();
                $improducts = ImportProduct::where('created_at', '>', $improduct->created_at)->where('product_id', $improduct->product_id)->orderBy('created_at', 'DESC')->get();

                foreach($improducts as $i){
                    $i->amount_before -= $improduct->amount;
                    $i->save();
                }
                foreach($exproducts as $x){
                    $x->amount_before -= $improduct->amount;
                    $x->save();
                }





                $product = Product::where('id', $improduct->product_id)->first();
                $product->amount -= $improduct->amount;

                $product->save();


            }


            $supplier = $import->supplier;
            $supplier->balance -= $import->price;
            $supplier->save();

            $import->updated_by = Auth::user()->id;
            $import->status = 0;
            $import->save();


            return "Successfully deleted import.";

        }
        else{
            return "Unauthorized action!";
        }

    }

    public function payment(Request $request){

        if(in_array(Auth::user()->level, [1,2,3])){
            $import = Import::where('id', $request->id)->first();
            $import->status = 2;
            $import->accountant_id = Auth::user()->id;
            $import->save();

            $supplier = $import->supplier;
            $supplier->balance -= $import->price;
            $supplier->save();
        }
        else{
            return "Unauthorized action!";
        }



    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Import  $import
     * @return \Illuminate\Http\Response
     */
    public function destroy(Import $import)
    {
        //
    }
}
