<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function ligneCommandes() {
        return $this->hasMany(LigneCommande::class, 'commande_id', 'id');
    }

    public function statusCommandes() {
        return $this->hasMany(StatusCommande::class, 'commande_id', 'id');
    }
    // protected $fillable = [
    //     'reference', 
    //     'dateCommande',
    //     'status',
    //     'client_id',
    //     'user_id',
    // ];
    protected static function boot()
    {
        parent::boot();

        // Define a deleting event to delete related records
        static::deleting(function ($commande) {
            $commande->ligneCommandes()->delete();
            $commande->statusCommandes()->delete();
        });
    }
}