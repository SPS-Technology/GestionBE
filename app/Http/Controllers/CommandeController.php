<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Carbon\carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (Gate::allows('view_all_commandes')) {

        try {
            //$this->authorize('view', Commande::class);
            $commandes = Commande::with('ligneCommandes.produit', 'statusCommandes', 'preparations.lignesPreparation', 'client', 'chargementCommandes')->get();
            $count = Commande::count();
            return response()->json(
                [
                    'message' => 'Liste des commandes récupérée avec succès',
                    'commandes' =>  $commandes,
                    'count' => $count
                ],
                200
            );
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de voir la liste des commandes.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        // } else {
        //     abort(403, 'You are not authorized to add clients.');
        // }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function getOrdersByClientId($clientId)
    {
        try {
            $commandes = Commande::where('client_id', $clientId)
                ->with('ligneCommandes.produit', 'statusCommandes', 'preparations.lignesPreparation', 'chargementCommandes')
                ->get();

            return response()->json(['commandes' => $commandes], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // if (Gate::allows('create_commandes')) {
        try {
            // Validation 
            $validator = Validator::make($request->all(), [
                'dateCommande' => 'required',
                'mode_payement' => 'nullable',

                'client_id' => [
                    Rule::requiredIf(function () use ($request) {
                        return !$request->has('fournisseur_id');
                    })
                ],
                'fournisseur_id' => [
                    Rule::requiredIf(function () use ($request) {
                        return !$request->has('client_id');
                    })
                ],
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $request['user_id'] = Auth::id();
            $reference = 'CMD' . now()->timestamp;
            $dateSaisis = Carbon::now()->format('Y-m-d H:i:s');

            $requestData = $request->all();
            $requestData['status'] = 'en_attente';
            $requestData['reference'] = $reference;
            $requstData['dateSaisis'] = $dateSaisis;

            $commande = Commande::create($requestData);

            return response()->json(['message' => 'Commande ajoutée avec succès', 'commande' => $commande], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        // } else {
        //     abort(403, 'You are not authorized to add commande.');
        // }
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $commande = Commande::with('ligneCommandes', 'statusCommandes')->findOrFail($id);
        return response()->json(['commande' => $commande]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Commande $commande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // if (Gate::allows('update_commandes')) {
        try {
            $commande = Commande::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'dateCommande' => 'nullable',
                'client_id' => 'nullable',
                'fournisseur_id' => 'nullable',
                'dateSaisis' => 'nullable',
                'status' => 'required',

            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $request['user_id'] = Auth::id();
            $commande->update($request->all());
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette commande.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        // } else {
        //     abort(403, 'Vous n\'avez pas l\'autorisation de modifier cette commande.');
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // if (Gate::allows('delete_commandes')) {
        try {
            $commande = Commande::findOrFail($id);
            $commande->delete();

            return response()->json(['message' => 'Commande supprimée avec succès'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de supprimer cette commande.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            // Si une exception est déclenchée, cela signifie que le client a des commandes associées
            return response()->json(['error' => 'Impossible de supprimer ce commande car il a des status ou lignes associées.'], 400);
        }
        // } else {
        //     abort(403, 'Vous n\'avez pas l\'autorisation de supprimer cette commande.');
        // }
    }
}
