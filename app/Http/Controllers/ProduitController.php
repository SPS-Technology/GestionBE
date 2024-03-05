<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class ProduitController extends Controller
{
    public function index()
    {
        // Vérifier si l'utilisateur a la permission de voir la liste des produits
        // if (Gate::allows('view_all_products')) {
            try {
                $produit = Produit::all();
                $count = Produit::count();

                return response()->json([
                    'message' => 'Liste des produits récupérée avec succès', 'produit' => $produit,
                    'count' => $count
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        // } else {
        //     abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des produits.');
        // }
    }

    public function store(Request $request)
    {
        // Vérifier si l'utilisateur a la permission de créer un produit
        if (Gate::allows('create_product')) {
            try {
                // Validation des données du formulaire
                $validator = Validator::make($request->all(), [
                    'nom' => 'required',
                    'type_quantite' => 'required',
                    'calibre' => 'required',
                    'user_id' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }

                $produit = Produit::create($request->all());

                return response()->json(['message' => 'Produit ajouté avec succès', 'produit' => $produit], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de créer un produit.');
        }
    }

    // public function show($id)
    // {
    //     // Vérifier si l'utilisateur a la permission de voir un produit spécifique
    //     if (Gate::allows('view_product')) {
    //         try {
    //             $produit = Produit::findOrFail($id);

    //             return response()->json(['produit' => $produit]);
    //         } catch (\Exception $e) {
    //             return response()->json(['error' => $e->getMessage()], 500);
    //         }
    //     } else {
    //         abort(403, 'Vous n\'avez pas l\'autorisation de voir ce produit.');
    //     }
    // }

    public function update(Request $request, $id)
    {
        // Vérifier si l'utilisateur a la permission de mettre à jour un produit
        if (Gate::allows('edit_product')) {
            try {
                // Validation des données du formulaire
                $validator = Validator::make($request->all(), [
                    'nom' => 'required',
                    'type_quantite' => 'required',
                    'calibre' => 'required',
                    'user_id' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }

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
                $produit->delete();

                return response()->json(['message' => 'Produit supprimé avec succès'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            } catch (QueryException $e) {
                return response()->json(['error' => 'Impossible de supprimer ce produit car il a des fournisseurs associés.'], 400);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de supprimer ce produit.');
        }
    }
}