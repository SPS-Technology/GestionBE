<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return response()->json(['clients' => $clients]);
    }

    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json(['client' => $client]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'required',
            'adresse' => 'required',
            'tel' => 'required',
            'ville' => 'required',
            'abreviation' => 'required',
            'zone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $client = Client::create($request->all());

        return response()->json(['client' => $client], 201);
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'raison_sociale' => 'required',
            'adresse' => 'required',
            'tel' => 'required',
            'ville' => 'required',
            'abreviation' => 'required',
            'zone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $client->update($request->all());

        return response()->json(['client' => $client], 200);
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Client supprimé avec succès'], 204);
    }
}
