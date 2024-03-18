<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChiffreAffaire;
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
