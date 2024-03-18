<?php

namespace App\Http\Controllers;

use App\Models\Banque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BanqueController extends Controller
{
    public function index()
    {
        try {
            $banques = Banque::all();
            $count = Banque::count();

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
                'Montant' => 'nullable',
                'Status' => 'nullable',
                'remarque' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $requestData = $request->all();
            $requestData['Status'] = 'En Cours';
            $banque = Banque::create($requestData);
           // $banque = Banque::create($request->all());

            return response()->json(['message' => 'Banque ajoutée avec succès', 'banque' => $banque], 200);
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
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $banque = Banque::findOrFail($id);
            $banque->update($request->all());

            return response()->json(['message' => 'Banque modifiée avec succès', 'banque' => $banque], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $banque = Banque::findOrFail($id);
            $banque->delete();

            return response()->json(['message' => 'Banque supprimée avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
