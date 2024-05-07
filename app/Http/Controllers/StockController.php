<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    public function index()
{
    try {
        $stocks = Stock::with('produit.categorie')->get();
        return response()->json(['stocks' => $stocks], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'produit_id' => 'required',
                'quantite' => 'required',
                'seuil_minimal' => 'required',
                
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $stock = Stock::create($request->all());
            return response()->json(['message' => 'Stock ajouté avec succès', 'stock' => $stock], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            // Récupérer les données de stock en fonction de l'ID du produit
            $stock = Stock::where('produit_id', $id)->first();
    
            // Vérifier si des données de stock ont été trouvées
            if ($stock) {
                return response()->json(['stock' => $stock], 200);
            } else {
                return response()->json(['message' => 'Aucune donnée de stock trouvée pour cet ID de produit.'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'produit_id' => 'required',
                'quantite' => 'required',
                'seuil_minimal' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $stock = Stock::findOrFail($id);
            $stock->update($request->all());

            return response()->json(['message' => 'Stock modifié avec succès', 'stock' => $stock], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $stock = Stock::findOrFail($id);
            $stock->delete();

            return response()->json(['message' => 'Stock supprimé avec succès'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}