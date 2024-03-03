<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteClient extends Model
{
    use HasFactory;
    protected $guarded=[]; 

    public function zone() {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
    public function client() {
        return $this->belongsTo(Client::class);
    }
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
