<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;
use Illuminate\Support\Facades\Validator;

class ZoneApiController extends Controller
{
    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ZNAME' => 'required|unique:zones,ZNAME',
                'DESCRIPTION' => 'required',
            ], [
                'ZNAME.required' => 'ໃສ່ຊື່ກ່ອນ!',
                'DESCRIPTION.required' => 'ໃສ່ວັນທີກ່ອນ!',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            } else {
                $data = new Zone();
                $data->ZNAME  = $request->ZNAME;
                $data->DESCRIPTION =  $request->DESCRIPTION;
                $data->save();
                return response()->json(['status' => 'true', 'message' => "ເພີ່ມຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
    public function get()
    {
        return response([
            'data' => Zone::orderBy('ZID', 'DESC')->get()
        ], 200);
    }
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'ZNAME' => 'required',
                'DESCRIPTION' => 'required',
            ], [
                'ZNAME.required' => 'ໃສ່ຊື່ Zone ກ່ອນ!',
                'DESCRIPTION.required' => 'ໃສ່ວັນທີກ່ອນ!',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            } else {
                $data = Zone::where('ZID', $request->ZID)->first();
                $data->ZNAME  = $request->ZNAME;
                $data->DESCRIPTION =  $request->DESCRIPTION;
                $data->update();
                return response()->json(['status' => 'true', 'message' => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }
    public function delete(Request $request)
    {
        try {
            $data = Zone::where('ZID', $request->id)->first();
            $data->delete();
            return response()->json(['status' => 'true', 'message' => "ລືບຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }
}
