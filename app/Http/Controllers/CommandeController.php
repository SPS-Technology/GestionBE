<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
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
            'fournisseur_id' =>'nullable',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $commande = Commande::create($request->all());
            return response()->json(['commande'=> $commande], 200);
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
            'fournisseur_id' =>'nullable',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $commande = Commande::create($request->all());
            return response()->json(['commande'=> $commande], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->delete();
        return response()->json(['message' => 'Commande supprimé avec succès'], 204);
    }
}
