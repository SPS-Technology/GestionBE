<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class ProduitController extends Controller
{
    public function index()
    {
        // Vérifier si l'utilisateur a la permission de voir la liste des produits
        if (Gate::allows('view_all_products')) {
        try {
            $produits = Produit::with('categorie','calibre','user')->get();
            $count = Produit::count();

            return response()->json([
                'message' => 'Liste des produits récupérée avec succès', 'produit' => $produits,
                'count' => $count
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des produits.');
        }
    }

    public function store(Request $request)
    {
        if (Gate::allows('create_product')) {
        try {
            $validator = Validator::make($request->all(), [
                'Code_produit' => 'required|unique:produits,Code_produit',
                'designation' => 'required',
                'calibre_id' => 'required',
                'type_quantite' => 'required',
                'categorie_id' => 'nullable',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $request['user_id'] = Auth::id();
            $produit = Produit::create($request->all());
            return response()->json(['message' => 'Produit ajouté avec succès', 'produit' => $produit], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de créer un produit.');
        }
    }

    public function show($id)
    {
        // Vérifier si l'utilisateur a la permission de voir un produit spécifique
        // if (Gate::allows('view_product')) {
            try {
                $produit = Produit::with('calibre', 'categorie')->findOrFail($id);
    
                return response()->json(['produit' => $produit]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        // } else {
        //     abort(403, 'Vous n\'avez pas l\'autorisation de voir ce produit.');
        // }
    }
    

    public function update(Request $request, $id)
    {
        if (Gate::allows('edit_product')) {
            try {
                // Validation des données du formulaire
                $validator = Validator::make($request->all(), [
                    'Code_produit' => 'required|unique:produits,Code_produit,'.$id,
                    'designation' => 'required',
                    'calibre_id' => 'required',
                    'type_quantite' => 'required',
                    'categorie_id' => 'nullable',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }
                $request['user_id'] = Auth::id(); // Ajoutez ceci


                $produit = Produit::findOrFail($id);
                $produit->update($request->all());

                return response()->json(['message' => 'Produit modifié avec succès', 'produit' => $produit], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de modifier ce produit.');
        }
    }

    public function destroy($id)
    {
        // Vérifier si l'utilisateur a la permission de supprimer un produit
        if (Gate::allows('delete_product')) {
            try {
                $produit = Produit::findOrFail($id);
                $produit->categorie_id = null;
                $produit->save();
                $produit->delete();

                return response()->json(['message' => 'Produit supprimé avec succès'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de supprimer ce produit.');
        }
    }
}
