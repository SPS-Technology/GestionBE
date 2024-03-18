<?php

namespace App\Http\Controllers;

use App\Models\Objectif;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ObjectifController extends Controller
{
    public function index()
    {
        try {
            $objectifs = Objectif::all();
            $count = Objectif::count();
            return response()->json(['objectifs' => $objectifs,
            'count'=>$count
        ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
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
    }

    public function destroy($id)
    {
        try {
            $objectif = Objectif::findOrFail($id);
            $objectif->delete();

            return response()->json(['message' => 'Objectif supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}