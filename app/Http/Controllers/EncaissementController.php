<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Encaissement;

class EncaissementController extends Controller
{
    public function index()
    {
        try {
            $encaissements = Encaissement::with('ligneEncaissement')->get();

            $count = Encaissement::count();

            return response()->json([
                'message' => 'Liste des encaissements récupérée avec succès',
                'encaissements' => $encaissements,
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
                'referencee' => 'required',
                'date_encaissement' => 'required',
                'montant_total' => 'required',
                'comptes_id' => 'required',
                'type_encaissement' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $encaissement = Encaissement::create($request->all());

            return response()->json(['message' => 'Encaissement ajouté avec succès', 'encaissement' => $encaissement], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'referencee' => 'required',
                'date_encaissement' => 'required|date',
                'montant_total' => 'required',
                'comptes_id' => 'required|exists:comptes,id',
                'type_encaissement' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $encaissement = Encaissement::findOrFail($id);
            $encaissement->update($request->all());

            return response()->json(['message' => 'Encaissement modifié avec succès', 'encaissement' => $encaissement], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $encaissement = Encaissement::findOrFail($id);
            $encaissement->delete();

            return response()->json(['message' => 'Encaissement supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
