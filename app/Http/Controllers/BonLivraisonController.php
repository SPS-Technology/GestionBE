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
            $livraison = Bon_Livraison::with('client', 'commande.ligneCommandes.produit')->get();
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
    // public function store(Request $request)
    // {
    //     try {
    //         $messages = [
    //             'reference.required' => 'Le champ reference est requis.',
    //             'date.required' => 'Le champ date est requis.',
    //             'commande_id.required' => 'Le champ commande est requis.',

    //         ];
    //         $validator = Validator::make($request->all(), [
    //             'reference' => 'required',
    //             'date' => 'required',
    //             'commande_id' => 'required',
    //         ],$messages);

    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()], 400);
    //         }

    //         // Récupérez la commande correspondante à l'ID envoyé
    //         $commande = Commande::findOrFail($request->commande_id);

    //         // Utilisez l'ID du client de la commande pour remplir automatiquement client_id
    //         $request->merge(['client_id' => $commande->client_id]);

    //         // Créez la livraison en utilisant les données de la requête
    //         $livraison = Bon_Livraison::create($request->all());
    //         $livraison->user_id = $request['user_id'] = Auth::id();

    //         return response()->json(['message' => 'Livraison ajoutée avec succès', 'bon_livraison' => $livraison], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }


    // public function store(Request $request)
    // {
    //     try {
    //         $messages = [
    //             'reference.required' => 'Le champ reference est requis.',
    //             'date.required' => 'Le champ date est requis.',
    //             'commande_id.required' => 'Le champ commande est requis.',
    //             'user_id.required' => 'Le champ commande est requis.',
    //         ];

    //         $validator = Validator::make($request->all(), [
    //             'reference' => 'required',
    //             'date' => 'required',
    //             'commande_id' => 'required',
    //             'user_id' => 'required',
    //         ], $messages);

    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()], 400);
    //         }

    //         // Récupérez la commande correspondante à l'ID envoyé
    //         $commande = Commande::with('ligneCommandes.produit')->findOrFail($request->commande_id);

    //         // Utilisez l'ID du client de la commande pour remplir automatiquement client_id
    //         $request->merge(['client_id' => $commande->client_id]);

    //         // Créez la livraison en utilisant les données de la requête
    //         $livraison = Bon_Livraison::create($request->all());
    //         // Associez la livraison à la commande
    //         $livraison->commande()->associate($commande);

    //         // Enregistrez la livraison
    //         $livraison->save();

    //         // Chargez à nouveau la livraison avec les relations
    //         $livraison = $livraison->load('client', 'commande.ligneCommandes.produit');

    //         return response()->json(['message' => 'Livraison ajoutée avec succès', 'bon_livraison' => $livraison], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }


    public function store(Request $request)
    {
        try {
            $messages = [
                'reference.required' => 'Le champ reference est requis.',
                'date.required' => 'Le champ date est requis.',
                'commande_id.required' => 'Le champ commande est requis.',
                'user_id.required' => 'Le champ user_id est requis.',
            ];
    
            $validator = Validator::make($request->all(), [
                'reference' => 'required',
                'date' => 'required',
                'commande_id' => 'required',
                'user_id' => 'required',
            ], $messages);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            // Récupérez la commande correspondante à l'ID envoyé
            $commande = Commande::with('ligneCommandes.produit')->findOrFail($request->commande_id);
    
            // Utilisez l'ID du client de la commande pour remplir automatiquement client_id
            // Si le client_id n'est pas fourni dans la commande, utilisez le fournisseur_id
            if ($commande->client_id) {
                $request->merge(['client_id' => $commande->client_id]);
            } elseif ($commande->fournisseur_id) {
                $request->merge(['fournisseur_id' => $commande->fournisseur_id]);
            } else {
                return response()->json(['error' => 'La commande ne contient ni client_id ni fournisseur_id'], 400);
            }
    
            // Créez la livraison en utilisant les données de la requête
            $livraison = Bon_Livraison::create($request->all());
            // Associez la livraison à la commande
            $livraison->commande()->associate($commande);
    
            // Enregistrez la livraison
            $livraison->save();
    
            // Chargez à nouveau la livraison avec les relations
            $livraison = $livraison->load('client', 'commande.ligneCommandes.produit');
    
            return response()->json(['message' => 'Livraison ajoutée avec succès', 'bon_livraison' => $livraison], 200);
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

            return response()->json(['message' => 'Livraison mise à jour avec succès', 'bon_livraison' => $livraison], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // public function store(Request $request)
    // {
    //     try {
    //         $messages = [
    //             'reference.required' => 'Le champ référence est requis.',
    //             'date.required' => 'Le champ date est requis.',
    //             'commande_id.required' => 'Le champ commande est requis.',
    //             'client_id.required' => 'Le champ client est requis si le fournisseur n\'est pas spécifié.',
    //             'fournisseur_id.required' => 'Le champ fournisseur est requis si le client n\'est pas spécifié.'
    //         ];

    //         $validator = Validator::make($request->all(), [
    //             'reference' => 'required',
    //             'date' => 'required',
    //             'commande_id' => 'required',
    //             'client_id' => [
    //                 Rule::requiredIf(function () use ($request) {
    //                     return !$request->has('fournisseur_id');
    //                 })
    //             ],
    //             'fournisseur_id' => [
    //                 Rule::requiredIf(function () use ($request) {
    //                     return !$request->has('client_id');
    //                 })
    //             ],
    //         ], $messages);

    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()], 400);
    //         }

    //         // Récupérez la commande correspondante à l'ID envoyé
    //         $commande = Commande::findOrFail($request->commande_id);

    //         // Vérifiez si la commande a un client ou un fournisseur et définissez client_id ou fournisseur_id en conséquence
    //         if ($commande->client_id) {
    //             $request->merge(['client_id' => $commande->client_id]);
    //         } else {
    //             $request->merge(['fournisseur_id' => $commande->fournisseur_id]);
    //         }

    //         // Créez la livraison en utilisant les données de la requête
    //         $livraison = Bon_Livraison::create($request->all());
    //         $livraison->user_id = $request['user_id'] = Auth::id();

    //         // Mise à jour du stock
    //         if ($commande->ligne_commandes) {
    //             foreach ($commande->ligne_commandes as $ligneCommande) {
    //                 $produit = $ligneCommande->produit;
    //                 $stock = Stock::where('produit_id', $produit->id)->firstOrFail();

    //                 // Copiez la valeur du seuil minimal avant la modification
    //                 $seuilMinimalAvant = $stock->seuil_minimal;

    //                 if ($commande->client_id) {
    //                     // Diminuer 1 au seuil minimal dans la table de stock
    //                     $stock->seuil_minimal -= 1;
    //                 } elseif ($commande->fournisseur_id) {
    //                     // Ajouter 1 au seuil minimal dans la table de stock
    //                     $stock->seuil_minimal += 1;
    //                 }

    //                 // Enregistrer les modifications dans la table de stock
    //                 $stock->save();

    //                 // Vérifier si le seuil minimal a été modifié
    //                 if ($stock->wasChanged('seuil_minimal')) {
    //                     // Le seuil minimal a été modifié
    //                     $message = 'Le seuil minimal du produit ' . $produit->id . ' a été modifié.';
    //                 } else {
    //                     // Le seuil minimal n'a pas été modifié
    //                     $message = 'Le seuil minimal du produit ' . $produit->id . ' n\'a pas été modifié.';
    //                 }

    //                 // Ajouter le message à la réponse JSON
    //                 $livraisonMessages[] = $message;
    //             }
    //         }

    //         return response()->json(['message' => 'Livraison ajoutée avec succès', 'bon livraison' => $livraison, 'livraison_messages' => $livraisonMessages ?? []], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

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
