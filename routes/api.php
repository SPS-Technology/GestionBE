<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\LigneCommandeController;
use App\Http\Controllers\PreparationLigneCommandeController;
use App\Http\Controllers\ChargementCommandeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StatusCommandeController;
use App\Http\Controllers\SiteClientController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\LivreurController;
use App\Http\Controllers\VehiculeController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\LigneDevisController;
use App\Http\Controllers\CalibreController;
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

// Route::apiResource('roles', RoleController::class);
// Route::apiResource('permissions', PermissionController::class);
Route::get('produits', [ProduitController::class, 'index']);
Route::post('produits', [ProduitController::class, 'store']);
Route::get('produits/{produit}', [ProduitController::class, 'show']);
Route::put('produits/{produit}', [ProduitController::class, 'update']);
Route::delete('produits/{produit}', [ProduitController::class, 'destroy']);
Route::middleware('auth:sanctum')->group(function () {
    //produits
   

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
    //user
      // Définition des routes pour les clients
   
      Route::post('clients', [ClientController::class, 'store']);
      Route::get('clients/{client}', [ClientController::class, 'show']);
      Route::put('clients/{client}', [ClientController::class, 'update']);
      Route::delete('clients/{client}', [ClientController::class, 'destroy']);
  
      // Définition des routes pour les site clients
      Route::get('siteclients', [SiteClientController::class, 'index']); // Route pour obtenir tous les site clients
      Route::post('siteclients', [SiteClientController::class, 'store']);
      Route::get('siteclients/{siteclient}', [SiteClientController::class, 'show']);
      Route::put('siteclients/{siteclient}', [SiteClientController::class, 'update']);
      Route::delete('siteclients/{siteclient}', [SiteClientController::class, 'destroy']);
  
      // Route pour obtenir les site clients associés à un client spécifique
      Route::get('clients/{clientId}/siteclients', [ClientController::class, 'siteclients']);
      
      //categories
  
      Route::apiResource('/categories', CategorieController::class);
    
    //zone
    Route::get('zones', [ZoneController::class, 'index']);
    Route::post('zones', [ZoneController::class, 'store']);
    Route::get('zones/{zone}', [ZoneController::class, 'show']);
    Route::put('zones/{zone}', [ZoneController::class, 'update']);
    Route::delete('zones/{zone}', [ZoneController::class, 'destroy']);
    

    //logout
    Route::post("/logout", [AuthController::class, 'logout']);
    Route::apiResource('/roles', RoleController::class);
});
Route::get('/users/{id}/edit', [AuthController::class, 'edit']);
Route::put('/users/{id}',  [AuthController::class, 'update']);
Route::delete('/users/{id}',   [AuthController::class, 'destroy']);
Route::get('/users', [AuthController::class, 'index']);
Route::apiResource('/categories', CategorieController::class);
Route::apiResource('/zones', ZoneController::class);

Route::get('commandes', [CommandeController::class, 'index']);
    Route::post('commandes', [CommandeController::class, 'store']);
    Route::get('commandes/{commande}', [CommandeController::class, 'show']);
    Route::put('commandes/{commande}', [CommandeController::class, 'update']);
    Route::delete('commandes/{commande}', [CommandeController::class, 'destroy']);

    Route::apiResource('/ligneCommandes', LigneCommandeController::class);
    Route::apiResource('/statusCommande', StatusCommandeController::class);
    Route::apiResource('/lignePreparationCommandes', PreparationLigneCommandeController::class);
    Route::apiResource('/chargementCommandes', ChargementCommandeController::class);
    Route::apiResource('/livreurs', LivreurController::class);
    Route::apiResource('/vehicules', VehiculeController::class);
     //stock
 Route::get('stock', [StockController::class, 'index']);
 Route::post('stock', [StockController::class, 'store']);
 Route::get('stock/{stock}', [StockController::class, 'show']);
 Route::put('stock/{stock}', [StockController::class, 'update']);
 Route::delete('stock/{stck}', [StockController::class, 'destroy']);


 //Devis
 Route::apiResource('/devises', DevisController::class);
 Route::apiResource('/lignedevis', LigneDevisController::class);

 //Calibre
 Route::apiResource('/calibres', CalibreController::class);

 
 Route::get('clients', [ClientController::class, 'index']);