<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StatusCommande;
use Illuminate\Support\Facades\Validator;

class StatusCommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $StatusCommande = StatusCommande::all();
        return response()->json(['StatusCommande'=> $StatusCommande]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // validation 
        $validator = Validator::make($request->all(),
        [
            'status' =>'required',
            'date_status' =>'required',
            'commande_id' =>'required',

        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $StatusCommande = StatusCommande::create($request->all());
            return response()->json(['message' => 'Status Commande ajouteé avec succès','StatusCommande'=> $StatusCommande], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $StatusCommande = StatusCommande::findOrFail($id);
        return response()->json(['StatusCommande' => $StatusCommande]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(StatusCommande $statusCommande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // validation
        $validator = Validator::make($request->all(),
        [
            'status' =>'required',
            'date_status' =>'required',
            'commande_id' =>'required',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $StatusCommande = StatusCommande::findOrFail($id);
            $StatusCommande->update($request->all());
            return response()->json(['message' => 'StatusCommande modifié avec succès','StatusCommande'=> $StatusCommande], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {   
        $StatusCommande = StatusCommande::findOrFail($id);
        $StatusCommande->delete();
        return response()->json(['message' => 'StatusCommande supprimé avec succès'], 204);
    }
}
