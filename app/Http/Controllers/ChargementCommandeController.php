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
            $chargementCommandes = ChargementCommande::with('livreur', 'vehicule', 'commande.ligneCommandes')->get();
            return response()->json(['chargementCommandes' => $chargementCommandes], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    public function store(Request $request)
    {
        try {
            $messages = [
                'vehicule_id.required' => 'Le champ vehicule_id est requis.',
                'livreur_id.required' => 'Le champ livreur_id est requis.',
                'confort.required' => 'Le champ confort est requis.',
                'remarque.required' => 'Le champ remarque est requis.',
                'commande_id.required' => 'Le champ commande_id est requis.',
                'dateLivraisonPrevue.required' => 'Le champ dateLivraisonPrevue est requis.',
                'dateLivraisonReelle.required' => 'Le champ dateLivraisonReelle est requis.',

            ];
            $validator = Validator::make($request->all(), [
                'vehicule_id' => 'required',
                'confort' => 'required',
                'remarque' => 'nullable',
                'livreur_id' => 'required',
                'commande_id' => 'required',
                'dateLivraisonPrevue' => 'required',
                'dateLivraisonReelle' => 'nullable',
            ],$messages);

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
    public function getByCommandeId($commandeId)
{
    try {
        $chargementCommandes = ChargementCommande::where('commande_id', $commandeId)->with('livreur', 'vehicule')->get();
        return response()->json(['chargementCommandes' => $chargementCommandes], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vehicule_id' => 'required',
                'confort' => 'required',
                'remarque' => 'nullable',
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