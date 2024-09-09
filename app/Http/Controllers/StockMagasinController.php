<?php

namespace App\Http\Controllers;

use App\Models\Stock_magasin;
use Illuminate\Http\Request;

class StockMagasinController extends Controller
{
    public function index()
    {
        $stockMagasin = Stock_magasin::with('produit')->get();
        return response()->json($stockMagasin);
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

        $stockMagasin = Stock_magasin::create($validatedData);
        return response()->json($stockMagasin, 201);
    }

    public function show($id)
    {
        $stockMagasin = Stock_magasin::findOrFail($id);
        return response()->json($stockMagasin);
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

        $stockMagasin = Stock_magasin::findOrFail($id);
        $stockMagasin->update($validatedData);

        return response()->json($stockMagasin);
    }

    public function destroy($id)
    {
        $stockMagasin = Stock_magasin::findOrFail($id);
        $stockMagasin->delete();

        return response()->json(null, 204);
    }
}
