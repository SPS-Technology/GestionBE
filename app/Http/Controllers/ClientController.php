<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;


class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // if (Gate::allows('view_all_clients')) {
        try {
            $client = Client::with('user')->get();
            $count = Client::count();
            return response()->json([
                'message' => 'Liste des client récupérée avec succès', 'client' =>  $client,
                'count' => $count
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    // }else {
    //     abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des Clients.');
    // }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Gate::allows('create_clients')) {
        try {
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

            $client = Client::create($request->all());
            return response()->json(['message' => 'client ajoutée avec succès', 'client' => $client], 200);
        }  catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }else {
        abort(403, 'Vous n\'avez pas l\'autorisation de creer  un client.');
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['client' => $client]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (Gate::allows('update_clients')) {
        try {
            $client = Client::findOrFail($id);
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

            $client->update($request->all());
            return response()->json(['message' => 'Client modifié avec succès', 'client' => $client], 200);
        }  catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }else {
        abort(403, 'Vous n\'avez pas l\'autorisation de modifier les clients.');
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

            return response()->json(['message' => 'Client supprimée avec succès'], 200);
        }  catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            // Si une exception est déclenchée, cela signifie que le client a des commandes associées
            return response()->json(['error' => 'Impossible de supprimer ce client car il a des commandes associées.'], 400);
        }
    }else {
        abort(403, 'Vous n\'avez pas l\'autorisation de supprimer un clients.');
    }
}
}