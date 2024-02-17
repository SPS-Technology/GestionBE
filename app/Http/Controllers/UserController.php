<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::where('role', '<>', 'admin')->get();
        return response()->json($users, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function logout()
    {
        if (Auth::check()) {
            $cookie = Cookie::forget('jwt');

            $user_id = Auth::user()->id;
            $user = User::where('id', $user_id)->first();

            if ($user) {
                $user->tokens()->delete();
            }

            return response()->json([
                'status'    => 1,
                'message'   => "User Logged out",
            ])->withCookie($cookie);
        }

        return response()->json([
            'status'    => 0,
            'message'   => "User not authenticated",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'role' => 'required|string',
            'photo' => 'nullable|string',
            'password' => 'required|string|min:8',
        ], [
            'name.required' => 'Veuillez entrer un nom valide.',
            'role.required' => 'Veuillez entrer un rôle.',
            'email.required' => 'Veuillez entrer une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée.',
            'password.required' => 'Veuillez entrer un mot de passe valide (au moins 8 caractères).',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // Stocke le mot de passe de manière non cryptée
            'role' => $request->role,
            'photo' => $request->photo,
        ]);

        $user->save();

        return response()->json([
            'message' => 'User registered',
            'user' => $user,
        ], 201);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user || $user->password !== $request->password) {
            return response()->json([
                'status'  => 0,
                'message' => 'Email or password incorrect',
            ], 400);
        }
    
        Auth::login($user);
    
        $token = $user->createToken('API_TOKEN')->plainTextToken;
    
        $cookie = Cookie::make('jwt', $token, 60, null, null, false, true);
    
        return response()->json([
            'status'    => 1,
            'message'   => 'Utilisateur connecté',
            'user' => $user,
            'role' => $user->role,
            'photo' => $user->photo,
            'token'     => $token,
        ], 200)->withCookie($cookie);
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
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'role' => 'string|max:255',
            'photo' => 'string',
            'password' => 'string|min:8',
        ], [
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'message' => 'Erreur de validation',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user->name = $request->input('name', $user->name);
        $user->email = $request->input('email', $user->email);
        $user->role = $request->input('role', $user->role);
        $user->photo = $request->input('photo', $user->photo);

        if ($request->has('password')) {
            $user->password = $request->input('password');
        }

        $user->save();
        return response()->json([
            'status' => 1,
            'message' => 'Utilisateur mis à jour avec succès',
            'user' => $user,
        ]);
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
