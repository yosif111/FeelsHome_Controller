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
Route::get('/audio/changeVolume/{volume}','AudioController@changeVolume');
Route::get('/audio/playPlayList','AudioController@playPlayList');
Route::get('/audio/pause','AudioController@pause');
Route::get('/audio/resume','AudioController@resume');
Route::get('/audio/getProgress','AudioController@getProgress');
Route::get('/audio/play/{id}','AudioController@play');
Route::get('/audio/getAllStatus','AudioController@getAllStatus');
Route::get('/audio/getVolume','AudioController@getVolume');
Route::get('/audio/playNext','AudioController@playNext');
Route::get('/audio/getQueue','AudioController@getQueue');
Route::get('/audio/getCurrentId','AudioController@getCurrentId');
Route::get('/audio/getCurrentTrack','AudioController@getCurrentTrack');
Route::get('/audio/getCurrentState','AudioController@getCurrentState');
Route::get('/audio/playPrevious','AudioController@playPrevious');
Route::get('/test','UserController@test');
