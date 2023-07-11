<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
class AppointmentController extends Controller
{
    public function add(Request $request){  
        try {
            $validator = Validator::make($request->all(), [
            'TRACK' => 'required',
            'PID' => 'required',
        ], [
                'TRACK.required' => 'ໃສ່ຊື່ກ່ອນ!',
                'PID.required' => 'ໃສ່ວັນທີກ່ອນ!',
            ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all()[0];
            return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
        } else {
                $date = new Appointment();
                $data->TRACK  = $request->TRACK;
                $data->PID =  $request->PID;
                $data->save();
                return response()->json(['status' => 'true', 'message' => "ເພີ່ມຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        }
    } catch (\Exception $e) {
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
