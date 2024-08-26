<?php

namespace App\Http\Resources\Preorder;

use App\Models\Appointment;
use App\Models\Plan;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPlanByMonthResource extends JsonResource
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
            'id' => $this->CID,
            'name' => $this->CNAME,
            'plan' => Plan::whereMonth('created_at', $request->month)->where('CID', $this->CID)->count(),
            'meet' => Appointment::join('plans as p','p.PID','=', 'appointments.PID')->whereMonth('appointments.created_at', $request->month)->where('p.CID', $this->CID)->count(),
        ];
    }
}
