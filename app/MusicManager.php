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
    
    static function curl_delete($url)
    {
        $curl = curl_init($url); 
        /*$curl_post_data = array( 
            'email' => $Email, 
            'password' => $Paswoord 
        );*/ 
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
    
    static function curl_put($url, $data_array)
    {
        $curl = curl_init($url); 
        /*$curl_post_data = array( 
            'email' => $Email, 
            'password' => $Paswoord 
        );*/ 
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
    
    static function curl_post($url, $json)
    {
        $curl = curl_init($url); 
        /*$curl_post_data = array( 
            'email' => $Email, 
            'password' => $Paswoord 
        );*/ 
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
    
    static function curl($url)
    {
        //$url = 'http://ws.audioscrobbler.com/2.0/?method=album.getInfo&api_key=1c29722debda9b327250154f911004b6&artist=Nickelback&album=Silver&format=json';
        //Log::info($url);
        $curl = curl_init($url); 
        /*$curl_post_data = array( 
            'email' => $Email, 
            'password' => $Paswoord 
        );*/ 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
        //curl_setopt($curl, CURLOPT_POST, true); 
        //curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($curl_post_data)); 
        curl_setopt($curl, CURLOPT_HTTPHEADER, array( 
            'Accept: application/json', 
            'Content-Type: application/json' 
            )); 
        $curl_response = curl_exec($curl); 
        curl_close($curl);
        return $curl_response;
    }
    
    static function getLyricsAnalysis($lyrics)
    {
        //$ip = "52.137.63.124";
        //For documentation, please refer to http://lyricsanalyzerapi.westeurope.azurecontainer.io/documentation/
        
        $url = "http://lyricsanalyzerapi.westeurope.azurecontainer.io/analysis/". rawurlencode($lyrics)."/10";
        //$url = "http://" . $ip . "/analysis/". rawurlencode($lyrics)."/10";
        
        return $response = json_decode(MusicManager::curl($url));
    }
    
    static function setWikipediaInformation($artist)
    {
        $url = 'https://en.wikipedia.org/w/api.php?action=opensearch&search='.rawurlencode($artist->name) . '&format=json';
        Log::info("Looking up wikipedia info: " . $url);
        $response = MusicManager::curl($url);
        Log::info($response);
        return json_decode($response);
    }
    
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
    /*
    static function addAlbums($artist,$api_key)
    {
        $url = "http://ws.audioscrobbler.com/2.0/?method=artist.gettopalbums&artist={$artist}&api_key={$api_key}&format=json";
        $json_return = json_decode(MusicManager::curl($url));
        
        $albums = $json_return->topalbums->album;
        
        foreach($albums as $album)
        {
            $newAlbum = new Album();
            $newAlbum->name = $album->name;
            //$newAlbum->img_url = $album->image[0];
            $newAlbum->img_url = 'urls uitlezen werkt nog niet :(';
            $newAlbum->artist_id = MusicManager::getArtistId($artist,$api_key);
            //If the album has not been stored in the database yet, store it
            $oldAlbum = Album::where(['name'=>$newAlbum->name,
                                      'artist_id'=>$newAlbum->artist_id,
                                      'img_url'=>$newAlbum->img_url])->first();
            if($oldAlbum == NULL)
            {
                $newAlbum->save();
            }
        }
    }
    */
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
    
   
    static function addArtist($name)
    {
        $artist = new Artist();
        $artist->name = $name;
        $artist->wikipedia_url = '';
        $artist->img_url = '';
        $artist->save();
        return TRUE;
    }
    
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
                    ->wsdl('http://localhost:59176/BreakPlacer.asmx?WSDL')
                    ->trace(true)
                    ->classmap([BreakPlacerRequest::class]);
        });
        
        $response = $soapWrapper->call('BreaksPlacer.TransformLineBreaks',[new BreakPlacerRequest($lyrics)]);
        
        //Extract the lyrics from the response
        return $response->TransformLineBreaksResult;
    }
    
    static function translateLyrics($lyrics)
    {
        //$wsdl = "http://localhost:59176/BreakPlacer.asmx?WSDL";
        $soapWrapper = new SoapWrapper();
        $soapWrapper->add('TranslatorEN2NL',function ($service)
        {
            $service
                    ->wsdl("http://localhost:59176/BreakPlacer.asmx?WSDL")
                    ->trace(true)
                    ->classmap([TranslateEnglishToDutchRequest::class]);
        });
        
       $response = $soapWrapper->call('TranslatorEN2NL.TranslateEnglishToDutch',[new TranslateEnglishToDutchRequest($lyrics)]);
       $translated_text = $response->TranslateEnglishToDutchResult;
       //Log::info($translated_text);
       /*
       $text1 = ltrim($translated_text,"[ \"");
       $text2 = rtrim($text1,"\" ]");
        */
       return $translated_text;
       //return preg_replace("/\[ \"/","",$translated_text);
    }
    
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
    
    static function addSongsToAlbum($album, $artist)
    {
        //$album_url = urlencode($album);
        //$artist_url = urlencode($artist);
        
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
