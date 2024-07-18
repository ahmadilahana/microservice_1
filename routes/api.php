<?php

use App\Http\Controllers\TaskManagementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware("verifyTokenUser")->group(function () {
    Route::controller(TaskManagementController::class)->group(function () {
        Route::prefix('/tasks')->group(function () {
            Route::get('', "getTasks");
            Route::post('', "createTasks");
            Route::get('/{task}', "detailTasks");
            Route::put('/{task}', "updateTasks");
            Route::delete('/{task}', "deleteTasks");
        });
    });
});

Route::get('/check-sub', function () {
    var_dump("test");
    $response = Redis::ping();
    dd($response);
    Redis::subscribe(["updated_user"], function ($message) {
        var_dump("Received message on channel updated_user: {$message}");
    });
});
