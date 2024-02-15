<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fournisseur = Fournisseur::all();
        return response()->json(['fournisseur'=> $fournisseur]);
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
            'raison_sociale' =>'required',
            'adresse' =>'required',
            'tele' =>'required',
            'ville' =>'required',
            'abreviation' =>'required',
            'zone' =>'required',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $fournisseur = Fournisseur::create($request->all());
            return response()->json(['message' => 'Fournisseur ajouteé avec succès','fournisseur'=> $fournisseur], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        return response()->json(['fournisseur' => $fournisseur]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);

        $validator = Validator::make($request->all(),
        [
            'raison_sociale' =>'required',
            'adresse' =>'required',
            'tele' =>'required',
            'ville' =>'required',
            'abreviation' =>'required',
            'zone' =>'required',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $fournisseur->update($request->all());
            return response()->json(['message' => 'Fournisseur modifié avec succès','fournisseur'=> $fournisseur], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->delete();
        return response()->json(['message' => 'Fournisseur supprimé avec succès'], 204);
    }
}
