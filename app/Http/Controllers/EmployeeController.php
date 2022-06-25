<?php

namespace App\Http\Controllers;

use App\ActiveEmail;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index(){

            if(Auth::user()->level<=2){

                $admins = User::where('level', 2)->where('status', '!=', 2)->get();
                $accountants = User::where('level', 3)->where('status', '!=', 2)->get();
                $workers = User::where('level', 4)->where('status', '!=', 2)->get();

                return view('employees.index', compact('admins', 'accountants', 'workers'));

            }
            else{
                return view('home2');
            }


    }
    public function create(Request $request){

        if(Auth::user()->level<=2){

        $level = $request->level;

        return view('employees.create', compact('level'));
        }
        else{
            return view('home2');
        }
    }
    public function store(Request $request){

        $this->validator($request->all())->validate();

        $employee = new User();
        $employee->name = $request->name;
        $employee->email = $request->email;
        $employee->level = $request->level;
        $employee->password = bcrypt($request->password);
        $employee->save();

        $email = new ActiveEmail();
        $email->email = $employee->email;
        $email->user_id = $employee->id;
        $email->save();

        Session()->flash('message','Успешен запис!');

        return $this->index();

    }

    public function edit(Request $request){

        if(Auth::user()->level <= 2){

        $user = User::where('id', $request->id)->first();

        return view('employees.edit', compact('user'));
        }
        else{
            return view('home2');
        }


    }

    public function storeEdit(Request $request){

        $user = User::where('id', $request->id)->first();

        if((Auth::user()->level==1 && $user->level > 1) || (Auth::user()->level==2 && $user->level > 2)){

            if($request->level && (($request->level > 1 && Auth::user()->level == 1) || ($request->level > 2 && Auth::user()->level == 2))){
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

                $email = ActiveEmail::where('user_id',$user->id)->first();
                $email->email = $request->email;
                $email->save();

                $user->email = $request->email;
                $user->save();


            }
            elseif($request->password){
                $this->validatorPassword(["password"=>$request->password, "password_confirmation"=>$request->password_confirmation])->validate();
                $user->password = bcrypt($request->password);
                $user->save();
            }

            Session()->flash('message','Успешен запис!');


            return $this->edit($request);



        }
        else{

            return "<p>Nqmash Pravo!</p>";

        }


    }

    public function block(Request $request){
        if(Auth::user()->level <= 2){


            $user = User::where('id', $request->id)->first();


        if((Auth::user()->level==1 && $user->level > 1) || (Auth::user()->level==2 && $user->level > 2)){
            $user->status = 0;
            $user->save();

            return "<button id=\"".$user->id."A\" class=\"btn activate\" value=\"".$user->id."\" title=\"Activate the account\" onclick=\"activate(this.value)\">Activate</button>";
        }
        else{
            return "<p>Nqmash Pravo!</p>";
        }
        }
        else{
            return view('home2');
        }


    }

    public function activate(Request $request){
        if(Auth::user()->level <= 2){

            $user = User::where('id', $request->id)->first();
            if((Auth::user()->level==1 && $user->level > 1) || (Auth::user()->level==2 && $user->level > 2)){
                $user->status = 1;
                $user->save();

                return "<button id=\"".$user->id."B\" class=\"btn block\" value=\"".$user->id."\" title=\"Block the account\" onclick=\"block(this.value)\">Block</button>";
            }

            return "<p>Nqmash Pravo!</p>";
            }
        else{
            return view('home2');

        }



    }

    public function delete(Request $request){
        $user = User::where('id', $request->id)->first();

        if((Auth::user()->level == 1 && $user->level > 1) || (Auth::user()->level==2 && $user->level > 2)){

            $user->status = 2;
            $user->save();

            $email = ActiveEmail::where('user_id', $user->id);
            $email->delete();

            return $user->id;
        }
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:active_emails'],
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:active_emails'],

        ]);
    }
    protected function validatorPassword(array $data)
    {
        return Validator::make($data, [
            'password' => ['required', 'string', 'min:8', 'confirmed'],

        ]);
    }


}
