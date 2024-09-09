<?php

namespace App\Http\Controllers;

use App\Models\oeuffini_semifini;
use Illuminate\Http\Request;

class OeuffinisemifiniController extends Controller
{
    // Get all records
    public function index()
    {
        $items = oeuffini_semifini::all();
        return response()->json(['data' => $items]);
    }

    // Create a new record
    public function store(Request $request)
    {
        $item = oeuffini_semifini::create([
            'date' => $request->date,
            'eau_semifini' => $request->eau_semifini,
            'entier_semifini' => $request->entier_semifini,
            'janne_semifini' => $request->janne_semifini,
            'blan_semifini' => $request->blan_semifini,
            'LC_semifini' => $request->LC_semifini,
            'oeufcongles_semifini' => $request->oeufcongles_semifini,
            'entier_fini' => $request->entier_fini,
            'janne_fini' => $request->janne_fini,
            'blan_fini' => $request->blan_fini,
            'LC_fini' => $request->LC_fini,
            'oeufcongles_fini' => $request->oeufcongles_fini,
            'N_lot_eau_semifini' => $request->N_lot_eau_semifini,
            'N_lot_entier_semifini' => $request->N_lot_entier_semifini,
            'N_lot_janne_semifini' => $request->N_lot_janne_semifini,
            'N_lot_blan_semifini' => $request->N_lot_blan_semifini,
            'N_lot_LC_semifini' => $request->N_lot_LC_semifini,
            'N_lot_oeuf_congles_semifini' => $request->N_lot_oeuf_congles_semifini,
            'N_lot_entier_fini' => $request->N_lot_entier_fini,
            'N_lot_janne_fini' => $request->N_lot_janne_fini,
            'N_lot_blan_fini' => $request->N_lot_blan_fini,
            'N_lot_LC_fini' => $request->N_lot_LC_fini,
            'N_lot_oeuf_congles_fini' => $request->N_lot_oeuf_congles_fini,
        ]);

        return response()->json(['message' => 'Record created successfully', 'data' => $item], 201);
    }

    // Get a single record
    public function show($id)
    {
        $item = oeuffini_semifini::findOrFail($id);
        return response()->json(['message' => 'Record retrieved successfully', 'data' => $item]);
    }

    // Update a record
    public function update(Request $request, $id)
    {
        $item = oeuffini_semifini::findOrFail($id);
        $item->update([
            'date' => $request->date,
            'eau_semifini' => $request->eau_semifini,
            'entier_semifini' => $request->entier_semifini,
            'janne_semifini' => $request->janne_semifini,
            'blan_semifini' => $request->blan_semifini,
            'LC_semifini' => $request->LC_semifini,
            'oeufcongles_semifini' => $request->oeufcongles_semifini,
            'entier_fini' => $request->entier_fini,
            'janne_fini' => $request->janne_fini,
            'blan_fini' => $request->blan_fini,
            'LC_fini' => $request->LC_fini,
            'oeufcongles_fini' => $request->oeufcongles_fini,
            'N_lot_eau_semifini' => $request->N_lot_eau_semifini,
            'N_lot_entier_semifini' => $request->N_lot_entier_semifini,
            'N_lot_janne_semifini' => $request->N_lot_janne_semifini,
            'N_lot_blan_semifini' => $request->N_lot_blan_semifini,
            'N_lot_LC_semifini' => $request->N_lot_LC_semifini,
            'N_lot_oeuf_congles_semifini' => $request->N_lot_oeuf_congles_semifini,
            'N_lot_entier_fini' => $request->N_lot_entier_fini,
            'N_lot_janne_fini' => $request->N_lot_janne_fini,
            'N_lot_blan_fini' => $request->N_lot_blan_fini,
            'N_lot_LC_fini' => $request->N_lot_LC_fini,
            'N_lot_oeuf_congles_fini' => $request->N_lot_oeuf_congles_fini,
        ]);

        return response()->json(['message' => 'Record updated successfully', 'data' => $item]);
    }

    // Delete a record
    public function destroy($id)
    {
        $item = oeuffini_semifini::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Record deleted successfully']);
    }
}
