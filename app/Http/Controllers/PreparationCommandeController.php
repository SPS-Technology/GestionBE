<?php

namespace App\Http\Controllers;

use App\Models\Commande;
use App\Models\PreparationCommande;
use Illuminate\Http\Request;

class PreparationCommandeController extends Controller
{
    public function index()
    {
        $preparations = PreparationCommande::with('commande', 'lignesPreparation',)->get();
        return response()->json($preparations, 200);
    }

  
    public function show($id)
    {
        try {
            // Récupérer la préparation spécifiée par son ID avec les lignes de préparation
            $preparation = PreparationCommande::with('lignesPreparation')->findOrFail($id);
    
            // Récupérer la commande associée à cette préparation
            $commande = $preparation->commande()->firstOrFail();
    
            // Construire la réponse avec les données de la préparation et de la commande
            $response = [
                'preparation' => $preparation,
                'commande' => $commande
            ];
    
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Données non trouvées ou une erreur est survenue'], 404);
        }
    }
    

    public function getLignesPreparationByPreparation($preparation_id)
    {
        // Récupérer la préparation associée à la préparation spécifiée
        $preparation = PreparationCommande::with('lignesPreparation')
            ->where('commande_id', $preparation_id)
            ->get(); // Utiliser first() pour obtenir un seul résultat
    
    
        // Retourner les lignes de préparation au format JSON
        return response()->json($preparation, 200);
    }
    



    public function store(Request $request)
    {
        // Définir le statut initial comme "En Attente"
        // $request->merge(['status_preparation' => 'En Attente']);

        $request->validate([
            'commande_id' => 'required|exists:commandes,id',
            'datePreparationCommande' => 'required|date',
        ]);

        // Récupérer la commande associée
        $commande = Commande::findOrFail($request->input('commande_id'));

        // Récupérer la référence de commande
        $reference = $commande->reference;

        // Générer le nouveau code de préparation
        $latestPreparation = $commande->preparations->last();
        $lastNumber = $latestPreparation ? intval(substr($latestPreparation->CodePreparation, strrpos($latestPreparation->CodePreparation, '-') + 1)) : 0;
        $newCode = $reference . '-' . ($lastNumber + 1);

        // Créer une nouvelle instance de préparation de commande
        $preparation = new PreparationCommande();
        $preparation->commande_id = $request->input('commande_id');
        $preparation->status_preparation = $request->input('status_preparation');
        $preparation->datePreparationCommande = $request->input('datePreparationCommande');
        $preparation->CodePreparation = $newCode;
        $preparation->save();

        // Retourner une réponse JSON avec la nouvelle préparation de commande
        return response()->json($preparation, 201);
    }



    public function update(Request $request, $id)
    {
        $request->validate([
            'status_preparation' => 'required',
            'datePreparationCommande' => 'required|date',
        ]);

        $preparation = PreparationCommande::findOrFail($id);
        $preparation->status_preparation = $request->input('status_preparation');
        $preparation->datePreparationCommande = $request->input('datePreparationCommande');
        $preparation->save();

        return response()->json($preparation, 200);
    }

    public function destroy($id)
    {
        $preparation = PreparationCommande::findOrFail($id);
        $preparation->delete();

        return response()->json(null, 204);
    }
}
