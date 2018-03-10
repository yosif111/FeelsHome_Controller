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

        $client = new Client();
        $crawler = $client->request('GET', 'http://192.168.8.107/settings');
        return $crawler->filter('input')->each(function ($node) {
            return $node->parents();
        });
    }

   
   

    public function addNewMode(){
        
    }

}
