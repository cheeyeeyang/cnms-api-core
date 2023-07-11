<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Notplan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotplantApiController extends Controller
{
    public function add_not_plan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'CID' => 'required',
                'LAT' => 'required',
                'LNG' => 'required'
            ], [
                'CID.required' => 'ເລືອກລູກຄ້າກ່ອນ',
                'LAT.required' => 'ເລືອກສະຖານທີ່ກ່ອນ',
                'LNG.required' => 'ເລືອກສະຖານທີ່ກ່ອນ'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['message' => $error], 422);
            } else {
                $data = new Notplan();
                $data->NPDATE = Carbon::now();
                $data->UID = auth()->user()->UID;
                $data->CID = $request->CID;
                $data->LAT = $request->LAT;
                $data->LNG = $request->LNG;
                $data->save();
                return response()->json(['message' => 'ບັນທຶກແຜນນັດພົບສໍາເລັດແລ້ວ'], 200);
            }
        } catch (\Exception $ex) {
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
}
