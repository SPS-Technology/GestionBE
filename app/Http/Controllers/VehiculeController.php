<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class VehiculeController extends Controller
{
    public function index()
    {
        if (Gate::allows('view_all_vehicules')) {
            try {
                $vehicules = Vehicule::all();
                $count = Vehicule::count();
                return response()->json(['vehicules' => $vehicules, 'count' => $count], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des vehicules.');
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('create_vehicules')) {

            try {
                $messages = [
                    'marque.required' => 'Le champ marque est requis.',
                    'matricule.required' => 'Le champ matricule est requis.',
                    'model.required' => 'Le champ model est requis.',
                    'matricule.unique' => 'cette matricule est déja enregistrer.',
                    'capacite.required' => 'Le champ capacite est requis.',


                ];
                $validator = Validator::make($request->all(), [
                    'marque' => 'required',
                    'matricule' => 'required|unique:vehicules',
                    'model' => 'required',
                    'capacite' => 'required',
                ], $messages);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }

                $vehicule = Vehicule::create($request->all());
                return response()->json(['message' => 'Vehicule ajouté avec succès', 'vehicule' => $vehicule], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation d\'ajouter Vehicule');
        }
    }

    public function show($id)
    {
        try {
            $vehicule = Vehicule::findOrFail($id);
            return response()->json(['vehicule' => $vehicule], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::allows('update_vehicules')) {

            try {
                $validator = Validator::make($request->all(), [
                    'marque' => 'required',
                    'matricule' => 'required|unique:vehicules,matricule,' . $id,
                    'model' => 'required',
                    'capacite' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }

                $vehicule = Vehicule::findOrFail($id);
                $vehicule->update($request->all());

                return response()->json(['message' => 'Vehicule modifié avec succès', 'vehicule' => $vehicule], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de modifer un vehicule.');
        }
    }


    public function destroy($id)
    {
        if (Gate::allows('delete_vehicules')) {
            try {
                $vehicule = Vehicule::findOrFail($id);
                $vehicule->delete();
    
                return response()->json(['message' => 'Vehicule supprimé avec succès'], 200);
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[1] === 1451) {
                    return response()->json(['error' => 'Impossible de supprimer le véhicule car il est associé à des autres platforme.'], 400);
                } else {
                    // Renvoyer l'erreur par défaut
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de supprimer un véhicule.');
        }
    }
    
}
