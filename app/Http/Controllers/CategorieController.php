<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();
        return response()->json($categories);
    }

    public function show($id)
    {
        $categorie = Categorie::find($id);
        return response()->json($categorie);
    }

    public function store(Request $request)
    {
        $categorie = Categorie::create($request->all());
        return response()->json($categorie, 201);
    }

    public function update(Request $request, $id)
    {
        $categorie = Categorie::findOrFail($id);
        $categorie->update($request->all());
        return response()->json($categorie, 200);
    }

    public function destroy($id)
    {
        Categorie::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}
