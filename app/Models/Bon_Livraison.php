<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bon_livraison extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'date',
        'validation_offer',
        'modePaiement',
        'status',
        'type',
        'client_id',
        'user_id',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function lignelivraison()
    {
        return $this->hasMany(Lignelivraison::class, 'id_bon_Livraison', 'id');
    }
}
