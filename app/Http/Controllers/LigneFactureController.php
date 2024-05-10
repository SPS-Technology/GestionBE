<?php

namespace App\Http\Controllers;

use App\Models\LigneFacture;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class LigneFactureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ligneFacture = LigneFacture::all();
        return response()->json(['ligneFacture' => $ligneFacture]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'produit_id' => 'required',
                'prix_vente' => 'required',
                'quantite' => 'required',
                'id_facture' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $ligneFacture = LigneFacture::create($request->all());
            return response()->json(['message' => 'Ligne de facture ajoutée avec succès', 'ligneFacture' => $ligneFacture], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ligneFacture = LigneFacture::findOrFail($id);
        return response()->json(['ligneFacture' => $ligneFacture]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LigneFacture $ligneFacture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LigneFacture $id)
    {
        $ligneFacture = LigneFacture::findOrFail($id);
        $validator = Validator::make(
            $request->all(),
            [
                'produit_id' => 'required',

                'prix_vente' => 'required',
                'quantite' => 'required',
                'id_facture' =>'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            // Update the existing record
            $ligneFacture->update($request->all());
            return response()->json(['message' => 'ligneFacture modifié avec succès', 'ligneFacture' => $ligneFacture], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $ligneFacture = LigneFacture::findOrFail($id);
            $ligneFacture->delete();

            return response()->json(['message' => 'Le ligneFacture a été supprimé avec succès.'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Impossible de supprimer ce ligneFacture car il a des facture associées.'], 400);
        }
    }
}
