<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Plan\GetAllPlanResource;
use App\Models\Appointment;
use App\Models\Assign;
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
                if ($request->type == 'not_plan') {
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
                    $data->TARGET = $request->target;
                    // $data->AMOUNT = $request->amount;
                    $data->PERCENTAGE = $request->percent;
                    $data->LAT = $request->LAT;
                    $data->LNG = $request->LNG;
                    $data->TYPE = $request->type;
                    $data->save();
                } else {
                    $data = new Plan();
                    $data->UID = auth()->user()->UID;
                    $data->CID = $request->CID;
                    $data->TARGET = $request->target;
                    // $data->AMOUNT = $request->amount;
                    $data->PERCENTAGE = $request->percent;
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
    public function get_all_plan()
    {
        return response()->json([
            'data' => GetAllPlanResource::collection(Plan::orderBy('PID', 'DESC')->where('UID', auth()->user()->UID)->get())
        ], 200);
    }
    public function get_plan_by_employee()
    {
        return response()->json([
            'data' => User::select('users.*', 'e.EMPNAME', 'e.EMPPHONE', DB::raw("(SELECT SUM(plans.TARGET) from plans where UID = users.UID) as TARGET"))->join('employees as e', 'users.EMPID', '=', 'e.EMPID')->get()
        ], 200);
    }
    public function get_plan_detail_employee($id)
    {
        return response()->json(['data' => GetAllPlanResource::collection(Plan::orderBy('PID', 'DESC')->where('UID', $id)->get())], 200);
    }
    public function get_history_plan_by_employee()
    {
        $data  = [];
        $get_data = Plan::select(DB::raw('DATE(created_at) as date'))
            ->groupBy('date')->where('UID', auth()->user()->UID)->orderBy('date', 'DESC')->get();
        $count_customer = 0;
        $count_customer_meet = 0;
        foreach ($get_data as $item) {
            $count_customer = Plan::whereDate('created_at', $item->date)->count();
            $count_customer_meet =  Plan::whereDate('created_at', $item->date)->where('STATUS', 'success')->count();
            if (!empty(auth()->user()->employee)) {
                $zone = Assign::where('EMPID', auth()->user()->employee->EMPID)->first();
            } else {
                $zone = '';
            }
            $data[] = [
                'created_at' => date('Y-m-d', strtotime($item->date)),
                'qty_customer' => $count_customer,
                'qty_customer_meet' => $count_customer_meet,
                'percent' => $count_customer > 0 ? number_format(($count_customer_meet  / $count_customer) * 100) : 0,
                'name' => !empty(auth()->user()->employee->EMPNAME) ?  auth()->user()->employee->EMPNAME : '',
                'zone' => !empty($zone) ? $zone->zone->ZNAME : ''
            ];
        }
        return response(['data' => $data], 200);
    }
    public function get_history_plan_by_employee_by_date(Request $request)
    {
        $date =  $request->date;
        $data  = [];
        $get_data = Plan::where('UID', auth()->user()->UID)->whereDate('created_at', $date)->get();
        $qty_meet = 0;
        foreach ($get_data as $item) {
            $appointment = Appointment::where('PID', $item->PID)->first();
            if ($appointment) {
                if (($appointment->LAT == $item->LAT) && $appointment->LNG == $item->LNG) {
                    $qty_meet = 1;
                }
            }
            $data[] = [
                'customer' => $item->customer->CNAME ?? '',
                'qty' => 1,
                'qty_meet' => $qty_meet,
            ];
        }
        return response(['data' => $data], 200);
    }
}
