<?php

namespace App\Http\Controllers;

use App\Models\oeufcasses;
use App\Models\oeuffini_semifini;
use App\Models\Stock_Production;
use Illuminate\Http\Request;

class CasseController extends Controller
{
    public function index()
    {
        $items = oeufcasses::all();
        $itemssemi = oeuffini_semifini::all();
        $stockProduction = Stock_Production::with('produit')->get();

        return response()->json([ 'data' => $items,
    'datasemi' => $itemssemi,
    'stockProduction' => $stockProduction
]);
    }

    // Create a new record
    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'N_lot' => 'required|string',
            'nbr_oeuf_cass' => 'required|string',
            'Poid_moy_oeuf' => 'required|string',
        ]);

        $item = oeufcasses::create($data);
        return response()->json(['message' => 'Record created successfully', 'data' => $item], 201);
    }

    // Get a single record
    public function show($id)
    {
        $item = oeufcasses::findOrFail($id);
        return response()->json(['message' => 'Record retrieved successfully', 'data' => $item]);
    }

    // Update a record
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'N_lot' => 'required|string',
            'nbr_oeuf_cass' => 'required|string',
            'Poid_moy_oeuf' => 'required|string',
        ]);

        $item = oeufcasses::findOrFail($id);
        $item->update($data);

        return response()->json(['message' => 'Record updated successfully', 'data' => $item]);
    }

    // Delete a record
    public function destroy($id)
    {
        $item = oeufcasses::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Record deleted successfully']);
    }
}
