<?php

namespace App\Http\Controllers;

use App\Models\ClientGroupeClient;
use App\Models\GroupeClient;
use Illuminate\Http\Request;

class ClientGroupeClientController extends Controller
{
    public function index()
    {
        $clientGroupeClients = ClientGroupeClient::with(['client', 'groupe'])->get(); 

        return response()->json([
            'clientGroupeClients' => $clientGroupeClients,
            'message' => 'Client-Groupe relationships retrieved successfully'
        ]);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'CodeClient' => 'required|array',
            'Id_groupe' => 'required|exists:groupe_clients,Id_groupe'
        ]);

        $createdRelations = [];

        foreach ($validatedData['CodeClient'] as $codeClient) {
            $clientGroupeClient = ClientGroupeClient::create([
                'CodeClient' => $codeClient,
                'Id_groupe' => $validatedData['Id_groupe']
            ]);
            $createdRelations[] = $clientGroupeClient;
        }

        return response()->json([
            'clientGroupeClients' => $createdRelations,
            'message' => 'Relationships created successfully'
        ], 201);
    }
   

    public function show($id)
    {
        $clientGroupeClient = ClientGroupeClient::where('Id_groupe', $id)->get();
    
        return response()->json([
            'clientGroupeClient' => $clientGroupeClient,
            'message' => 'Relationship retrieved successfully'
        ]);
    }
    
    public function removeClientFromGroup(Request $request, $groupId)
    {
        $validatedData = $request->validate([
            'CodeClient' => 'required|string'
        ]);

        $removed = ClientGroupeClient::where('Id_groupe', $groupId)
                                    ->where('CodeClient', $validatedData['CodeClient'])
                                    ->delete();

        if ($removed) {
            return response()->json(['message' => 'Client removed from group successfully'], 200);
        } else {
            return response()->json(['message' => 'Client not found in group'], 404);
        }
    }

    public function destroy($id)
    {
        $groupeClient = GroupeClient::findOrFail($id);
        $groupeClient->clients()->detach(); 
        $groupeClient->delete(); 

        return response()->json(['message' => 'Groupe supprimé avec succès'], 200);
    }
    
}








































// namespace App\Http\Controllers;

// use App\Models\ClientGroupeClient;
// use Illuminate\Http\Request;

// class ClientGroupeClientController extends Controller
// {
//     public function index()
//     {
//         $clientGroupeClients = ClientGroupeClient::with(['client', 'groupeClient'])->get();

//         return response()->json([
//             'clientGroupeClients' => $clientGroupeClients,
//             'message' => 'Client-Groupe relationships retrieved successfully'
//         ]);
//     }

//     public function store(Request $request)
//     {
//         $validatedData = $request->validate([
//             'client_id' => 'required|exists:clients,id',
//             'groupe_id' => 'required|exists:groupe_clients,id'
//         ]);

//         $clientGroupeClient = ClientGroupeClient::create($validatedData);

//         return response()->json([
//             'clientGroupeClient' => $clientGroupeClient,
//             'message' => 'Relationship created successfully'
//         ], 201);
//     }

//     public function destroy($id)
// {
//     try {
//         if (!Gate::allows('delete_groupes')) {
//             \Log::warning('User attempted to delete groupe without permission', ['user_id' => auth()->id(), 'groupe_id' => $id]);
//             return response()->json(['error' => 'You do not have permission to delete groupes'], 403);
//         }

//         $groupe = GroupeClient::findOrFail($id);
        
//         $groupe->clients()->detach();
//         $groupe->delete();

//         return response()->json(['message' => 'Groupe supprimé avec succès'], 200);
//     } catch (\Exception $e) {
//         \Log::error('Error deleting groupe: ' . $e->getMessage(), ['user_id' => auth()->id(), 'groupe_id' => $id]);
//         return response()->json(['error' => 'An error occurred while deleting the groupe'], 500);
//     }
// }
// }
