<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ligne_Bon_Entre extends Model
{
    use HasFactory;
    protected $fillable = ['produit_id', 'quantite','N_lot', 'id_bon_Entre','N_lot'];

}
