<?php

namespace App\Http\Controllers;

use App\Models\Livreur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class LivreurController extends Controller
{
    public function index()
    {
        if (Gate::allows('view_all_livreurs')) {
            try {
                $livreurs = Livreur::with('permis')->get();
                $count = Livreur::count();
                return response()->json([
                    'livreurs' => $livreurs,
                    'count' => $count
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des livreurs.');
        }
    }


    public function store(Request $request)
    {
        if (Gate::allows('create_livreurs')) {
            try {
                $messages = [
                    'nom.required' => 'Le champ nom est requis.',
                    'prenom.required' => 'Le champ prenom est requis.',
                    'cin.required' => 'Le champ cin est requis.',
                    'cin.unique' => 'Le champ cin doit etre unique.',
                    'tele.required' => 'Le champ tele est requis.',
                    'adresse.required' => 'Le champ adresse est requis.',

                ];
                $validator = Validator::make($request->all(), [
                    'nom' => 'required',
                    'prenom' => 'required',
                    'cin' => 'required|unique:livreurs',
                    'tele' => 'required',
                    'adresse' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }

                $livreur = Livreur::create($request->all());
                return response()->json(['message' => 'Livreur ajouté avec succès', 'livreur' => $livreur], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des fournisseurs.');
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
        if (Gate::allows('update_livreurs')) {
            try {
                $validator = Validator::make($request->all(), [
                    'nom' => 'required',
                    'prenom' => 'required',
                    'cin' => 'required|unique:livreurs,cin,' . $id,
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
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des fournisseurs.');
        }
    }

    public function destroy($id)
    {
        if (Gate::allows('delete_livreurs')) {
            try {
                $livreur = Livreur::findOrFail($id);
                $livreur->delete();

                return response()->json(['message' => 'Livreur supprimé avec succès'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de supprimer un livreur.');
        }
    }
}
