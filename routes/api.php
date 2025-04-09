<?php

use App\Http\Controllers\catatancontroller;
use App\Http\Controllers\usercontroller;
use Illuminate\Support\Facades\Route;

Route::post('/regist', [usercontroller::class, 'regist'])->name('regist');
Route::post('/login', [usercontroller::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function() {
    Route::get('/home',[catatancontroller::class, "index"])->name("home");
    Route::post('/addcatatan', [catatancontroller::class, 'addcatatan'])->name('addcatatan');
    Route::post('/editcatatan/{id}', [catatancontroller::class, 'editcatatan'])->name('editcatatan');
    Route::post('/hapuscatatan/{id}', [catatancontroller::class, 'hapuscatatan'])->name('hapuscatatan');
    Route::post('/logout', [usercontroller::class, 'logout'])->name('logout');
});
