<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class FournisseurController extends Controller
{
    public function index()
    {
        if (Gate::allows('view_all_fournisseurs')) {
            try {
                $fournisseurs = Fournisseur::with('user')->get();
                $count = Fournisseur::count();

                return response()->json([
                    'message' => 'Liste des fournisseurs récupérée avec succès',
                    'fournisseurs' => $fournisseurs,
                    'count' => $count
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des fournisseurs.');
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('create_fournisseurs')) {
            try {
                $validator = Validator::make($request->all(), [
                    'raison_sociale' => 'required',
                    'adresse' => 'required',
                    'tele' => 'required',
                    'ville' => 'required',
                    'abreviation' => 'required',
                    'zone' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }
                $request['user_id'] = Auth::id(); // Ajoutez ceci


                $fournisseur = Fournisseur::create($request->all());

                return response()->json(['message' => 'Fournisseur ajouté avec succès', 'fournisseur' => $fournisseur], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de créer un fournisseur.');
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::allows('update_fournisseurs')) {
            try {
                $validator = Validator::make($request->all(), [
                    'raison_sociale' => 'required',
                    'adresse' => 'required',
                    'tele' => 'required',
                    'ville' => 'required',
                    'abreviation' => 'required',
                    'zone' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }
                $request['user_id'] = Auth::id(); // Ajoutez ceci

                $fournisseur = Fournisseur::findOrFail($id);
                $fournisseur->update($request->all());

                return response()->json(['message' => 'Fournisseur modifié avec succès', 'fournisseur' => $fournisseur], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de modifier ce fournisseur.');
        }
    }

    public function destroy($id)
    {
        if (Gate::allows('delete_fournisseurs')) {
            try {
                $fournisseur = Fournisseur::findOrFail($id);
                $fournisseur->delete();

                return response()->json(['message' => 'Fournisseur supprimé avec succès'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de supprimer ce fournisseur.');
        }
    }
}
