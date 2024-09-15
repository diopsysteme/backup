<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Article extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function dettes()
    {
        return $this->belongsToMany(Dette::class, 'article_dette')
                    ->withPivot('qte_vente', 'prix_vente')
                    ->withTimestamps();
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Scope pour filtrer par disponibilité de stock
    public function scopeDisponible($query, $disponible)
    {
        if ($disponible === 'oui') {
            return $query->where('qtstock', '>', 0);
        } elseif ($disponible === 'non') {
            return $query->where('qtstock', '<=', 0);
        }
        return $query;
    }

    public function scopeByLabel($query, $label)
    {
        return $query->where('label', 'like', '%' . $label . '%');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeFilterByRequest($query, $request)
    {
        if ($request->missing('all')) {
            $query->ownedBy(Auth::id());
        }

        if ($request->has('disponible')) {
            $query->disponible($request->input('disponible'));
        }

        if ($request->has('labell')) {
            dd($request->input('labell'));
            $query->byLabel($request->input('labell'));
        }

        if ($request->has('categorys')) {
            $query->byCategory($request->input('categoryss'));
        }

        return $query;
    }
}
