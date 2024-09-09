<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class oeuffini_semifini extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'eau_semifini',
        'entier_semifini',
        'janne_semifini',
        'blan_semifini',
        'LC_semifini',
        'oeufcongles_semifini',
        'entier_fini',
        'janne_fini',
        'blan_fini',
        'LC_fini',
        'oeufcongles_fini',
        'N_lot_eau_semifini',
        'N_lot_entier_semifini',
        'N_lot_janne_semifini',
        'N_lot_blan_semifini',
        'N_lot_LC_semifini',
        'N_lot_oeuf_congles_semifini',
        'N_lot_entier_fini',
        'N_lot_janne_fini',
        'N_lot_blan_fini',
        'N_lot_LC_fini',
        'N_lot_oeuf_congles_fini',
    ];
    
}
