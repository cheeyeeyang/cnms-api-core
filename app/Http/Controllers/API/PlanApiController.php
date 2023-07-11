<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Plan\GetAllPlanResource;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Plan;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlanApiController extends Controller
{
    public function add_plan(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'LAT' => 'required',
                'LNG' => 'required'
            ], [
                'LAT.required' => 'ເລືອກສະຖານທີ່ກ່ອນ',
                'LNG.required' => 'ເລືອກສະຖານທີ່ກ່ອນ'
            ]);
            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['message' => $error], 422);
            } else {
                if($request->type =='not_plan'){
                    DB::beginTransaction();
                    $cus = Customer::create([
                        'CNAME' => $request->CNAME,
                        'TEL' => $request->TEL,
                        'LAT' => $request->LAT,
                        'LNG' => $request->LNG,
                        'ZID' => 1
                    ]);
                    $data = new Plan();
                    $data->UID = auth()->user()->UID;
                    $data->CID = $cus->CID;
                    $data->TARGET = 1;
                    $data->LAT = $request->LAT;
                    $data->LNG = $request->LNG;
                    $data->TYPE = $request->type;
                    $data->save();
                }else{
                    $data = new Plan();
                    $data->UID = auth()->user()->UID;
                    $data->CID = $request->CID;
                    $data->TARGET = 1;
                    $data->LAT = $request->LAT;
                    $data->LNG = $request->LNG;
                    $data->save();
                }
                DB::commit();
                return response()->json(['message' => 'ບັນທຶກແຜນນັດພົບສໍາເລັດແລ້ວ'], 200);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json(['message' => $ex->getMessage()], 500);
        }
    }
    public function get_all_plan(){
        return response()->json([
            'data' => GetAllPlanResource::collection(Plan::orderBy('PID', 'DESC')->where('UID', auth()->user()->UID)->get())
        ],200);
    }
    public function get_plan_by_employee(){
        return response()->json([
            'data' => User::select('users.*','e.EMPNAME','e.EMPPHONE',DB::raw("(SELECT SUM(plans.TARGET) from plans where UID = users.UID) as TARGET"))->join('employees as e','users.EMPID', '=', 'e.EMPID')->get()
        ],200);
    }
    public function get_plan_detail_employee($id){
        return response()->json(['data' => GetAllPlanResource::collection(Plan::orderBy('PID', 'DESC')->where('UID', $id)->get())], 200);
    }
}
