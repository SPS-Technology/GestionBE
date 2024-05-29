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
            $livraison = Bon_Livraison::with('client','commande.ligneCommandes')->get();
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
//    public function store(Request $request)
//    {
//        try {
//            $messages = [
//                'reference.required' => 'Le champ reference est requis.',
//                'date.required' => 'Le champ date est requis.',
//            ];
//
//            $validator = Validator::make($request->all(), [
//                'reference' => 'required',
//                'date' => 'required',
//            ], $messages);
//
//            if ($validator->fails()) {
//                return response()->json(['error' => $validator->errors()], 400);
//            }
//
//            $reference = 'CMD' . now()->timestamp;
//
//            $requestData = [
//                'reference' => $reference,
//                'date' => $request->date,
//                'client_id' => $request->client_id,
//                'user_id' => Auth::id(),
//            ];
//
//            // Vérifiez si commande_id est présent dans la requête
//            if ($request->has('commande_id')) {
//                // Récupérez la commande correspondante à l'ID envoyé
//                $commande = Commande::findOrFail($request->commande_id);
//
//                // Utilisez l'ID du client de la commande pour remplir automatiquement client_id
//                $requestData['client_id'] = $commande->client_id;
//            }
//
//            // Créez la livraison en utilisant les données de la requête
//            $livraison = Bon_Livraison::create($requestData);
//
//            return response()->json(['message' => 'Livraison ajoutée avec succès', 'bon livraison' => $livraison], 200);
//        } catch (\Exception $e) {
//            return response()->json(['error' => $e->getMessage()], 500);
//        }
//    }

    public function store(Request $request)
    {
        try {
            $messages = [
                'reference.required' => 'Le champ reference est requis.',
                'date.required' => 'Le champ date est requis.',
            ];

            $validator = Validator::make($request->all(), [
                'reference' => 'required',
                'date' => 'required',
            ], $messages);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $reference = 'CMD' . now()->timestamp;

            $requestData = [
                'reference' => $reference,
                'date' => $request->date,
                'client_id' => $request->client_id,
                'user_id' => $request->user_id,
            ];

            // Créez la livraison en utilisant les données de la requête
            $livraison = Bon_Livraison::create($requestData);

            return response()->json(['message' => 'Livraison ajoutée avec succès', 'livraison' => $livraison], 200);
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

    public function lignelivraison($livraisonId)
    {
        try {
            // Récupérer la facture spécifiée
            $livraison = Bon_Livraison::findOrFail($livraisonId);

            // Récupérer les lignes de facture associées à la facture spécifiée
            $lignelivraison = $livraison->ligneLivraisons;

            return response()->json(['message' => 'Lignes de facture récupérées avec succès', 'lignelivraison' => $lignelivraison], 200);
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
            // Récupérez la livraison à mettre à jour
            $livraison = Bon_Livraison::findOrFail($id);

            // Validez les données de la requête
            $validator = Validator::make($request->all(), [
                'reference' => 'required',
                'date' => 'required',
                'commande_id' => 'nullable',
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
    public function destroy($id)
    {
        try {
            // $this->authorize('delete', Bon_Livraison::class);
            $livraison = Bon_Livraison::findOrFail($id);
            $livraison->delete();

            return response()->json(['message' => 'Livraison supprimée avec succès'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de supprimer cette livraison.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Impossible de supprimer cette livraison car elle a des lignes de livraison associées.'], 400);
        }
    }

}
