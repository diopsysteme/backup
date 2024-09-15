<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $guarded=['id'];
    public function dette()
    {
        return $this->belongsTo(Dette::class);
    }

    /**
     * Relation entre Payment et User (si applicable, si un utilisateur fait le paiement).
     * Un paiement appartient à un utilisateur.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
