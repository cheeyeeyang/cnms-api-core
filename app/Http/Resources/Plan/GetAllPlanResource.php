<?php

namespace App\Http\Resources\Plan;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllPlanResource extends JsonResource
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
            'PID' => $this->PID,
            'UID' => $this->UID,
            'CID' => $this->CID,
            'TARGET' => $this->TARGET,
            'ACTUAL' => $this->ACTUAL,
            'PERCENTAGE' => $this->PERCENTAGE,
            'LAT' => $this->LAT,
            'LNG' => $this->LNG,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'TYPE' => $this->TYPE,
            'STATUS' => $this->STATUS,
            'user' => User::select('emp.*')->where('UID', $this->UID)->join('employees as emp', 'emp.EMPID', '=', 'users.EMPID')->first(),
            'customer' => Customer::where('CID', $this->CID)->first(),
            'appointment' => Appointment::where('PID', $this->PID)->first()
        ];
    }
}
