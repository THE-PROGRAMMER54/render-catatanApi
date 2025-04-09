<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class usercontroller extends Controller
{
    public function regist(Request $request){
            try{
            $request->validate(
                [
                    'name' => 'required|string|min:1',
                    'email' =>'required|email|unique:users',
                    'password' => 'required|string|min:8',
                    'confirm_password' => 'required|same:password',
                ]
            );

            $email = $request->email;

            if(!Str::endsWith($email,["@gmail.com","@yahoo.com"])){

                if(!Str::endsWith($email,[".com"])){
                    $email = $email .= ".com";
                }

            }

            $user = new User();
            $user->id = Str::uuid();
            $user->name = $request->name;
            $user->email = $email;
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json(['success' => "berhasil membuat akun"], 201);
        }catch(Exception $e){
            return response()->json(['error' => "gagal membuat akun"], 500);
        }
    }

    public function login(Request $request){
        try{
            $request->validate([
                'email' =>'required|email',
                'password' => 'required|string'
            ]);
            $user = User::where('email', $request->email)->first();
            if($request->email != $user->email){
                return response()->json(['error' => "email tidak terdaftar"],500);
            }
            if(!Hash::check($request->password,$user->password)){
                return response()->json(['error' => "password salah"],500);
            }
            $token= JWTauth::attempt(['email' => $request->email,'password' => $request->password]);
            if(!$token){
                return response()->json(['error' => "gagal login"],500);
            }
            return response()->json(['success' => "berhasil login", 'token' => $token], 200);
        }catch(Exception $e){
            return response()->json(['error' => "gagal login",$e], 500);
        }
    }

    public function logout(){
    try {
        JWTAuth::parseToken()->invalidate();
        return response()->json(['success' => "berhasil logout"], 200);
    } catch (Exception $e) {
        return response()->json([
            'error' => "Gagal logout",
            'message' => $e->getMessage()
        ], 500);
    }
}

}
