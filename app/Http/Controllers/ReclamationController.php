<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reclamation;
use Illuminate\Support\Facades\Validator;

class ReclamationController extends Controller
{
    public function index()
    {
        try {
            $reclamations = Reclamation::all();
            $count = Reclamation::count();

            return response()->json([
                'message' => 'Liste des réclamations récupérée avec succès',
                'reclamations' => $reclamations,
                'count' => $count
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
                'sujet' => 'required|string',
                'date_reclamation' => 'required|date',
                'status_reclamation' => 'required|string',
                'traitement_reclamation' => 'required|string',
                'date_traitement' => 'required|date',
                'remarque'=> 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $reclamation = Reclamation::create($request->all());

            return response()->json(['message' => 'Réclamation ajoutée avec succès', 'reclamation' => $reclamation], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:clients,id',
                'sujet' => 'required|string',
                'date_reclamation' => 'required|date',
                'status_reclamation' => 'required|string',
                'traitement_reclamation' => 'nullable|string',
                'date_traitement' => 'nullable|date',
                'remarque'=> 'required',

            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $reclamation = Reclamation::findOrFail($id);
            $reclamation->update($request->all());

            return response()->json(['message' => 'Réclamation modifiée avec succès', 'reclamation' => $reclamation], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $reclamation = Reclamation::findOrFail($id);
            $reclamation->delete();

            return response()->json(['message' => 'Réclamation supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
