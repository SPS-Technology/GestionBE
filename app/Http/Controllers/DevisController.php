<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use App\Models\LigneDevis;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;

class DevisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $devis = Devis::with('lignedevis', 'client')->get();
            $count = Devis::count();
            return response()->json(['message' => 'Liste des devis récupérée avec succès', 'devis' =>  $devis, 'count' => $count], 200);
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
                'reference' => 'required',
                'date' => 'required',
                'validation_offer' => 'required',
                'modePaiement' => 'required',
                'total_ht'=>'required',
                'tva'=>'required',
                'total_ttc'=>'required',
                'status' => 'required',
                'client_id' => 'required',
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $devis = Devis::create($request->all());
            return response()->json(['message' => 'Devis ajoutée avec succès', 'devis' => $devis], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function lignedevis($devisId)
    {
        try {
            // Récupérer les  ligne devises associés au devis spécifié par son ID
            $lignedevis = LigneDevis::where('id_devis', $devisId)->get();

            return response()->json(['message' => 'ligne devises récupérés avec succès', 'lignedevis' => $lignedevis], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur s\'est devis lors de la récupération des  ligne devises'], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $devis = Devis::with('client','lignedevis')->findOrFail($id);
        return response()->json(['devis' => $devis]);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Devis $devis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            // $this->authorize('modify', Devis::class);
            $devis = Devis::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'reference' => 'required',
                'date' => 'required',
                'validation_offer' => 'required',
                'modePaiement' => 'required',
                'total_ht'=>'required',
                'tva'=>'required',
                'total_ttc'=>'required',
                'status' => 'required',
                'client_id' => 'required',
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $devis->update($request->all());
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette devis.'], 403);
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
            // $this->authorize('delete', Devis::class);
            $devis = Devis::findOrFail($id);
            $devis->delete();

            return response()->json(['message' => 'Devis supprimée avec succès'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de supprimer cette devis.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            // Si une exception est déclenchée, cela signifie que le client a des commandes associées
            return response()->json(['error' => 'Impossible de supprimer ce devis car il a des BLs &  ou Factures associées.'], 400);
        }
    }
}
