<?php

namespace App\Http\Controllers;

use App\Category;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class SubcategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2])) {

            $category = Category::where('id',$request->cat)->first();

            if($request->temp){
                $temp = $request->temp;

                return view('subcategories.create', compact('category','temp'));
            }

            return view('subcategories.create',compact('category'));
        }
        else{
            return view('home2');
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
        {
            if(in_array(Auth::user()->level, [1,2])){
                $this->validatorName(["name"=>$request->name, 'cat'=>$request->cat])->validate();

                $subcategory = new Subcategory();
                $subcategory->name = $request->name;
                $subcategory->category_id = $request->cat;
                $subcategory->save();



                return redirect(route('subcategories.create', ['temp'=>1,'cat'=>$request->cat]));


            }
            else{
                return "Unauthorized action!";
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function show(Subcategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2])) {

            $subcategory = Subcategory::where('id',$request->id)->first();
            if($request->temp){
                $temp = $request->temp;

                return view('subcategories.edit', compact('temp','subcategory'));
            }

            return view('subcategories.edit', compact('subcategory'));
        }
        else{
            return view('home2');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2])){
            $this->validatorName(["name"=>$request->name, 'cat'=>$request->cat])->validate();

            $subcategory = Subcategory::where('id', $request->id)->first();
            $subcategory->name = $request->name;
            $subcategory->save();

            return redirect(route('subcategories.edit', ['temp'=>1, 'id'=>$request->id]));


        }
        else{
            return "Unauthorized action!";
        }
    }

    public function delete(Request $request){
        if(in_array(Auth::user()->level, [1,2])){
            $subcategory = Subcategory::where('id',$request->id)->first();
            if($subcategory->product->where('deleted',0)){
                foreach($subcategory->product->where('deleted',0) as $product){
                    $product->deleted = 1;
                    $product->save();
                }

            }
            $subcategory->deleted = 1;
            $subcategory->save();

            return $subcategory->name;
        }
        else{
            return "Unauthorized action!";
        }


        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subcategory  $subcategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subcategory $subcategory)
    {
        //
    }
    protected function validatorName(array $data)
    {
        return Validator::make($data, [
            'name' => Rule::unique('subcategories')->where('deleted',0)->where('category_id',$data['cat'])

        ]);
    }
}
