<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encaissement extends Model
{
    use HasFactory;

    protected $fillable = [
        'referencee',
        'date_encaissement',
        'montant_total',
        'comptes_id',
        'type_encaissement',
    ];

    // Relation avec le modèle Compte
    public function compte()
    {
        return $this->belongsTo(Comptes::class, 'comptes_id');
    }
}
