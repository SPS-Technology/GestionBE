<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function user()
    {
        $user = Auth::user();

        return response()->json([
            'status' => 1,
            'user' => $user,
            'name' => $user->name,
            'role' => $user->role,
            'photo' => $user->photo,
        ]);
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

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users|max:255',
            'role' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Assurez-vous d'ajuster les types et la taille autorisés
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
            'password' => $request->password,
            'role' => $request->role,
        ]);
    
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('public/photos'); // Le fichier sera stocké dans le dossier storage/app/public/photos
            $user->photo = Storage::url($photoPath);
        }
    
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
            'password'=>$user->password,
            'token'=> $token,
        ], 200)->withCookie($cookie);
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'role' => 'string|max:255',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // Adjust the allowed file types and size
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
    
        if ($request->hasFile('photo')) {
            // Handle photo upload
            $photoPath = $request->file('photo')->store('public/photos');
            $user->photo = Storage::url($photoPath);
        }
    
        if ($request->has('password')) {
            $user->password = $request->input('password'); // Store the password as plain text
        }
    
        $user->save();
    
        return response()->json([
            'status' => 1,
            'message' => 'Utilisateur mis à jour avec succès',
            'user' => $user,
        ]);
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json([
            'status' => 1,
            'message' => 'Utilisateur supprimé avec succès',
        ]);
    }

  public function index()
{
    $users = User::where('role', '<>', 'admin')->get();
 
    return response()->json($users, 200);
}


    public function edit($id)
    {
        $user = User::findOrFail($id);

        return response()->json([
            'status' => 1,
            'user' => $user,
        ]);
    }
}
