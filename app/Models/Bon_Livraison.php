<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bon_Livraison extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function client(){
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'commande_id');
    }
    public function lignelivraison()
    {
        return $this->hasMany(LigneLivraisons::class, 'id_bon__livraisons', 'id');
    }
}
