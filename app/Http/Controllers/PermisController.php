<?php

namespace App\Http\Controllers;

use App\Models\Permis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermisController extends Controller
{
    public function index()
    {
        $permis = permis::all();
        return response()->json(['permis'=>$permis]);
    }
    public function store(Request $request)
    {
        try {
            $messages = [
                'livreur_id.required' => 'Le champ livreur_id est requis.',
                'n_permis.required' => 'Le champ n_permis est requis.',
                'date_permis.unique' => 'Le champ date_permis doit etre unique.',
                'type_permis.required' => 'Le champ type_permis est requis.',


            ];
            $validator = Validator::make($request->all(), [
                'n_permis' => 'required',
                'date_permis' => 'required|date',
                'type_permis' => 'required',
                'livreur_id' => 'required',
            ],$messages);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $permis = Permis::create($request->all());
            return response()->json(['message' => 'permis ajouté avec succès', 'permis' => $permis], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $permis = Permis::findOrFail($id);
    
            $validator = Validator::make($request->all(), [
                'n_permis' => 'required',
                'type_permis' => 'required',
                'date_permis' => 'required|date',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $permis->update($request->all());
    
            return response()->json(['message' => 'Permis mis à jour avec succès', 'permis' => $permis], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    // public function destroy($livreurId, $permisId)
    // {
    //     try {
    //         $permis = Permis::where('livreur_id', $livreurId)->findOrFail($permisId);
    //         $permis->delete();

    //         return response()->json(['message' => 'Permis supprimé avec succès'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
    public function destroy($id)
    {
        try {
            $permis = Permis::findOrFail($id);
            $permis->delete();

            return response()->json(['message' => 'permis supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($livreurId)
    {
        $permis = Permis::where('livreur_id', $livreurId)->get();
        return response()->json(['permis' => $permis]);
    }
}
