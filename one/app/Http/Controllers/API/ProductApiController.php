<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
class ProductApiController extends Controller
{
    public function add(Request $request){  
        try {
            $validator = Validator::make($request->all(), [
            'PDNAME' => 'required|unique:products',
        ], [
                'PDNAME.required' => 'ໃສ່ຊື່ສິນຄ້າກ່ອນ!',
                'PDNAME.unique' => 'ຊື່ສິນຄ້ານິ້ມີໃນລະບົບແລ້ວ!'
            ]);

        if ($validator->fails()) {
            $error = $validator->errors()->all()[0];
            return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
        } else {
                $data = new Product();
                $data->PDNAME  = $request->PDNAME;
                $data->DESCRIPTION  = $request->DESCRIPTION;
                $data->save();
                return response()->json(['status' => 'true', 'message' => "ເພີ່ມຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        }
    } catch (\Exception $e) {
        return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
    }
}
public function get(){
    return response([
        'data' => Product::get()
    ],200);
}
public function update(Request $request){
    try{
        $data = Product::where('PDID', $request->PDID)->first();
        $data->PDNAME  = $request->PDNAME;
        $data->DESCRIPTION  = $request->DESCRIPTION;
        $data->update();
        return response()->json(['status' => 'true', 'message' => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
    }catch(\Exception $ex){
        return response()->json(['status' => 'false', 'message' => $ex], 500);
    }
}
public function delete(Request $request){
    try{
        $data = Product::where('PDID', $request->PDID)->first();
        $data->delete();
        return response()->json(['status' => 'true', 'message' => "ລືບຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
    }catch(\Exception $ex){
        return response()->json(['status' => 'false', 'message' => $ex], 500);
    }
}
}