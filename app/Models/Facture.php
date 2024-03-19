<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function devis()
    {
        return $this->belongsTo(Devis::class, 'id_devis');
    }
    public function lignedevis()
    {
        return $this->hasMany(LigneDevis::class, 'id_devis', 'id');
    }
    public function clients()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function ligneFacture()
    {
        return $this->hasMany(LigneFacture::class, 'id_facture', 'id');
    }


}