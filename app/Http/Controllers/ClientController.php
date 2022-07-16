<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if($request->name){
            $clients = Client::where('deleted',0)->where('id',$request->name)->orderBy('name', 'ASC')->paginate(15);

        }
        else{
            $clients = Client::where('deleted',0)->orderBy('name', 'ASC')->paginate(15);
        }

        $searchclients = Client::where('deleted',0)->orderBy('name', 'ASC')->paginate(15);


        return view('clients.index', compact('clients', 'searchclients'));
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

                return view('clients.create', compact('temp'));
            }

            return view('clients.create');
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

            $client = new Client();
            $client->name = $request->name;
            $client->information = $request->info;
            $client->save();


            return redirect(route('clients.create', ['temp'=>1]));


        }
        else{
            return "Unauthorized action!";
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        if(in_array(Auth::user()->level, [1,2])) {

            $client = Client::where('id',$request->id)->first();
            if($request->temp){
                $temp = $request->temp;

                return view('clients.edit', compact('temp','client'));
            }

            return view('clients.edit', compact('client'));
        }
        else{
            return $this->index($request);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        if(in_array(Auth::user()->level, [1,2])){
            $this->validatorName(["name"=>$request->name, "id"=>$request->id])->validate();

            $client = Client::where('id', $request->id)->first();
            $client->name = $request->name;
            $client->information = $request->info;
            $client->save();

            return redirect(route('clients.edit', ['temp'=>1, 'id'=>$request->id]));


        }
        else{
            return "Unauthorized action!";
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }

    public function delete(Request $request){
        if(in_array(Auth::user()->level, [1,2])){
            $client = Client::where('id',$request->id)->first();
            $client->deleted = 1;
            $client->save();

            return $client->name;
        }
        else{
            return "Unauthorized action!";
        }
    }

    protected function validatorName(array $data)
    {
        return Validator::make($data, [
            'name' => Rule::unique('clients')->where('deleted',0)->whereNot('id',$data['id'])

        ]);
    }
}
