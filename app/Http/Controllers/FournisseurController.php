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
        $fournisseurs = Fournisseur::all();
        return response()->json(['fournisseurs' => $fournisseurs]);
    }

    public function show($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        return response()->json(['fournisseur' => $fournisseur]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'required',
            'adresse' => 'required',
            'tel' => 'required',
            'ville' => 'required',
            'abreviation' => 'required',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $fournisseur = Fournisseur::create($request->all());

        return response()->json(['message' => 'Fournisseur ajouté avec succès', 'fournisseur' => $fournisseur], 201);
    }

    public function update(Request $request, $id)
    {
        $fournisseur = Fournisseur::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'required',
            'adresse' => 'required',
            'tel' => 'required',
            'ville' => 'required',
            'abreviation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $fournisseur->update($request->all());

        return response()->json(['message' => 'Fournisseur modifié avec succès', 'fournisseur' => $fournisseur], 200);
    }

    public function destroy($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->delete();

        return response()->json(['message' => 'Fournisseur supprimé avec succès'], 204);
    }
}
