<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Album;
use App\Artist;
use App\Song;
use \App\MusicManager;
class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('allAlbumsView')->with('albums',Album::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('testPost');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        
        //return MusicManager::getLyrics("Just for", "Nickelback");
        //$album = Album::find($id);
        //$artist = Artist::find($album->artist_id);
        //return MusicManager::addSongsToAlbum($album->name, $artist->name);
        
        $songs = Song::where('album_id','=',$id)->get();
        //\Illuminate\Support\Facades\Log::info($songs);
        return view('singleAlbumView')->with('album',Album::find($id)->name)
                                      ->with('album_img',Album::find($id)->img_url)
                                      ->with('album_id',$id)
                                      ->with('artist',Artist::find(Album::find($id)->artist_id)->name)
                                      ->with('artist_id',Album::find($id)->artist_id)
                                      ->with('songs',$songs);
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
    
    public function addSongs($id)
    {
        $album = Album::find($id);
        $artist = Artist::find($album->artist_id);
        
        return MusicManager::addSongsToAlbum($album, $artist);
    }
}
