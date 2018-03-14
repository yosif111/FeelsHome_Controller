<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;

class AudioController extends Controller
{
    public $client;
    public $uri;
    
    public function __construct() {
        $this->uri = env('RASPBERRY_ZERO_IP') .'/mopidy/rpc';
        $this->client = new \GuzzleHttp\Client();
    }
    
    public function clearQueue(){
        
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.tracklist.clear']
        ]);
        
    }
    private function getNextTrackId(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.tracklist.get_next_tlid']
        ]);
        
        return  $contents = json_decode($res->getBody()->getContents(), true)['result'];
    }
    //core.playback.next
    // public function playPlayList(){
    //     $tlid = $this->getNextTrackId();
        
    //     $res = $this->client->request('POST', $this->uri, [
    //     'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.play',
    //     'params' => ['tlid' => $tlid ]]
    //     ]);
        
    //     if($res->getStatusCode() == 200)
    //     return new Response(['Msg' => 'success'],200);
        
    //     return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    // }

    public function pause(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.pause']
        ]);
        
        if($res->getStatusCode() == 200)
        return new Response(['Msg' => 'success'],200);
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }

    public function resume(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.resume']
        ]);
        
        if($res->getStatusCode() == 200)
        return new Response(['Msg' => 'success'],200);
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }

    public function play($id){
       
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.play',
        'params' => ['tlid' => $id * 1]]
        ]);
        
        if($res->getStatusCode() == 200)
        return new Response(['Msg' => 'success'],200);
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }

    //core.playback.next
    // public function playNext(){
    //     $res = $this->client->request('POST', $this->uri, [
    //     'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.next']
    //     ]);
        
    //     if($res->getStatusCode() == 200)
    //     return new Response(['Msg' => 'success'],200);
        
    //     return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    // }
    public function playPrevious(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.previous']
        ]);
        
        if($res->getStatusCode() == 200)
        return new Response(['Msg' => 'success'],200);
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }

    public function getCurrentState(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.get_state']
        ]);
        
        if($res->getStatusCode() == 200) {
            $content = json_decode($res->getBody()->getContents(), true);
            return $content['result'];
        }
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }

    public function getVolume(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.get_volume']
        ]);
        
        if($res->getStatusCode() == 200) {
            $content = json_decode($res->getBody()->getContents(), true);
            return $content['result'];
        }
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }

    public function getProgress(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.get_time_position']
        ]);
        
        if($res->getStatusCode() == 200) {
            $content = json_decode($res->getBody()->getContents(), true);
            return $content['result'];
        }
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }

    public function getCurrentTrack(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.get_current_track']
        ]);
        
        if($res->getStatusCode() == 200) {
            $tarck = [];
            $content = json_decode($res->getBody()->getContents(), true);
            $content = $content['result'];

            $track['track'] = $content['name'];
            $track['album'] = $content['album']['name'];
            $track['artist'] = $content['artists'][0]['name'];

            return $track;
        }
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }

    public function InsertPlaylistToQueue($playlist_uri){
        $this->clearQueue();
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.tracklist.add',
        'params' => ['uri' => $playlist_uri ]]
        ]);
        
        if($res->getStatusCode() == 200)
        return new Response(['Msg' => $res->getBody()->getContents()],200);
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }

    public function changeVolume($volume){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.set_volume',
        'params' => ['volume' => $volume * 1 ]] 
        ]);
        
        if($res->getStatusCode() == 200)
        return new Response(['Msg' => 'success'],200);
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }
    
    public function getQueue(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.tracklist.get_tl_tracks']
        ]);
        
        $contents = json_decode($res->getBody()->getContents(), true);
        $contents = $contents['result'];

        $tracks = [];
        foreach ($contents as $content) {
                $track = [];
                $track['track_name'] = $content['track']['name'];
                $track['track_length'] = $content['track']['length'];
                $track['track_uri'] = $content['track']['uri'];
            
                if(isset($content['track']['album'])){
                    $track['album_name'] = $content['track']['album']['name'];
                    $track['album_uri'] = $content['track']['album']['uri'];
                    $track['artist'] =$content['track']['album']['artists'][0]['name'];
                }

                $track['tlid'] = $content['tlid'];

                $tracks[] = $track;
        }
        
        return $tracks;
    }

    public function getPlaylists(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playlists.get_playlists']
        ]);
        
        $contents = json_decode($res->getBody()->getContents(), true);
        $contents = $contents['result'];
        $playlists = [];
        //return $contents;
        foreach($contents as $content){
            if(! isset($content['tracks'])) //playlist doesnt contain tracks
            continue ;
            if($content['name'] == '[Radio Streams]')
            continue ;
            
            $playlist['name'] = $content['name'];
            $playlist['uri'] = $content['uri'];
            
            // $tracks = [];
            // foreach($content['tracks'] as $tr){
            //     $track = [];
            //     $track['track_name'] = $tr['name'];
            //     $track['track_length'] = $tr['length'];
            //     $track['track_uri'] = $tr['uri'];
            
            //     if(isset($tr['album'])){
            //         $track['album_name'] = $tr['album']['name'];
            //         $track['album_uri'] = $tr['album']['uri'];
            //         $track['artist'] =$tr['album']['artists'][0]['name'];
            //     }
            //     $tracks[] = $track;
            // }
            
            // $playlist['tracks'] = $tracks;
            
            // $this->play
            
            $playlists[] = $playlist;
            
        }
        
        return $playlists;
    }

    public function getCurrentId(){
        $res = $this->client->request('POST', $this->uri, [
        'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.playback.get_current_tlid']
        ]);

        if($res->getStatusCode() == 200)
        return new Response(json_decode($res->getBody()->getContents(), true)['result'],200);
        
        return new Response(['Msg' => $res->getReasonPhrase()],$res->getStatusCode());
    }

    public function getAllStatus(){
        $allStates = [];
        
        $allStates['state'] = $this->getCurrentState();

        
        $content = $this->getCurrentTrack();
        $allStates['track'] = $content['track'];
        $allStates['artist'] = $content['artist'];
        $allStates['album'] = $content['album'];

        if ($allStates['track'] == null) {
            $id = $this->getNextTrackId();
            $queue = $this->getQueue();
            foreach ($queue as $track) {
                if ($track['tlid'] == $id) {
                    $allStates['track'] = $track['track_name'];
                    $allStates['artist'] = $track['artist'];
                    $allStates['album'] = $track['album_name'];
                    break;
                }
            }
        }

        $currentTrack = $this->getCurrentId();
        
        if ($currentTrack){

            $res = $this->client->request('POST', $this->uri, [
                'json' => ['jsonrpc' => '2.0', 'id' => '1', 'method' => 'core.tracklist.index',
                'params' => ['tlid' => $currentTrack]]
                ]);
            
            $allStates['index'] = json_decode($res->getBody()->getContents(), true)['result'];

            $progress = $this->getProgress();
            $progress /= 1000;

            $allStates['progress'] = $progress;
        }

        $allStates['volume'] = $this->getVolume();

        return $allStates;
    }
}