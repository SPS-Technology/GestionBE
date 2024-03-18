<?php

namespace App\Http\Controllers;

use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VehiculeController extends Controller
{
    public function index()
    {
        try {
            $vehicules = Vehicule::all();
            $count = Vehicule::count();
            return response()->json(['vehicules' => $vehicules, 'count'=>$count], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
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
            ],$messages);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $vehicule = Vehicule::create($request->all());
            return response()->json(['message' => 'Vehicule ajouté avec succès', 'vehicule' => $vehicule], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
    }
    

    public function destroy($id)
    {
        try {
            $vehicule = Vehicule::findOrFail($id);
            $vehicule->delete();

            return response()->json(['message' => 'Vehicule supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}