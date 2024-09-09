<?php
namespace App\Http\Controllers;

use App\Models\Vis;
use App\Models\Visite;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class VisiteController extends Controller
{
    public function index()
    {
        $visites = Vis::all();
        return response()->json($visites);
    }
    public function store(Request $request)
    {
  

        // Créer une nouvelle visite avec les données validées
        $visite = Vis::create([
            'date_visite' => $request->date_visite,
            'commentaire' =>  $request->commentaire,
            'vehicule_id' =>  $request->vehicule_id,
        ]);

        // Retourner une réponse appropriée
        return response()->json(['message' => 'Visite créée avec succès', 'visite' => $visite], 201);
    }

    public function show(Vis $visite)
    {
        return response()->json($visite);
    }
    public function update(Request $request, $id)
    {
      try {
        // Retrieve the visit
        $visite = Vis::findOrFail($id);
    
        // Update attributes based on request data
        $visite->update([
          'date_visite' => $request->date_visite,
          'commentaire' => $request->commentaire,
          'vehicule_id' => $request->vehicule_id,
        ]);
    
        // Save changes and return success response
        $visite->save();
        return response()->json([
          'message' => 'Visite mise à jour avec succès.',
          'visite' => $visite
        ]);
      } catch (ModelNotFoundException $exception) {
        // Handle case where visit is not found
        return response()->json([
          'message' => 'Visite introuvable.',
          'error' => $exception->getMessage()
        ], 404);
      } catch (Exception $exception) {
        // Handle other unexpected exceptions
        return response()->json([
          'message' => 'Une erreur est survenue lors de la mise à jour de la visite.',
          'error' => $exception->getMessage()
        ], 500);
      }
    }

    public function destroy(Vis $visite)
    {
        $visite->delete();

        return response()->json(['message' => 'Visite supprimée avec succès.']);
    }
}
