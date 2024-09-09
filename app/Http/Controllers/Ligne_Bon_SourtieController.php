<?php

namespace App\Http\Controllers;

use App\Models\Ligne_Bon_Sourtie;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Ligne_Bon_SourtieController extends Controller
{
    
    public function index()
    {
        $Bon_Sortie =Ligne_Bon_Sourtie::all();
        return response()->json(['Bon_enBon sortie' => $Bon_Sortie]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'produit_id' => 'required|exists:produits,id',
                'quantite' => 'required|string',
                'N_lot' => 'required|string',
                'id_bon_Sourtie' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $Bon_enBon_Sourtie =Ligne_Bon_Sourtie::create($request->all());
            return response()->json(['message' => 'Ligne de livraison ajoutée avec succès', 'Bon_enBon_Sourtie' => $Bon_enBon_Sourtie], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $Bon_enBon_Sourtie =Ligne_Bon_Sourtie::where('id_bon_Sourtie',$id)->get();
        return response()->json(['Bon_enBon_Sourtie' => $Bon_enBon_Sourtie]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $Bon_enBon_Sourtie =Ligne_Bon_Sourtie::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'produit_id' => 'required|exists:produits,id',
                'quantite' => 'required|string',
                'N_lot' => 'required|string',
                'id_bon_Sourtie' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $Bon_enBon_Sourtie->update($request->all());
            return response()->json(['message' => 'Ligne de livraison modifiée avec succès', 'Bon_enBon_Sourtie' => $Bon_enBon_Sourtie], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $Bon_enBon_Sourtie =Ligne_Bon_Sourtie::findOrFail($id);
            $Bon_enBon_Sourtie->delete();

            return response()->json(['message' => 'La ligne de livraison a été supprimée avec succès.'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Impossible de supprimer cette ligne de livraison car elle est associée à des livraisons.'], 400);
        }
    }
}
