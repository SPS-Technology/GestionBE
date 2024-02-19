<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\LigneCommandeController;
use App\Http\Controllers\ProduitController;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post("/login", [AuthController::class, 'login']);
Route::post("/register", [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
});
//clients
Route::get('clients', [ClientController::class, 'index']);
// ->middleware('can:view,App\Models\Client');
Route::post('clients', [ClientController::class, 'store']);
// ->middleware('can:add,App\Models\Client');
Route::get('clients/{client}', [ClientController::class, 'show']);
// ->middleware('can:view,client');
Route::put('clients/{client}', [ClientController::class, 'update']);
// ->middleware('can:modify,client');
Route::delete('clients/{client}', [ClientController::class, 'destroy']);
// ->middleware('can:delete,client');
// Fournisseurs
Route::get('fournisseurs', [FournisseurController::class, 'index']);
//->middleware('can:view,App\Models\Fournisseur')
Route::post('fournisseurs', [FournisseurController::class, 'store']);
// ->middleware('can:add,App\Models\Fournisseur')
Route::get('fournisseurs/{fournisseur}', [FournisseurController::class, 'show']);
// ->middleware('can:view,fournisseur')
Route::put('fournisseurs/{fournisseur}', [FournisseurController::class, 'update']);
// ->middleware('can:modify,fournisseur')
Route::delete('fournisseurs/{fournisseur}', [FournisseurController::class, 'destroy']);
// ->middleware('can:delete,fournisseur')
//Produits
Route::get('produits', [ProduitController::class, 'index']);
// ->middleware('can:view,App\Models\Produit');
Route::post('produits', [ProduitController::class, 'store']);
// ->middleware('can:add,App\Models\Produit');
Route::get('produits/{produit}', [ProduitController::class, 'show']);
// ->middleware('can:view,produit');
Route::put('produits/{produit}', [ProduitController::class, 'update']);
//->middleware('can:modify,produit')
Route::delete('produits/{produit}', [ProduitController::class, 'destroy']);
    // ->middleware('can:delete,produit');
//Commandes
Route::get('commandes', [CommandeController::class, 'index']);
// ->middleware('can:view,App\Models\Commande');
Route::post('commandes', [CommandeController::class, 'store']);
// ->middleware('can:add,App\Models\Commande');
Route::get('commandes/{commande}', [CommandeController::class, 'show']);
// ->middleware('can:view,commande');
Route::put('commandes/{commande}', [CommandeController::class, 'update']);
// ->middleware('can:modify,commande');
Route::delete('commandes/{commande}', [CommandeController::class, 'destroy']);
// ->middleware('can:delete,commande');

Route::get('ligne_commandes', [LigneCommandeController::class, 'index']);
Route::post('ligne_commandes', [LigneCommandeController::class, 'store']);

Route::get('status_commandes', [LigneCommandeController::class, 'index']);
Route::post('status_commandes', [LigneCommandeController::class, 'store']);

Route::get('/users', [AuthController::class, 'index']);
Route::get('/users/{id}/edit', [AuthController::class, 'edit']);
Route::put('/users/{id}',  [AuthController::class, 'update']);
Route::delete('/users/{id}',   [AuthController::class, 'destroy']);
