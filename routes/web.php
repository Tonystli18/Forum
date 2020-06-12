<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ThreadController;

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

Route::get('/home', function() {
    return redirect('/threads');
})->name('home');

Route::get('/threads', 'ThreadController@index')->name('threads');

Route::get('/threads/create', 'ThreadController@create');

Route::get('/threads/{channel}/{thread}', 'ThreadController@show');

Route::delete('/threads/{channel}/{thread}', 'ThreadController@destroy');

Route::post('/threads', 'ThreadController@store');

Route::get('threads/{channel}', 'ThreadController@index');

// Route::resource('threads', 'ThreadController');

Route::get('/threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('/threads/{channel}/{thread}/replies', 'ReplyController@store');

Route::post('/replies/{reply}/favorites', 'FavoriteController@store');
Route::delete('/replies/{reply}/favorites', 'FavoriteController@destroy');

Route::delete('/replies/{reply}', 'ReplyController@destroy');
Route::patch('/replies/{reply}', 'ReplyController@update');

Route::post('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store');
Route::delete('/threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy');

Route::get('/profiles/{user}', 'ProfileController@show')->name('profile');
Route::get('/profiles/{user}/notifications', 'UserNotificationController@index');
Route::delete('/profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy');


