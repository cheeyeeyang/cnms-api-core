<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'PDID' => $this->PDID,
            'PDNAME' => $this->PDNAME,
            'CHEMISTRY_NAME' => $this->CHEMISTRY_NAME,
            'UNIT_ID' => Unit::where('id', $this->UNIT_ID)->first(),
            'CATE_ID' => Category::where('id', $this->CATE_ID)->first(),
            'BUY_PRICE' => $this->BUY_PRICE,
            'SALE_PRICE' => $this->SALE_PRICE,
            'SUPPLIER' => $this->SUPPLIER,
            'DESCRIPTION' => $this->DESCRIPTION,
            'IMAGE' => $this->IMAGE,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
