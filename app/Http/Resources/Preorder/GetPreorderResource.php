<?php

namespace App\Http\Resources\Preorder;

use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Models\OrderDetail;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPreorderResource extends JsonResource
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
            'UID' => $this->UID,
            'CID' => $this->CID,
            'AMOUNT' => $this->AMOUNT,
            'created_at' => date('d/m/Y', strtotime($this->created_at)),
            'updated_at' => $this->updated_at,
            'customer' => new CustomerResource(Customer::where('CID', $this->CID)->first()),
            'items' => OrderDetail::select('order_details.*', 'p.PDNAME AS PDNAME')->join('products as p', 'p.PDID', '=', 'order_details.PDID')->where('order_details.ORID', $this->ORID)->get()
        ];
    }
}
