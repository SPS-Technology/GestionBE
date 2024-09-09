<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bon_Sourtie extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'source',
        'date',
        'emetteur',
        'recepteur',
        'type',
    ];

    public function ligneBonSortie()
    {
        return $this->hasMany(Ligne_Bon_Sourtie::class, 'id_bon_Sourtie', 'id');
    }
}
