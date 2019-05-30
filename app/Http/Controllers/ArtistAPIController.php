<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Artist;
use App\Song;
use App\Album;
use App\MusicManager;
use \Illuminate\Support\Facades\DB;
class ArtistAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Artist::all();
        
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
        $artist = $request->artist;
        $img_url = $request->img_url;
        
        if(MusicManager::isArtistInDatabaseAndHasImage($artist))
        {
            return $request;
        }
        else
        {
            MusicManager::addArtistWithImage($artist, $img_url);
            return $request;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Artist::find($id);
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
    public function getJoinedArtist($artist_object)
    {
        return DB::table('artists')->find($artist_object->id)->join('');
    }
    */
    public function search_artist($artist)
    {
        return Artist::where('name','like',$artist)->get();
    }
    
    public function search_album($album)
    {
        $album_list = Album::where('name','like','%'.$album.'%')->get();
            
        $result_list = [];
        foreach($album_list as $album_object)
        {
            $artist_id = $album_object->artist_id;
            
             array_push($result_list, Artist::all()->get($artist_id));
            /*
            $artist_results = Artist::where('id','=',$album_id)->get();
            foreach($artist_results as $artist_result)
            {
                array_push($result_list, $artist_result);
            }
             * 
             */
        }
        return $result_list;
    }
    
    public function search_song($song)
    {
        $result_list = [];
        
        $song_list = Song::where('title','like','%'.$song.'%')->get();
        foreach($song_list as $song_object)
        {
            $artist_id = $song_object->artist_id;
            array_push($result_list, Artist::where('id','=',$artist_id)->first());
        }
        return $result_list;
    }
    
    public function search_album_artist($album,$artist)
    {
        return ArtistAPIController::search_artist_album($artist, $album);
    }
    
    public function search_artist_album($artist, $album)
    {
        $result_list = [];
        
        $album_list = Album::where('name','like','%'.$album.'%')->get();
        foreach($album_list as $album_object)
        {
            $artist_id = $album_object->artist_id;

            $artist_results = Artist::where('name','like',$artist)->where('id','=',$artist_id)->get();
            foreach($artist_results as $artist_result)
            {
                array_push($result_list,$artist_result);
            }
        }
        return $result_list;
    }
    
    public function search_song_artist($song, $artist)
    {
        return ArtistAPIController::search_artist_song($artist, $song);
    }
    
    public function search_artist_song($artist, $song)
    {
        $result_list = [];
        
        $song_list = Song::where('title','like','%'.$song.'%')->get();
        foreach($song_list as $song_object)
        {
            $artist_id = $song_object->artist_id;
            $artist_list = Artist::where('id','=',$artist_id)->where('name','like','%'.$artist.'%')->get();
            foreach($artist_list as $artist_object)
            {
                array_push($result_list, $artist_object);
            }
        }
        return $result_list;
    }
    
    public function search_album_song($album, $song)
    {
        return ArtistAPIController::search_song_album($song, $album);
    }
    
    public function search_song_album($song, $album)
    {
        $result_list = [];
        $album_list = Album::where('name','like','%'.$album.'%')->get();

        foreach($album_list as $album_object)
        {
            $album_id = $album_object->id;

            $song_list = Song::where('title','like','%'.$song.'%')->where('album_id','=',$album_id)->get();
            foreach($song_list as $song_object)
            {
                $artist_id = $song_object->artist_id;

                array_push($result_list, Artist::where('id','=',$artist_id)->first());
            }
        }
        return $result_list;
    }
    
    public function search_song_album_artist($song, $album, $artist)
    {
        
        $result_list = [];
        $used_artists = [];
        
        $album_list = Album::where('name','like','%'.$album.'%')->groupBy('artist_id','name','id','img_url','created_at','updated_at')->get();
        $checked_album_list = [];
        foreach($album_list as $album_object)
        {
            if(!in_array($album_object->artist_id,$used_artists))
            {
                array_push($used_artists,$album_object->artist_id);
                array_push($checked_album_list,$album_object);
            }
        }

        foreach($checked_album_list as $album_object)
        {
            $album_id = $album_object->id;

            $song_list = Song::where('title','like','%'.$song.'%')->where('album_id','=',$album_id)->get();
            foreach($song_list as $song_object)
            {
                $artist_id = $song_object->artist_id;
                $result = Artist::where('name','like','%'.$artist.'%')->where('id','=',$artist_id)->first();
                //$results = Album::where('name','like',$album)->where('artist_id',$artist_id)->where('id','=',$song_object->album_id)->get();
                array_push($result_list,$result);
            }
        }
        return $result_list;
    }

    public function search_song_artist_album($song, $artist, $album)
    {
        return ArtistAPIController::search_song_album_artist($song, $album, $artist);
    }
    
    public function search_artist_song_album($artist, $song, $album)
    {
        return ArtistAPIController::search_song_album_artist($song, $album, $artist);
    }
    
    public function search_album_song_artist($album, $song, $artist)
    {
        return ArtistAPIController::search_song_album_artist($song, $album, $artist);
    }
    
    public function search_album_artist_song($album, $artist, $song)
    {
        return ArtistAPIController::search_song_album_artist($song, $album, $artist);
    }
    
    public function search_artist_album_song($artist, $album, $song)
    {
        return ArtistAPIController::search_song_album_artist($song, $album, $artist);
    }
}
