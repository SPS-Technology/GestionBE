<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Facture;
use App\Models\Ligneentrercompte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LigneentrercompteController extends Controller
{
    public function index()
    {
        try {
            $ligneentrercomptes = Ligneentrercompte::all();
            $count = Ligneentrercompte::count();

            return response()->json([
                'message' => 'Liste des lignes d\'entrée de compte récupérée avec succès',
                'ligneentrercomptes' => $ligneentrercomptes,
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
                'id_facture' => 'required',
                'avance' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $ligneentrercompte = Ligneentrercompte::create($request->all());

            return response()->json(['message' => 'Ligne d\'entrée de compte ajoutée avec succès', 'ligneentrercompte' => $ligneentrercompte], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:clients,id',
                'id_facture' => 'required|exists:factures,id',
                'avance' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $ligneentrercompte = Ligneentrercompte::findOrFail($id);
            $ligneentrercompte->update($request->all());

            return response()->json(['message' => 'Ligne d\'entrée de compte modifiée avec succès', 'ligneentrercompte' => $ligneentrercompte], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $ligneentrercompte = Ligneentrercompte::findOrFail($id);
            $ligneentrercompte->delete();

            return response()->json(['message' => 'Ligne d\'entrée de compte supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
