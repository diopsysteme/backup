<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetteResource extends JsonResource
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
            'client_id' => $this->client_id,
            'client' => new ClientResource($this->whenLoaded('client')),
            'date' => $this->date,
            'montant' => $this->montant,
            'montant_restant' => $this->montant_restant,
            'montant_verse' => $this->montant_verse,
            'etat_solde' => $this->etat_solde,
            'user_id' => $this->user_id,
            'articles' => ArticleResource::collection($this->whenLoaded('articles')), 
            'payements' => PaymentResource::collection($this->whenLoaded('payement')),

        ];
    }
}
