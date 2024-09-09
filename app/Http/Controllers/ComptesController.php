<?php

namespace App\Http\Controllers;

use App\Models\Comptes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComptesController extends Controller
{
    public function index()
    {
        try {
            $comptes = Comptes::all();
            $count = Comptes::count();

            return response()->json([
                'message' => 'Liste des comptes récupérée avec succès',
                'comptes' => $comptes,
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
                'designations' => 'required|string',
                'type_compte' => 'required|string',
                'devise' => 'required|string',
                'rib' => 'required|string',
                'swift' => 'required|string',
                'adresse' => 'required|string',
                'remarque' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $compte = Comptes::create($request->all());

            return response()->json(['message' => 'Compte ajouté avec succès', 'compte' => $compte], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'designations' => 'required|string',
                'type_compte' => 'required|string',
                'devise' => 'required|string',
                'rib' => 'required|string',
                'swift' => 'required|string',
                'adresse' => 'required|string',
                'remarque' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $compte = Comptes::findOrFail($id);
            $compte->update($request->all());

            return response()->json(['message' => 'Compte modifié avec succès', 'compte' => $compte], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $compte = Comptes::findOrFail($id);
            $compte->delete();

            return response()->json(['message' => 'Compte supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
