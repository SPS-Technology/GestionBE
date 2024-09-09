<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use App\Models\bon_Livraison;
use App\Models\Client;
use App\Models\Commande;
use App\Models\LigneLivraison;
use App\Models\Lignelivraison as ModelsLignelivraison;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;

class BonLivraisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $livraison = bon_livraison::with('client','lignelivraison')->get();
            $count = bon_livraison::count();
            return response()->json(['message' => 'Liste des Bon Livraison récupérée avec succès', 'livraison' =>  $livraison, 'count' => $count], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getAllDataBonLIvraisan()
    {
        try {
            // Récupérer les clients avec les relations nécessaires
            $clients = Client::with('user', 'zone', 'siteclients.zone', 'siteclients.region', 'region')->get();
    
            // Récupérer les livraisons
            $livraisons =Bon_livraison::with('client','lignelivraison')->get();
    
            // Récupérer les produits avec les relations nécessaires
            $produits = Produit::with('categorie', 'calibre', 'user')->get();
    
            // Retourner toutes les données dans une seule réponse JSON
            return response()->json([
                'message' => 'Données récupérées avec succès',
                'clients' => $clients,
                'livraisons' => $livraisons,
                'produits' => $produits,
            ], 200);
    
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'reference' => 'nullable|string',
                'date' => 'nullable|date',
                'validation_offer' => 'nullable|string',
                'modePaiement' => 'nullable|string',
                'status' => 'nullable|string',
                'client_id' => 'nullable|integer',
                'user_id' => 'nullable|integer',
                'type' => 'nullable|string'
            ]);
            

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $Bon_Livraison = bon_livraison::create($request->all());
            return response()->json(['message' => 'Bon_Livraison ajoutée avec succès', 'devis' => $Bon_Livraison], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $livraison = bon_livraison::with('client','ligneLivraisons')->findOrFail($id);
        return response()->json(['livraison' => $livraison]);
    }

    public function lignelivraison($livraisonId)
    {
        try {
            // Récupérer la facture spécifiée
            $livraison = bon_livraison::findOrFail($livraisonId);

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
    public function edit(Bon_Livraison $bon_Livraison)
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
            $devis = bon_livraison::findOrFail($id);
    
            $validator = Validator::make($request->all(), [
                'reference' => 'required',
                'date' => 'required',
                'validation_offer' => 'required',
                'modePaiement' => 'required',
                'status' => 'required',
                'client_id' => 'required',
                'user_id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
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
            // $this->authorize('delete', bon_livraison::class);
            $livraison = bon_livraison::findOrFail($id);
    
            // Find and delete associated ligne_livraisons
            $ligneLivraisons = LigneLivraison::where('id_bon_Livraison', $id)->get();
            foreach ($ligneLivraisons as $ligneLivraison) {
                $ligneLivraison->delete();
            }
    
            // Delete the bon_livraison
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
