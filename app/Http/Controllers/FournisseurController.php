<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('view', Fournisseur::class);
            $fournisseur = Fournisseur::all();
            return response()->json(['message' => 'Liste des fournisseur récupérée avec succès', 'fournisseur' =>  $fournisseur], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de voir la liste des fournisseur.'], 403);
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
            $this->authorize('add', Fournisseur::class);

            // Validation 
            $validator = Validator::make($request->all(), [
                'raison_sociale' => 'required',
                'adresse' => 'required',
                'tele' => 'required',
                'ville' => 'required',
                'abreviation' => 'required',
                'zone' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $fournisseur = Fournisseur::create($request->all());
            return response()->json(['message' => 'fournisseur ajoutée avec succès', 'fournisseur' => $fournisseur], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette fournisseur.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        return response()->json(['fournisseur' => $fournisseur]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fournisseur $fournisseur)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $this->authorize('modify', Fournisseur::class);
            $fournisseur = Fournisseur::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'raison_sociale' => 'required',
                'adresse' => 'required',
                'tele' => 'required',
                'ville' => 'required',
                'abreviation' => 'required',
                'zone' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $fournisseur->update($request->all());
            return response()->json(['message' => 'fournisseur modifié avec succès', 'fournisseur' => $fournisseur], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette fournisseur.'], 403);
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
            $this->authorize('delete', Fournisseur::class);
            $fournisseur = Fournisseur::findOrFail($id);
            $fournisseur->delete();

            return response()->json(['message' => 'Fournisseur supprimée avec succès'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de supprimer cette Fournisseur.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            // Si une exception est déclenchée, cela signifie que le client a des commandes associées
            return response()->json(['error' => 'Impossible de supprimer ce Fournisseur car il a des produits associées.'], 400);
        }
    }
}
