<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bon_Entre extends Model
{
    use HasFactory;
      
    protected $fillable = [
        'reference',
        'source',
        'date',
        'emetteur',
        'recepteur',
        'type'
    ];

    public function ligneBonEntre()
    {
        return $this->hasMany(ligne_Bon_Entre::class, 'id_bon_Entre', 'id');
    }
}
