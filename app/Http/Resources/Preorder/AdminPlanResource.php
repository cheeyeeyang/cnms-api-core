<?php

namespace App\Http\Resources\Preorder;

use App\Models\Appointment;
use App\Models\Assign;
use App\Models\Plan;
use App\Models\Tartget;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = User::where('EMPID', $this->EMPID)->first();
        $uid = $user ? $user->UID : null;

        $target = $uid ? Tartget::whereMonth('created_at', date('m'))->where('UID', $uid)->where('AMOUNT', '<=', 0)->sum('TARGET') : 0;
        $actual = $uid ? Appointment::whereMonth('created_at', date('m'))->where('UID', $uid)->count() : 0;
        $achievementPercentage = $target > 0 ? ($actual / $target) * 100 : 0;

        $zone = Assign::join('zones as z', 'z.ZID', '=', 'assigns.ZID')
            ->where('assigns.EMPID', $this->EMPID)
            ->select('z.ZNAME')
            ->first()->ZNAME ?? '';
        return [
            'id' => $user->UID ?? 0,
            'name' => $this->EMPNAME,
            'zone' => $zone,
            'target' => $target,
            'actual' => $actual,
            'archived' => $achievementPercentage,
        ];
    }
}
