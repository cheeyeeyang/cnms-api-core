<?php

namespace App\Http\Resources;

use App\Models\Zone;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'CID' => $this->CID,
            'CNAME' => $this->CNAME,
            'TEL' => $this->TEL,
            'VILLNAME' => $this->VILLNAME,
            'DISNAME' => $this->DISNAME,
            'PRONAME' => $this->PRONAME,
            'WORK_PLACE' => $this->WORK_PLACE,
            'BOD' => $this->BOD,
            'NOTE' => $this->NOTE,
            'ZID' => $this->ZID,
            'LAT' => $this->LAT,
            'LNG' => $this->LNG,
            'zone' => Zone::select(
                'ZID',
                'ZNAME',
                'DESCRIPTION'
            )->where('ZID', $this->ZID)->first(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
