<?php

namespace App\Http\Controllers;

use App\Models\EntrerBanque;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EntrerBanqueController extends Controller
{
    public function index()
    {
        try {
            $banques = EntrerBanque::with('ligneEntrerCompte')->get();;
            $count = EntrerBanque::count();

            return response()->json([
                'message' => 'Liste des banques récupérée avec succès',
                'banques' => $banques,
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
                'datee' => 'required|date',
                'numero_cheque' => 'required',
                'mode_de_paiement' => 'required',

                'Status' => 'nullable',
                'remarque' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $requestData = $request->all();
//            $requestData['Status'] = 'En Cours';
            $banque = EntrerBanque::create($requestData);
           // $banque = EntrerBanque::create($request->all());

            return response()->json(['message' => 'EntrerBanque ajoutée avec succès', 'banque' => $banque], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client_id' => 'required|exists:clients,id',
                'numero_cheque' => 'required|string',
                'mode_de_paiement' => 'required|string',
                'datee' => 'required|date',
                'remarque' => 'nullable|string',
                'Status' => 'nullable',


            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $banque = EntrerBanque::findOrFail($id);
            $banque->update($request->all());

            return response()->json(['message' => 'EntrerBanque modifiée avec succès', 'banque' => $banque], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $banque = EntrerBanque::findOrFail($id);
            $banque->delete();

            return response()->json(['message' => 'EntrerBanque supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
