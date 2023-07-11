<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function add(Request $request){  
        try {
            $validator = Validator::make($request->all(), [
            'PID' => 'required',
            'LAT' => 'required',
            'LNG' => 'required',
        ], [
                'PID.required' => 'ໃສ່ວັນທີກ່ອນ!',
            ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all()[0];
            return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
        } else {
            DB::beginTransaction();
                $data = new Appointment();
                $data->APDATE  = Carbon::now();
                $data->PID =  $request->PID;
                $data->LAT =  $request->LAT;
                $data->LNG =  $request->LNG;
                $data->UID = auth()->user()->UID;
                $data->save();
                $plan = Plan::find($data->PID);
                $plan->status ='success';
                $plan->update();
                DB::commit();
                return response()->json(['status' => 'true', 'message' => "ເພີ່ມຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        }
    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
    }
}
public function get(){
    return response([
        'data' => Appointment::get()
    ],200);
}
public function update(Request $request, $id){
    try{
        $data = Appointment::where('APID',$id)->first();
        $data->TRACK  = $request->TRACK;
        $data->PID =  $request->PID;
        $data->update();
        return response()->json(['status' => 'true', 'message' => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
    }catch(\Exception $ex){
        return response()->json(['status' => 'false', 'message' => $ex], 500);
    }
}
public function delete($id){
    try{
        $data = Appointment::where('APID',$id)->first();
        $data->delete();
        return response()->json(['status' => 'true', 'message' => "ລືບຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
    }catch(\Exception $ex){
        return response()->json(['status' => 'false', 'message' => $ex], 500);
    }
}
}
