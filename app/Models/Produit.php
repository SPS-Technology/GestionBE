<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Produit extends Model
{
    use HasFactory;
    protected $guarded=[]; 

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }
    public function calibre()
    {
        return $this->belongsTo(Calibre::class, 'calibre_id');
    }
}