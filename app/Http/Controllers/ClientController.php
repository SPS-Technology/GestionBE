<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $client = Client::all();
        return response()->json(['client'=> $client]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation 
        $validator = Validator::make($request->all(),
        [
            'raison_sociale' => 'required',
            'adresse' => 'required',
            'tele' => 'required',
            'ville' => 'required',
            'abreviation' => 'required',
            'zone' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $client = Client::create($request->all());
            return response()->json(['client'=> $client], 200);
        }
            


    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['client' => $client]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $validator = Validator::make($request->all(),
        [
            'raison_sociale' => 'required',
            'adresse' => 'required',
            'tele' => 'required',
            'ville' => 'required',
            'abreviation' => 'required',
            'zone' => 'required',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $client->update($request->all());
            return response()->json(['client'=> $client], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Client supprimé avec succès'], 204);
    }
}
