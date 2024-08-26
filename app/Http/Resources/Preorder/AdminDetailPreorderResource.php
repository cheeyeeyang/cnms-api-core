<?php

namespace App\Http\Resources\Preorder;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminDetailPreorderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'PDNAME' => $this->product->PDNAME ?? '',
            'UNIT' => $this->product->unit->name ?? '',
            'QTY' => $this->QTY,
            'PRICE' => $this->PRICE,
        ];
    }
}
