<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use App\Models\OffreDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;
use App\Models\GroupeClient;
class OffreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $offres = Offre::with('offreDetails')->get();
            $count = Offre::count();
            return response()->json(['message' => 'Liste des offres récupérée avec succès', 'offres' => $offres, 'count' => $count], 200);
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
        $validator = Validator::make($request->all(), [
            'Désignation' => 'required',
            'Offre_prix' => 'required|numeric',
            'Date_début' => 'required|date',
            'Date_fin' => 'required|date',
            'groupe_clients' => 'array|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $offre = Offre::create($request->all());

        if (!empty($request->groupe_clients)) {
            $offre->groupeClients()->sync($request->groupe_clients);
        }

        return response()->json(['message' => 'Offre ajoutée avec succès', 'offre' => $offre], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

public function offreDetails($offreId)
{
    try {
        $offreDetails = OffreDetail::where('id_offre', $offreId)
            ->with('produit:id,Code_produit,designation')
            ->get();
        
        return response()->json([
            'message' => 'Détails de l\'offre récupérés avec succès',
            'offreDetails' => $offreDetails
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Une erreur s\'est produite lors de la récupération des détails de l\'offre'
        ], 500);
    }
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $offre = Offre::with('offreDetails')->findOrFail($id);
        return response()->json(['offre' => $offre]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Offre $offre)
    {
        //
    }


    public function getOffreDetails($id)
{
    $offreDetails = OffreDetail::where('id_offre', $id)->with('produit')->get();
    return response()->json($offreDetails);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // $this->authorize('modify', Offre::class);
            $offre = Offre::findOrFail($id);
    
            $validator = Validator::make($request->all(), [
                'Désignation' => 'required',
                'Offre_prix' => 'required|numeric',
                'Date_début' => 'required|date',
                'Date_fin' => 'required|date',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $offre->update($request->all());
            return response()->json(['message' => 'Offre mise à jour avec succès', 'offre' => $offre], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette offre.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateGroupes(Request $request, $id)
{
    try {
        $offre = Offre::findOrFail($id);
        $groupeClientIds = $request->input('groupe_clients', []);

        // Sync the relations
        $offre->groupeClients()->sync($groupeClientIds);

        return response()->json(['message' => 'Groupe clients updated successfully']);
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
            // $this->authorize('delete', Offre::class);
            $offre = Offre::findOrFail($id);
            $offre->delete();

            return response()->json(['message' => 'Offre supprimée avec succès'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de supprimer cette offre.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Impossible de supprimer cette offre car elle a des détails associés.'], 400);
        }
    }
}
