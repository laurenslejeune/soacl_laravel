<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Song;
use App\Artist;
use App\Album;
use App\MusicManager;
use \Illuminate\Support\Facades\DB;
//SOAP resources
use Artisaninweb\SoapWrapper\SoapWrapper;
use App\SOAP\SearchLyricDirectRequest;


class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $joined_songs =  DB::table('songs')->join('artists','songs.artist_id','=','artists.id')
                                ->join('albums','songs.album_id','=','albums.id')
                                ->select('songs.title as title', 'artists.name as artistname','albums.name as albumname',
                                         'songs.id as song_id','artists.id as artist_id','albums.id as album_id')->get();
        return view('allSongsView')->with('songs',$joined_songs);
        //return Song::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $text = MusicManager::getLyrics("How you remind me", "Nickelback");
        return MusicManager::translatedLyricsToHTML(MusicManager::translateLyrics($text));
        //$html_text = preg_replace( "/\r|\n/", "<br>", $text );
        //\Illuminate\Support\Facades\Log::info($text);
        //$html_text = MusicManager::lyricsToHTML($text);
        \Illuminate\Support\Facades\Log::info($html_text);
        return MusicManager::translatedLyricsToHTML($html_text);
        //return view('songSearcher');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->type == 'list')
        {
            $songTitle = $request->song;
            $songs = Song::where('title',$songTitle);

            if($songs->count() == 0)
            {
                $availableSongs = MusicManager::getSongsFromWeb($songTitle,'1c29722debda9b327250154f911004b6');
                return view('foundSongs')->with('songs',$availableSongs);
            }
            else if($songs->count() > 1)
            {
                $availableSongs = Song::where('title',$songTitle);
                return view('foundSongs')->with('songs',$availableSongs);
            }
            else
            {
                return $songs[0];
            }
        }
        else if($request->type == 'single')
        {
            //Deprecated code, should never pass here
            $id = $request->song_id;
            return view('singleSongView')->with('song',Song::where('id',$id)->first());
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
        
        $song_obj = Song::find($id);
        
        //$response = MusicManager::getLyricsAnalysis($song_obj->lyrics);
        //$words = $response->words->mostCommon;
        //$sentiment = $response->sentiment;
        //return $words;
        //return json_decode(MusicManager::curl($url))->mostCommon;
        
        
        $song = $song_obj->title;
        $artist = Artist::find($song_obj->artist_id)->name;
        //$artist = Artist::all()->get($song_obj->artist_id)->name;
        $album = Album::find($song_obj->album_id)->name;
        //$album = Album::all()->get($song_obj->album_id)->name;
        $lyrics = $song_obj->lyrics;
        
        $words = NULL;
        $sentiment = NULL;
        $analysisAvailable = false;
        if($lyrics != "")
        {
            try
            {
                $analysisAvailable = true;
                $response = MusicManager::getLyricsAnalysis($lyrics);
                $words = $response->words->mostCommon;
                $sentiment = $response->sentiment;
            } catch (\Exception $ex) 
            {
                \Illuminate\Support\Facades\Log::info($ex);
                $analysisAvailable = false;
            }    
        }
        
        //\Illuminate\Support\Facades\Log::info($lyrics);
        if($song_obj->youtube_id == "")
        {
            //Provide a list of possible youtube clips
            $youtube_items = MusicManager::getYoutubeData($song, $artist);
            
            //Sample data for testing
            //$sample_youtube_data = file_get_contents('C:\xampp\htdocs\mediamanager\app\youtube.json');
            //$youtube_items = json_decode($sample_youtube_data)->items;
            
            return view('singleSongView')->with('song_obj',$song_obj)
                                         ->with('song',$song)
                                         ->with('artist',$artist)
                                         ->with('album',$album)
                                         ->with('lyrics',$lyrics)
                                         ->with('chooseYoutube',true)
                                         ->with('youtube',$youtube_items)
                                         ->with('words',$words)
                                         ->with('sentiment',$sentiment)
                                         ->with('analysis',$analysisAvailable);
            
        }
        else
        {
            $url =  $song_obj->youtube_html;
            return view('singleSongView')->with('song_obj',$song_obj)
                                         ->with('song',$song)
                                         ->with('artist',$artist)
                                         ->with('album',$album)
                                         ->with('lyrics',$lyrics)
                                         ->with('chooseYoutube',false)
                                         ->with('youtube',$url)
                                         ->with('words',$words)
                                         ->with('sentiment',$sentiment)
                                         ->with('analysis',$analysisAvailable);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
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
        if($request->intention == 'clear_youtube')
        {
            $song_obj = Song::find($id);
            $song_obj->youtube_html = '';
            $song_obj->youtube_id = '';
            $song_obj->save();
            
            $lyrics = $song_obj->lyrics;
            $words = NULL;
            $sentiment = NULL;
            $analysisAvailable = false;
            if($lyrics != "")
            {
                try
                {
                    $analysisAvailable = true;
                    $response = MusicManager::getLyricsAnalysis($lyrics);
                    $words = $response->words->mostCommon;
                    $sentiment = $response->sentiment;
                } catch (\Exception $ex) 
                {
                    $analysisAvailable = false;
                }    
            }
            
            $youtube_items = MusicManager::getYoutubeData($song_obj->title, $song_obj->artist()->first()->name);
            return view('singleSongView')->with('song_obj',$song_obj)
                                         ->with('song',$song_obj->title)
                                         ->with('artist',$song_obj->artist()->first()->name)
                                         ->with('album',$song_obj->album()->first()->name)
                                         ->with('lyrics',$song_obj->lyrics)
                                         ->with('chooseYoutube',true)
                                         ->with('youtube',$youtube_items)
                                         ->with('words',$words)
                                         ->with('sentiment',$sentiment)
                                         ->with('analysis',$analysisAvailable);
        }
        else if($request->intention == 'add_youtube')
        {
            $youtube_id = $request->youtube_id;
            $response = MusicManager::getEmbeddedHTMLYoutube($youtube_id);
            //$response = "<iframe width=\"480\" height=\"360\" src=\"//www.youtube.com/embed/Aiay8I5IPB8\" frameborder=\"0\" allow=\"accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
            $song_obj = Song::find($id);

            //Store both the embedded html as well as the videoId
            $song_obj->youtube_html = $response;
            $song_obj->youtube_id = $youtube_id;
            $song_obj->save();
            
            $lyrics = $song_obj->lyrics;
            $words = NULL;
            $sentiment = NULL;
            $analysisAvailable = false;
            if($lyrics != "")
            {
                try
                {
                    $analysisAvailable = true;
                    $response = MusicManager::getLyricsAnalysis($lyrics);
                    $words = $response->words->mostCommon;
                    $sentiment = $response->sentiment;
                } catch (\Exception $ex) 
                {
                    $analysisAvailable = false;
                }    
            }
            return view('singleSongView')->with('song_obj',$song_obj)
                                             ->with('song',$song_obj->title)
                                             ->with('artist',Artist::find($song_obj->artist_id)->name)
                                             ->with('album',Album::find($song_obj->album_id)->name)
                                             ->with('lyrics',$song_obj->lyrics)
                                             ->with('chooseYoutube',false)
                                             ->with('youtube',$song_obj->youtube_html)
                                             ->with('words',$words)
                                             ->with('sentiment',$sentiment)
                                             ->with('analysis',$analysisAvailable);
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
        //
    }
}
