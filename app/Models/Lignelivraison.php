<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lignelivraison extends Model
{
    use HasFactory;
    protected $fillable = ['produit_id', 'quantite', 'id_bon_Livraison'];

}
