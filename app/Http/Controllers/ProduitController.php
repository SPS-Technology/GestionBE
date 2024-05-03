<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProduitController extends Controller
{
    public function index()
    {
        // Vérifier si l'utilisateur a la permission de voir la liste des produits
        if (Gate::allows('view_all_products')) {
            try {
                $produits = Produit::with('categorie', 'calibre', 'user')->get();
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
                    'unite' => 'required',
                    'seuil_alerte' => 'required',
                    'stock_initial' => 'required',
                    'etat_produit' => 'required',
                    'marque' => 'required',
                    'logoP' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'categorie_id' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }

                // $request['user_id'] = Auth::id();
                // $produit = Produit::create($request->all());
                $produit = new Produit();
                $produit->Code_produit = $request->input('Code_produit');
                $produit->designation = $request->input('designation');
                $produit->calibre_id = $request->input('calibre_id');
                $produit->type_quantite = $request->input('type_quantite');
                $produit->unite = $request->input('unite');
                $produit->seuil_alerte = $request->input('seuil_alerte');
                $produit->stock_initial = $request->input('stock_initial');
                $produit->etat_produit = $request->input('etat_produit');
                $produit->marque = $request->input('marque');
                $produit->categorie_id = $request->input('categorie_id');
                $produit->user_id = Auth::id(); // Set the user_id attribute

                if ($request->hasFile('logoP')) {
                    $photoPath = $request->file('logoP')->store('public/logop');
                    $produit->logoP = Storage::url($photoPath);
                }

                $produit->save();
                return response()->json(['message' => 'Produit ajouté avec succès', 'produit' => $produit], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de créer un produit.');
        }
    }
    // public function store(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'CodeClient' => 'required|unique:clients,CodeClient',
    //             'raison_sociale' => 'required',
    //             'adresse' => 'required',
    //             'tele' => 'required',
    //             'ville' => 'required',
    //             'abreviation' => 'required',
    //             'type_client' => 'required',
    //             'categorie' => 'required',
    //             'ice' => 'required',
    //             'code_postal' => 'required',
    //             'zone_id' => 'required',
    //             'region_id' => 'required',
    //             'logoC' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);

    //         if ($validator->fails()) {
    //             return response()->json(['error' => $validator->errors()], 400);
    //         }

    //         $client = new Client();
    //         $client->CodeClient = $request->input('CodeClient');
    //         $client->raison_sociale = $request->input('raison_sociale');
    //         $client->adresse = $request->input('adresse');
    //         $client->tele = $request->input('tele');
    //         $client->ville = $request->input('ville');
    //         $client->abreviation = $request->input('abreviation');
    //         $client->type_client = $request->input('type_client');
    //         $client->categorie = $request->input('categorie');
    //         $client->ice = $request->input('ice');
    //         $client->code_postal = $request->input('code_postal');
    //         $client->zone_id = $request->input('zone_id');
    //         $client->region_id = $request->input('region_id');
    //         $client->user_id = $request['user_id'] = Auth::id();

    //         if ($request->hasFile('logoC')) {
    //             $photoPath = $request->file('logoC')->store('public/logoc');
    //             $client->logoC = Storage::url($photoPath);
    //         }

    //         $client->save();

    //         return response()->json(['message' => 'Client ajouté avec succès', 'client' => $client], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }
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
                    'Code_produit' => 'required|unique:produits,Code_produit,' . $id,
                    'designation' => 'required',
                    'calibre_id' => 'required',
                    'type_quantite' => 'required',
                    'unite' => 'required',
                    'seuil_alerte' => 'required',
                    'stock_initial' => 'required',
                    'etat_produit' => 'required',
                    'categorie_id' => 'required',
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
