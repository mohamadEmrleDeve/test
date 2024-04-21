<?php

use App\Http\Controllers\Site\BlogController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Site', 'prefix' => 'site'], function () {
    Route::get('/blogs',[BlogController::class,'index'])->name('admin.blogs');
    Route::get('/blogs/show/{id}',[BlogController::class,'show'])->name('admin.blogs.show');
});