<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Alert\AlertResource;
use Illuminate\Http\Request;
use App\Models\Alert;
use App\Models\AlertTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AlertApiController extends Controller
{
    public function add(Request $request)
    {
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
    public function get()
    {
        $data  =  Alert::where('DEL', 1)->orderBy('AID', 'DESC')->get();
        return response([
            'data' => AlertResource::collection($data)
        ], 200);
    }
    public function update(Request $request)
    {
        try {
            $data = Alert::where('AID', $request->AID)->first();
            $data->ANAME  = $request->ANAME;
            if ($request->DATEALERT) {
                $data->DATEALERT =  $request->DATEALERT;
            }
            if ($request->CONTENT) {
                $data->CONTENT =  $request->CONTENT;
            }
            if ($request->NOTE) {
                $data->NOTE =  $request->NOTE;
            }
            $data->update();
            return response()->json(['status' => 'true', 'message' => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }
    public function delete_alert(Request $request)
    {
        try {
            $data = Alert::where('AID', $request->AID)->first();
            $data->DEL = 0;
            $data->save();
            return response()->json(['status' => 'true', 'message' => "ລືບຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }
    public function confirm_alert_employee(Request $request)
    {
        try {
            DB::beginTransaction();
            if($request->AID){
                $data = new AlertTransaction();
                $data->AID = $request->AID;
                $data->UID = auth()->user()->UID;
                $data->save();
            }else{
                return response()->json(['message' => "ມີບາງຢ່າງຜິດພາດ!"], 200);
                return;
            }
            DB::commit();
            return response()->json(['status' => 'true', 'message' => "ຢືນຢືນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }
    public function count_alert_employee()
    {
        return response([
            'count' => AlertTransaction::where('UID', auth()->user()->UID)->count()
        ], 200);
    }
}
