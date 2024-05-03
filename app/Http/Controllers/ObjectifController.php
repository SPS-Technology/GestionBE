<?php

namespace App\Http\Controllers;

use App\Models\Objectif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;

class ObjectifController extends Controller
{
    public function index()
    {
        if (Gate::allows('view_all_objectifs')) {

        try {
            $objectifs = Objectif::all();
            $count = Objectif::count();
            return response()->json(['objectifs' => $objectifs,
            'count'=>$count
        ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }  } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des objectifs.');
        }

    }

    public function store(Request $request)
    {
        if (Gate::allows('create_objectifs')) {

        try {
            $messages = [
                'type_objectif.required' => 'Le champ type_objectif est requis.',
                'unite.required' => 'Le champ unite est requis.',
                'cin.required' => 'Le champ cin est requis.',
                'valeur.required' => 'Le champ valeur est requis.',
                'periode.required' => 'Le champ periode est requis.',

            ];
            $validator = Validator::make($request->all(), [
                'type_objectif' => 'required',
                'unite' => 'required',
                'valeur' => 'required',
                'periode' => 'required',
            ],$messages);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $objectif = Objectif::create($request->all());
            return response()->json(['message' => 'Objectif ajouté avec succès', 'objectif' => $objectif], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } else {
        abort(403, 'Vous n\'avez pas l\'autorisation de ajouter un objectifs.');
    }
    }

    public function show($id)
    {
        try {
            $objectif = Objectif::findOrFail($id);
            return response()->json(['objectif' => $objectif], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function update(Request $request, $id)
    {
        if (Gate::allows('update_objectifs')) {

        try {
            $validator = Validator::make($request->all(), [
                'type_objectif' => 'required',
                'unite' => 'required',
                'valeur' => 'required',
                'periode' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $objectif = Objectif::findOrFail($id);
            $objectif->update($request->all());

            return response()->json(['message' => 'Objectif modifié avec succès', 'objectif' => $objectif], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } else {
        abort(403, 'Vous n\'avez pas l\'autorisation de modifier la liste des objectifs.');
    }
    }

    public function destroy($id)
    {
        if (Gate::allows('delete_objectifs')) {
        try {
            $objectif = Objectif::findOrFail($id);
            $objectif->delete();

            return response()->json(['message' => 'Objectif supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    } else {
        abort(403, 'Vous n\'avez pas l\'autorisation de supprimer un objectif .');
    }
    }
}