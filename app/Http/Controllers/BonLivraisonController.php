<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bon_Livraison;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Access\AuthorizationException;

class BonLivraisonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $livraison = Bon_Livraison::with('client')->get();
            $count = Bon_Livraison::count();
            return response()->json(['message' => 'Liste des Bon Livraison récupérée avec succès', 'livraison' =>  $livraison, 'count' => $count], 200);
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
                'ref_BC'=> 'nullable',
                'client_id' => 'required',
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }

            $livraison = Bon_Livraison::create($request->all());
            return response()->json(['message' => 'Livraison ajoutée avec succès', 'bon livraison' => $livraison], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $livraison = Bon_Livraison::with('client')->findOrFail($id);
        return response()->json(['livraison' => $livraison]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bon_Livraison $bon_Livraison)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        try {
            // $this->authorize('modify', Bon_Livraison::class);
            $livraison = Bon_Livraison::findOrFail($id);
    
            $validator = Validator::make($request->all(), [
                'reference' => 'required',
                'date' => 'required',
                'ref_BC'=> 'nullable',
                'client_id' => 'required',
                'user_id' => 'required',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $livraison->update($request->all());
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de modifier cette livraison.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bon_Livraison $id)
    {
        try {
            // $this->authorize('delete', Bon_Livraison::class);
            $livraison = Bon_Livraison::findOrFail($id);
            $livraison->delete();

            return response()->json(['message' => 'livraison supprimée avec succès'], 200);
        } catch (AuthorizationException $e) {
            return response()->json(['error' => 'Vous n\'avez pas l\'autorisation de supprimer cette livraison.'], 403);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Impossible de supprimer ce livraison car il a des ligne livraison associées.'], 400);
        }
    }
}
