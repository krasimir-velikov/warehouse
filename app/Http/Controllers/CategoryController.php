<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(in_array(Auth::user()->level, [1,2])) {
            $categories = Category::orderBy('name','ASC')->where('deleted',0)->get();

            return view('categories.index', compact('categories'));
        }
        else{
            return view('home2');
        }
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

                return view('categories.create', compact('temp'));
            }

            return view('categories.create');
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
        if(in_array(Auth::user()->level, [1,2])){
            $this->validatorName(["name"=>$request->name])->validate();

            $category = new Category();
            $category->name = $request->name;
            $category->save();


            return redirect(route('categories.create', ['temp'=>1]));


        }
        else{
            return "Unauthorized action!";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2])) {

            $category = Category::where('id',$request->id)->first();
            if($request->temp){
                $temp = $request->temp;

                return view('categories.edit', compact('temp','category'));
            }

            return view('categories.edit', compact('category'));
        }
        else{
            return view('home2');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2])){
            $this->validatorName(["name"=>$request->name])->validate();

            $category = Category::where('id', $request->id)->first();
            $category->name = $request->name;
            $category->save();

            return redirect(route('categories.edit', ['temp'=>1, 'id'=>$request->id]));


        }
        else{
            return "Unauthorized action!";
        }
    }
    public function delete(Request $request){
        if(in_array(Auth::user()->level, [1,2])){
            $category = Category::where('id',$request->id)->first();
            if($category->product->where('deleted',0)){
                foreach($category->product->where('deleted',0) as $product){
                    $product->deleted = 1;
                    $product->save();
                }
                foreach($category->subcategory->where('deleted',0) as $subcategory){
                    $subcategory->deleted = 1;
                    $subcategory->save();
                }

            }
            $category->deleted = 1;
            $category->save();

            return $category->name;
        }
        else{
            return "Unauthorized action!";
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        //
    }
    protected function validatorName(array $data)
    {
        return Validator::make($data, [
            'name' => Rule::unique('categories')->where(function($query){
            return $query->where('deleted',0);
    })

        ]);
    }
}
