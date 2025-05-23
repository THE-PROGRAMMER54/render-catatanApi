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
                return response()->json(["message" => "Email harus diakhiri dengan @gmail.com atau @yahoo.com"],422);
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
                'password' => 'required'
            ]);
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json(['error' => "Email tidak terdaftar"], 422);
            }
            if(!Hash::check($request->password,$user->password)){
                return response()->json(['error' => "password salah"],422);
            }
            $token= JWTauth::attempt(['email' => $request->email,'password' => $request->password]);
            if(!$token){
                return response()->json(['error' => "gagal login"],422);
            }
            return response()->json(['success' => "berhasil login","token" => $token], 200)->cookie("token",$token,1440,'/',null,false,false);
        }catch(Exception $e){
            return response()->json(['error' => "gagal login","pesan" => $e], 500);
        }
    }

    public function logout(Request $request){
    try {
        if(!$request->hasCookie("token")){
            return response()->json(["error" => "token tidak ditemukan"]);
        }
        $token = $request->cookie("token");
        JWTAuth::setToken($token);
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            return response()->json(["error" => "Token tidak valid"], 401);
        }
        JWTAuth::invalidate($token);
        return response()->json(['success' => "berhasil logout"], 200)->cookie("token","",-1);
    } catch (Exception $e) {
        return response()->json([
            'error' => "Gagal logout",
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function getedituser(Request $request){
        try{
            if (!$request->hasCookie("token")) {
                return response()->json(["error" => "Token tidak ditemukan"], 401);
            }
            $token = $request->cookie("token");
            JWTAuth::setToken($token);
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return response()->json(["error" => "Token tidak valid"], 401);
            }
            $data = User::where("id",$user->id)->first();
            if(!$data){
                return response()->json(["error" => "data tidak ada"],404);
            }
            return response()->json($data);
        }catch(Exception $e){
            return response()->json(["error" => "data error","message" => $e->getMessage()]);
        }
    }

    public function edituser(Request $request){
        try{
            if(!$request->hasCookie("token")){
                return response()->json(["error" => "token tidak ditemukan"]);
            }
            $token = $request->cookie("token");
            JWTAuth::setToken($token);
            $user = JWTAuth::parseToken()->authenticate();
            $name = [];
            $email = [];

            if ($request->name) {
                if ($user->name != $request->name) {
                    $request->validate(['name' => 'string|min:1']);
                    $user->name = $request->name;
                $name = ['name' => $request->name];
                }
            }

            if ($request->email) {
                if ($user->email != $request->email) {
                    $request->validate(['email' => 'email|unique:users,email,' . $user->id]);
                    if(!Str::endsWith($request->email,["@gmail.com","@yahoo.com"])){
                        return response()->json(["message" => "Email harus diakhiri dengan @gmail.com atau @yahoo.com"],422);
                    }
                    $user->email = $request->email;
                    $email = ['email' => $request->email];
                }
            }
            if (empty($email) && empty($name)) {
                return response()->json(['error' => 'Tidak ada data yang diubah'], 422);
            }

            $user->save();
            if (!empty($name) && !empty($email)) {
                return response()->json(["success" => "Berhasil mengganti username dan email anda"], 200);
            } elseif (!empty($name)) {
                return response()->json(["success" => "Berhasil mengganti username anda"], 200);
            } elseif (!empty($email)) {
                return response()->json(["success" => "Berhasil mengganti email anda"], 200);
            }
        }catch(Exception $e){
            return response()->json(["error" => "Gagal mengedit data anda","message" => $e->getMessage()],500);
        }
    }

    public function ubahpassword(Request $request){
        try {
            if (!$request->hasCookie("token")) {
                return response()->json(["error" => "token tidak ditemukan"]);
            }

            $token = $request->cookie("token");
            JWTAuth::setToken($token);
            $user = JWTAuth::parseToken()->authenticate();

            $request->validate([
                'password' => 'required|min:8',
                'new_password' => 'required|min:8',
                'confirm_password' => 'required|min:8|same:new_password'
            ]);

            if(!Hash::check($request->password,$user->password)){
                return response()->json(['error' => "password salah"],422);
            }

            $user->password = bcrypt($request->new_password);
            $user->save();

            return response()->json(["success" => "Berhasil mengubah password anda"], 200);
        } catch (Exception $e) {
            return response()->json(["error" => "Gagal mengedit password anda", "message" => $e->getMessage()], 500);
        }
    }

    public function hapususer(Request $request){
        try {
            if(!$request->hasCookie("token")){
                return response()->json(["error" => "token tidak ditemukan"],401);
            }
            $token = $request->cookie("token");
            JWTAuth::setToken($token);
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
               return response()->json(["error" => "Gagal menghapus data anda"], 500);
            }
            JWTAuth::invalidate($token);
            $user->catatan()->delete();
           $user->delete();

           return response()->json(['success' => 'Akun berhasil dihapus'], 200)->cookie('token','0',-1);
        } catch (Exception $e) {
            return response()->json([
                "error" => "Gagal menghapus data anda",
                "message" => $e->getMessage()
            ], 500);
        }
    }
}
