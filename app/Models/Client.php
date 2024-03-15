<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $guarded=[]; 

    
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function zone() {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function siteclients() {
        return $this->hasMany(SiteClient::class, 'client_id');
    }
    public function devis() {
        return $this->hasMany(Devis::class, 'client_id');
    }
    public function facture() {
        return $this->hasMany(Devis::class, 'client_id');
    }


}
