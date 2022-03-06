<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



Route::get('/mail', function () {
    try {
        $details = [
            'title' => 'Test cho quan',
            'body' => 'co ni chi qua'
        ];
        \Mail::to('vntya002@gmail.com')->send(new \App\Mail\SendMail($details));
        return response()->json(['status' => 'successful']);
    } catch (\Exception $e) {
        return response($e->getMessage(), 422);
    }
});
