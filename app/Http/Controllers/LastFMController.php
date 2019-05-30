<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MusicManager;
use App\Album;
use App\Artist;
use App\Song;
use App\Playlist;
class LastFMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $sample_artist = "http://ws.audioscrobbler.com/2.0/?method=artist.search&artist=nickelback&api_key=1c29722debda9b327250154f911004b6&format=json";
        $sample_similar = "http://ws.audioscrobbler.com/2.0/?method=artist.getSimilar&artist=nickelback&api_key=1c29722debda9b327250154f911004b6&format=json";
        $albums_nickelback = "http://ws.audioscrobbler.com//2.0/?method=artist.gettopalbums&artist=nickelback&api_key=1c29722debda9b327250154f911004b6&format=json";
        //$curl = curl_init($albums_nickelback); 
        /*$curl_post_data = array( 
            'email' => $Email, 
            'password' => $Paswoord 
        );*/ 
        /*curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($curl, CURLOPT_POST, true); 
        //curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data)); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
            'Accept: application/json', 
            'Content-Type: application/json' 
            )); 
        $curl_response = curl_exec($curl); 
        
        curl_close($curl);
        
        //$data = $curl_response;
        $data = json_decode($curl_response);
        //$artist_array = $data->results->artistmatches->artist;
        //$data->topalbums->album;
        */
        MusicManager::addAlbums("Nickelback","1c29722debda9b327250154f911004b6");
        return Album::all();
        /*
        $artist = new Artist();
        $artist->name = 'Smash Mouth';
        $artist->wikipedia_url = 'dit is een url';
        $artist->save();
        */
        
        //return Playlist::first()->songs()->get();
        //return Artist::first()->songs()->get();
        
        //return Artist::first()->songs();
        //return $data->topalbums->album;//view('index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('search');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Deze methode wordt uitgevoerd door de post van het zoekformulier
        $albums = Artist::where('name',$request->artist)->first()->albums()->get();
        return view('artistview')->with('albums',$albums);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return "{{$id}}";
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    
    
    
}
