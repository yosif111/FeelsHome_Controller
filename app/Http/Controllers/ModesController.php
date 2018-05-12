<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use Symfony\Component\Debug\Exception\FatalThrowableError;

class ModesController extends Controller
{
    public $client;
    public $audioController;
    public $lightController;

    public function __construct() {
        $this->client = new \GuzzleHttp\Client(['connect_timeout' => 10]);
        $this->audioController = new AudioController();
        $this->lightController = new LightsController();
    }


    public function applyMode(Request $request){
        
        //app('App\Http\Controllers\UserController')->_sendNotificationsToAllUsers($msg, $url);
         $res1 = $this->audioController->InsertPlaylistToQueue($request['playlist_uri']);
         $res2 = $this->lightController->changeStateToApplyMode($request);
        
        return new Response(['res1' => $res1, 'res2' => $res2],200);
    }
}