<?php

namespace App\Http\Controllers;

use App\Models\Livreur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LivreurController extends Controller
{
    public function index()
    {
        try {
            $livreurs = Livreur::all();
            return response()->json(['livreurs' => $livreurs], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom' => 'required',
                'prenom' => 'required',
                'cin' => 'required|unique:livreurs',
                'tele' => 'required',
                'adresse' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $livreur = Livreur::create($request->all());
            return response()->json(['message' => 'Livreur ajouté avec succès', 'livreur' => $livreur], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $livreur = Livreur::findOrFail($id);
            return response()->json(['livreur' => $livreur], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom' => 'required',
                'prenom' => 'required',
                'cin' => 'required|unique:livreurs,cin,'.$id,
                'tele' => 'required',
                'adresse' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $livreur = Livreur::findOrFail($id);
            $livreur->update($request->all());

            return response()->json(['message' => 'Livreur modifié avec succès', 'livreur' => $livreur], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $livreur = Livreur::findOrFail($id);
            $livreur->delete();

            return response()->json(['message' => 'Livreur supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
