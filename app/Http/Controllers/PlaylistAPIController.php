<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Song;
use App\Playlist;
use \Illuminate\Foundation\Auth\User;

class PlaylistAPIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Playlist::all();
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
        $playlist = new Playlist();
        $playlist->name = $request->name;
        $playlist->description = $request->description;
        if($request->api_key == "admin")
        {
            //If this api key is provided, this is a playlist for the admin (for testing);
            $playlist->user_id = 1;
        }
        else
        {
            try
            {
                $playlist->user_id = User::where('access_key',$request->api_key)->first()->id;
            } catch (\Exception $ex) 
            {
                return "invalid api key";
            }
            
        }
        
        $playlist->save();
        return "Playlist successfully created!";
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Return information about each song in the playlist:
        $songs = Playlist::find($id)->songs();
        
        return $songs->join('artists','songs.artist_id','=','artists.id')
                     ->join('albums','songs.album_id','=','albums.id')
                     ->select('songs.title as title', 'artists.name as artistname','albums.name as albumname',
                              'songs.id as song_id','artists.id as artist_id','albums.id as album_id',
                              'songs.youtube_id as youtube_id', 'songs.youtube_html as youtube_html')->get();
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
        \Illuminate\Support\Facades\Log::info("Received a log request for playlist " . $id . ", type: " . $request->type . ", song id: " . $request->song_id);
        $song_id = $request->song_id;
        if($request->type == 'song_addition')
        {
            Playlist::find($id)->songs()->save(Song::find($song_id));
            return ("Succesfully added the song");
        }
        else if($request->type == 'song_removal')
        {
            Playlist::find($id)->songs()->detach(Song::find($song_id));
            return ("Succesfully removed the song");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        \Illuminate\Support\Facades\DB::table('additions')->where('playlist_id',$id)->delete();
        Playlist::destroy($id);
        return "Playlist removed";
    }
}
