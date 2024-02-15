<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ligneCommande;
use Illuminate\Support\Facades\Validator;

class LigneCommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ligneCommande = ligneCommande::all();
        return response()->json(['ligneCommande'=> $ligneCommande]);
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
           'quantite' =>'required',
           'produit_id' =>'required',
           'commande_id' =>'required',
           'prix_unitaire' =>'required',
       ]);
       if ($validator->fails()){
           return response()->json(['error'=> $validator->errors()],400);
       }else
       {
           $ligneCommande = ligneCommande::create($request->all());
           return response()->json(['message' => 'ligneCommande ajouteé avec succès','ligneCommande'=> $ligneCommande], 200);
       }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ligneCommande = ligneCommande::findOrFail($id);
        return response()->json(['ligneCommande' => $ligneCommande]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ligneCommande $ligneCommande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ligneCommande = ligneCommande::findOrFail($id);
        $validator = Validator::make($request->all(),
       [
           'quantite' =>'required',
           'produit_id' =>'required',
           'commande_id' =>'required',
           'prix_unitaire' =>'required',
       ]);
       if ($validator->fails()){
           return response()->json(['error'=> $validator->errors()],400);
       }else
       {
           $ligneCommande = ligneCommande::create($request->all());
           return response()->json(['message' => 'ligneCommande modifié avec succès','ligneCommande'=> $ligneCommande], 200);
       }    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ligneCommande = ligneCommande::findOrFail($id);
        $ligneCommande->delete();

        return response()->json(['message' => 'ligneCommande supprimé avec succès'], 204);
    }
}
