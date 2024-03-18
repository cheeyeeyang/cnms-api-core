<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function add(Request $request)
    {
        try {
            $data = new Category();
            $data->name  = $request->name;
            $data->save();
            return response()->json(['status' => 'true', 'message' => "ເພີ່ມຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
    public function get()
    {
        return response([
            'data' => Category::orderBy('id', 'DESC')->get()
        ], 200);
    }
    public function update(Request $request)
    {
        try {
            $data = Category::find($request->id);
            $data->name  = $request->name;
            $data->update();
            return response()->json(['status' => 'true', 'message' => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }
    public function delete(Request $request)
    {
        try {
            $data = Category::find($request->id);
            $check_category_in_product =  Product::where('CATE_ID', $request->id)->first();
            if ($check_category_in_product) {
                return response()->json(['message' => "ບໍ່ສາມາດລຶບໄດ້ເພາະຍັງມີການໃຊ້ຢູ່!"], 201);
                return;
            }
            $data->delete();
            return response()->json(['status' => 'true', 'message' => "ລືບຂໍ້ມູນສໍາເລັດແລ້ວ!", 'data' => $data], 200);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'false', 'message' => $ex], 500);
        }
    }
}
