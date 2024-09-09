<?php

namespace App\Http\Controllers;

use App\Models\Autorisation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AutorisationController extends Controller
{
    public function index()
    {
        $autorisations = Autorisation::all();
        return response()->json(['autorisations' => $autorisations], Response::HTTP_OK);
    }

    public function create()
    {
        // Pas nécessaire dans une API
    }

    public function store(Request $request)
    {
        $request->validate([
            'autorisation_onas' => 'required',
            'date_autorisation' => 'nullable|date',
            'date_expiration' => 'nullable|date',
            'date_alerte' => 'nullable|date',
            'vehicule_id' => 'required|exists:vehicules,id',
        ]);

        $autorisation = Autorisation::create($request->all());

        return response()->json(['success' => 'Autorisation créée avec succès.', 'data' => $autorisation], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $autorisation=Autorisation::findOrFail($id);
        return response()->json(['autorisation' => $autorisation], Response::HTTP_OK);
    }

    public function edit(Autorisation $autorisation)
    {
        
    }

    public function update(Request $request, Autorisation $autorisation)
    {
        $request->validate([
            'autorisation_onas' => 'required',
            'date_autorisation' => 'required|date',
            'date_expiration' => 'required|date',
            'date_alerte' => 'required|date',
            'vehicule_id' => 'required|exists:vehicules,id',
        ]);

        $autorisation->update($request->all());

        return response()->json(['success' => 'Autorisation mise à jour avec succès.', 'data' => $autorisation], Response::HTTP_OK);
    }

    public function destroy(Autorisation $autorisation)
    {
        $autorisation->delete();

        return response()->json(['success' => 'Autorisation supprimée avec succès.'], Response::HTTP_OK);
    }
}
