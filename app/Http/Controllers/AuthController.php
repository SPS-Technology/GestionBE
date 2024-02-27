<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class AuthController extends Controller
{

    public function user()
    {
        if (Gate::allows('view_all_users')) {
            $user = Auth::user();
            $user = User::with('roles.permissions')->find($user->id)->get();
            return response()->json([
                'status' => 1,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'photo' => $user->photo,
                    'roles' => $user->roles->pluck('name'), // Liste des noms de rôles
                    'permissions' => $user->roles->flatMap(function ($role) {
                        return $role->permissions->pluck('name');
                    }), // Liste des noms de permissions
                ],
            ]);
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des utilisateurs.');
        }
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
        if (Gate::allows('create_user')) {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users|max:255',
                'password' => 'required|string|min:8',
                'role' => 'required|string', // You can add validation for the role
                'permissions' => 'array', // Permissions must be an array
                'permissions.*' => 'string|exists:permissions,name', // Validate each permission
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Check for validation errors
            if ($validator->fails()) {
                return response()->json([
                    'status' => 0,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            // Create a new user without hashing the password
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password, // Ne pas utiliser bcrypt ici
            ]);

            // Upload and store the photo if provided
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('public/photos'); // Le fichier sera stocké dans le dossier storage/app/public/photos
                $user->photo = Storage::url($photoPath);
            }


            // Save the user
            $user->save();

            // Create or retrieve the role
            $role = Role::Create(['name' => $request->role]);

            // Assign the role to the user
            $user->roles()->sync([$role->id]);

            // Assign permissions to the user if provided
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('name', $request->permissions)->get();
                $role->permissions()->sync($permissions);
            }

            // Success response
            return response()->json([
                'status' => 1,
                'message' => 'User registered with role and permissions',
                'user' => $user,
            ], 201);
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de ajouter un utilisateur.');
        }
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
            'photo' => $user->photo,
            'token' => $token,
        ], 200)->withCookie($cookie);
    }


    public function update(Request $request, $id)
    {
        if (Gate::allows('edit_user')) {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'string|max:255',
                'email' => 'string|email|max:255',
                'role' => 'string', // Validation rule for role (you can customize this)
                'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'password' => 'string|min:8',
                'permissions' => 'array',
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

            // Create a new role each time, even if it already exists
            $role = Role::CreateorFirst(['name' => $request->role]);

            // Assign the role to the user
            $user->roles()->sync([$role->id]);

            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('public/photos');
                $user->photo = Storage::url($photoPath);
            }

            if ($request->has('password')) {
                $user->password = $request->input('password');
            }

            $user->save();

            // Sync user permissions if necessary
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('name', $request->permissions)->get();
                $role->permissions()->sync($permissions);
            }

            return response()->json([
                'status' => 1,
                'message' => 'Utilisateur mis à jour avec succès',
                'user' => $user,
            ]);
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de modifier un utilisateur.');
        }
    }


    public function destroy($id)
    {
        if (Gate::allows('delete_user')) {
            $user = User::findOrFail($id);

            $user->delete();

            return response()->json([
                'status' => 1,
                'message' => 'Utilisateur supprimé avec succès',
            ]);
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de supprimer un utilisateur.');
        }
    }
    public function index()
    {
        if (Gate::allows('view_all_users')) {
            $users = User::whereHas('roles', function ($query) {
                $query->where('name', '<>', 'admin');
            })->with('roles.permissions')->get();

            return response()->json($users, 200);
        } else {
            abort(403, 'Vous n\'avez pas l\'autorisation de voir la liste des utilisateurs.');
        }
    }
    public function edit($id)
    {
        $user = User::with('roles.permissions')->findOrFail($id);

        return response()->json([
            'status' => 1,
            'user' => $user,
        ]);
    }
}
