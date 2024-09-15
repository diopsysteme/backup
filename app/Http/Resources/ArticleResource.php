<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom, 
            'description' => $this->description, 
            'prix_par_defaut' => $this->prix, 
            'quantite_en_stock' => $this->qtstock, 
            'created_at' => $this->created_at->toDateTimeString(),
            'qte_vente' => $this->whenPivotLoaded('article_dette', function () {
                return $this->pivot->qte_vente;
            }),
            'prix_vente' => $this->whenPivotLoaded('article_dette', function () {
                return $this->pivot->prix_vente;
            }),
        ];
    }
}
