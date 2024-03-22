<?php

namespace App\Http\Controllers;

use App\Models\SiteClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SiteClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siteclient = SiteClient::with('client', 'zone', 'user')->get();
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
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'CodeSiteclient' => 'required|unique:site_clients,CodeSiteclient',
    //         'raison_sociale' => 'required',
    //         'adresse' => 'required',
    //         'tele' => 'required',
    //         'ville' => 'required',
    //         'abreviation' => 'required',
    //         'ice' => 'required',
    //         'code_postal' => 'required',
    //         'zone_id' => 'required',
    //         'client_id' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['error' => $validator->errors()], 400);
    //     }

    //     $siteclient = SiteClient::create($request->all());
    //     return response()->json(['message' => 'siteclient ajoutée avec succès', 'siteclient' => $siteclient], 200);
    // }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'CodeSiteclient' => 'required|unique:site_clients,CodeSiteclient',
                'raison_sociale' => 'required',
                'adresse' => 'required',
                'tele' => 'required',
                'ville' => 'required',
                'abreviation' => 'required',
                'ice' => 'required',
                'code_postal' => 'required',
                'zone_id' => 'required',
                'logoSC' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'client_id' => 'required'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 400);
            }
    
            $client = new SiteClient();
            $client->CodeSiteclient = $request->input('CodeSiteclient');
            $client->raison_sociale = $request->input('raison_sociale');
            $client->adresse = $request->input('adresse');
            $client->tele = $request->input('tele');
            $client->ville = $request->input('ville');
            $client->abreviation = $request->input('abreviation');
            $client->ice = $request->input('ice');
            $client->code_postal = $request->input('code_postal');
            $client->zone_id = $request->input('zone_id');
            $client->client_id = $request->input('client_id');
        
            if ($request->hasFile('logoSC')) {
                $photoPath = $request->file('logoSC')->store('public/logoSc');
                $client->logoSC = Storage::url($photoPath);
            }
        
            $client->save();
            
            return response()->json(['message' => 'SieClient ajouté avec succès', 'client' => $client], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
            'CodeSiteclient' => 'required|unique:site_clients,CodeSiteclient,' . $id,
            'raison_sociale' => 'required',
            'adresse' => 'required',
            'tele' => 'required',
            'ville' => 'required',
            'abreviation' => 'required',
            'ice' => 'required',
            'code_postal' => 'required',
            'zone_id' => 'required',
            'client_id' => 'required',
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
    public function destroy($id)
    {
        $siteclient = SiteClient::findOrFail($id);
        $siteclient->delete();

        return response()->json(['message' => 'SiteClient supprimée avec succès'], 200);
    }
}
