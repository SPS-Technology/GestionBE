<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\LigneFacture;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;

class FactureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $facture = Facture::with('devis.lignedevis','client','ligneEntrerCompte','ligneFacture')->get();
            $count = Facture::count();
            return response()->json(['message' => 'Liste des facture récupérée avec succès', 'facture' =>  $facture, 'count' => $count], 200);
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
                'reference' => 'nullable',
                'date' => 'nullable',
                'ref_BL' => 'nullable',
                'ref_BC' => 'nullable',
                'modePaiement' => 'nullable',
                'total_ttc'=>'nullable',
                'client_id' => 'required',
                'user_id' => 'required',
                'id_devis' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $reference = 'FTR' . now()->timestamp;
            $date = Carbon::now()->format('Y-m-d H:i:s');

            $requestData = $request->all();
            $requestData['reference'] = $reference;
            $requestData['date'] = $date;

            $facture = Facture::create($requestData);

            return response()->json(['message' => 'Facture ajoutée avec succès', 'facture' => $facture], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($clientId)
    {
        $factures = Facture::where('client_id', $clientId)->get();
        $facture = Facture::with('client', 'devis','lignedevis','ligneFacture','lignefacture')->findOrFail($clientId);
        return response()->json(['facture' => $facture]);
    }

    public function lignefacture($factureId)
    {
        try {
            // Récupérer la facture spécifiée
            $facture = Facture::findOrFail($factureId);

            // Récupérer les lignes de facture associées à la facture spécifiée
            $lignefactures = $facture->ligneFacture;

            return response()->json(['message' => 'Lignes de facture récupérées avec succès', 'lignefacture' => $lignefactures], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur s\'est produite lors de la récupération des lignes de facture'], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Facture $facture)
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
            $facture = Facture::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'reference' => 'required',
                'date' => 'nullable',
                'ref_BL' => 'nullable',
                'ref_BC' => 'nullable',
                'modePaiement' => 'nullable',
                'total_ttc'=>'nullable',
                'client_id' => 'required',
                'user_id' => 'required',
                'id_devis' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $facture->update($request->all());
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette facture.'], 403);
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
            $devis = Facture::findOrFail($id);
            $devis->delete();

            return response()->json(['message' => 'Facture supprimée avec succès'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de supprimer cette Facture.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            // Si une exception est déclenchée, cela signifie que le Facture a des Devises associées
            return response()->json(['error' => 'Impossible de supprimer ce Facture car il a des BLs & ou Devises associées.'], 400);
        }
    }
}
