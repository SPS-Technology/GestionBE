<?php

use App\Http\Controllers\BonLivraisonController;
use App\Http\Controllers\CalibreController;
use App\Http\Controllers\ComptesController;
use App\Http\Controllers\EncaissementController;
use App\Http\Controllers\EntrerBanqueController;
use App\Http\Controllers\ChiffreAffaireController;
use App\Http\Controllers\DevisController;
use App\Http\Controllers\EtatRecouvrementController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\LigneDevisController;
use App\Http\Controllers\LigneencaissementController;
use App\Http\Controllers\LigneentrercompteController;
use App\Http\Controllers\LigneFactureController;
use App\Http\Controllers\LigneLivraisonController;
use App\Http\Controllers\ReclamationController;
use App\Models\Role;
use App\Models\SiteClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SiteClientController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\LigneCommandeController;
use App\Http\Controllers\StatusCommandeController;
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

Route::middleware('auth:sanctum')->group(function () {

    // Fournisseurs
    Route::get('fournisseurs', [FournisseurController::class, 'index']);
    Route::post('fournisseurs', [FournisseurController::class, 'store']);
    Route::get('fournisseurs/{fournisseur}', [FournisseurController::class, 'show']);
    Route::put('fournisseurs/{fournisseur}', [FournisseurController::class, 'update']);
    Route::delete('fournisseurs/{fournisseur}', [FournisseurController::class, 'destroy']);

//zone
    Route::get('zones', [ZoneController::class, 'index']);
    Route::post('zones', [ZoneController::class, 'store']);
    Route::get('zones/{zone}', [ZoneController::class, 'show']);
    Route::put('zones/{zone}', [ZoneController::class, 'update']);
    Route::delete('zones/{zone}', [ZoneController::class, 'destroy']);


     // Définition des routes pour les clients


    // Définition des routes pour les site clients
    Route::get('siteclients', [SiteClientController::class, 'index']); // Route pour obtenir tous les site clients
    Route::post('siteclients', [SiteClientController::class, 'store']);
    Route::get('siteclients/{siteclient}', [SiteClientController::class, 'show']);
    Route::put('siteclients/{siteclient}', [SiteClientController::class, 'update']);
    Route::delete('siteclients/{siteclient}', [SiteClientController::class, 'destroy']);

    // Route pour obtenir les site clients associés à un client spécifique
    Route::get('clients/{clientId}/siteclients', [ClientController::class, 'siteclients']);

    //logout
    Route::post("/logout", [AuthController::class, 'logout']);
    Route::apiResource('/roles', RoleController::class);

    Route::get('/users/{id}/edit', [AuthController::class, 'edit']);
    Route::put('/users/{id}',  [AuthController::class, 'update']);
    Route::delete('/users/{id}',   [AuthController::class, 'destroy']);
    Route::get('/users', [AuthController::class, 'index']);

    Route::apiResource('/categories', CategorieController::class);

    Route::get('produits', [ProduitController::class, 'index']);
    Route::post('produits', [ProduitController::class, 'store']);
    Route::get('produits/{produit}', [ProduitController::class, 'show']);
    Route::put('produits/{produit}', [ProduitController::class, 'update']);
    Route::delete('produits/{produit}', [ProduitController::class, 'destroy']);




// Routes for ChiffreAffaire
    Route::get('/chiffre-affaire', [ChiffreAffaireController::class, 'index']);
    Route::post('/chiffre-affaire', [ChiffreAffaireController::class, 'store']);
    Route::get('/chiffre-affaire/{id}', [ChiffreAffaireController::class, 'show']);
    Route::put('/chiffre-affaire/{id}', [ChiffreAffaireController::class, 'update']);
    Route::delete('/chiffre-affaire/{id}', [ChiffreAffaireController::class, 'destroy']);

//Calibre
    Route::apiResource('/calibres', CalibreController::class);












});
// Devises
Route::apiResource('/devises', DevisController::class);
Route::apiResource('/ligneDevis', LigneDevisController::class);
// Route pour obtenir les lignedevis associés à un devis spécifique
Route::get('devises/{devisId}/ligneDevis', [DevisController::class, 'lignedevis']);
Route::post('devises/{devisId}/ligneDevis', [DevisController::class, 'lignedevis']);
Route::put('devises/{devisId}/ligneDevis', [DevisController::class, 'lignedevis']);
Route::delete('devises/{devisId}/ligneDevis', [DevisController::class, 'lignedevis']);


