<?php

namespace App\Http\Controllers;

use App\Models\ChargementCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChargementCommandeController extends Controller
{
    public function index()
    {
        try {
            $chargementCommandes = ChargementCommande::all();
            return response()->json(['chargementCommandes' => $chargementCommandes], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'veihicule_id' => 'required',
                'livreur_id' => 'nullable',
                'commande_id' => 'required',
                'dateLivraisonPrevue' => 'required',
                'dateLivraisonReelle' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $chargementCommande = ChargementCommande::create($request->all());
            return response()->json(['message' => 'ChargementCommande ajouté avec succès', 'chargementCommande' => $chargementCommande], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $chargementCommande = ChargementCommande::findOrFail($id);
            return response()->json(['chargementCommande' => $chargementCommande], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'veihicule_id' => 'required',
                'livreur_id' => 'required',
                'commande_id' => 'required',
                'dateLivraisonPrevue' => 'required',
                'dateLivraisonReelle' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $chargementCommande = ChargementCommande::findOrFail($id);
            $chargementCommande->update($request->all());

            return response()->json(['message' => 'ChargementCommande modifié avec succès', 'chargementCommande' => $chargementCommande], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $chargementCommande = ChargementCommande::findOrFail($id);
            $chargementCommande->delete();

            return response()->json(['message' => 'ChargementCommande supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
