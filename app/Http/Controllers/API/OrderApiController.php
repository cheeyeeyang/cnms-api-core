<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PreOrder\GetPreorderByCustomerResource;
use App\Http\Resources\PreOrder\GetPreorderByEmployeeResource;
use App\Http\Resources\Preorder\GetPreorderResource;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderApiController extends Controller
{
    public function preorder(Request $request)
    {
        try {
            $amount = 0;
            $items = $request->input('items');
            if (is_array($items)) {
                foreach ($items as $item) {
                    $amount += $item['total'];
                }
            } else {
                return response()->json(["message" => "ບໍ່ມີລາຍການສັ່ງຈອງ",], 401);
            }
            DB::beginTransaction();
            $data = new Order();
            $data->UID = auth()->user()->UID;
            $data->ORDATE = Carbon::today();
            $data->CID = $request->CID;
            $data->AMOUNT = $amount;
            $data->save();
            if (is_array($items)) {
                foreach ($items as $item) {
                    $order_detail = new OrderDetail();
                    $order_detail->ORID = $data->ORID;
                    $order_detail->UID = auth()->user()->UID;
                    $order_detail->PDID  = $item['id'];
                    $order_detail->QTY  = $item['qty'];
                    $order_detail->FREEQTY  = $item['freeqty'];
                    $order_detail->PRICE  = $item['price'];
                    $order_detail->save();
                }
            } else {
                return response()->json(["message" => "ບໍ່ມີລາຍການສັ່ງຈອງ",], 401);
            }
            DB::commit();
            return response()->json([
                'message' => 'ສັ່ງຈອງສໍາເລັດແລ້ວ'
            ], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }
    public function edit_preorder_detail(Request $request)
    {
        try {
            $amount = 0;
            $items = $request->input('items');
            if (is_array($items)) {
                foreach ($items as $item) {
                    $amount += $item['total'];
                }
            } else {
                return response()->json(["message" => "ບໍ່ມີລາຍການສັ່ງຈອງ",], 405);
                return;
            }
            DB::beginTransaction();
            $data =  Order::find($request->ORID);
            $data->AMOUNT = $amount;
            $data->update();
            if (is_array($items)) {
                foreach ($items as $item) {
                    $order_detail = OrderDetail::where('ODID', $item['orderdetailId'])->first();
                    if (!empty($order_detail)) {
                        $order_detail->QTY  = $item['qty'];
                        $order_detail->FREEQTY  = $item['freeqty'];
                        $order_detail->PRICE  = $item['price'];
                        $order_detail->update();
                    }
                }
            } else {
                return response()->json(["message" => "ບໍ່ມີລາຍການສັ່ງຈອງ",], 401);
            }
            DB::commit();
            return response()->json([
                'message' => 'ແກ້ໄຂສໍາເລັດແລ້ວ'
            ], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            // return response()->json([
            //     'message' => 'ມີບາງຢ່າງຜິດພາດ'
            // ], 500);
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }
    public function get_preorder()
    {
        return response()->json(['data' => GetPreorderResource::collection(Order::where('UID', auth()->user()->UID)->orderBy('ORID', 'desc')->get())], 200);
    }
    public function get_preorder_detail($id)
    {
        return response()->json(['data' => OrderDetail::select('order_details.*', 'p.PDNAME AS PDNAME')->join('products as p', 'p.PDID', '=', 'order_details.PDID')->where('order_details.UID', auth()->user()->UID)->where('order_details.ORID', $id)->get()], 200);
    }
    public function get_preorder_by_employee()
    {
        // return response()->json(['data' => GetPreorderByEmployeeResource::collection(Order::select('UID')->groupBy('UID')->get())], 200);
        $selectdata  = Order::select('UID')->groupBy('UID')->get();
        $data = [];
        foreach ($selectdata  as $item) {
            $data[] = [
                'UID' => $item->UID,
                'employee' => Employee::whereIn('EMPID', User::where('UID', $item->UID)->pluck('EMPID'))->first(),
                'qty' => strval(Order::where('UID', $item->UID)->count()),
                'freeqty' => OrderDetail::where('UID', $item->UID)->sum('FREEQTY')
            ];
        }
        return response()->json(['data' => $data], 200);
    }
    public function get_preorder_by_customer($id)
    {
        return response()->json(['data' => GetPreorderByCustomerResource::collection(Order::where('UID', $id)->get())], 200);
    }
    public function get_preorder_detail_by_customer($id)
    {
        return response()->json(['data' => OrderDetail::select('order_details.*', 'p.PDNAME AS PDNAME')->join('products as p', 'p.PDID', '=', 'order_details.PDID')->where('order_details.ORID', $id)->get()], 200);
    }
    public function delete_preorder($id)
    {
        try {
            DB::beginTransaction();
            $order =  Order::where('ORID', $id)->first();
            if ($order) {
                OrderDetail::where('ORID', $id)->delete();
                $order->delete();
            }
            DB::commit();
            return response()->json(['message' => 'ລຶບຂໍ້ມູນຈອງສໍາເລັດແລ້ວ'], 200);
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => $ex->getMessage()
            ], 500);
        }
    }
}
