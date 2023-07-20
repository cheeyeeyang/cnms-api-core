<?php

namespace App\Http\Resources\PreOrder;

use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class GetPreorderByEmployeeResource extends JsonResource
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
            'UID' => $this->UID,
            'employee' => Employee::whereIn('EMPID', User::where('UID', $this->UID)->pluck('EMPID'))->first(),
            'qty' => strval(Order::where('UID', $this->UID)->count()),
            'freeqty' => OrderDetail::where('UID', $this->UID)->sum('FREEQTY')
        ];
    }
}
