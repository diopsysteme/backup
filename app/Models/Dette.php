<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Observers\DetteObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
#[ObservedBy([DetteObserver::class])]
class Dette extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'montant', 'client_id', 'user_id','date_echeance'];
    protected $casts = [
        'date' => 'datetime:Y-m-d',
        'montant' => 'float',
    ];
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->translatedFormat('l d F Y');
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function payement()
    {
        return $this->hasMany(Payment::class);
    }
    public function getEtatSoldeAttribute()
    {
        $montantTotalPaye = $this->payement()->sum('montant');

        return $montantTotalPaye >= $this->montant;
    }
    public function getMontantVerseAttribute()
    {
        return $this->payement()->sum('montant');
    }

    public function getMontantRestantAttribute()
    {
        return $this->montant - $this->montant_verse;
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_dette')
            ->withPivot('qte_vente', 'prix_vente')
            ->withTimestamps();
    }
}
