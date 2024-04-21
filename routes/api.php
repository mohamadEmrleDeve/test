<?php

use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\TagController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['namespace' => 'API' , 'prefix' => 'admin'],function(){
    Route::post('login',[AdminController::class,'login'])->name('admin.login');
});

Route::group(['namespace' => 'API', 'prefix' => 'admin', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/blogs',[BlogController::class,'index'])->name('admin.blogs');
    Route::post('/blogs/store',[BlogController::class,'store'])->name('admin.blogs.store');
    Route::get('/blogs/show/{id}',[BlogController::class,'show'])->name('admin.blogs.show');
    Route::post('/blogs/update/{id}',[BlogController::class,'update'])->name('admin.blogs.update');
    Route::get('/blogs/delete/{id}',[BlogController::class,'delete'])->name('admin.blogs.delete');
});

Route::group(['namespace' => 'API', 'prefix' => 'admin', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/tags',[TagController::class,'index'])->name('admin.tags');
    Route::post('/tags/store',[TagController::class,'store'])->name('admin.tags.store');
    Route::get('/tags/delete/{id}',[TagController::class,'delete'])->name('admin.tags.delete');
});