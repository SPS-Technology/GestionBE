<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock_magasin extends Model
{
    use HasFactory;


    protected $table = 'stock_magasins';

    protected $fillable = [
        'produit_id',
        'date',
        'quantite',
        'n_lot',
        'nom_fournisseur',
    ];

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }
}
