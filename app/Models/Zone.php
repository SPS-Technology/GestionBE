<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;
    public function Clients() {
        return $this->hasMany(Client::class);
    }

    public function siteClients() {
        return $this->hasMany(SiteClient::class);
    }
}
