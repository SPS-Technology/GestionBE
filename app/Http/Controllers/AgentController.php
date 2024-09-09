<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgentRequest;
use App\Models\Agent;
use App\Models\AgenceLocation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $agents = Agent::all();
            return response()->json(['agent'=>$agents], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while fetching agencies', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AgentRequest $request)
    {
        try {
            $validatedData = $request->validate($request->rules());
            $agent = Agent::create($validatedData);
            return response()->json(['agent' => $agent], 201);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error creating agent', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Validation error', 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $agent = Agent::with('agenceLocation')->findOrFail($id);
            
            $agent_agencelocation = [
                'id'=>$agent->id,
                'NomAgent' => $agent->NomAgent,
                'PrenomAgent' => $agent->PrenomAgent,
                'SexeAgent' => $agent->SexeAgent,
                'EmailAgent' => $agent->EmailAgent,
                'TelAgent' => $agent->TelAgent,
                'AdresseAgent' => $agent->AdresseAgent,
                'VilleAgent' => $agent->VilleAgent,
                'CodePostalAgent' => $agent->CodePostalAgent,
                'NomAgence' => $agent->agenceLocation->NomAgence,
            ];
        
            return response()->json($agent_agencelocation, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Agent not found', 'error' => $e->getMessage()], 404);
        }
    }        

    /**
     * Update the specified resource in storage.
     */
    public function update(AgentRequest $request, string $id)
    {
        try {
            $agent = Agent::findOrFail($id);
            $validatedData = $request->validate($request->rules());
            $agent->update($validatedData);
            return response()->json(['agent' => $agent], 200);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Error updating agent', 'error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Validation error', 'error' => $e->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $agent = Agent::findOrFail($id);
            $agent->delete();
            return response()->json(['message' => 'Agency deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred while deleting the agency', 'error' => $e->getMessage()], 500);
        }
    }
}
