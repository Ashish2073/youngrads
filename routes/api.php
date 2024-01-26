<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\CampusController;

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

Route::get('all-clear', function () {
    $exitCode = Artisan::call('cache:clear');
    // $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('view:clear');
    echo 'done';
    die;
});

//select2 option ajax
Route::post('/get-program', [ProgramController::class,'getPrograms'])->name('select-programs');
Route::post('/get-university',[UniversityController::class,'selectUniverstiy'])->name('select-university');
Route::post('/get-campus', [CampusController::class,'selectCampus'])->name('select-campus');

Route::post('/countries', [AddressController::class,'getCountries'])->name('get-countries');
Route::post('/get-state', [AddressController::class,'getState'])->name('get-state');
//Route::get('/countries','CourseFinderController@countryAutoComplete');


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');

    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('logout', 'AuthController@logout');
        Route::get('user', 'AuthController@user');
    });

});