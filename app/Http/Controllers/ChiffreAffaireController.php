<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\Produit;
use Illuminate\Http\Request;
use App\Models\ChiffreAffaire;
use App\Models\Client;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\Facades\Validator;

class ChiffreAffaireController extends Controller
{
    public function index()
    {
        try {
            $chiffresAffaires = ChiffreAffaire::all();
            $count = ChiffreAffaire::count();

            return response()->json([
                'message' => 'Liste des chiffres d\'affaires récupérée avec succès',
                'chiffres_affaires' => $chiffresAffaires,
                'count' => $count,
                
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getAllDataChefreDaffer()
    {
        try {
            // Récupérer toutes les factures avec leurs relations si nécessaire
            $factures = Facture::all();
    
            // Récupérer tous les chiffres d'affaires
            $chiffresAffaires = ChiffreAffaire::all();
    
            // Vérifier si l'utilisateur a la permission de voir la liste des clients
                // Récupérer tous les clients avec leurs relations
                $clients = Client::with('user', 'zone', 'siteclients.zone', 'siteclients.region', 'region')->get();
         
    
            // Retourner toutes les données dans une seule réponse JSON
            return response()->json([
                'message' => 'Données récupérées avec succès',
                'factures' => $factures,
                'chiffres_affaires' => $chiffresAffaires,
                'clients' => $clients
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $chiffreAffaire = ChiffreAffaire::create($request->all());

            return response()->json(['message' => 'Chiffre d\'affaire ajouté avec succès', 'chiffre_affaire' => $chiffreAffaire], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:clients,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $chiffreAffaire = ChiffreAffaire::findOrFail($id);
            $chiffreAffaire->update($request->all());

            return response()->json(['message' => 'Chiffre d\'affaire modifié avec succès', 'chiffre_affaire' => $chiffreAffaire], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $chiffreAffaire = ChiffreAffaire::findOrFail($id);

            return response()->json(['chiffre_affaire' => $chiffreAffaire]);
        } catch (\Exception $e) {
            return response()->json(['error'=> $e->getMessage()],500);
        }
    }

    public function destroy($id)
    {
        try {
            $chiffreAffaire = ChiffreAffaire::findOrFail($id);
            $chiffreAffaire->delete();

            return response()->json(['message' => 'Chiffre d\'affaire supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
