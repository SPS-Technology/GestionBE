<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return response()->json(['user'=> $user]);
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
        $validator = Validator::make($request->all(),
        [
            'name' =>'required',
            'email' =>'required',
            'password' =>'required',
            'photo' =>'nullable',
        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $user = User::create($request->all());
            return response()->json(['user'=> $user], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),
        [
            'name' =>'required',
            'email' =>'required',
            'password' =>'required',
            'photo' =>'nullable',
            'role'=>'required'
        ]);
        if ($validator->fails()){
            return response()->json(['error'=> $validator->errors()],400);
        }else
        {
            $user = User::findOrFail($id);
            $user->update($request->all());
            return response()->json(['user'=> $user], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    { 
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => ' user a été supprimé avec succès.'], 200);
        } catch (QueryException $e) {
            // Si une exception est déclenchée, cela signifie que le user a des table associées
            return response()->json(['error' => 'Impossible de supprimer ce user car il a des tables associées.'], 400);
        }
    }
}
