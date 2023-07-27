<?php

namespace App\Http\Resources\Alert;

use App\Models\AlertTransaction;
use Illuminate\Http\Resources\Json\JsonResource;

class AlertResource extends JsonResource
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
            'AID' => $this->AID,
            'ANAME' => $this->ANAME,
            'DATEISUE' => $this->DATEISUE,
            'DATEALERT' => $this->DATEALERT,
            'CONTENT' => $this->CONTENT,
            'NOTE' => $this->NOTE,
            'DE' => $this->DEL,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'count' => AlertTransaction::where('AID', $this->AID)->where('UID', auth()->user()->UID)->count()
        ];
    }
}
