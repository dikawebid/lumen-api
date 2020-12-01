<?php

namespace App\Http\Controllers\V1;

use Carbon\Carbon;
use Exception;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller {
    
    public function register(Request $request) {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:users',
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ]);
    
            if($validator->fails()){
                return $this->returnData(['errors' =>  $validator->errors()], "Validation errors.", 422);
            }

            $req = $request->only(['username', 'name', 'email']);
            $req['email_verified_at'] = Carbon::now();
            $req['password'] = Hash::make($request->password);

            $user = User::create($req);
            
            return $this->returnData($user, "Register successfully.");
        } catch (\Throwable $t) {
            return $this->returnError($t);
        }
    }

    public function me(){
        $user = Auth::user();

        return $this->returnData($user, "Data Berhasil Diambil", 200);
    }

    public function logout(Request $request){
        Auth::logout();
        return redirect('/');
    }
}