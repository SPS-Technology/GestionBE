<?php

namespace App\Http\Controllers;

use App\Models\SiteClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siteclient = SiteClient::with('client','zone','user')->get();
        $count = SiteClient::count();
        return response()->json([
            'message' => 'Liste des SiteClient récupérée avec succès', 'siteclient' =>  $siteclient,
            'count' => $count
        ], 200);
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
        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'required',
            'adresse' => 'required',
            'tele' => 'required',
            'ville' => 'required',
            'abreviation' => 'required',
            'ice'=>'required',
            'code_postal'=>'required',
            'zone_id' => 'required',
            'client_id'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $siteclient = SiteClient::create($request->all());
        return response()->json(['message' => 'siteclient ajoutée avec succès', 'siteclient' => $siteclient], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $siteclient = SiteClient::findOrFail($id);
        return response()->json(['siteclient' => $siteclient]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SiteClient $siteClient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $siteclient = SiteClient::findOrFail($id);
            $validator = Validator::make($request->all(), [
                'raison_sociale' => 'required',
                'adresse' => 'required',
                'tele' => 'required',
                'ville' => 'required',
                'abreviation' => 'required',
                'ice'=>'required',
                'code_postal'=>'required',
                'zone_id' => 'required',
                'client_id'=>'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
            $siteclient->update($request->all());
            return response()->json(['message' => 'SiteClient modifié avec succès', 'siteclient' => $siteclient], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $siteclient = SiteClient::findOrFail($id);
            $siteclient->delete();

            return response()->json(['message' => 'SiteClient supprimée avec succès'], 200);
    }
}