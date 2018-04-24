<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
class UserController extends Controller
{
    


    public function getUserInfo($id){
        // retreieve user info from cloud
    }

    public function test(){
        //
        //Naive Spin - Aaron Lansing
        // $client = new Client();
        // $crawler = $client->request('GET', 'https://open.spotify.com/track/1JoAjYaI3zvhXVx41HH7Fc');

        // return $crawler->filter('#cover-img')->each(function ($node) {
        //     return $node ."\n";
        // });


    }

}
