<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App;

use App\Album;
use App\Artist;

//Debugging
use Illuminate\Support\Facades\Log;

//SOAP resources
use Artisaninweb\SoapWrapper\SoapWrapper;
use App\SOAP\SearchLyricDirectRequest;
use App\SOAP\TranslateEnglishToDutchRequest;
use App\SOAP\BreakPlacerRequest;

/**
 * Description of MusicManager
 *
 * @author Laurens
 */
class MusicManager {
    
    public $artists;
    //put your code here
    
    /**
     * Function to send a DELETE HTTP request to a given location
     * @param string $url: The url to send the request to
     * @return The response to the DELETE request
     */
    static function curl_delete($url)
    {
        $curl = curl_init($url); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
            'Accept: application/json', 
            'Content-Type: application/json' 
            )); 
        $curl_response = curl_exec($curl); 
        curl_close($curl);
        return $curl_response;
    }
    
    /**
     * Function to send a PUT HTTP request to a given location
     * @param string $url: The url to send the request to
     * @return The response to the PUT request
     */
    static function curl_put($url, $data_array)
    {
        $curl = curl_init($url); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_array));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
            'Accept: application/json', 
            'Content-Type: application/json' 
            )); 
        $curl_response = curl_exec($curl); 
        curl_close($curl);
        return $curl_response;
    }
    
    /**
     * Function to send a POST HTTP request to a given location
     * @param string $url: The url to send the request to
     * @return The response to the POST request
     */
    static function curl_post($url, $json)
    {
        $curl = curl_init($url); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_POST, true); 
        curl_setopt($curl, CURLOPT_POSTFIELDS, $json); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
            'Accept: application/json', 
            'Content-Type: application/json' 
            )); 
        $curl_response = curl_exec($curl); 
        curl_close($curl);
        return $curl_response;
    }
    
    /**
     * Function to send a GET HTTP request to a given location
     * @param string $url: The url to send the request to
     * @return The response to the GET request
     */
    static function curl($url)
    {
        $curl = curl_init($url); 

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
            'Accept: application/json', 
            'Content-Type: application/json' 
            )); 
        $curl_response = curl_exec($curl); 
        curl_close($curl);
        return $curl_response;
    }
    
    /**
     * Send the given lyrics to the lyrics analyzer API for analysis.
     * @param string $lyrics: The lyrics to be analyzed
     * @return JSON object containing the lyrics analysis
     */
    static function getLyricsAnalysis($lyrics)
    {
        //For documentation, please refer to http://lyricsanalyzerapi.westeurope.azurecontainer.io/documentation
        $url = "http://lyricsanalyzerapi.westeurope.azurecontainer.io/analysis/". rawurlencode($lyrics)."/10";
        
        return $response = json_decode(MusicManager::curl($url));
    }
    
    /**
     * Get Wikipedia search results for a given artist
     * @param string $artist The name of the artist
     * @return JSON response from Wikipedia
     */
    static function setWikipediaInformation($artist)
    {
        $url = 'https://en.wikipedia.org/w/api.php?action=opensearch&search='.rawurlencode($artist->name) . '&format=json';
        Log::info("Looking up wikipedia info: " . $url);
        $response = MusicManager::curl($url);
        Log::info($response);
        return json_decode($response);
    }
    
    /*
    static function getSongsFromWeb($title,$api_key)
    {
        $url = 'http://ws.audioscrobbler.com/2.0/?method=track.search&track=' . $title . '&api_key=' . $api_key.'&format=json';
        $json_return = json_decode(MusicManager::curl($url));
        $songlist = $json_return->results->trackmatches->track;
        
        //return $songlist;
        //Create an array with all the songs
        $songObjectList = array();
        
        foreach($songlist as $song)
        {
            $songObject = new Song();
            $songObject->title = $song->name;
            $songObject->artist_id = MusicManager::getArtistId($song->artist, $api_key);
            if($songObject->artist_id > -1)
            {
                $songObject->album_id = MusicManager::getAlbumId($song->name,$song->artist, $api_key);
                $songObject->youtube_url = 'to be implemented';
                $songObject->lyrics = 'to be implemented';
            
                if($songObject->album_id > -1)
                {
                    $existingSong = Song::where(['title'=>$songObject->title,'artist_id'=>$songObject->artist_id,'album_id'=>$songObject->album_id])->first();
                    if($existingSong == NULL)
                    {
                        $songObject->save();
                    }
                    else
                    {
                        $songObject->id = $existingSong->id;
                    }
                    array_push($songObjectList, $songObject);
                }
            }
            
        }
        return $songObjectList;
        
    }
    
    static function getSongsFromWebWithArtist($title,$artist,$api_key)
    {
        $url = 'http://ws.audioscrobbler.com/2.0/?method=track.search&track='.$title.'&artist='.$artist.'&api_key='.$api_key.'&format=json';
        $json_return = json_decode(MusicManager::curl($url));
        
    }
    
    static function getArtistId($name,$api_key)
    {
        $artist = Artist::where('name',$name)->first();
        if($artist == NULL)
        {
            $addition = MusicManager::addArtist($name,$api_key);
            if($addition)
            {
                $addedArtist = Artist::where('name',$name)->first();
                if ($addedArtist != NULL)
                {
                    return $addedArtist->id;
                }
                else
                {
                    return -1;
            
                }
            }
            return -1;
        }
        else
        {
            return $artist->id;
        }
    }
    */
   
    /**
     * Add an artist to the database
     * @param string $name The name of the artist to be added.
     */
    static function addArtist($name)
    {
        $artist = new Artist();
        $artist->name = $name;
        $artist->wikipedia_url = '';
        $artist->img_url = '';
        $artist->save();
    }
    
    /**
     * Add an artist with url to a corresponding image to the database. If the
     * artist already exists, simply add the image to that artist.
     * @param string $name The name of the artist to be added.
     * @param string $img_url The url to the corresponding image
     */
    static function addArtistWithImage($name, $img_url)
    {
        $artist = Artist::where('name','=',$name)->first();
        if($artist==NULL)
        {
            $artist = new Artist();
            $artist->name = $name;
            $artist->wikipedia_url = '';
            $artist->img_url = $img_url;
            $artist->save();
        }
        else
        {
            $artist->img_url = $img_url;
            $artist->save();
        }
    }
    
    /**
     * Add an album to the databse
     * @param type $name Name of the album
     * @param type $artist_id Id of the artist that made the album
     * @return boolean
     */
    static function addAlbum($name, $artist_id)
    {
        
        $album = new Album();
        $album->name = $name;
        $album->img_url = '';
        //$album->wikipedia_url = '';
        $album->artist_id = $artist_id;
        $album->save();
        return FALSE;
    }
    
    static function addAlbumWithImageAndArtist($album, $artist_id, $img_url)
    {
        
        $album_obj = Album::where('name','=',$album)->where('artist_id','=',$artist_id)->first();
        if($album_obj != NULL)
        {
            $album_obj->img_url = $img_url;
            $album_obj->save();
        }
        else
        {
            $album_obj = new Album();
            $album_obj->name = $album;
            $album_obj->img_url = $img_url;
            $album_obj->artist_id = $artist_id;
            $album_obj->save();
        }
    }
    
    static function addAlbum2($name,$artist,$img_url,$artist_id,$api_key)
    {
        //$url ='/2.0/?method=album.getinfo&album='.$name.'&artist='.$artist.'&api_key='.$api_key.'&format=json';
        //$json_return = json_decode(MusicManager::curl($url));
        
        //$album_url = $json_return->album;
        
        $album = new Album();
        $album->name = $name;
        $album->artist_id = $artist_id;
        $album->img_url = $img_url;
        $album->save();
        
        
    }
    
    static function getLyrics($song, $artist)
    {
        $soapWrapper = new SoapWrapper();
        $soapWrapper->add('LyricSearcher',function ($service)
        {
            $service
                    ->wsdl('http://api.chartlyrics.com/apiv1.asmx?WSDL')
                    ->trace(true)
                    ->classmap([SearchLyricDirectRequest::class]);
        });
                
        //Sometimes stopwords cause the soapwrapper to crash.
        //Catch these errors with a try-catch structure
        try 
        {
            $response = $soapWrapper->call('LyricSearcher.SearchLyricDirect',[new SearchLyricDirectRequest($artist,$song)]);
            //Extract the lyrics from the response
            return $response->SearchLyricDirectResult->Lyric;

        } 
        //Note: use "\Exception" because laravel is namespaced: "Exception" does not work
        catch (\Exception $ex) 
        {
            //Log the error, and return an empty string
            Log::info("Error artist".$artist);
            Log::info("Error song".$song);
            Log::info($ex);
            return "";
        }
        
        
        
    }
    
    static function lyricsToHTML($lyrics)
    {
        /*
        $soapWrapper = new SoapWrapper();
        $soapWrapper->add('BreaksPlacer',function ($service)
        {
            $service
                    ->wsdl('http://localhost:59176/BreakPlacer.asmx?WSDL')
                    ->trace(true)
                    ->classmap([BreakPlacerRequest::class]);
        });
        
        $response = $soapWrapper->call('BreaksPlacer.TransformLineBreaks',[new BreakPlacerRequest($lyrics)]);
        
        //Extract the lyrics from the response
        return $response->TransformLineBreaksResult;
        */
        return preg_replace( "/\r|\n/", "<br>", $lyrics);
    } 
    
    static function translatedLyricsToHTML($lyrics)
    {
        $soapWrapper = new SoapWrapper();
        $soapWrapper->add('BreaksPlacer',function ($service)
        {
            $service
                    ->wsdl('https://lyricstranslator.azurewebsites.net/LyricsProcessor.asmx?WSDL')
                    ->trace(true)
                    ->classmap([BreakPlacerRequest::class]);
        });
        
        $response = $soapWrapper->call('BreaksPlacer.TransformLineBreaks',[new BreakPlacerRequest($lyrics)]);
        
        //Extract the lyrics from the response
        return $response->TransformLineBreaksResult;
    }
    
    /**
     * Translate the given lyrics using the C# translation API
     * @param string $lyrics Lyrics to be translated
     * @return string Translated lyrics
     */
    static function translateLyrics($lyrics)
    {
        $soapWrapper = new SoapWrapper();
        $soapWrapper->add('TranslatorEN2NL',function ($service)
        {
            $service
                    ->wsdl("https://lyricstranslator.azurewebsites.net/LyricsProcessor.asmx?WSDL")
                    ->trace(true)
                    ->classmap([TranslateEnglishToDutchRequest::class]);
        });
        
       $response = $soapWrapper->call('TranslatorEN2NL.TranslateEnglishToDutch',[new TranslateEnglishToDutchRequest($lyrics)]);
       $translated_text = $response->TranslateEnglishToDutchResult;
       return $translated_text;
    }
    
    /**
     * Get the five most relevant results for a given youtube search
     * @param string $song The song to search for
     * @param string $artist The artist to search for
     * @return array List containing search results
     */
    static function getYoutubeData($song, $artist)
    {
        $query = $artist." ".$song; 
        $query = str_replace(" ","%20",$query);
        $api_key = "AIzaSyB5gEyBbMqLJABP1PgfHycdTwHmTHCkpBI";
        //nickelback
        $url = "https://www.googleapis.com/youtube/v3/search?part=id,snippet&fields=items(id,snippet(title,description,thumbnails))&q=".$query."&type=video&key=".$api_key."&maxResults=5";
        //$url = "http://127.0.0.1:8000/api/songs";
        //$url = "https://www.googleapis.com/youtube/v3/search?part=id,snippet&fields=items(id,snippet(title,description,thumbnails))&q=Nickelback%20How%20You%20Remind%20Me&type=video&key=AIzaSyB5gEyBbMqLJABP1PgfHycdTwHmTHCkpBI&maxResults=5";
        $curl_response = MusicManager::curl($url);
        $response = json_decode($curl_response);
        return $response->items;
    }
    
    /**
     * For a given youtube ID, get the embedded youtube HTML player
     * @param string $youtube_id The given youtube ID
     * @return string HTML embedded youtube player
     */
    static function getEmbeddedHTMLYoutube($youtube_id)
    {
        $api_key = "AIzaSyB5gEyBbMqLJABP1PgfHycdTwHmTHCkpBI";
        $url = "https://www.googleapis.com/youtube/v3/videos?part=player&fields=items(player)&id=".$youtube_id."&key=".$api_key;
        return json_decode(MusicManager::curl($url))->items[0]->player->embedHtml;
    }
    
    static function addSong($title, $artist, $album)
    {
        $artist_object = Artist::where('name','=',$artist)->first();
        $artist_id = -1;
        if($artist_object == NULL)
        {
            MusicManager::addArtist($artist);
            $artist_id = Artist::where('name','=',$artist)->first()->id;
        }
        else
        {
            $artist_id = $artist_object->id;
        }
        
        $album_object = Album::where('name','=',$album)->first();
        $album_id = -1;
        if($album_object == NULL)
        {
            MusicManager::addAlbum($album, $artist_id);
            $album_id = Album::where('name','=',$album)->first()->id;
        }
        else
        {
            $album_id = $album_object->id;
        }
        $song_object = new Song();
        $song_object->artist_id = $artist_id;
        $song_object->album_id = $album_id;
        $song_object->title = $title;
        $song_object->lyrics = MusicManager::lyricsToHTML(MusicManager::getLyrics($title, $artist));
        $song_object->youtube_html = '';
        $song_object->youtube_id = '';
        $song_object->save();
    }
    
    static function isSongInDatabase($song, $artist, $album)
    {
        
        $artist_list = Artist::where('name','=',$artist)->get();
        foreach($artist_list as $artist_object)
        {
            $artist_id = $artist_object->id;
            
            $album_list = Album::where('name','=',$album)->get();
            foreach($album_list as $album_object)
            {
                $album_id = $album_object->id;
                
                $song_object = Song::where('title','=',$song)->where('album_id','=',$album_id)->where('artist_id','=',$artist_id)->first();
                if($song_object != NULL)
                {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    
    /**
     * Check is an artist is stored in the database and already has an image associated with him
     * @param type $artist
     * @return type
     */
    static function isArtistInDatabaseAndHasImage($artist)
    {
        $number = Artist::where('name','=',$artist)->where('img_url','not','')->count();
        $status = ($number != 0);
        return $status;
    }
    
    static function isAlbumInDatabaseAndHasImage($album, $artist_id)
    {
        $number = Album::where('name','=',$album)->where('artist_id','=',$artist_id)->where('img_url','not','')->count();
        $status = ($number != 0);
        return $status;
    }
    
    /**
     * Function to find and all possible songs to an album
     * @param string $album Name of the album
     * @param string $artist Name of the artist
     * @return string Result of adding the songs
     */
    static function addSongsToAlbum($album, $artist)
    {
        $url = 'http://ws.audioscrobbler.com/2.0/?method=album.getInfo&api_key=1c29722debda9b327250154f911004b6&artist='.rawurlencode($artist).'&album='.rawurlencode($album).'&format=json';
        
        try
        {
            $album_response = json_decode(MusicManager::curl($url))->album;
            if(isset($album_response))
            {
                $tracks = $album_response->tracks->track;
                for($i = 0; $i<count($tracks);$i++)
                {
                    $song = $tracks[$i]->name;
                    if(!MusicManager::isSongInDatabase($song, $artist, $album))
                    {
                       MusicManager::addSong($song, $artist, $album);
                    }
                }
            }
            return 'Songs added';
        } catch (\Exception $ex) 
        {
            Log::info("No additional songs found");
            return "No additional songs found";
        }
    }
 
    
}
