<?php

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

Route::get('all-clear', function () {
    $exitCode = Artisan::call('cache:clear');
    // $exitCode = Artisan::call('route:cache');
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('view:clear');
    echo 'done';
    die;
});

//select2 option ajax
Route::post('/get-program', 'Admin\ProgramController@getPrograms')->name('select-programs');
Route::post('/get-university', 'Admin\UniversityController@selectUniverstiy')->name('select-university');
Route::post('/get-campus', 'Admin\CampusController@selectCampus')->name('select-campus');

Route::post('/countries', 'Admin\AddressController@getCountries')->name('get-countries');
Route::post('/get-state', 'Admin\AddressController@getState')->name('get-state');
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