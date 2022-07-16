<?php

namespace App\Http\Controllers;

use App\ActiveEmail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index(){

            if(in_array(Auth::user()->level, [1,2])){

                $admins = User::where('level', 2)->where('status', '!=', 2)->get();
                $accountants = User::where('level', 3)->where('status', '!=', 2)->get();
                $workers = User::where('level', 4)->where('status', '!=', 2)->get();

                return view('employees.index', compact('admins', 'accountants', 'workers'));

            }
            else{
                return view('home');
            }


    }
    public function create(Request $request){

        if(in_array(Auth::user()->level, [1,2])){

        $level = $request->level;
        if($request->temp) {
            $temp=$request->temp;
            return view('employees.create', compact('level','temp'));
        }
        else{
            return view('employees.create', compact('level'));
        }

        }
        else{
            return view('home');
        }
    }
    public function store(Request $request){

        if((Auth::user()->level == 1 && in_array($request->level, [2,3,4])) || (Auth::user()->level == 2 && in_array($request->level, [3,4]))) {

            $this->validator($request->all())->validate();

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->level = $request->level;
            $user->password = bcrypt($request->password);
            $user->save();



            return redirect(route('employees.create', ['temp'=>1,'level'=>$request->level]));

        }
        else{
            return "Unauthorized action!";
        }

    }

    public function edit(Request $request){

        if(in_array(Auth::user()->level, [1,2])){

            $user = User::where('id', $request->id)->first();

            if($request->temp){
                $temp = $request->temp;
                return view('employees.edit', compact('user', 'temp'));

            }else{
                return view('employees.edit', compact('user'));

            }

        }
        else{
            return view('home');
        }


    }

    public function update(Request $request){

        $user = User::where('id', $request->id)->first();


            if($request->level && (Auth::user()->level==1 && in_array($request->level, [2,3,4])) || (Auth::user()->level==2 && in_array($request->level, [3,4]))){
                $user->level = $request->level;
                $user->save();
            }
            elseif($request->name){
                $this->validatorName(["name"=>$request->name])->validate();
                $user->name = $request->name;
                $user->save();
            }
            elseif($request->email){
                $this->validatorEmail(["email"=>$request->email])->validate();

                $user->email = $request->email;
                $user->save();


            }
            elseif($request->password){
                $this->validatorPassword(["password"=>$request->password, "password_confirmation"=>$request->password_confirmation])->validate();
                $user->password = bcrypt($request->password);
                $user->save();
            }
            else{
                return "Unauthorized action!";
            }





            return redirect(route('employees.edit', ['temp'=>1,'id'=>$request->id,'level'=>$request->level]));





    }

    public function block(Request $request){
        if(in_array(Auth::user()->level, [1,2])){


            $user = User::where('id', $request->id)->first();


        if((Auth::user()->level==1 && $user->level > 1) || (Auth::user()->level==2 && $user->level > 2)){
            $user->status = 0;
            $user->save();

            return "<button id=\"".$user->id."A\" class=\"btn activate\" value=\"".$user->id."\" title=\"Activate the account\" onclick=\"activate(this.value)\">Activate</button>";
        }
        else{
            return "Unauthorized action!";
        }
        }
        else{
            return view('home');
        }


    }

    public function activate(Request $request){
        if(in_array(Auth::user()->level, [1,2])){

            $user = User::where('id', $request->id)->first();
            if((Auth::user()->level==1 && $user->level > 1) || (Auth::user()->level==2 && $user->level > 2)){
                $user->status = 1;
                $user->save();

                return "<button id=\"".$user->id."B\" class=\"btn block\" value=\"".$user->id."\" title=\"Block the account\" onclick=\"block(this.value)\">Block</button>";
            }

            return "Unauthorized action!";
            }
        else{
            return view('home');

        }



    }

    public function delete(Request $request){
        if(in_array(Auth::user()->level, [1,2])) {
            $user = User::where('id', $request->id)->first();

            if ((Auth::user()->level == 1 && $user->level > 1) || (Auth::user()->level == 2 && $user->level > 2)) {

                $user->status = 2;
                $user->save();

                return $user->id;
            } else {
                return "Unauthorized action!";
            }
        }
        else{
            return view('home');
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->where(function($query){
                return $query->where('status','!=',2);
            })],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    protected function validatorName(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],

        ]);
    }
    protected function validatorEmail(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->where(function($query){
                return $query->where('status','!=',2);
            })]

        ]);
    }
    protected function validatorPassword(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],

        ]);
    }


}
