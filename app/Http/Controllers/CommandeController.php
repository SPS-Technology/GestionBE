<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $commande = Commande::all();
        return response()->json(['commande'=> $commande]);
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
            'dateCommande' =>'required',
            'client_id' =>'required',
            'user_id' =>'required',
            'status' =>'required',
            'fournisseur_id' =>'nullable',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $commande = Commande::create($request->all());
            return response()->json(['message' => 'Commande ajouteé avec succès','commande'=> $commande], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $commande = Commande::findOrFail($id);
        return response()->json(['commande' => $commande]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commande $commande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);
        // validation 
        $validator = Validator::make($request->all(),
        [
            'dateCommande' =>'required',
            'client_id' =>'required',
            'user_id' =>'required',
            'status' =>'required',
            'fournisseur_id' =>'nullable',

        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $commande = Commande::create($request->all());
            return response()->json(['message' => 'commande modifié avec succès','commande'=> $commande], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $commande = Commande::findOrFail($id);
            $commande->delete();
            return response()->json(['message' => 'Le commande a été supprimé avec succès.'], 200);
        } catch (QueryException $e) {
            // Si une exception est déclenchée, cela signifie que le client a des commandes associées
            return response()->json(['error' => 'Impossible de supprimer ce commande car il a des status ou lignes associées.'], 400);
        }
    }
}
