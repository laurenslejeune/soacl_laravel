<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Song;
use App\Album;
use App\Artist;
use App\MusicManager;
class AlbumAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    { 
        return Album::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $album = $request->album;
        $artist = $request->artist;
        $img_url = $request->img_url;
        
        $artist_obj = Artist::where('name','=',$artist)->first();
        if($img_url == NULL)
        {
            return $request;
        }
        if($artist_obj != NULL)
        {
            
            if(MusicManager::isAlbumInDatabaseAndHasImage($album, $artist_obj->id))
            {
                return $request;
            }
            else
            {
                MusicManager::addAlbumWithImageAndArtist($album, $artist_obj->id, $img_url);
                return $request;
            }
        }
        else
        {
            MusicManager::addArtist($artist);
            MusicManager::addAlbumWithImageAndArtist($album, Artist::where('name','=',$artist)->first()->id, $img_url);
            return $request;
        }
        
        //\Illuminate\Support\Facades\Log::info("er is een request binnengekomen");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Album::find($id);
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
    
    public function search_song($song)
    {
        $result_list = [];
        
        $song_list = Song::where('title','like','%'.$song.'%')->get();
        foreach($song_list as $song_object)
        {
            $album_id = $song_object->album_id;
            array_push($result_list, Album::where('id','=',$album_id)->first());
        }
        return $result_list;
    }
    
    /**
     * Find songs belonging to an album corresponding to the search parameter
     * 
     */
    public function search_album($album)
    {
        return Album::where('name','like','%'.$album.'%')->get();
    }
    
    /**
     * Find songs belonging to the given artist
     */
    public function search_artist($artist)
    {
        $artist_list = Artist::where('name','like','%'.$artist.'%')->get();
            
        $result_list = [];
        foreach($artist_list as $artist_object)
        {
            $artist_id = $artist_object->id;
            $album_results = Album::where('artist_id','=',$artist_id)->get();
            foreach($album_results as $album_result)
            {
                array_push($result_list, $album_result);
            }
        }
        return $result_list;
    }
    
    
    public function search_artist_album($artist, $album)
    {
        $artist_list = Artist::where('name','like','%'.$artist.'%')->get();
        $result_list = [];
        foreach($artist_list as $artist_object)
        {
            $artist_id = $artist_object->id;

            $album_results = Album::where('name','like','%'.$album.'%')->where('artist_id',$artist_id)->get();
            foreach($album_results as $album_result)
            {
                array_push($result_list,$album_result);
            }
        }
        return $result_list;
    }
    
    public function search_album_artist($album, $artist)
    {
        return AlbumAPIController::search_artist_album($artist, $album);
    }
    
    public function search_album_song($album, $song)
    {
        $result_list = [];
        $song_list = Song::where('title','like','%'.$song.'%')->get();
               
        foreach($song_list as $song_object)
        {
            $album_id = $song_object->album_id;
            $result = Album::where('name','like','%'.$album.'%')->where('id','=',$album_id)->first();
            if($result != [])
            {
                array_push($result_list,$result);
            }
            
        }
        return $result_list;
    }
    
    public function search_song_album($song, $album)
    {
        return AlbumAPIController::search_album_song($album, $song);
    }
    
    public function search_artist_song($artist, $song)
    {
        $artist_list = Artist::where('name','like','%'.$artist.'%')->get();
        $result_list = [];
        
        foreach($artist_list as $artist_object)
        {
            $artist_id = $artist_object->id;

            $song_list = Song::where('title','like','%'.$song.'%')->where('artist_id','=',$artist_id)->get();
            foreach($song_list as $song_object)
            {
                $album_id = $song_object->album_id;

                array_push($result_list, Album::where('id','=',$album_id)->first());
            }
        }
        return $result_list;
    }
    
    public function search_song_artist($song, $artist)
    {
        return AlbumAPIController::search_artist_song($artist, $song);
    }
    
    public function search_song_artist_album($song, $artist, $album)
    {
        
        $result_list = [];
        
        $artist_list = Artist::where('name','like','%'.$artist.'%')->get();
                
        foreach($artist_list as $artist_object)
        {
            $artist_id = $artist_object->id;

            $song_list = Song::where('title','like','%'.$song.'%')->where('artist_id','=',$artist_id)->get();
            foreach($song_list as $song_object)
            {
                $album_id = $song_object->album_id;
                $result = Album::where('name','like','%'.$album.'%')->where('id','=',$album_id)->first();
                //$results = Album::where('name','like',$album)->where('artist_id',$artist_id)->where('id','=',$song_object->album_id)->get();
                array_push($result_list,$result);
            }
        }
        return $result_list;
    }
    
    public function search_song_album_artist($song, $album, $artist)
    {
        return AlbumAPIController::search_song_artist_album($song, $artist, $album);
    }
    
    public function search_album_song_artist($album, $song, $artist)
    {
        return AlbumAPIController::search_song_artist_album($song, $artist, $album);
    }
    
    public function search_artist_song_album($artist, $song, $album)
    {
        return AlbumAPIController::search_song_artist_album($song, $artist, $album);
    }
    
    public function search_artist_album_song($artist, $album, $song)
    {
        return AlbumAPIController::search_song_artist_album($song, $artist, $album);
    }
    
    public function search_album_artist_song($album, $artist, $song)
    {
        return AlbumAPIController::search_song_artist_album($song, $artist, $album);
    }
    
    public function addSongs($id)
    {
        $album = Album::find($id);
        $artist = Artist::find($album->artist_id);
        return MusicManager::addSongsToAlbum($album->name, $artist->name);
    }
}
