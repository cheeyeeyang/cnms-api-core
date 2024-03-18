<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Order;
use App\Models\Tartget;
use Illuminate\Http\Request;

class TargetApiController extends Controller
{
    public function add(Request $request)
    {
        try {
            $data = new Tartget();
            $data->UID = auth()->user()->UID;
            $data->TARGET = $request->target;
            $data->AMOUNT = $request->amount;
            $data->PERCENTAGE = $request->percent;
            $data->save();
            return response()->json(['status' => 'true', 'message' => "ສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage()], 500);
        }
    }
    public function get()
    {
        return response()->json(['data' =>  Tartget::where('UID', auth()->user()->UID)->orderBy('TGID', 'desc')->get()], 200);
    }
    public function getTarget()
    {
        return response()->json(['data' =>  Tartget::where('UID', auth()->user()->UID)->orderBy('TGID', 'desc')->first()], 200);
    }
    public function getDashboard()
    {
        $data = [];
        $total_preorder = 0;
        $total_appointment = 0;
        $cal_target = Tartget::where('UID', auth()->user()->UID)->orderBy('id', 'desc')->first();
        if ($cal_target) {
            $total_preorder = (Order::where('UID', auth()->user()->UID)->sum('AMOUNT') * $cal_target->PERCENTAGE) / $cal_target->AMOUNT;
            $total_appointment = (Appointment::where('UID', auth()->user()->UID)->sum('AMOUNT') * $cal_target->PERCENTAGE) / $cal_target->AMOUNT;
        }
        $data[] = [
            'total_preorder' => $total_preorder,
            'total_appointment' => $total_appointment
        ];
        return response()->json(['data' =>  $data], 200);
    }
}
