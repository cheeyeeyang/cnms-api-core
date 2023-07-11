<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Assign;
use DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Employee\GetEmployeeResource;
class EmployeeApiController extends Controller
{
    public function add(Request $request){  
        try {
            $validator = Validator::make($request->all(), [
            'EMPNAME' => 'required',
            'EMPPHONE' => 'required|numeric:max:11|unique:employees',
            'ZID' => 'required'
        ], [
                'EMPNAME.required' => 'ໃສ່ຊື່ພະນັກງານກ່ອນ!',
                'EMPPHONE.required' => 'ໃສ່ເບີກ່ອນ!',
                'EMPPHONE.unique' => 'ເບີໂທນີ້ມີໃນລະບົບແລ້ວ!',
                'EMPPHONE.unique' => 'ເບີໂທນີ້ມີໃນລະບົບແລ້ວ!',
                'EMPPHONE.max' => 'ເບີໂທສູງສຸດ 11 ຕົວ!',
                'ZID.required' => 'ເລືອກ Zone ກ່ອນ!',
            ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all()[0];
            return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
        } else {
                try{
                    DB::beginTransaction();
                    $data = new Employee();
                    $data->EMPNAME  = $request->EMPNAME;
                    $data->EMPPHONE  = $request->EMPPHONE;
                    $data->save();
                    $assign = new Assign();
                    $assign->EMPID = $data->EMPID;
                    $assign->ZID = $request->ZID;
                    $assign->save();
                    DB::commit();
                    return response()->json(['status' => 'true', 'message' => "ເພີ່ມຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
                }catch(\Exception $ex){
                   DB::rollBack();
                   return response()->json(['status' => 'false', 'message' => "ມີບາງຢ່າງຜິດພາດ!"], 405);
                }
                
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
    }
}
public function get(){
    return response([
        'data' => Assign::select('assigns.*','e.EMPNAME','e.EMPPHONE', 'z.ZNAME')->join('employees as e', 'e.EMPID', '=', 'assigns.EMPID')->join('zones as z','z.ZID', '=', 'assigns.ZID')->get()
    ],200);
}
public function update(Request $request){
    try{
        $data = Employee::where('EMPID', $request->EMPID)->first();
        $data->EMPNAME  = $request->EMPNAME;
        $data->EMPPHONE = $request->EMPPHONE;
        $data->update();
        return response()->json(['status' => 'true', 'message' => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
    }catch(\Exception $ex){
        return response()->json(['status' => 'false', 'message' => $ex], 500);
    }
}
public function delete(Request $request){
    try{
        $check = User::where('EMPID', $request->EMPID)->first();
        if($check){
            return response([
                'message' => 'ພະນັກງານຜູ້ນີ້ຍັງມີການໃຊ້ງານຮ່ວມກັນກັບຕາຕະລາງອື່ນ!'
            ], 403);
        }
        $data = Employee::where('EMPID', $request->EMPID)->first();
        $data->delete();
        return response()->json(['status' => 'true', 'message' => "ລືບຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
    }catch(\Exception $ex){
        return response()->json(['status' => 'false', 'message' => $ex], 500);
    }
}
}