<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\GroupeClient;
use Illuminate\Http\Request;

class OffreGroupeController extends Controller
{
    /**
     * Display a listing of the offres for a specific groupe client.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offreGroups = Offre::with('groupeClients')->get();
        return response()->json($offreGroups);
    }

    /**
     * Store a newly created relationship in the pivot table.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'offre_id' => 'required|exists:offres,id',
            'groupe_client_id' => 'required|exists:groupe_clients,Id_groupe',
        ]);

        $offre = Offre::findOrFail($validatedData['offre_id']);
        $offre->groupeClients()->attach($validatedData['groupe_client_id']);

        return response()->json(['message' => 'Offre added to Groupe Client successfully.']);
    }

    /**
     * Display the specified group of offres.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $groupeClient = GroupeClient::with('offres')->findOrFail($id);
        return response()->json($groupeClient);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'Désignation' => 'required|string',
            'Offre_prix' => 'required|numeric',
            'Date_début' => 'required|date',
            'Date_fin' => 'required|date',
            'groupe_clients' => 'array', // Expecting an array of groupe client IDs
            'groupe_clients.*' => 'integer|exists:groupe_clients,Id_groupe' // Validate each ID
        ]);

        $offre = Offre::find($id);

        if (!$offre) {
            return response()->json(['message' => 'Offre not found'], 404);
        }

        $offre->update($validatedData);

        // Sync the groupe clients
        $offre->groupeClients()->sync($validatedData['groupe_clients']);

        return response()->json(['message' => 'Offre updated successfully']);
    }

    /**
     * Remove the specified relationship from the pivot table.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offre = Offre::findOrFail($id);
        $offre->groupeClients()->detach();

        return response()->json(['message' => 'All Offres removed from Groupe Client successfully.']);
    }

    /**
     * Remove a specific Offre from a GroupeClient.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function removeOffreFromGroup($id, Request $request)
    {
        $validatedData = $request->validate([
            'groupe_client_id' => 'required|exists:groupe_clients,Id_groupe',
        ]);

        $offre = Offre::findOrFail($id);
        $offre->groupeClients()->detach($validatedData['groupe_client_id']);

        return response()->json(['message' => 'Offre removed from Groupe Client successfully.']);
    }
}
