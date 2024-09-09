<?php

namespace App\Http\Controllers;

use App\Models\OffreDetail;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class OffreDetailController extends Controller
{
    public function index()
    {
        try {
            $offreDetails = OffreDetail::with('produit')->get();
            return response()->json(['offreDetails' => $offreDetails]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'produit_id' => 'required|exists:produits,id',
                'Prix' => 'required|numeric',
                'id_offre' => 'required|exists:offres,id',
            ]);
        
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
        
            $offreDetail = OffreDetail::create($request->all());
            return response()->json(['message' => 'Détail d\'offre ajouté avec succès', 'offreDetail' => $offreDetail], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $offreDetail = OffreDetail::with('produit')->findOrFail($id);
            return response()->json(['offreDetail' => $offreDetail]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $offreDetail = OffreDetail::findOrFail($id);
    
            $validator = Validator::make($request->all(), [
                'produit_id' => 'required|exists:produits,id',
                'Prix' => 'required|numeric',
                'id_offre' => 'required|exists:offres,id',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $offreDetail->update($request->all());
            return response()->json(['message' => 'Détail d\'offre mis à jour avec succès', 'offreDetail' => $offreDetail], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $offreDetail = OffreDetail::findOrFail($id);
            $offreDetail->delete();

            return response()->json(['message' => 'Détail d\'offre supprimé avec succès'], 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Impossible de supprimer ce détail d\'offre car il est lié à une offre.'], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
// namespace App\Http\Controllers;

// use App\Models\OffreDetail;
// use Illuminate\Http\Request;
// use Illuminate\Database\QueryException;
// use Illuminate\Support\Facades\Validator;

// class OffreDetailController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//     public function index()
//     {
//         try {
//             $offreDetails = OffreDetail::all();
//             return response()->json(['offreDetails' => $offreDetails]);
//         } catch (\Exception $e) {
//             return response()->json(['error' => $e->getMessage()], 500);
//         }
//     }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
//         //
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//         try {
//             $validator = Validator::make($request->all(), [
//                 'Code_details' => 'required|string|max:255',
//                 'Désignation' => 'required|string|max:255',
//                 'Prix' => 'required|numeric',
//                 'id_offre' => 'required|exists:offres,id',
//             ]);
        
//             if ($validator->fails()) {
//                 return response()->json(['error' => $validator->errors()], 400);
//             }
        
//             $offreDetail = OffreDetail::create($request->all());
//             return response()->json(['message' => 'Détail d\'offre ajouté avec succès', 'offreDetail' => $offreDetail], 200);
//         } catch (\Exception $e) {
//             return response()->json(['error' => $e->getMessage()], 500);
//         }
//     }
    
    

//     /**
//      * Display the specified resource.
//      */
//     public function show($id)
//     {
//         try {
//             $offreDetail = OffreDetail::findOrFail($id);
//             return response()->json(['offreDetail' => $offreDetail]);
//         } catch (\Exception $e) {
//             return response()->json(['error' => $e->getMessage()], 500);
//         }
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit(OffreDetail $offreDetail)
//     {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request, $id)
//     {
//         try {
//             $offreDetail = OffreDetail::findOrFail($id);
    
//             $validator = Validator::make($request->all(), [
//                 'Code_details' => 'required',
//                 'Désignation' => 'required',
//                 'Prix' => 'required',
//                 'id_offre' => 'required',
//             ]);
    
//             if ($validator->fails()) {
//                 return response()->json(['error' => $validator->errors()], 400);
//             }
    
//             $offreDetail->update($request->all());
//             return response()->json(['message' => 'Détail d\'offre mis à jour avec succès', 'offreDetail' => $offreDetail], 200);
//         } catch (\Exception $e) {
//             return response()->json(['error' => $e->getMessage()], 500);
//         }
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy($id)
//     {
//         try {
//             $offreDetail = OffreDetail::findOrFail($id);
//             $offreDetail->delete();

//             return response()->json(['message' => 'Détail d\'offre supprimé avec succès'], 200);
//         } catch (QueryException $e) {
//             return response()->json(['error' => 'Impossible de supprimer ce détail d\'offre car il est lié à une offre.'], 400);
//         } catch (\Exception $e) {
//             return response()->json(['error' => $e->getMessage()], 500);
//         }
//     }
// }
