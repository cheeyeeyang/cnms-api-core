<?php

namespace App\Http\Resources\Employee;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Assign;
use App\Models\Employee;
use App\Models\Zone;
class GetEmployeeResource extends JsonResource
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
            'employee' => Employee::where('EMPID',$this->EMPID)->first(),
            'zone' => Zone::where('ZID',$this->ZID)->first(),
        ];
    }
}
