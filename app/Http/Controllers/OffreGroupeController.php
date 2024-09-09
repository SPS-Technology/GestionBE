<?php

namespace App\Http\Controllers;

use App\Models\OffreGroupe;
use App\Models\GroupeClient;
use Illuminate\Http\Request;

class OffreGroupeController extends Controller
{
    public function index()
    {
        $offreGroupes = OffreGroupe::with(['offre', 'groupe'])->get();

        return response()->json([
            'offreGroupes' => $offreGroupes,
            'message' => 'Offre-Groupe relationships retrieved successfully'
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Id_offre' => 'required|array',
            'Id_groupe' => 'required|exists:groupe_clients,Id_groupe'
        ]);

        $createdRelations = [];

        foreach ($validatedData['Id_offre'] as $idOffre) {
            $offreGroupe = OffreGroupe::create([
                'Id_offre' => $idOffre,
                'Id_groupe' => $validatedData['Id_groupe']
            ]);
            $createdRelations[] = $offreGroupe;
        }

        return response()->json([
            'offreGroupes' => $createdRelations,
            'message' => 'Relationships created successfully'
        ], 201);
    }

    public function show($id)
    {
        $offreGroupe = OffreGroupe::where('Id_groupe', $id)->get();

        return response()->json([
            'offreGroupe' => $offreGroupe,
            'message' => 'Relationship retrieved successfully'
        ]);
    }


    public function updateGroupes(Request $request, $offreId)
{
    $offre = Offre::findOrFail($offreId);
    $groupeClientIds = $request->input('groupe_clients', []);
    
    $validGroupeClients = GroupeClient::whereIn('Id_groupe', $groupeClientIds)->pluck('Id_groupe');
    
    $offre->groupeClients()->sync($validGroupeClients);
    
    return response()->json(['message' => 'Groupe clients updated successfully']);
}


    public function removeOffreFromGroup(Request $request, $groupId)
    {
        $validatedData = $request->validate([
            'Id_offre' => 'required|string'
        ]);

        $removed = OffreGroupe::where('Id_groupe', $groupId)
                              ->where('Id_offre', $validatedData['Id_offre'])
                              ->delete();

        if ($removed) {
            return response()->json(['message' => 'Offre removed from group successfully'], 200);
        } else {
            return response()->json(['message' => 'Offre not found in group'], 404);
        }
    }

    public function destroy($id)
    {
        $groupeClient = GroupeClient::findOrFail($id);
        $groupeClient->offres()->detach();  
        $groupeClient->delete(); 

        return response()->json(['message' => 'Group deleted successfully'], 200);
    }
}
