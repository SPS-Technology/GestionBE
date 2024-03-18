<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banque extends Model
{
    use HasFactory;
    protected $fillable =[
        'client_id',
        'numero_cheque',
        'datee',
        'mode_de_paiement',
        'Montant',
        'Status',
        'remarque',
        ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }



}
