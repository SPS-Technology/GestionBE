<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ligne_Bon_Sourtie extends Model
{
    use HasFactory;
    protected $fillable = ['produit_id', 'quantite','N_lot', 'id_bon_Sourtie','N_lot'];

}
