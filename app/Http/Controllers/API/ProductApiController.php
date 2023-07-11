<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductApiController extends Controller
{
    public function add(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'PDNAME' => 'required|unique:products',
                'UNIT' => 'required',
                'BUY_PRICE' => 'required',
                'SALE_PRICE' => 'required',
                'SUPPLIER' => 'required'
            ], [
                'PDNAME.required' => 'ໃສ່ຊື່ສິນຄ້າກ່ອນ!',
                'PDNAME.unique' => 'ຊື່ສິນຄ້ານິ້ມີໃນລະບົບແລ້ວ!',
                'UNIT.required' => 'ໃສ່ຊື່ຫົວໜ່ວຍກ່ອນ!',
                'BUY_PRICE.required' => 'ໃສ່ລາຄາຊື້ກ່ອນ!',
                'SALE_PRICE.required' => 'ໃສ່ລາຄາຂາຍກ່ອນ!',
                'SUPPLIER.required' => 'ໃສ່ຜູ້ສະໜ້ອງກ່ອນ!',
            ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            } else {
                if ($request->BUY_PRICE > $request->SALE_PRICE) {
                    return response()->json(['message' => 'ລາຄາຊື້ຕ້ອງໜ້ອຍກວ່າລາຄາຂາຍ'], 422);
                    return;
                }
                $data = new Product();
                $data->PDNAME  = $request->PDNAME;
                $data->CHEMISTRY_NAME  = $request->CHEMISTRY_NAME;
                $data->UNIT  = $request->UNIT;
                $data->BUY_PRICE  = $request->BUY_PRICE;
                $data->SALE_PRICE  = $request->SALE_PRICE;
                $data->SUPPLIER  = $request->SUPPLIER;
                $data->DESCRIPTION  = $request->DESCRIPTION;
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
            'data' => Product::get()
        ], 200);
    }
    public function update(Request $request)
    {
        try {
            $data = Product::where('PDID', $request->PDID)->first();
            if ($request->PDNAME) {
                $data->PDNAME  = $request->PDNAME;
            }
            if ($request->CHEMISTRY_NAME) {
                $data->CHEMISTRY_NAME  = $request->CHEMISTRY_NAME;
            }
            if ($request->UNIT) {
                $data->UNIT  = $request->UNIT;
            }
            if ($request->BUY_PRICE) {
                $data->BUY_PRICE  = $request->BUY_PRICE;
            }
            if ($request->SALE_PRICE) {
                $data->SALE_PRICE  = $request->SALE_PRICE;
            }
            if ($request->SUPPLIER) {
                $data->SUPPLIER  = $request->SUPPLIER;
            }
            $data->DESCRIPTION  = $request->DESCRIPTION;
            $data->update();
            return response()->json(['status' => 'true', 'message' => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }
    public function delete(Request $request)
    {
        try {
            $data = Product::where('PDID', $request->PDID)->first();
            $data->delete();
            return response()->json(['status' => 'true', 'message' => "ລືບຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }
}
