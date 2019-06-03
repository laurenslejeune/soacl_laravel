<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Song;
use App\Artist;
use App\Album;
use Illuminate\Support\Facades\Log;
use \Illuminate\Support\Facades\DB;
use App\MusicManager;
class SongAPIController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return Song::all();
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
        $song = $request->song;
        
        Log::info("Storing ".$song." by ".$artist." in ".$album);
        //Check if the song already is stored in the database
        if(MusicManager::isSongInDatabase($song, $artist, $album))
        {
            return $request;
        }
        else
        {
            MusicManager::addSong($song, $artist, $album);
            return $request;
        }
        /*
         if($request->getMethod() == 'OPTIONS')
         {
             Log::info("Dit is een OPTIONS request");
         }
        
        //Log::info($request);
        //Log::info($album . ' ' . $artist . ' ' . $song);
        //$songtitle = $request->song;
        Log::info($request);
        Log::info(json_decode(MusicManager::curl("http://127.0.0.1:8000/api/songs/song/".$song."/album/".$album."/artist/".$artist)));
        if(json_decode(MusicManager::curl("http://127.0.0.1:8000/api/songs/song/".$song."/album/".$album."/artist/".$artist)) == [])
        {
            //Only add a song if it is not present in the database
            MusicManager::addSong($song, $artist, $album, "1c29722debda9b327250154f911004b6");
        }
        $response = array('store' => 'ok');
        
         * return json_encode($response);
         */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Song::find($id);
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
    /*
    public function getJoinedSong($song_objects)
    {
        
        return DB::table('songs')->find($song_object->id)->join('artists','artists.id','songs.artist_id')
                                                         ->join('albums','albums.id','songs.aalbum_id')
                                                         ->select('songs.id as id','songs.title as title', 'artists.id',
                                                                  'artists.name','albums.id','albums.name')->get();
    }
    */
    public function search_song($song)
    {
        return Song::where('title','like','%'.$song.'%')->get();
        //return SongAPIController::getJoinedSong(Song::where('title','like','%'.$song.'%')->get());
    }
    
    /**
     * Find songs belonging to an album corresponding to the search parameter
     * 
     */
    public function search_album($album)
    {
        $album_list = Album::where('name','like','%'.$album.'%')->get();
            
        $result_list = [];
        foreach($album_list as $album_object)
        {
            $album_id = $album_object->id;
            $song_results = Song::where('album_id','=',$album_id)->get();

            foreach($song_results as $song_result)
            {
                array_push($result_list, $song_result);
            }
        }
        return $result_list;
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

            $song_results = Song::where('artist_id','=',$artist_id)->get();
            foreach($song_results as $song_result)
            {
                array_push($result_list, $song_result);
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

            $album_list = Album::where('name','like','%'.$album.'%')->where('artist_id','=',$artist_id)->get();
            foreach($album_list as $album_object)
            {
                $album_id = $album_object->id;
                $song_results = Song::where('artist_id','=',$artist_id)->where('album_id','=',$album_id)->get();
                foreach($song_results as $song_result)
                {
                    array_push($result_list, $song_result);
                }
            }
        }
        return $result_list;
    }
    
    public function search_album_artist($album, $artist)
    {
        return SongAPIController::search_artist_album($artist, $album);
    }
    
    public function search_album_song($album, $song)
    {
        $album_list = Album::where('name','like','%'.$album.'%')->get();
        $result_list = [];
            
        foreach($album_list as $album_object)
        {
            $album_id = $album_object->id;
            $song_results = Song::where('title','like','%'.$song.'%')->where('album_id',$album_id)->get();

            foreach($song_results as $song_result)
            {
                array_push($result_list, $song_result);
            }
        }
        return $result_list;
    }
    
    public function search_song_album($song, $album)
    {
        return SongAPIController::search_album_song($album, $song);
    }
    
    public function search_artist_song($artist, $song)
    {
        $artist_list = Artist::where('name','like','%'.$artist.'%')->get();
        $result_list = [];
        
        foreach($artist_list as $artist_object)
        {
            $artist_id = $artist_object->id;
            $song_results =  Song::where('title','like','%'.$song.'%')->where('artist_id',$artist_id)->get();

            foreach($song_results as $song_result)
            {
                array_push($result_list, $song_result);
            }
        }
        return $result_list;
    }
    
    public function search_song_artist($song, $artist)
    {
        return SongAPIController::search_artist_song($artist, $song);
    }
    
    public function search_song_artist_album($song, $artist, $album)
    {
        $artist__list = Artist::where('name','like','%'.$artist.'%')->get();
        $result_list = [];
        
        foreach($artist__list as $artist_object)
        {
            $artist_id = $artist_object->id;

            $album_list = Album::where('name','like','%'.$album.'%')->where('artist_id','=',$artist_id)->get();

            foreach($album_list as $album_object)
            {
                $album_id = $album_object->id;
                $song_results = Song::where('title','like','%'.$song.'%')->where('artist_id',$artist_id)->where('album_id','=',$album_id)->get();

                foreach($song_results as $song_result)
                {
                    array_push($result_list, $song_result);
                }
            }
        }
        return $result_list;
    }
    
    public function search_song_album_artist($song, $album, $artist)
    {
        return SongAPIController::search_song_artist_album($song, $artist, $album);
    }
    
    public function search_album_song_artist($album, $song, $artist)
    {
        return SongAPIController::search_song_artist_album($song, $artist, $album);
    }
    
    public function search_artist_song_album($artist, $song, $album)
    {
        return SongAPIController::search_song_artist_album($song, $artist, $album);
    }
    
    public function search_artist_album_song($artist, $album, $song)
    {
        return SongAPIController::search_song_artist_album($song, $artist, $album);
    }
    
    public function search_album_artist_song($album, $artist, $song)
    {
        return SongAPIController::search_song_artist_album($song, $artist, $album);
    }
}
