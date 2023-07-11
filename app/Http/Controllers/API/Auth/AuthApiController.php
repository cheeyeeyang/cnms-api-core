<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class AuthApiController extends Controller
{
    public function get(){
        return response([
            'data' => User::select('users.*', 'e.EMPNAME','e.EMPPHONE')->join('employees as e', 'e.EMPID', '=', 'users.EMPID')->get()
        ],200);
    }
    public function add(Request $request)
    {
        try {
                $validator = Validator::make($request->all(), [
                'USERNAME' => 'required|unique:users',
                'password' => 'required',
                'TYPE' => 'required',
                'EMPID' => 'required',
            ], [
                    'USERNAME.required' => 'ໃສ່ຊື້ຜູ້ໃຊ້ກ່ອນ!',
                    'USERNAME.unique' => 'ຜູ້ໃຊ້ນີ້ມີໃນລະບົບແລ້ວ!',
                    'password.required' => 'ໃສ່ລະຫັດຜ່ານກ່ອນ',
                    'EMPID.required' => 'ເລືອກພະນັກງານກ່ອນ',
                ]);

            if ($validator->fails()) {
                $error = $validator->errors()->all()[0];
                return response()->json(['status' => 'false', 'message' => $error, 'data' => []], 422);
            } else {
                    $user = new User();
                    $user->USERNAME  = $request->USERNAME;
                    $user->password =  bcrypt($request->password);
                    $user->TYPE =  $request->TYPE;
                    $user->EMPID =  $request->EMPID;
                    $user->save();
                return response()->json(['status' => 'true', 'message' => "ບັນທຶກຂໍ້ມູນສໍາເລັດແລ້ວ", 'data' => $user], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
    public function update(Request $request)
    {
        try{
                $user = User::where('UID', $request->UID)->first();
                $user->USERNAME =  $request->USERNAME;
                if(!empty($request->password)){
                    $user->password =  bcrypt($request->password);
                }
                if(!empty($request->TYPE)){
                $user->TYPE =  $request->TYPE;
                }
                $user->EMPID =  $request->EMPID;
                $user->update();
                    return response()->json(['status' => 'true', 'message' => "ແກ້ໄຂຂໍ້ມູນສໍາເລັດແລ້ວ", 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
    public function delete(Request $request)
    {
        try {
            $user = User::where('UID', $request->UID)->first();
            $user->delete();
                return response()->json(['status' => 'true', 'message' => "ລຶບຂໍ້ມູນສໍາເລັດແລ້ວ", 'data' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'false', 'message' => $e->getMessage(), 'data' => []], 500);
        }
    }
    public function login(Request $request)
    {
        $request->validate([
            'USERNAME' => 'required',
            'password' => 'required|min:6'
        ], [
                'USERNAME.required' => 'ໃສ່ຊື່ຜູ້ໃຊ້ກ່ອນ!',
                'password.required' => 'ໃສ່ລະຫັດຜ່ານກ່ອນ!',
            ]);

        if (Auth::attempt($request->all())) {
                    return response([
                        'data' => auth()->user(),
                        'token' => auth()->user()->createToken('secret')->plainTextToken,
                    ], 200);
         } else {
            return response([
                'message' => 'ຊື່ຜູ້ໃຊ້ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ!'
            ], 403);
        }
    }
    public function getProfile(){
        return response([
            'data' => User::select('users.*', 'e.EMPNAME')->join('employees as e', 'e.EMPID', '=', 'users.EMPID')->where('users.UID', auth()->user()->UID)->first()
        ],200);
    }
    public function logout()
    {
        $user = auth()->user();
        if ($user instanceof User) {
            $user->tokens()->delete();
        }
        return response([
            'message' => 'ອອກລະບົບສຳເລັດ!'
        ], 200);
    }
    public function reset_password(Request $request)
    {
        $userData = User::find('id', auth()->user()->id);
        $old_password = $request->old_password;
        $new_password = $request->new_password;
        if (request()->old_password && request()->new_password) {
            if (Hash::check($old_password, $userData->password)) {
                if ($old_password == $new_password) {
                    return response([
                        'message' => 'ລະຫັດໃຫມ່ຕ້ອງຕ່າງຈາກລະຫັດເກົ່າ'
                    ], 403);
                } else {
                    $setUserData = User::find($userData->id);
                    $setUserData->password = bcrypt($new_password);
                    $setUserData->save();
                    return response([
                        'message' => 'ປ່ຽນລະຫັດຜ່ານໃຫ່ມສຳເລັດແລ້ວ'
                    ], 200);
                }
            } else {
                return response([
                    'message' => 'ລະຫັດເກົ່າບໍ່ຖືກຕ້ອງ'
                ], 401);
            }
        } else {
            return response([
                'message' => 'ປ້ອນຂໍ້ມູນກອນ'
            ], 400);
        }
    }
}
