<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Validator;
class CustomerApiController extends Controller
{
    public function add(Request $request){  
        try {
            $validator = Validator::make($request->all(), [
            'CNAME' => 'required',
            'TEL' => 'required|numeric:max:11|unique:customers',
            'LOCATION' => 'required',
            'ZID' => 'required'
        ], [
                'CNAME.required' => 'ໃສ່ຊື່ພະນັກງານກ່ອນ!',
                'TEL.required' => 'ໃສ່ເບີກ່ອນ!',
                'TEL.unique' => 'ເບີໂທນີ້ມີໃນລະບົບແລ້ວ!',
                'TEL.unique' => 'ເບີໂທນີ້ມີໃນລະບົບແລ້ວ!',
                'LOCATION.required' => 'ໃສ່ທີຢູ່ລູກຄ້າກ່ອນ!',
                'TEL.max' => 'ເບີໂທສູງສຸດ 11 ຕົວ!',
                'ZID.required' => 'ເລືອກ Zone ກ່ອນ!',
            ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all()[0];
            return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
        } else {
                $data = new Customer();
                $data->CNAME  = $request->CNAME;
                $data->TEL  = $request->TEL;
                $data->LOCATION  = $request->LOCATION;
                $data->ZID  = $request->ZID;
                $data->save();
                return response()->json(['status' => 'true', 'message' => "ເພີ່ມຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
    }
}
public function get(){
    return response([
        'data' => Customer::select('customers.*','z.ZNAME')->join('zones as z','z.ZID', '=', 'customers.ZID')->orderBy('CID', 'DESC')->get()
    ],200);
}
public function update(Request $request){
    try{
        $data = Customer::where('CID', $request->CID)->first();
        $data->CNAME  = $request->CNAME;
        $data->TEL  = $request->TEL;
        $data->LOCATION  = $request->LOCATION;
        $data->ZID = $request->ZID;
        $data->update();
        return response()->json(['status' => 'true', 'message' => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
    }catch(\Exception $ex){
        return response()->json(['status' => 'false', 'message' => $ex], 500);
    }
}
public function delete(Request $request){
    try{
        $data = Customer::where('CID', $request->CID)->first();
        $data->delete();
        return response()->json(['status' => 'true', 'message' => "ລືບຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
    }catch(\Exception $ex){
        return response()->json(['status' => 'false', 'message' => $ex], 500);
    }
}
}