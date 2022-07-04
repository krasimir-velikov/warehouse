<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->name){
            $suppliers = Supplier::where('deleted',0)->where('id',$request->name)->orderBy('name', 'ASC')->paginate(13);

        }
        else{
            $suppliers = Supplier::where('deleted',0)->orderBy('name', 'ASC')->paginate(13);
        }

        $searchsuppliers = Supplier::where('deleted',0)->orderBy('name', 'ASC')->paginate(13);


        return view('suppliers.index', compact('suppliers', 'searchsuppliers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2])) {

            if($request->temp){
                $temp = $request->temp;

                return view('suppliers.create', compact('temp'));
            }

            return view('suppliers.create');
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
            $this->validatorName(["name"=>$request->name, "id"=>$request->id])->validate();

            $supplier = new Supplier();
            $supplier->name = $request->name;
            $supplier->information = $request->info;
            $supplier->save();


            return redirect(route('suppliers.create', ['temp'=>1]));


        }
        else{
            return "Unauthorized action!";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2])) {

            $supplier = Supplier::where('id',$request->id)->first();
            if($request->temp){
                $temp = $request->temp;

                return view('suppliers.edit', compact('temp','supplier'));
            }

            return view('suppliers.edit', compact('supplier'));
        }
        else{
            return $this->index($request);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        if(in_array(Auth::user()->level, [1,2])){
            $this->validatorName(["name"=>$request->name, "id"=>$request->id])->validate();

            $supplier = Supplier::where('id', $request->id)->first();
            $supplier->name = $request->name;
            $supplier->information = $request->info;
            $supplier->save();

            return redirect(route('suppliers.edit', ['temp'=>1, 'id'=>$request->id]));


        }
        else{
            return "Unauthorized action!";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

    public function delete(Request $request){
        if(in_array(Auth::user()->level, [1,2])){
            $supplier = Supplier::where('id',$request->id)->first();
            $supplier->deleted = 1;
            $supplier->save();

            return $supplier->name;
        }
        else{
            return "Unauthorized action!";
        }
    }

    protected function validatorName(array $data)
    {
        return Validator::make($data, [
            'name' => Rule::unique('suppliers')->where('deleted',0)->whereNot('id',$data['id'])

        ]);
    }
}
