<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\CommandeController;
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
Route::apiResource('/users', UserController::class);
Route::apiResource('/clients', ClientController::class);
Route::apiResource('/fournisseurs', FournisseurController::class);
Route::apiResource('/produits', ProduitController::class);
Route::apiResource('/commandes', CommandeController::class);
Route::apiResource('/roles', RoleController::class);
Route::apiResource('/ligneCommandes', LigneCommandeController::class);
Route::apiResource('/statusCommande', StatusCommandeController::class);









