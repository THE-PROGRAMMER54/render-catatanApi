<?php

namespace App\Http\Controllers;

use App\Models\catatan;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class catatancontroller extends Controller
{
    public function index(Request $request)
{
    try {
        if (!$request->hasCookie("token")) {
            return response()->json(["error" => "Token tidak ditemukan"], 401);
        }
        $token = $request->cookie("token");
        JWTAuth::setToken($token);
        $user = JWTAuth::parseToken()->authenticate();
        if (!$user) {
            return response()->json(["error" => "Token tidak valid"], 401);
        }
        $data = $user->catatan()->latest()->get();
        return response()->json(["data" => $data, "token" => $token]);

    } catch (Exception $e) {
        return response()->json([
            "error" => "Gagal mengambil data",
            "message" => $e->getMessage()
        ], 500);
    }
}

    public function addcatatan(Request $request){
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $request->validate([
                "judul" => "required",
                "catatan" => "required"
            ]);
            $data = catatan::where("user_id" , $user->id)->where("judul", $request->judul)->first();
            if(!$data){
                    $catat = new catatan;
                    $catat->user_id = $user->id;
                    $catat->judul = $request->judul;
                    $catat->catatan = $request->catatan;
                    $catat->save();
                    return response()->json(["success" => "data berhasil di tambahkan"],200);
            }else{
                return response()->json(["error" => "data sudah ada"],409);
            }
        }catch(Exception $e){
            return response()->json(["error" => "not error","message" => $e->getMessage()],500);
        }
    }

    public function geteditcatatan(string $id){
        try{
            JWTAuth::parseToken()->authenticate();
            $data = catatan::where("id",$id)->first();
            if(!$data){
                return response()->json(["error" => "data tidak ada"],404);
            }
            return response()->json($data);
        }catch(Exception $e){
            return response()->json(["error" => "data error","message" => $e->getMessage()]);
        }
    }
    public function editcatatan(string $id,Request $request){
        try{
            JWTAuth::parseToken()->authenticate();
            $request->validate([
                "judul" => "required",
                "catatan" => "required"
            ]);
            $data = catatan::where("id",$id)->first();
            if(!$data){
                return response()->json(["error" => "data tidak ada"],404);
            }
            $data->judul = $request->judul;
            $data->catatan = $request->catatan;
            $data->save();

            return response()->json($data);

        }catch(Exception $e){
            return response()->json(["error" => "not error","message" => $e],500);
        }
    }

    public function hapuscatatan(string $id){
        try{
            JWTAuth::parseToken()->authenticate();
            $data = catatan::where("id",$id)->first();
            if(!$data){
                return response()->json(["error" => "data tidak ada"],404);
            }
            $data->delete();
            return response()->json(["success" => "catatan berhasil di hapus"],200);
        }catch(Exception $e){
            return response()->json(["error" => "not error","message" => $e],500);
        }
    }
}
