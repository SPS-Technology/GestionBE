<?php

namespace App\Http\Controllers;

use App\Models\Bon_Entre;
use App\Models\Bon_Sourtie;
use App\Models\ligne_Bon_Entre;
use App\Models\Produit;
use App\Models\Stock_magasin;
use App\Models\Stock_Production;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Bon_EntreController extends Controller
{
    public function index()
    {
        try {
            $livraison = Bon_Entre::with('ligneBonEntre')->get();
            $count = Bon_Entre::count();
            return response()->json(['message' => 'Liste des Bon Livraison récupérée avec succès', 'bonEntre' =>  $livraison], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getAllDataBonEntre()
    {
        try {
            // Récupérer les données de Bon_Entre avec les relations nécessaires
            $bonEntre = Bon_Entre::with('ligneBonEntre')->get();
    
            // Récupérer les produits avec les relations nécessaires
            $produits = Produit::with('categorie', 'calibre', 'user')->get();
    
            // Récupérer les stocks du magasin avec les relations nécessaires
            $stockMagasin = Stock_magasin::with('produit')->get();
    
            // Récupérer les stocks de production avec les relations nécessaires
            $stockProduction = Stock_Production::with('produit')->get();
    
            // Récupérer les données de Bon_Sortie avec les relations nécessaires
            $bonSortie = Bon_Sourtie::with('ligneBonSortie')->get();
    
            // Retourner toutes les données dans une seule réponse JSON
            return response()->json([
                'message' => 'Données récupérées avec succès',
                'bonEntre' => $bonEntre,
                'produits' => $produits,
                'stockMagasin' => $stockMagasin,
                'stockProduction' => $stockProduction,
                'bonSortie' => $bonSortie,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    /**
     * Store a newly created resource in storage.
     */


    public function store(Request $request)
    {
        try {
            $validator = validator::make($request->all(), [
                'reference' => 'required|string',
                'source' => 'required|string',
                'date' => 'required|date',
                'emetteur' => 'required|string',
                'recepteur' => 'required|string',
                'type'=>'required'
            ]);
            

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $Bon_Entre = Bon_Entre::create($request->all());
            return response()->json(['message' => 'Bon_Entre ajoutée avec succès', 'devis' => $Bon_Entre], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $livraison = Bon_Entre::with('client','ligneLivraisons')->findOrFail($id);
        return response()->json(['livraison' => $livraison]);
    }

    public function lignelivraison($livraisonId)
    {
        try {
            // Récupérer la facture spécifiée
            $livraison = Bon_Entre::findOrFail($livraisonId);

            // Récupérer les lignes de facture associées à la facture spécifiée
            $ligneLivraisons = $livraison->ligneLivraisons;

            return response()->json(['message' => 'Lignes de facture récupérées avec succès', 'lignelivraison' => $ligneLivraisons], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur s\'est produite lors de la récupération des lignes de livraison'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bon_Entre $Bon_Entre)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // $this->authorize('modify', Devis::class);
            $devis = Bon_Entre::findOrFail($id);
    
            $validator = Validator::make($request->all(), [
                'reference' => 'required|string',
                'source' => 'required|string',
                'date' => 'required|date',
                'emetteur' => 'required|string',
                'recepteur' => 'required|string',
                'type'=>'required'

            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors(),], 400);
            }
    
            $devis->update($request->all());
            
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette devis.'], 403);
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
            // $this->authorize('delete', Bon_Entre::class);
            $livraison = Bon_Entre::findOrFail($id);
    
            // Find and delete associated ligne_livraisons
            $ligneLivraisons = ligne_Bon_Entre::where('id_Bon_Entre', $id)->get();
            foreach ($ligneLivraisons as $ligneLivraison) {
                $ligneLivraison->delete();
            }
    
            // Delete the Bon_Entre
            $livraison->delete();
    
            return response()->json(['message' => 'Livraison et ses lignes de livraison supprimées avec succès'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de supprimer cette livraison.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Impossible de supprimer cette livraison car elle a des lignes de livraison associées.'], 400);
        }
    }
    

}
