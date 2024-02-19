<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $guarded=[]; 

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lignesCommandes()
    {
        return $this->hasMany(LigneCommande::class, 'commande_id');
    }

    public function statusCommande()
    {
        return $this->hasOne(StatusCommande::class, 'commande_id');
    }
}
