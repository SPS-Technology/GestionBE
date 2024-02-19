<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // $this->authorize('view', Produit::class);
            $produit = Produit::with('fournisseur')->get();
            return response()->json(['message' => 'Liste des produit récupérée avec succès', 'produit' =>  $produit], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de voir la liste des produit.'], 403);
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
            // $this->authorize('add', Produit::class);
            // Validation 
            $validator = Validator::make($request->all(), [
                'nom' => 'required',
                'type_quantite' => 'required',
                'calibre' => 'required',
                'fournisseur_id' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $produit = Produit::create($request->all());
            return response()->json(['message' => 'produit ajoutée avec succès', 'produit' => $produit], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette produit.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produit = Produit::findOrFail($id);
        return response()->json(['produit' => $produit]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produit $produit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // $this->authorize('modify', Produit::class);
            $produit = Produit::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nom' => 'required',
                'type_quantite' => 'required',
                'calibre' => 'required',
                'fournisseur_id' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $produit->update($request->all());
            return response()->json(['message' => 'produit modifié avec succès', 'produit' => $produit], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette produit.'], 403);
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
            // $this->authorize('delete', Produit::class);
            $produit = Produit::findOrFail($id);
            $produit->delete();

            return response()->json(['message' => 'produit supprimée avec succès'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de supprimer cette produit.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            // Si une exception est déclenchée,
            return response()->json(['error' => 'Impossible de supprimer ce produit car il a des fournisseurs associées.'], 400);
        }
    }
}