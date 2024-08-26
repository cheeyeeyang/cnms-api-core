<?php

namespace App\Http\Resources\Preorder;

use App\Models\Assign;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Tartget;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminPreorderResource extends JsonResource
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

        $target = $uid ? Tartget::whereMonth('created_at', date('m'))->where('UID', $uid)->sum('AMOUNT') : 0;
        $actual = $uid ? Order::whereMonth('created_at', date('m'))->where('UID', $uid)->sum('AMOUNT') : 0;
        $achievementPercentage = $target > 0 ? ($actual / $target) * 100 : 0;

        $zone = Assign::join('zones as z', 'z.ZID', '=', 'assigns.ZID')
            ->where('assigns.EMPID', $this->EMPID)
            ->select('z.ZNAME')
            ->first()->ZNAME ?? '';

        $items = OrderDetail::join('orders as or', 'or.ORID', '=', 'order_details.ORID')
            ->whereMonth('or.created_at', date('m'))
            ->where('or.UID', $uid)
            ->select('order_details.PDID', 'order_details.QTY', 'order_details.PRICE')->get();

        return [
            'id' => $this->EMPID,
            'name' => $this->EMPNAME,
            'zone' => $zone,
            'target' => $target,
            'actual' => $actual,
            'archived' => $achievementPercentage,
            'items' => AdminDetailPreorderResource::collection($items)
        ];
    }
}
