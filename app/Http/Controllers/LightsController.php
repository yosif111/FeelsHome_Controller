<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Devices;
use App\User;
use GuzzleHttp\Client;

class LightsController extends Controller
{
    
    
    public function createUser(){
        $ip = $this->getHueIP();
        
        $client = new \GuzzleHttp\Client(['base_uri' => "http://$ip/api/", 'connect_timeout' => 10]);
        $res = $client->request('POST','',[
             'json' => [
                          'devicetype' => 'Demo'
                       ]
        ]);
        $contents = json_decode($res->getBody()->getContents(), true);
        
        if(isset($contents[0]['error']['type'])){ // have to press the link
            return new Response(['Msg' => 'please press the bridge link'], 400);
        }
        else{
            $username =$contents[0]['success']['username'];
            User::first()->update('hue_username',$username);
            return $username;
        }
    }

    public static function changeEnvironmentVariable($key,$value)
    {
        $path = base_path('.env');

        if(is_bool(env($key)))
        {
            $old = env($key)? 'true' : 'false';
        }
        elseif(env($key)===null){
            $old = 'null';
        }
        else{
            $old = env($key);
        }

        if (file_exists($path)) {
            file_put_contents($path, str_replace(
                "$key=".$old, "$key=".$value, file_get_contents($path)
            ));
        }
    }
    
    public function getLightsInfo(){
         $hue_username = $this->getHueUserName();
        $ip = $this->getHueIP();
        $client = new \GuzzleHttp\Client();
        $res = $client->get("http://$ip/api/$hue_username/lights");
        $contents = json_decode($res->getBody()->getContents(), true);
        
        $lights = [];
        $index = 0;
        foreach($contents as $light){
            $lights[$index]['id']=  $index + 1;
            $lights[$index]['name']= $light['name'];
            $lights[$index]['on'] = $light['state']['on'];
            $lights[$index]['bri'] = $light['state']['bri'];
            $lights[$index]['hue'] = $light['state']['hue'];
            $index++;
           
        }

        return $lights;
    }
    public function getHueUserName(){
        $hueIP = $this->getHueIP();
        $hue_username = User::first()['hue_username'];
        if(!$hue_username || $hue_username == ''){
            $hue_username = $this->createUser();
        }
        return $hue_username;
    }
    
    // changes the state of the bulb, this includes on/off, hue, satuartion, etc ...
    public function changeState(Request $request){
        $hue_username = $this->getHueUserName();
        $ip = $this->getHueIP();
        $bulb_id = $request['id'];
        unset($request['id']);
        $request['sat'] = 255;

        $client = new \GuzzleHttp\Client();
        $res = $client->request('PUT',"http://$ip/api/$hue_username/lights/$bulb_id/state",[
        'json' => $request->all()
        ]);
        
        return $res->getBody();
    }

    public function changeStateOfAllLights(Request $request){
        $hue_username = $this->getHueUserName();
        $ip = $this->getHueIP();
        $client = new \GuzzleHttp\Client();
        $ids = $this->getIDsOfLights($hue_username, $ip);

        foreach($ids as $id){
            $res = $client->request('PUT',"http://$ip/api/$hue_username/lights/$id/state",[
                'json' => $request->all()
                ]);
        }
        return new Response(['msg' => 'done'], 200);
    }

    public function getIDsOfLights($hue_username, $ip){
        $client = new \GuzzleHttp\Client();
        $res = $client->get("http://$ip/api/$hue_username/lights");
        $contents = json_decode($res->getBody()->getContents(), true);
        $ids = [];

        $index=0;
        foreach($contents as $light){
            $ids[$index] = $index + 1;
            $index++;
        }

        return $ids;
    }

    public function getHueIP(){
        return env('BRIDGE_IP');
    }
    
}