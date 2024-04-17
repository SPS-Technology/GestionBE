<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bon_Livraison;
use App\Models\Commande;
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
            $livraison = Bon_Livraison::with('client','commande')->get();
            $count = Bon_Livraison::count();
            return response()->json(['message' => 'Liste des Bon Livraison récupérée avec succès', 'livraison' =>  $livraison, 'count' => $count], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
            $messages = [
                'reference.required' => 'Le champ reference est requis.',
                'date.required' => 'Le champ date est requis.',
                'commande_id.required' => 'Le champ commande est requis.',

            ];
            $validator = Validator::make($request->all(), [
                'reference' => 'required',
                'date' => 'required',
                'commande_id' => 'required',
            ],$messages);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            // Récupérez la commande correspondante à l'ID envoyé
            $commande = Commande::findOrFail($request->commande_id);
    
            // Utilisez l'ID du client de la commande pour remplir automatiquement client_id
            $request->merge(['client_id' => $commande->client_id]);
    
            // Créez la livraison en utilisant les données de la requête
            $livraison = Bon_Livraison::create($request->all());
            $livraison->user_id = $request['user_id'] = Auth::id();

            return response()->json(['message' => 'Livraison ajoutée avec succès', 'bon livraison' => $livraison], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $livraison = Bon_Livraison::with('client')->findOrFail($id);
        return response()->json(['livraison' => $livraison]);
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
            // Récupérez la livraison à mettre à jour
            $livraison = Bon_Livraison::findOrFail($id);
    
            // Validez les données de la requête
            $validator = Validator::make($request->all(), [
                'reference' => 'required',
                'date' => 'required',
                'commande_id' => 'required',
                'user_id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            // Récupérez la commande associée à l'ID de la commande envoyée dans la requête
            $commande = Commande::findOrFail($request->commande_id);
    
            // Utilisez l'ID du client de la commande pour remplir automatiquement client_id
            $request->merge(['client_id' => $commande->client_id]);
    
            // Mettez à jour la livraison avec les données de la requête
            $livraison->update($request->all());
    
            return response()->json(['message' => 'Livraison mise à jour avec succès', 'bon livraison' => $livraison], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bon_Livraison $id)
    {
        try {
            // $this->authorize('delete', Bon_Livraison::class);
            $livraison = Bon_Livraison::findOrFail($id);
            $livraison->delete();

            return response()->json(['message' => 'livraison supprimée avec succès'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de supprimer cette livraison.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Impossible de supprimer ce livraison car il a des ligne livraison associées.'], 400);
        }
    }
}