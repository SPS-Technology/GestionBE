<?php

namespace App\Http\Controllers;

use App\Models\Stock_Production;
use Illuminate\Http\Request;

class StockProductionController extends Controller
{
    public function index()
    {
        $stockProduction = Stock_Production::with('produit')->get();
        return response()->json($stockProduction);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'date' => 'required|date',
            'quantite' => 'required|integer',
            'n_lot' => 'required|string|max:255',
            'nom_fournisseur' => 'required|string|max:255',
        ]);

        $stockProduction = Stock_Production::create($validatedData);
        return response()->json($stockProduction, 201);
    }

    public function show($id)
    {
        $stockProduction = Stock_Production::findOrFail($id);
        return response()->json($stockProduction);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
           'produit_id' => 'required|exists:produits,id',
            'date' => 'required|date',
            'quantite' => 'required|integer',
            'n_lot' => 'required|string|max:255',
            'nom_fournisseur' => 'nullable|string|max:255',
        ]);

        $stockProduction = Stock_Production::findOrFail($id);
        $stockProduction->update($validatedData);

        return response()->json($stockProduction);
    }

    public function destroy($id)
    {
        $stockProduction = Stock_Production::findOrFail($id);
        $stockProduction->delete();

        return response()->json(null, 204);
    }
}
