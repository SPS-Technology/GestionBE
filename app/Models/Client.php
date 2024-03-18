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
    public function invoices() {
        return $this->hasMany(Facture::class, 'client_id');
    }
    public function banques() {
        return $this->hasMany(Banque::class, 'client_id');
    }


}
