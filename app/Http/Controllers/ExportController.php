<?php

namespace App\Http\Controllers;

use App\Export;
use App\ExportProduct;
use App\Import;
use App\ImportProduct;
use App\Product;
use App\Supplier;
use App\Client;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $exports = Export::whereNotIn('status',[0])->orderBy('created_at', 'DESC');

        if($request->daterange){
            $from = strtotime(substr($request->daterange,6,4)."-".substr($request->daterange,3,2)."-".substr($request->daterange,0,2));
            $to = strtotime(substr($request->daterange,19,4)."-".substr($request->daterange,16,2)."-".substr($request->daterange,13,2))+23*60*60+59;
            $exports = $exports->where('created_at','>=',Carbon::createFromTimestamp($from))->where('created_at','<=',Carbon::createFromTimestamp($to));

        }
        if($request->product){
            $product = $request->product;
            $exports = $exports->whereHas('export_product', function($q) use ($product){$q->where('product_id', $product);});
        }
        if($request->client){
            $exports = $exports->where('client_id',$request->client);
        }
        if($request->created_by){
            $exports = $exports->where('created_by',$request->created_by);
        }
        if($request->status){
            $exports = $exports->where('status', $request->status);
        }

        $products = Product::orderBy('name','ASC')->get();
        $clients = Client::orderBy('name','ASC')->get();
        $employees = User::orderBy('name','ASC')->get();
        $exports = $exports->paginate(15);

        return view('exports.index', compact('clients', 'exports','employees','products'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2,4])){
            $clients = Client::where('deleted', 0)->orderBy('name','ASC')->get();

            return view('exports.create', compact('clients'));

        }
        else{
            return $this->index($request);
        }



    }

    public function client(Request $request){
        if(in_array(Auth::user()->level, [1,2,4])){
            $products = Product::where('deleted', 0)->where('amount','!=',0)->orderBy('name','ASC')->get();
            $select = '';
            foreach($products as $product){
                $select.= "<option value=\"".$product->id."\">".$product->name." : ".$product->category->name." -> ".$product->subcategory->name."</option>";
            }

            return "<div id='p1' class=\"card my-3\">
                            <div class=\"card-header text-center\"><h3>Export Product</h3></div>

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
                                        <h4 id=\"exportprice\" name=\"exportprice\">Export Price: 0.00 lv</h4>
                                    </div>


                            <div class=\"col-12 text-center\">
                                <button type=\"submit\" class=\"btn btn-primary\">
                                    Save Export
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
                                            <input id=\"priceper".$request->id."\" type=\"number\" step=\"0.01\" min=\"0.01\" class=\"form-control\" onchange=\"price(this.id)\" value=\"".$product->sold_for."\" name=\"priceper".$request->id."\" required autofocus>

                                        </div>
                                        <p class=\"col-2 col-md-1 col-form-label\">lv</p>
                                    </div>

                                    <div class=\"form-group row\">
                                        <label for=\"amount".$request->id."\" class=\"col-md-4 col-form-label text-md-right\">Amount</label>

                                        <div class=\"col-10 col-md-5\">
                                            <input".(($product->amount==0)? "disabled":"")." onchange=\"price(this.id)\" id=\"amount".$request->id."\" type=\"number\" step=\"1\" min=\"1\" max='$product->amount' class=\"form-control\" name=\"amount".$request->id."\" required autofocus>

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
            $products = Product::where('deleted',0)->where('amount','!=',0)->whereNotIn('id',$request->taken)->orderBy('name','ASC')->get();
            $select = '';
            foreach($products as $product){
                $select.= "<option value=\"".$product->id."\">".$product->name." : ".$product->category->name." -> ".$product->subcategory->name."</option>";
            }

            return "<div id='p".$request->id."' class=\"card my-3\">
                            <div class=\"card-header text-center\"><h3>Export Product</h3></div>

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
            $export = new Export();
            $export->price = 0;
            $export->client_id = $request->client;
            $export->created_by = Auth::user()->id;

            $export->save();

            for($i = 1;$i < 101;$i++){
                if($request->{"amount".$i}){
                    $product = Product::where('id',$request->{"name".$i})->first();
                    $exproduct = new ExportProduct();

                    $exproduct->product_id = $request->{"name".$i};
                    $exproduct->export_id = $export->id;
                    $exproduct->amount = $request->{"amount".$i};
                    $exproduct->price = $request->{"priceper".$i};
                    $exproduct->amount_before = $product->amount;
                    $exproduct->save();

                    $product->amount -= $request->{"amount".$i};
                    $product->save();

                    $export->price += round($exproduct->amount*$exproduct->price,2);

                }
            }

            $export->save();

            $client = Client::where('id', $export->client_id)->first();
            $client->balance -= $export->price;
            $client->save();

            return redirect(route('exports'));



        }
        else{
            return "Unauthorized action!";
        }



    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $export = Export::where('id',$request->id)->first();

        return view('exports.show', compact('export'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $export = Export::where('id',$request->id)->first();

        if((in_array(Auth::user()->level, [1, 2]) || (Auth::user()->level == 4 && $export->created_by == Auth::user()->id )) && $export->status != 2){


            return view('exports.edit', compact('export'));

        }
        else{
            return $this->index($request);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Export  $export
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $exproduct = ExportProduct::where('id', $request->id)->first();
        $export = $exproduct->export;


        if ((in_array(Auth::user()->level, [1, 2]) || (Auth::user()->level == 4 && $export->created_by == Auth::user()->id )) && $export->status != 2) {


            if ($exproduct->price == $request->priceper && $exproduct->amount == $request->amount) {
                return "No changes have been made.";
            }
            else{
                $exproducts = ExportProduct::where('created_at', ">", $exproduct->created_at)->where('product_id',$exproduct->product_id)->where('amount','!=',0)->whereHas('export', function($q){$q->where('status','!=', 0);})->orderBy('created_at', 'DESC')->get();




                if ($exproduct->amount_before - $request->amount >= 0) {
                    foreach($exproducts as $ex){
                        if($ex->amount_before + $exproduct->amount - $request->amount - $ex->amount >= 0){

                        }
                        else{
                            return "ATTENTION! If the export product is edited to the requested amount, a subsequent export of ".$ex->product->name." from ".date('d.m.Y H:i:s', strtotime($ex->created_at))." to client ".$ex->export->client->name." exceeds the product amount at that time and is thus impossible. If you want to edit this export product with the requested amount, fix the corresponding export!";
                        }
                    }
                    $exproducts = ExportProduct::where('created_at', ">", $exproduct->created_at)->where('product_id',$exproduct->product_id)->orderBy('created_at', 'DESC')->get();
                    $improducts = ImportProduct::where('created_at', '>', $exproduct->created_at)->where('product_id', $exproduct->product_id)->orderBy('created_at', 'DESC')->get();

                    foreach($improducts as $i){
                        $i->amount_before += $exproduct->amount;
                        $i->amount_before -= $request->amount;
                        $i->save();
                    }
                    foreach($exproducts as $x){
                        $x->amount_before += $exproduct->amount;
                        $x->amount_before -= $request->amount;
                        $x->save();
                    }

                    $product = Product::where('id', $exproduct->product_id)->first();
                    $product->amount += $exproduct->amount;
                    $product->amount -= $request->amount;
                    $product->save();

                    $client = $export->client;
                    $client->balance += $export->price;
                    $client->balance -= $request->price;
                    $client->save();

                    $export->updated_by = Auth::user()->id;
                    $export->price = $request->price;
                    $export->save();

                    $exproduct->amount = $request->amount;
                    $exproduct->price = $request->priceper;
                    $exproduct->save();

                    return ["Successfully edited export product.",$product->amount,$product->unit];


                } else {
                    return "ATTENTION! The export product can not be edited to the requested amount, because the product amount at the time was insufficient.";
                }
            }
        }
        else{
            return "Unauthorized action!";
        }
    }
    public function delete(Request $request){
        $exproduct = ExportProduct::where('id', $request->id)->first();
        $export = $exproduct->export;

        if ((in_array(Auth::user()->level, [1, 2]) || (Auth::user()->level == 4 && $export->created_by == Auth::user()->id )) && $exproduct->status != 2){

            $exproducts = ExportProduct::where('created_at', ">", $exproduct->created_at)->where('product_id',$exproduct->product_id)->orderBy('created_at', 'DESC')->get();
            $improducts = ImportProduct::where('created_at', '>', $exproduct->created_at)->where('product_id', $exproduct->product_id)->orderBy('created_at', 'DESC')->get();
            foreach($improducts as $i){
                $i->amount_before += $exproduct->amount;
                $i->save();
            }
            foreach($exproducts as $x){
                $x->amount_before += $exproduct->amount;
                $x->save();
            }


            $product = Product::where('id', $exproduct->product_id)->first();
            $product->amount += $exproduct->amount;
            $product->save();

            $client = $export->client;
            $client->balance += $exproduct->price * $exproduct->amount;
            $client->save();

            $export->updated_by = Auth::user()->id;
            $export->price -= $exproduct->price * $exproduct->amount;
            $export->save();

            $exproduct->amount = 0;
            $exproduct->save();

            return "Successfully deleted export product.";


        }
        else{
            return "Unauthorized action!";
        }
    }
    public function deleteWhole(Request $request){
        $export = Export::where('id', $request->id)->first();

        if ((in_array(Auth::user()->level, [1, 2]) || (Auth::user()->level == 4 && $export->created_by == Auth::user()->id )) && $export->status != 2) {


            foreach ($export->export_product as $exproduct) {
                $exproducts = ExportProduct::where('created_at', ">", $exproduct->created_at)->where('product_id',$exproduct->product_id)->orderBy('created_at', 'DESC')->get();
                $improducts = ImportProduct::where('created_at', '>', $exproduct->created_at)->where('product_id', $exproduct->product_id)->orderBy('created_at', 'DESC')->get();
                foreach($improducts as $i){
                    $i->amount_before += $exproduct->amount;
                    $i->save();
                }
                foreach($exproducts as $x){
                    $x->amount_before += $exproduct->amount;
                    $x->save();
                }


                $product = Product::where('id', $exproduct->product_id)->first();
                $product->amount += $exproduct->amount;

                $product->save();


            }


            $client = $export->client;
            $client->balance += $export->price;
            $client->save();

            $export->updated_by = Auth::user()->id;
            $export->status = 0;
            $export->save();


            return "Successfully deleted export.";

        }
        else{
            return "Unauthorized action!";
        }

    }
    public function payment(Request $request){

        if(in_array(Auth::user()->level, [1,2,3])){
            $export = Export::where('id', $request->id)->first();
            $export->status = 2;
            $export->accountant_id = Auth::user()->id;
            $export->save();

            $client = $export->client;
            $client->balance += $export->price;
            $client->save();
        }
        else{
            return "Unauthorized action!";
        }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Export  $export
     * @return \Illuminate\Http\Response
     */
    public function destroy(Export $export)
    {
        //
    }
}
