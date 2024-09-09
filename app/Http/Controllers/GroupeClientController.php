<?php

namespace App\Http\Controllers;

use App\Models\GroupeClient;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class GroupeClientController extends Controller
{   
        public function index()
    {
        try {
            $groupes = GroupeClient::with('clients')->get();
            return response()->json($groupes);
        } catch (\Exception $e) {
            \Log::error('Error fetching groupes: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Name' => 'required|string|max:255',
            'Designation' => 'required|string|max:255',
            'clients' => 'array|nullable'
        ]);

        $groupe = GroupeClient::create($validatedData);

        if (!empty($validatedData['clients'])) {
            $groupe->clients()->sync($validatedData['clients']);
        }

        return response()->json($groupe, 201);
    }

    
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'Name' => 'sometimes|required|string|max:255',
            'Designation' => 'sometimes|required|string|max:255',
            'clients' => 'array|nullable'
        ]);

        $groupe = GroupeClient::findOrFail($id);
        $groupe->update($validatedData);

        if (!empty($validatedData['clients'])) {
            $groupe->clients()->sync($validatedData['clients']);
        }

        return response()->json($groupe, 200);
    }

    public function show($id)
    {
            $groupe = GroupeClient::with('clients')->findOrFail($id);
            return response()->json($groupe);
    }
    public function getRelations()
    {
            $relations = DB::table('client_groupe_client')->get();
            return response()->json($relations);
    }

    public function destroy($id)
    {
        try {
            $groupe = GroupeClient::findOrFail($id);
            $groupe->clients()->detach();
            $groupe->delete();

            return response()->json(['message' => 'Groupe supprimé avec succès'], 200);
        } catch (QueryException $e) {
            if ($e->errorInfo[1] === 1451) {
                return response()->json(['error' => 'Impossible de supprimer ce groupe car il est associé à des clients.'], 400);
            }
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}

// namespace App\Http\Controllers;

// use App\Models\GroupeClient;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Gate;
// use Illuminate\Database\QueryException;

// class GroupeClientController extends Controller
// {
//     public function index()
//     {
//         try {
//             $groupes = GroupeClient::with('clients')->get();
//             return response()->json($groupes);
//         } catch (\Exception $e) {
//             \Log::error('Error fetching groupes: ' . $e->getMessage());
//             return response()->json(['error' => 'Internal Server Error'], 500);
//         }
//     }

//     public function store(Request $request)
//     {
//         $validatedData = $request->validate([
//             'Name' => 'required|string|max:255',
//             'Designation' => 'required|string|max:255',
//             'clients' => 'array|nullable'
//         ]);

//         $groupe = GroupeClient::create($validatedData);

//         if (isset($validatedData['clients'])) {
//             $groupe->clients()->sync($validatedData['clients']);
//         }

//         return response()->json($groupe, 201);
//     }

//     public function update(Request $request, $id)
//     {
//         $validatedData = $request->validate([
//             'Name' => 'sometimes|required|string|max:255',
//             'Designation' => 'sometimes|required|string|max:255',
//             'clients' => 'array|nullable'
//         ]);

//         $groupe = GroupeClient::findOrFail($id);
//         $groupe->update($validatedData);

//         if (isset($validatedData['clients'])) {
//             $groupe->clients()->sync($validatedData['clients']);
//         }

//         return response()->json($groupe, 200);
//     }

//     public function show($id)
//     {
//         $groupe = GroupeClient::with('clients')->findOrFail($id);
//         return response()->json($groupe);
//     }

//     public function destroy($id)
//     {
//         try {
//             $groupe = GroupeClient::findOrFail($id);
//             $groupe->clients()->detach();
//             $groupe->delete();

//             return response()->json(['message' => 'Groupe supprimé avec succès'], 200);
//         } catch (\Exception $e) {
//             return response()->json(['error' => $e->getMessage()], 500);
//         } catch (QueryException $e) {
//             if ($e->errorInfo[1] === 1451) {
//                 return response()->json(['error' => 'Impossible de supprimer ce groupe car il est associé à des clients.'], 400);
//             } else {
//                 return response()->json(['error' => $e->getMessage()], 500);
//             }
//         }
//     }
// }

