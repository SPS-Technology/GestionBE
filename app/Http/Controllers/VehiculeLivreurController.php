<?php

namespace App\Http\Controllers;

use App\Models\VehiculeLivreur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehiculeLivreurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $vehiculeLivreurs = VehiculeLivreur::with('livreur', 'vehicule')->get();
            return response()->json(['vehicule_livreurs' => $vehiculeLivreurs], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            $messages = [
                'livreur_id.required' => 'Le champ livreur_id est requis.',
                'vehicule_id.required' => 'Le champ vehicule_id est requis.',
                'date_debut_affectation.required' => 'Le champ date_debut_affectation est requis.',
                'date_debut_affectation.date' => 'Le champ date_debut_affectation doit être une date valide.',
                'date_fin_affectation.date' => 'Le champ date_fin_affectation doit être une date valide.',
                'date_fin_affectation.after_or_equal' => 'Le champ date_fin_affectation doit être postérieur ou égal à date_debut_affectation.',
                'kilometrage_debut.required' => 'Le champ kilometrage_debut est requis.',
                'kilometrage_debut.numeric' => 'Le champ kilometrage_debut doit être un nombre.',
                'kilometrage_fin.numeric' => 'Le champ kilometrage_fin doit être un nombre.',
                'kilometrage_fin.gte' => 'Le champ kilometrage_fin doit être supérieur ou égal à kilometrage_debut.',
                'heure.required' => 'Le champ heure est requis.',
            ];
    
            $validator = Validator::make($request->all(), [
                'livreur_id' => 'required',
                'vehicule_id' => 'required',
                'date_debut_affectation' => 'required|date',
                'date_fin_affectation' => 'nullable|date|after_or_equal:date_debut_affectation',
                'kilometrage_debut' => 'required|numeric',
                'kilometrage_fin' => 'nullable|numeric|gte:kilometrage_debut',
                'heure' => 'required',
            ], $messages);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $vehiculeLivreur = VehiculeLivreur::create($request->all());
            return response()->json(['message' => 'Affectation de véhicule au livreur ajoutée avec succès', 'vehicule_livreur' => $vehiculeLivreur], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $vehiculeLivreur = VehiculeLivreur::findOrFail($id);
            return response()->json(['vehicule_livreur' => $vehiculeLivreur], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'livreur_id' => 'required',
                'vehicule_id' => 'required',
                'date_debut_affectation' => 'required|date',
                'date_fin_affectation' => 'nullable|date|after_or_equal:date_debut_affectation',
                'kilometrage_debut' => 'required|numeric',
                'kilometrage_fin' => 'nullable|numeric|gte:kilometrage_debut',
                'heure' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $vehiculeLivreur = VehiculeLivreur::findOrFail($id);
            $vehiculeLivreur->update($request->all());

            return response()->json(['message' => 'Affectation de véhicule au livreur modifiée avec succès', 'vehicule_livreur' => $vehiculeLivreur], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $vehiculeLivreur = VehiculeLivreur::findOrFail($id);
            $vehiculeLivreur->delete();

            return response()->json(['message' => 'Affectation de véhicule au livreur supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
