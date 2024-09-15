<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'dette_id' => $this->dette_id,
            'montant' => $this->montant,
            'date_paiement' => $this->date_paiement,
            'moyen_paiement' => $this->moyen_paiement,
            'user_id' => $this->user_id,
        ];
    }
}
