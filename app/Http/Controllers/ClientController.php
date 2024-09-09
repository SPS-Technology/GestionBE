<?php

namespace App\Http\Controllers;

use App\Models\Bon_Livraison;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Livreur;
use App\Models\Objectif;
use App\Models\Produit;
use App\Models\SiteClient;
use App\Models\Vehicule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Gate::allows('view_all_clients')) {
            try {
                $client = Client::with('user', 'zone', 'siteclients.zone', 'siteclients.region', 'region')->get();
                $count = Client::count();
                return response()->json([
                    'message' => 'Liste des client récupérée avec succès', 'client' =>  $client,
                    'count' => $count
                ], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des Clients.');
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function getAllDataDachborde()
{
    try {
        // Fetch data from multiple models
        $clients = Client::count();
        $produits = Produit::count();
        $fournisseurs = Fournisseur::count();
        $commandes = Commande::count();
        $livreurs = Livreur::count();
        $vehicules = Vehicule::count();
        $objectifs = Objectif::count();

        // Return the consolidated data as a JSON response
        return response()->json([
            'clients' => $clients,
            'produits' => $produits,
            'fournisseurs' => $fournisseurs,
            'commandes' => $commandes,
            'livreurs' => $livreurs,
            'vehicules' => $vehicules,
            'objectifs' => $objectifs,
        ], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    public function siteclients($clientId)
    {
        try {
            $siteClients = SiteClient::where('client_id', $clientId)
                ->with('zone', 'region')
                ->get();

            return response()->json(['message' => 'Site clients récupérés avec succès', 'siteClients' => $siteClients], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur s\'est produite lors de la récupération des site clients'], 500);
        }
    }
    public function bonsLivraisonClient($clientId)
    {
        try {
            $bonsLivraison = Bon_Livraison::with('client', 'commande')->where('client_id', $clientId)->get();

            return response()->json(['message' => 'Bons de livraison récupérés avec succès', 'bonsLivraison' => $bonsLivraison], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur s\'est produite lors de la récupération des bons de livraison'], 500);
        }
    }



    public function store(Request $request)
    {
        if (Gate::allows('create_clients')) {
            try {
                $validator = Validator::make($request->all(), [
                    'CodeClient' => 'required|unique:clients,CodeClient',
                    'raison_sociale' => 'required',
                    'adresse' => 'required',
                    'tele' => 'required',
                    'ville' => 'required',
                    'abreviation' => 'required',
                    'type_client' => 'required',
                    'categorie' => 'required',
                    'ice' => 'required',
                    'code_postal' => 'required',
                    'zone_id' => 'required',
                    'region_id' => 'required',
                    'logoC' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }

                $client = new Client();
                $client->CodeClient = $request->input('CodeClient');
                $client->raison_sociale = $request->input('raison_sociale');
                $client->adresse = $request->input('adresse');
                $client->tele = $request->input('tele');
                $client->ville = $request->input('ville');
                $client->abreviation = $request->input('abreviation');
                $client->type_client = $request->input('type_client');
                $client->categorie = $request->input('categorie');
                $client->ice = $request->input('ice');
                $client->code_postal = $request->input('code_postal');
                $client->zone_id = $request->input('zone_id');
                $client->region_id = $request->input('region_id');
                $client->user_id = $request['user_id'] = Auth::id();

                if ($request->hasFile('logoC')) {
                    $photoPath = $request->file('logoC')->store('public/logoc');
                    $client->logoC = Storage::url($photoPath);
                }

                $client->save();

                return response()->json(['message' => 'Client ajouté avec succès', 'client' => $client], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'You are not authorized to add clients.');
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::with('user', 'zone', 'siteclients')->findOrFail($id);
        $client['logo_url'] = asset('storage/' . $client->logoC); 
        return response()->json(['client' => $client]);
    }


    public function update(Request $request, $id)
    {
        if (Gate::allows('update_clients')) {
            try {
                $client = Client::findOrFail($id);
                $validator = Validator::make($request->all(), [
                    'CodeClient' => 'string|unique:clients,CodeClient,' . $id,
                    'raison_sociale' => 'string',
                    'adresse' => 'string',
                    'tele' => 'string',
                    'ville' => 'string',
                    'abreviation' => 'string',
                    'type_client' => 'string',
                    'categorie' => 'string',
                    'ice' => 'numeric|min:-9223372036854775808|max:9223372036854775807',
                    'code_postal' => 'numeric|min:-9223372036854775808|max:9223372036854775807',
                    'zone_id' => 'integer',
                    'region_id' => 'integer',
                    'logoC' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error' => $validator->errors()], 400);
                }

                if ($request->hasFile('logoC')) {
                    $logoName = $request->file('logoC')->store('logos');
                    $request['logoC'] = $logoName;
                }

                $client->update($request->all());
                return response()->json(['message' => 'Client modified successfully', 'client' => $client], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            abort(403, 'You are not authorized to modify clients.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (Gate::allows('delete_clients')) {
            try {
                $client = Client::findOrFail($id);
                $client->delete();
    
                return response()->json(['message' => 'Client supprimé avec succès'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            } catch (\Illuminate\Database\QueryException $e) {
                // Vérifier si l'erreur est liée à une contrainte d'intégrité
                if ($e->errorInfo[1] === 1451) {
                    // Renvoyer le message d'erreur spécifique
                    return response()->json(['error' => 'Impossible de supprimer ce client car il est associées a d\'autres platformes.'], 400);
                } else {
                    // Renvoyer l'erreur par défaut
                    return response()->json(['error' => $e->getMessage()], 500);
                }
            }
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de supprimer un client.');
        }
    }
    
}
