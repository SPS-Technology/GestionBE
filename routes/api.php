<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\LigneCommandeController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\ObjectifController;
use App\Http\Controllers\PermisController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SiteClientController;
use App\Http\Controllers\StatusCommandeController;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\VehiculeLivreurController;
use App\Http\Controllers\ZoneController;
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



Route::middleware('auth:sanctum')->group(function () {
    Route::post("/register", [AuthController::class, 'register']);

    //produits
    Route::get('produits', [ProduitController::class, 'index']);
    Route::get('produits/{produit}', [ProduitController::class, 'show']);
    Route::put('produits/{produit}', [ProduitController::class, 'update']);
    Route::delete('produits/{produit}', [ProduitController::class, 'destroy']);
    Route::post('produits', [ProduitController::class, 'store']);

    // Fournisseurs
    Route::get('fournisseurs', [FournisseurController::class, 'index']);
    Route::post('fournisseurs', [FournisseurController::class, 'store']);
    Route::get('fournisseurs/{fournisseur}', [FournisseurController::class, 'show']);
    Route::put('fournisseurs/{fournisseur}', [FournisseurController::class, 'update']);
    Route::delete('fournisseurs/{fournisseur}', [FournisseurController::class, 'destroy']);
    //clients
    Route::get('clients', [ClientController::class, 'index']);
    Route::post('clients', [ClientController::class, 'store']);
    Route::get('clients/{client}', [ClientController::class, 'show']);
    Route::put('clients/{client}', [ClientController::class, 'update']);
    Route::delete('clients/{client}', [ClientController::class, 'destroy']);

    //Commandes
    Route::get('commandes', [CommandeController::class, 'index']);
    Route::post('commandes', [CommandeController::class, 'store']);
    Route::get('commandes/{commande}', [CommandeController::class, 'show']);
    Route::put('commandes/{commande}', [CommandeController::class, 'update']);
    Route::delete('commandes/{commande}', [CommandeController::class, 'destroy']);
    //user
    Route::get('/users/{id}/edit', [AuthController::class, 'edit']);
    Route::put('/users/{id}',  [AuthController::class, 'update']);
    Route::delete('/users/{id}',   [AuthController::class, 'destroy']);
    Route::get('/users', [AuthController::class, 'index']);
    Route::apiResource('/ligneCommandes', LigneCommandeController::class);
    Route::apiResource('/statusCommande', StatusCommandeController::class);

    // Définition des routes pour les site clients
    Route::get('siteclients', [SiteClientController::class, 'index']); // Route pour obtenir tous les site clients
    Route::get('siteclients/{siteclient}', [SiteClientController::class, 'show']);
    Route::put('siteclients/{siteclient}', [SiteClientController::class, 'update']);
    Route::post('siteclients', [SiteClientController::class, 'store']);
    Route::delete('siteclients/{siteclient}', [SiteClientController::class, 'destroy']);
    // Route pour obtenir les site clients associés à un client spécifique
    Route::get('clients/{clientId}/siteclients', [ClientController::class, 'siteclients']);


    //logout
    Route::post("/logout", [AuthController::class, 'logout']);
    Route::apiResource('/roles', RoleController::class);
    Route::apiResource('/categories', CategorieController::class);

    //zone
    Route::get('zones', [ZoneController::class, 'index']);
    Route::post('zones', [ZoneController::class, 'store']);
    Route::get('zones/{zone}', [ZoneController::class, 'show']);
    Route::put('zones/{zone}', [ZoneController::class, 'update']);
    Route::delete('zones/{zone}', [ZoneController::class, 'destroy']);

    Route::get('/objectifs', [ObjectifController::class, 'index']);
    Route::post('/objectifs', [ObjectifController::class, 'store']);
    Route::get('/objectifs/{id}', [ObjectifController::class, 'show']);
    Route::put('/objectifs/{id}', [ObjectifController::class, 'update']);
    Route::delete('/objectifs/{id}', [ObjectifController::class, 'destroy']);


    Route::get('/permis', [PermisController::class, 'index']);
    Route::post('/permis', [PermisController::class, 'store']);
    Route::get('/permis/{id}', [PermisController::class, 'show']);
    Route::put('/permis/{id}', [PermisController::class, 'update']);
    Route::delete('/permis/{id}', [PermisController::class, 'destroy']);
    // Routes pour Livreurs
    Route::get('/livreurs', [LivreurController::class, 'index']);
    Route::post('/livreurs', [LivreurController::class, 'store']);
    Route::get('/livreurs/{id}', [LivreurController::class, 'show']);
    Route::put('/livreurs/{id}', [LivreurController::class, 'update']);
    Route::delete('/livreurs/{id}', [LivreurController::class, 'destroy']);


    // Routes pour Vehicules
    Route::get('/vehicules', [VehiculeController::class, 'index']);
    Route::post('/vehicules', [VehiculeController::class, 'store']);
    Route::get('/vehicules/{id}', [VehiculeController::class, 'show']);
    Route::put('/vehicules/{id}', [VehiculeController::class, 'update']);
    Route::delete('/vehicules/{id}', [VehiculeController::class, 'destroy']);

    Route::get('/vehicule-livreurs', [VehiculeLivreurController::class, 'index']);
    Route::post('/vehicule-livreurs', [VehiculeLivreurController::class, 'store']);
    Route::get('/vehicule-livreurs/{id}', [VehiculeLivreurController::class, 'show']);
    Route::put('/vehicule-livreurs/{id}', [VehiculeLivreurController::class, 'update']);
    Route::delete('/vehicule-livreurs/{id}', [VehiculeLivreurController::class, 'destroy']);
});
