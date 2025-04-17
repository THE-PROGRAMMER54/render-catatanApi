<?php

namespace App\Http\Controllers;

use App\Models\catatan;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class catatancontroller extends Controller
{
    public function index(){
        try{
            $user = JWTAuth::parseToken()->authenticate();
            $data = $user->catatan;
            return response()->json(["data" => $data]);
        }catch(Exception $e){
            return response()->json(["error" => "gagal","massage" => $e->getMessage()]);
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
