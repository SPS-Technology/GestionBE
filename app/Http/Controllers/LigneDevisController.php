<?php

namespace App\Http\Controllers;

use App\Models\LigneDevis;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class LigneDevisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $lingeDevis = LigneDevis::all();
        return response()->json(['lingeDevis' => $lingeDevis]);
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
                'id_devis' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $lignedevis = LigneDevis::create($request->all());
            return response()->json(['message' => 'Ligne de devis ajoutée avec succès', 'lignedevis' => $lignedevis], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
//    public function show($id)
//    {
//        $lignedevis = LigneDevis::findOrFail($id);
//        return response()->json(['lignedevis' => $lignedevis]);
//
//    }
    public function show($devisId)
    {
        $ligneDevis = LigneDevis::where('id_devis', $devisId)->get();
        return response()->json(['ligneDevis' => $ligneDevis]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LigneDevis $lingeDevis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ligneDevis = LigneDevis::findOrFail($id);
        $validator = Validator::make(
            $request->all(),
            [
                'produit_id'=>'required',

                'prix_vente' => 'required',
                'quantite' => 'required',
                'id_devis' =>'required',
            ]
        );
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        } else {
            // Update the existing record
            $ligneDevis->update($request->all());
            return response()->json(['message' => 'ligneDevis modifié avec succès', 'ligneDevis' => $ligneDevis], 200);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $ligneDevis = LigneDevis::findOrFail($id);
            $ligneDevis->delete();

            return response()->json(['message' => 'Le ligneDevis a été supprimé avec succès.'], 200);
        } catch (QueryException $e) {
            // Si une exception est déclenchée, cela signifie que le ligneDevis a des commandes associées
            return response()->json(['error' => 'Impossible de supprimer ce ligneDevis car il a des devises associées.'], 400);
        }
    }
}
