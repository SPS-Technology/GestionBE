<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //$this->authorize('view', Commande::class);
            $commandes = Commande::all();
            return response()->json(['message' => 'Liste des commandes récupérée avec succès', 'commandes' =>  $commandes], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de voir la liste des commandes.'], 403);
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
           // $this->authorize('add', Commande::class);

            // Validation 
            $validator = Validator::make($request->all(), [
                'dateCommande' => 'required',
                'client_id' => 'required',
                'user_id' => 'required',
                'status' => 'required',
                'fournisseur_id' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $commande = Commande::create($request->all());
            return response()->json(['message' => 'Commande ajoutée avec succès', 'commande' => $commande], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette commande.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $commande = Commande::findOrFail($id);

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
        try {
           // $this->authorize('modify', Commande::class);
            $commande = Commande::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'dateCommande' => 'required',
                'client_id' => 'required',
                'user_id' => 'required',
                'status' => 'required',
                'fournisseur_id' => 'nullable'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $commande->update($request->all());
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette commande.'], 403);
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
           // $this->authorize('delete', Commande::class);
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
    }
}
