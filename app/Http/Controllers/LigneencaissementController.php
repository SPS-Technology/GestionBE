<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Ligneencaissement;

class LigneencaissementController extends Controller
{
    public function index()
    {
        try {
            $ligneencaissements = Ligneencaissement::all();
            $count = Ligneencaissement::count();

            return response()->json([
                'message' => 'Liste des lignes d\'encaissement récupérée avec succès',
                'ligneencaissements' => $ligneencaissements,
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
                'entrer_comptes_id' => 'required',
                'encaissements_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $ligneencaissement = Ligneencaissement::create($request->all());

            return response()->json(['message' => 'Ligne d\'encaissement ajoutée avec succès', 'ligneencaissement' => $ligneencaissement], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'entrer_comptes_id' => 'required',
                'encaissements_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $ligneencaissement = Ligneencaissement::findOrFail($id);
            $ligneencaissement->update($request->all());

            return response()->json(['message' => 'Ligne d\'encaissement modifiée avec succès', 'ligneencaissement' => $ligneencaissement], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $ligneencaissement = Ligneencaissement::findOrFail($id);
            $ligneencaissement->delete();

            return response()->json(['message' => 'Ligne d\'encaissement supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
