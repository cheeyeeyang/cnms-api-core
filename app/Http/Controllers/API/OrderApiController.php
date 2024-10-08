<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Preorder\AdminPlanByMonthResource;
use App\Http\Resources\Preorder\AdminPlanResource;
use App\Http\Resources\Preorder\AdminPreorderResource;
use App\Http\Resources\Preorder\GetPreorderResource;
use App\Models\Alert;
use App\Models\AlertTransaction;
use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Tartget;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderApiController extends Controller
{
    public function get_dashboard()
    {
        $total_percent_order = 0.00;
        $total_percent_appointment = 0.00;
        $count_noti = 0;
        if (auth()->user()->TYPE == 1) {
            $amount_order =  Order::whereMonth('created_at', date('m'))->sum('AMOUNT');
            $count_appointment = Appointment::whereMonth('created_at', date('m'))->count();
            $total_data_target = Tartget::whereMonth('created_at', date('m'))->sum('AMOUNT');
            $data_target = Tartget::whereMonth('created_at', date('m'))->where('AMOUNT', '<=', 0)->sum('TARGET');
            $count_noti =  Alert::whereNotIn('AID', AlertTransaction::select('AID')->pluck('AID')->toArray())->count();
            if ($total_data_target) {
                $total_percent_order = ($amount_order / $total_data_target) * 100;
            }
            if ($data_target) {
                $total_percent_appointment = ($count_appointment / $data_target) * 100;
            }
        } else {
            $amount_order =  Order::whereMonth('created_at', date('m'))->where('UID', auth()->user()->UID)->sum('AMOUNT');
            $count_appointment = Appointment::whereMonth('created_at', date('m'))->where('UID', auth()->user()->UID)->count();
            $data_target = Tartget::whereMonth('created_at', date('m'))->where('UID', auth()->user()->UID)->where('AMOUNT', '<=', 0)->orderBy('TGID', 'desc')->first();
            $total_data_target = Tartget::whereMonth('created_at', date('m'))->where('UID', auth()->user()->UID)->where('AMOUNT', '>', 0)->orderBy('TGID', 'desc')->first();
            $count_noti =  Alert::whereNotIn('AID', AlertTransaction::where('UID', auth()->user()->UID)->select('AID')->pluck('AID')->toArray())->count();
            if ($total_data_target) {
                $total_percent_order = ($amount_order / $total_data_target->AMOUNT) * 100;
            }
            if ($data_target) {
                $total_percent_appointment = ($count_appointment / $data_target->TARGET) * 100;
            }
        }
        $data = [
            'total_percent_order' => $total_percent_order,
            'total_percent_appointment' => $total_percent_appointment,
            'count_noti' => $count_noti
        ];
        return response()->json(['data' => $data], 200);
    }
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
                DB::rollBack();
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
        return response()->json(['data' => GetPreorderResource::collection(Order::where('UID', auth()->user()->UID)->orderBy('ORID', 'DESC')->get())], 200);
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
        // return response()->json(['data' => GetPreorderByCustomerResource::collection(Order::where('UID', $id)->get())], 200);
        $selectdata  = Order::where('UID', $id)->get();
        $data = [];
        foreach ($selectdata  as $item) {
            $data[] = [
                'ORID' => $item->ORID,
                'CID' => $item->CID,
                'customer' => Customer::select('CNAME', 'TEL')->where('CID', $item->CID)->first(),
                'created_at' => $item->created_at
            ];
        }
        return response()->json(['data' => $data], 200);
    }
    public function get_preorder_detail_by_customer($id)
    {
        return response()->json(['data' => OrderDetail::select('order_details.*', 'p.PDNAME AS PDNAME')
            ->join('products as p', 'p.PDID', '=', 'order_details.PDID')->where('order_details.ORID', $id)->get()], 200);
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
    public function history_preorder_employee()
    {
        $data  = Customer::whereIn('CID', Order::where('UID', auth()->user()->UID)->pluck('CID'))->get();
        $order = [];
        foreach ($data as $item) {
            $customer = Customer::where('CID', $item->CID)->first();
            $order[] = [
                'id' => $item->CID,
                'customer' => $customer->CNAME ?? '',
                'zone' => $customer->zone->ZNAME ?? '',
                'tel' => $customer->TEL ?? '',
                'tpi' => OrderDetail::select('order_details.order_details')
                    ->join('orders', 'orders.ORID', '=', 'order_details.ORID')
                    ->where('orders.UID', auth()->user()->UID)
                    ->where('orders.CID', $item->CID)->sum('order_details.QTY'),
                'tay' => Order::where('CID', $item->CID)->where('UID', auth()->user()->UID)->whereYear('created_at', Carbon::now()->year)->sum('AMOUNT'),
                'tam' => Order::where('CID', $item->CID)->where('UID', auth()->user()->UID)->whereMonth('created_at', Carbon::now()->month)->sum('AMOUNT')
            ];
        }
        if (!empty($order)) {
            $order = collect($order)->sortByDesc('tay')->values()->all();
        }
        return response()->json(['data' => $order], 200);
    }
    public function history_preorder_employee_by_month(Request $request)
    {
        $data  = Product::whereIn('PDID', OrderDetail::pluck('PDID'))->get();
        $order = [];
        foreach ($data as $item) {
            $product = Product::where('PDID', $item->PDID)->first();
            $order[] = [
                'id' => $item->PDID,
                'product' => $product->PDNAME ?? '',
                'unit' => $product->unit->name ?? '',
                'jan' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 1)->sum('order_details.QTY'),
                'feb' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 2)->sum('order_details.QTY'),
                'mar' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 3)->sum('order_details.QTY'),
                'apr' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 4)->sum('order_details.QTY'),
                'may' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 5)->sum('order_details.QTY'),
                'jun' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 6)->sum('order_details.QTY'),
                'junly' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 7)->sum('order_details.QTY'),
                'aug' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 8)->sum('order_details.QTY'),
                'sep' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 9)->sum('order_details.QTY'),
                'oct' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 10)->sum('order_details.QTY'),
                'nov' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 11)->sum('order_details.QTY'),
                'dec' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.UID', auth()->user()->UID)->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 12)->sum('order_details.QTY'),
            ];
        }
        return response()->json(['data' => $order], 200);
    }
    public function history_preorder_admin()
    {
        $data  = Customer::whereIn('CID', Order::pluck('CID'))->get();
        $order = [];
        foreach ($data as $item) {
            $customer = Customer::where('CID', $item->CID)->first();
            $order[] = [
                'id' => $item->CID,
                'customer' => $customer->CNAME ?? '',
                'zone' => $customer->zone->ZNAME ?? '',
                'tel' => $customer->TEL ?? '',
                'item' => OrderDetail::select('order_details.order_details')
                    ->join('orders', 'orders.ORID', '=', 'order_details.ORID')
                    ->where('orders.CID', $item->CID)->sum('order_details.QTY'),
                'amount' => Order::where('CID', $item->CID)->whereYear('created_at', Carbon::now()->year)->sum('AMOUNT'),
            ];
        }
        if (!empty($order)) {
            $order = collect($order)->sortByDesc('amount')->values()->all();
        }
        return response()->json(['data' => $order], 200);
    }
    public function history_preorder_admin_by_month(Request $request)
    {
        $data  = Product::whereIn('PDID', OrderDetail::pluck('PDID'))->get();
        $order = [];
        foreach ($data as $item) {
            $product = Product::where('PDID', $item->PDID)->first();
            $order[] = [
                'id' => $item->PDID,
                'product' => $product->PDNAME ?? '',
                'unit' => $product->unit->name ?? '',
                'jan' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 1)->sum('order_details.QTY'),
                'feb' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 2)->sum('order_details.QTY'),
                'mar' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 3)->sum('order_details.QTY'),
                'apr' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 4)->sum('order_details.QTY'),
                'may' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 5)->sum('order_details.QTY'),
                'jun' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 6)->sum('order_details.QTY'),
                'junly' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 7)->sum('order_details.QTY'),
                'aug' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 8)->sum('order_details.QTY'),
                'sep' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 9)->sum('order_details.QTY'),
                'oct' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 10)->sum('order_details.QTY'),
                'nov' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 11)->sum('order_details.QTY'),
                'dec' => OrderDetail::select('order_details.QTY')->join('orders', 'orders.ORID', '=', 'order_details.ORID')->where('orders.CID', $request->CID)->where('order_details.PDID', $item->PDID)->whereMonth('order_details.created_at', 12)->sum('order_details.QTY'),
            ];
        }
        return response()->json(['data' => $order], 200);
    }
    public function get_preorder_total()
    {
        $total_target = Tartget::whereMonth('created_at', date('m'))->where('UID', auth()->user()->UID)->sum('AMOUNT');
        $total_balance = Order::whereMonth('created_at', date('m'))->where('UID', auth()->user()->UID)->sum('AMOUNT');
        if (auth()->user()->TYPE == 1) {
            $items = Order::whereMonth('created_at', date('m'))->orderBy('ORID', 'DESC')->get();
        } else {
            $items = Order::whereMonth('created_at', date('m'))->where('UID', auth()->user()->UID)->orderBy('ORID', 'DESC')->get();
        }
        if ($total_target <= 0) {
            $total_achived = 0;
        } else {
            $total_achived = ($total_balance / $total_target) * 100;
        }
        $data = [
            'total_achived' => $total_achived,
            'total_target' => $total_target,
            'total_balance' => $total_balance,
            'orders' => GetPreorderResource::collection($items)
        ];
        return response()->json(['data' => $data], 200);
    }
    public function get_admin_preorder()
    {
        $total_target = Tartget::whereMonth('created_at', date('m'))->sum('AMOUNT');
        $total_balance = Order::whereMonth('created_at', date('m'))->sum('AMOUNT');
        $items = Employee::whereIn('EMPID', Order::join('users as u', 'u.UID', '=', 'orders.UID')->whereMonth('orders.created_at', date('m'))->select('u.EMPID')->pluck('u.EMPID')->toArray())->orderBy('EMPID', 'ASC')->get();
        if ($total_target <= 0) {
            $total_achived = 0;
        } else {
            $total_achived = ($total_balance / $total_target) * 100;
        }
        $data = [
            'total_achived' => $total_achived,
            'total_target' => $total_target,
            'total_balance' => $total_balance,
            'orders' => AdminPreorderResource::collection($items)
        ];
        return response()->json(['data' => $data], 200);
    }
    public function get_admin_appointment()
    {
        $total_target = Tartget::whereMonth('created_at', date('m'))->where('AMOUNT', '<=', 0)->sum('TARGET');
        $total_balance = Appointment::whereMonth('created_at', date('m'))->count();
        $items = Employee::whereIn('EMPID', Appointment::join('users as u', 'u.UID', '=', 'appointments.UID')->whereMonth('appointments.created_at', date('m'))->select('u.EMPID')->pluck('u.EMPID')->toArray())->orderBy('EMPID', 'ASC')->get();
        if ($total_target <= 0) {
            $total_achived = 0;
        } else {
            $total_achived = ($total_balance / $total_target) * 100;
        }
        $data = [
            'total_achived' => $total_achived,
            'total_target' => $total_target,
            'total_balance' => $total_balance,
            'plans' => AdminPlanResource::collection($items)
        ];
        return response()->json(['data' => $data], 200);
    }
    public function get_admin_appointment_by_month(Request $request)
    {
        $data = Customer::whereIn('CID', function ($query) use ($request) {
            $query->select('c.CID')
                ->from('customers as c')
                ->join('plans as a', 'c.CID', '=', 'a.CID')
                ->whereMonth('a.created_at', $request->month)
                ->where('a.UID', $request->UID);
        })->get();
        return response()->json(['data' => AdminPlanByMonthResource::collection($data)], 200);
    }
}
