<?php

namespace App\Http\Controllers;

use App\Models\LignePreparationCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

    class PreparationLigneCommandeController extends Controller
{
    public function index()
    {
        try {
            $lignePreparationCommandes = LignePreparationCommande::with('preparation')->get();
            return response()->json(['lignePreparationCommandes' => $lignePreparationCommandes], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'preparation_id' => 'required',
                'produit_id' => 'required',
                'quantite' => 'required',
                'prix_unitaire' => 'required',
                'lot' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $lignePreparationCommande = LignePreparationCommande::create($request->all());
            return response()->json(['message' => 'LignePreparationCommande ajouté avec succès', 'lignePreparationCommande' => $lignePreparationCommande], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function show($id)
    // {
    //     try {
    //         $lignePreparationCommande = LignePreparationCommande::findOrFail($id);
    //         return response()->json(['lignePreparationCommande' => $lignePreparationCommande], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 404);
    //     }
    // }

    public function show($commandeId)
    {
        $lignePreparationCommandes = LignePreparationCommande::where('preparation_id', $commandeId)->get();
        return response()->json(['lignePreparationCommandes' => $lignePreparationCommandes]);
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'preparation_id' => 'required',
                'produit_id' => 'required',
                'quantite' => 'required',
                'prix_unitaire' => 'required',
                'lot' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $lignePreparationCommande = LignePreparationCommande::findOrFail($id);
            $lignePreparationCommande->update($request->all());

            return response()->json(['message' => 'LignePreparationCommande modifié avec succès', 'lignePreparationCommande' => $lignePreparationCommande], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $lignePreparationCommande = LignePreparationCommande::findOrFail($id);
            $lignePreparationCommande->delete();

            return response()->json(['message' => 'LignePreparationCommande supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}