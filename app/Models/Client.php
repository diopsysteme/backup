<?php

namespace App\Models;

use App\Enums\CategoryEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'surnom',
        'telephone',
        'address',
        'qrcode',
        'max_montant',
        'category_id',
    ];

    // Attributs non mass-assignables
    // protected $guarded = [
    //     'user_id',
    // ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function demandes(){
        return $this->hasMany(Demande::class);
    }
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function getCategoryLabelAttribute(){
        return $this->category->libelle;
    }
    public function getSoldeAttribute(){
        $cummule = 0;
        foreach($this->dettes as $dette){
            // dd($dette);
            if(!$dette->etat_solde){
                $cummule += $dette->montant;
            }
        }
        return $cummule;
    }
    public function getDettesCountAttribute(){
        return $this->dettes->filter(function($dette) {
            return !$dette->etat_solde;
        })->count();
    }
    public function getHasUserAttribute(){
        return (bool) $this->user;
    }

    public function getHasDemandeAttribute(){
        return (bool) $this->demandes;
    }
    public function getHasDemandeEnCoursAttribute(){
        return (bool) $this->demandes->where('etat', 'En cours')->count();
    }
    public function getCanSilverAttribute(){
        // dd($this->solde);
        return $this->max_montant > $this->solde;
    }
    public  function getCanBronzeAttribute(){
        return $this->dettes_count==0;
    }
    public function getCanGoldAttribute(){
        // dd($this->category_label);
        return $this->category_label==CategoryEnum::Gold->value;
    }


    public function dettes()
    {
        return $this->hasMany(Dette::class);
    }
    public function scopeFilterByRequest($query, $request)
    {
        if ($request->has('compte')) {
            $comptes = $request->input('compte');
            if ($comptes === 'oui') {
                $query->has('user');
            } elseif ($comptes === 'non') {
                $query->doesntHave('user');
            }
        }
        if ($request->has('active')) {
            $active = $request->input('active') === 'oui' ? true : false;
            $query->whereHas('user', function ($q) use ($active) {
                $q->where('active', $active);
            });
        }
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        return $query;
    }

    protected static function booted()
    {
        static::addGlobalScope('byPhone', function (Builder $builder) {
            if (request()->has('telephon')) {
                $telephone = request()->input('telephone');
                $builder->where('telephone', 'like', '%' . $telephone . '%');
            }
        });
    }
    public function scopeWithoutPhoneFilter($query)
    {
        return $query->withoutGlobalScope('byPhone');
    }

    
}
