<?php

use App\Http\Controllers\catatancontroller;
use App\Http\Controllers\usercontroller;
use Illuminate\Support\Facades\Route;

Route::middleware("guest.login")->group(function () {
    Route::post('/regist', [usercontroller::class, 'regist'])->name('regist');
    Route::post('/login', [usercontroller::class, 'login'])->name('login');
});

Route::middleware('user.login')->group(function() {
    Route::post('/edituser', [usercontroller::class, 'edituser'])->name('edituser');
    Route::post('/hapususer', [usercontroller::class, 'hapususer'])->name('hapususer');
    Route::get('/home',[catatancontroller::class, "index"])->name("home");
    Route::post('/addcatatan', [catatancontroller::class, 'addcatatan'])->name('addcatatan');
    Route::post('/editcatatan/{id}', [catatancontroller::class, 'editcatatan'])->name('editcatatan');
    Route::post('/hapuscatatan/{id}', [catatancontroller::class, 'hapuscatatan'])->name('hapuscatatan');
    Route::post('geteditcatatan/{id}',[catatancontroller::class,'geteditcatatan'])->name('geteditcatatan');
    Route::post('/logout', [usercontroller::class, 'logout'])->name('logout');
});
