<?php

namespace App\Http\Controllers;

use App\Models\Lignelivraison;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class LigneLivraisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ligneLivraisons = Lignelivraison::all();
        return response()->json(['ligneLivraisons' => $ligneLivraisons]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'produit_id' => 'required|exists:produits,id',
                'quantite' => 'nullable|string',
                'id_bon_Livraison' => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $ligneLivraison = Lignelivraison::create($request->all());
            return response()->json(['message' => 'Ligne de livraison ajoutée avec succès', 'ligneLivraison' => $ligneLivraison], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ligneLivraison = Lignelivraison::where('id_bon_Livraison',$id)->get();
        return response()->json(['lignelivraison' => $ligneLivraison]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $ligneLivraison = Lignelivraison::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'Code_produit'=>'nullable',
                'quantite' => 'nullable',
                'id_bon_livraisons' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $ligneLivraison->update($request->all());
            return response()->json(['message' => 'Ligne de livraison modifiée avec succès', 'ligneLivraison' => $ligneLivraison], 200);
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
            $ligneLivraison = Lignelivraison::findOrFail($id);
            $ligneLivraison->delete();

            return response()->json(['message' => 'La ligne de livraison a été supprimée avec succès.'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Impossible de supprimer cette ligne de livraison car elle est associée à des livraisons.'], 400);
        }
    }
}
