<?php

use App\Http\Controllers\catatancontroller;
use App\Http\Controllers\usercontroller;
use Illuminate\Support\Facades\Route;
    // user
    Route::post('/regist', [usercontroller::class, 'regist'])->name('regist');
    Route::post('/login', [usercontroller::class, 'login'])->name('login');
    Route::post('/edituser', [usercontroller::class, 'edituser'])->name('edituser');
    Route::post('/hapususer', [usercontroller::class, 'hapususer'])->name('hapususer');
    Route::post('/logout', [usercontroller::class, 'logout'])->name('logout');
    Route::post('/ubahpassword', [usercontroller::class, 'ubahpassword'])->name('ubahpassword');
    Route::get('/getedituser', [usercontroller::class, 'getedituser'])->name('getedituser');
    //catatan
    Route::get('/home',[catatancontroller::class, "index"])->name("home");
    Route::post('/addcatatan', [catatancontroller::class, 'addcatatan'])->name('addcatatan');
    Route::post('/editcatatan/{id}', [catatancontroller::class, 'editcatatan'])->name('editcatatan');
    Route::post('/hapuscatatan/{id}', [catatancontroller::class, 'hapuscatatan'])->name('hapuscatatan');
    Route::post('geteditcatatan/{id}',[catatancontroller::class,'geteditcatatan'])->name('geteditcatatan');
