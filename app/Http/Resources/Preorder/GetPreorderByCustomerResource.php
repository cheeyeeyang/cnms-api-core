<?php

namespace App\Http\Resources\PreOrder;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPreorderByCustomerResource extends JsonResource
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
            'ORID' => $this->ORID,
            'CID' => $this->CID,
            'customer' => Customer::select('CNAME','TEL')->where('CID',$this->CID)->first(),
            'created_at' => $this->created_at
        ];
    }
}
