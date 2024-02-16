<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ProduitController;
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

Route::get('clients', [ClientController::class, 'index'])->middleware('can:view,App\Models\Client');
Route::post('clients', [ClientController::class, 'store'])->middleware('can:add,App\Models\Client');
Route::get('clients/{client}', [ClientController::class, 'show'])->middleware('can:view,client');
Route::put('clients/{client}', [ClientController::class, 'update'])->middleware('can:modify,client');
Route::delete('clients/{client}', [ClientController::class, 'destroy'])->middleware('can:delete,client');

// Fournisseurs
Route::get('fournisseurs', [FournisseurController::class, 'index'])->middleware('can:view,App\Models\Fournisseur');
Route::post('fournisseurs', [FournisseurController::class, 'store'])->middleware('can:add,App\Models\Fournisseur');
Route::get('fournisseurs/{fournisseur}', [FournisseurController::class, 'show'])->middleware('can:view,fournisseur');
Route::put('fournisseurs/{fournisseur}', [FournisseurController::class, 'update'])->middleware('can:modify,fournisseur');
Route::delete('fournisseurs/{fournisseur}', [FournisseurController::class, 'destroy'])->middleware('can:delete,fournisseur');

// Produits
Route::get('produits', [ProduitController::class, 'index'])->middleware('can:view,App\Models\Produit');
Route::post('produits', [ProduitController::class, 'store'])->middleware('can:add,App\Models\Produit');
Route::get('produits/{produit}', [ProduitController::class, 'show'])->middleware('can:view,produit');
Route::put('produits/{produit}', [ProduitController::class, 'update'])->middleware('can:modify,produit');
Route::delete('produits/{produit}', [ProduitController::class, 'destroy'])->middleware('can:delete,produit');
