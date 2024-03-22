<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ligneentrercompte extends Model
{
    use HasFactory;
    protected $fillable =[
        'client_id',
        'id_facture',
        'avance',
    ];
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function facture()
    {
        return $this->belongsTo(Facture::class);
    }

}
