<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alert;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class AlertApiController extends Controller
{
    public function add(Request $request){  
            try {
                $validator = Validator::make($request->all(), [
                'ANAME' => 'required',
                'DATEALERT' => 'required',
                'CONTENT' => 'required',
            ], [
                    'ANAME.required' => 'ໃສ່ຊື່ກ່ອນ!',
                    'DATEALERT.required' => 'ໃສ່ວັນທີແຈ້ງເຕືອນກ່ອນ',
                    'CONTENT.required' => 'ໃສ່ເປີເຊັນ%ກ່ອນ',
                ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            } else {
                    $data = new Alert();
                    $data->ANAME  = $request->ANAME;
                    $data->DATEISUE =  Carbon::today();
                    $data->DATEALERT =  $request->DATEALERT;
                    $data->CONTENT =  $request->CONTENT;
                    $data->NOTE =  $request->NOTE;
                    $data->save();
                    return response()->json(['status' => 'true', 'message' => "ເພີ່ມຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
    public function get(){
        return response([
            'data' => Alert::get()
        ],200);
    }
    public function update(Request $request, $id){
        try{
            $data = Alert::where('AID', $id)->first();
            $data->ANAME  = $request->ANAME;
            $data->DATEALERT =  $request->DATEALERT;
            $data->CONTENT =  $request->CONTENT;
            $data->NOTE =  $request->NOTE;
            $data->update();
            return response()->json(['status' => 'true', 'message' => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        }catch(\Exception $ex){
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }
    public function delete($id){
        try{
            $data = Alert::where('AID', $id)->first();
            $data->delete();
            return response()->json(['status' => 'true', 'message' => "ລືບຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        }catch(\Exception $ex){
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }

}
