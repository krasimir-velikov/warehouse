<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

            return view('home2');

    }
    public function finances(){
        if(Auth::user()->level<=3){
            return view('finances');
        }
        else{
            return view('home2');
        }
    }
    public function transfers()
    {
        return view('transfers');
    }

}
