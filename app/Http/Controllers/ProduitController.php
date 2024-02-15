<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produit = Produit::all();
        return response()->json(['produit' => $produit]);
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
        $validator = Validator::make(
            $request->all(),
            [
                'nom' => 'required',
                'type_quantite' => 'required',
                'calibre' => 'required',
                'fournisseur_id' => 'nullable',

            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            $produit = Produit::create($request->all());
            return response()->json(['message' => 'Produit ajouteé avec succès', 'produit' => $produit], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produit = Produit::findOrFail($id);
        return response()->json(['produit' => $produit]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $produit = Produit::findOrFail($id);
        // validation 
        $validator = Validator::make(
            $request->all(),
            [
                'nom' => 'required',
                'type_quantite' => 'required',
                'calibre' => 'required',
                'fournisseur_id' => 'nullable',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            $produit = Produit::create($request->all());
            return response()->json(['message' => 'Produit modifie avec succes', 'produit' => $produit], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $produit = Produit::findOrFail($id);
            $produit->delete();

            return response()->json(['message' => 'Le produit a été supprimé avec succès.'], 200);
        } catch (QueryException $e) {
            // Si une exception est déclenchée, cela signifie que le fournisseur a des commandes associées
            return response()->json(['error' => 'Impossible de supprimer ce produit car il a des tables associées.'], 400);
        }
    }
}
