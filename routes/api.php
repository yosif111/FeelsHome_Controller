<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/lights/change','LightsController@changeState');
Route::post('/lights/changeAll','LightsController@changeStateOfAllLights');
Route::get('/lights','LightsController@getLightsInfo');


Route::get('/audio/playlists','AudioController@getPlaylists');
Route::get('/audio/InsertPlaylistToQueue/{playlist_uri}','AudioController@InsertPlaylistToQueue');
Route::get('/audio/playPlayList','AudioController@playPlayList');
Route::get('/audio/pause','AudioController@pause');
Route::get('/audio/play','AudioController@play');
Route::get('/audio/playNext','AudioController@playNext');
Route::get('/test','UserController@test');
