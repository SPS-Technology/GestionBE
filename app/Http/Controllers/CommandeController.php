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
        $commandes = Commande::all();
        return response()->json(['Commandes' => $commandes]);
    }

    public function show($id)
    {
        $commande = Commande::findOrFail($id);
        return response()->json(['commande' => $commande]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'dateCommande' => 'required',
            'idClient' => 'required',
            'tel' => 'required',
            'ville' => 'required',
            'abreviation' => 'required',
           
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $commande = Commande::create($request->all());

        return response()->json(['message' => 'Commande ajouté avec succès', 'Commande' => $commande], 201);
    }

    public function update(Request $request, $id)
    {
        $commande = Commande::findOrFail($id);

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

        $commande->update($request->all());

        return response()->json(['message' => 'Commande modifié avec succès', 'Commande' => $commande], 200);
    }

    public function destroy($id)
    {
        $commande = Commande::findOrFail($id);
        $commande->delete();

        return response()->json(['message' => 'Commande supprimé avec succès'], 204);
    }
}