Route::get('clients', [ClientController::class, 'index']);
Route::post('clients', [ClientController::class, 'store']);
Route::get('clients/{client}', [ClientController::class, 'show']);
Route::put('clients/{client}', [ClientController::class, 'update']);
Route::delete('clients/{client}', [ClientController::class, 'destroy']);
//Ligneentrercompte
Route::apiResource('/ligneentrercompte',LigneentrercompteController::class);
//Route for EntrerBanque
Route::get('/banques', [EntrerBanqueController::class, 'index']);
Route::post('/banques', [EntrerBanqueController::class, 'store']);
Route::get('/banques/{id}', [EntrerBanqueController::class, 'show']);
Route::put('/banques/{id}', [EntrerBanqueController::class, 'update']);
Route::delete('/banques/{id}', [EntrerBanqueController::class, 'destroy']);
Route::apiResource('/etat-recouvrements', EtatRecouvrementController::class,);

// Routes for Reclamation
Route::get('/reclamations', [ReclamationController::class, 'index']);
Route::post('/reclamations', [ReclamationController::class, 'store']);
Route::get('/reclamations/{id}', [ReclamationController::class, 'show']);
Route::put('/reclamations/{id}', [ReclamationController::class, 'update']);
Route::delete('/reclamations/{id}', [ReclamationController::class, 'destroy']);

Route::apiResource('/encaissements', EncaissementController::class,);
Route::apiResource('/comptes', ComptesController::class,);
Route::apiResource('/ligneencaissement', LigneencaissementController::class,);

//Factures
Route::apiResource('/factures', FactureController::class);
Route::apiResource('/ligneFacture', LigneFactureController::class);
// Route pour obtenir les lignedevis associés à un devis spécifique
Route::get('factures/{facturesId}/ligneFacture', [FactureController::class, 'lignefacture']);
Route::post('factures/{facturesId}/ligneFacture', [FactureController::class, 'lignefacture']);
Route::put('factures/{facturesId}/ligneFacture', [FactureController::class, 'lignefacture']);
Route::delete('factures/{facturesId}/ligneFacture', [FactureController::class, 'lignefacture']);


Route::apiResource('/livraisons', BonLivraisonController::class);
Route::apiResource('/lignelivraisons', LigneLivraisonController::class);
// Route pour obtenir les lignedevis associés à un devis spécifique
Route::get('livraisons/{livraisonsId}/lignelivraisons', [BonLivraisonController::class, 'lignelivraisons']);
Route::post('livraisons/{livraisonsId}/lignelivraisons', [BonLivraisonController::class, 'lignelivraisons']);
Route::put('livraisons/{livraisonsId}/lignelivraisons', [BonLivraisonController::class, 'lignelivraisons']);
Route::delete('livraisons/{livraisonsId}/lignelivraisons', [BonLivraisonController::class, 'lignelivraisons']);

//Commandes
Route::get('commandes', [CommandeController::class, 'index']);
Route::post('commandes', [CommandeController::class, 'store']);
Route::get('commandes/{commande}', [CommandeController::class, 'show']);
Route::put('commandes/{commande}', [CommandeController::class, 'update']);
Route::delete('commandes/{commande}', [CommandeController::class, 'destroy']);

Route::apiResource('/ligneCommandes', LigneCommandeController::class);
Route::apiResource('/statusCommande', StatusCommandeController::class);
