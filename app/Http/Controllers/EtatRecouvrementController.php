<?php

namespace App\Http\Controllers;

use App\Models\EtatRecouvrement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EtatRecouvrementController extends Controller
{
    public function index()
    {
        try {
            $etatRecouvrements = EtatRecouvrement::all();
            $count = EtatRecouvrement::count();

            return response()->json([
                'message' => 'Liste des états de recouvrement récupérée avec succès',
                'etat_recouvrements' => $etatRecouvrements,
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
                'client_id' => 'required|exists:clients,id',
                'id_facture' => 'required|exists:factures,id',
//                'avance' => 'required|numeric',
                'reste' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $etatRecouvrement = EtatRecouvrement::create($request->all());

            return response()->json(['message' => 'État de recouvrement ajouté avec succès', 'etat_recouvrement' => $etatRecouvrement], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:clients,id',
                'id_facture' => 'required|exists:factures,id',
//                'avance' => 'required|numeric',
                'reste' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $etatRecouvrement = EtatRecouvrement::findOrFail($id);
            $etatRecouvrement->update($request->all());

            return response()->json(['message' => 'État de recouvrement modifié avec succès', 'etat_recouvrement' => $etatRecouvrement], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $etatRecouvrement = EtatRecouvrement::findOrFail($id);
            $etatRecouvrement->delete();

            return response()->json(['message' => 'État de recouvrement supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
