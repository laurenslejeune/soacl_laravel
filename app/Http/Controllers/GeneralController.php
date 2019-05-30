<?php

namespace App\Http\Controllers;

use App\MusicManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GeneralController extends Controller
{
    const local_api_base = "http://laurenslaravelwebapp.azurewebsites.net/api";
    const use_local_api_key = true;
    const api_key = "25e384169d5e6e4b359747ef7e8932b7b38210ae87ed33c017891e07f610127b";
    
    public function testAPIKey()
    {
        return "de api key werkt";
    }
    
    public function translation_en_nl($id)
    {
        $final_url = self::local_api_base . '/translate/en-nl/' . $id;
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info($final_url);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function addApiKeyLastFM($url)
    {
        //The API key that needs to be added
        $api_key = "1c29722debda9b327250154f911004b6";
        
        //The url that will be used to sent a HTTP GET request
        $final_url = "http://ws.audioscrobbler.com/2.0?" . $url . "&api_key=" . $api_key;
        
        //Optional: log the final url for debugging
        Log::info("LastFM final url = " . $final_url);
        
        //Return the json response as sent by LastFM, leave any additional editing to the JS
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    
    public function songs_store(Request $request)
    {
        $final_url = self::local_api_base . '/songs';
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info("POST song:".$final_url);
        
        $album = $request->album;
        $artist = $request->artist;
        $song = $request->song;
        $source = $request->source;
        $post_data = array('artist' => $artist, 'song' => $song, 'album' => $album, 'source' => $source);
        return MusicManager::curl_post($final_url, json_encode($post_data));
    }
    
    public function albums_store(Request $request)
    {
        $final_url = self::local_api_base . '/albums';
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info("POST ".$final_url);
        
        $album = $request->album;
        $artist = $request->artist;
        $img_url = $request->img_url;
        $source = $request->source;
        $post_data = array('artist' => $artist, 'img_url' => $img_url, 'album' => $album, 'source' => $source);
        return MusicManager::curl_post($final_url, json_encode($post_data));
    }
    
    public function artists_store(Request $request)
    {
        $final_url = self::local_api_base . '/artists';
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info("POST ".$final_url);
        
        $artist = $request->artist;
        $img_url = $request->img_url;
        $source = $request->source;
        $post_data = array('artist' => $artist, 'img_url' => $img_url, 'source' => $source);
        return MusicManager::curl_post($final_url, json_encode($post_data));
    }
    
    public function playlists_put(Request $request, $id)
    {
        $final_url = self::local_api_base . '/playlists/' . $id;
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info("PUT playlist " . $id . ", type: " . $request->type . ", song id: " . $request->song_id);
        
        $song_id = $request->song_id;
        $type = $request->type;
        $put_data = array('song_id' => $song_id, 'type' => $type);
        return MusicManager::curl_put($final_url, $put_data);
    }
    
    public function playlists_get_id($id)
    {
        $final_url = self::local_api_base . '/playlists/' . $id;
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info($final_url);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function playlists_delete_id($id)
    {
        $final_url = self::local_api_base . '/playlists/' . $id;
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info($final_url);
        return response(MusicManager::curl_delete($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function songs_get_index()
    {
        
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info($final_url);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function songs_get_id($id)
    {
        
        $final_url = self::local_api_base . '/songs/' . $id;
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info($final_url);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function albums_get_id($id)
    {        
        $final_url = self::local_api_base . '/albums/' . $id;
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info($final_url);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function albums_get_index()
    {        
        $final_url = self::local_api_base . '/albums';
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info($final_url);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function albums_add($id)
    {
        $final_url = self::local_api_base . '/albums/add/' . $id;
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info("Adding songs to album " . $id);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function artists_get_id($id)
    {
        
        $final_url = self::local_api_base . '/artists/' . $id;
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info($final_url);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function artists_get_index()
    {
        $final_url = self::local_api_base . '/artists';
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info($final_url);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function songs_search_song($song)
    {
        
        $final_url = self::local_api_base . '/songs/song/' . rawurlencode($song);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        Log::info($final_url);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function songs_search_album($album)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/songs/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function songs_search_artist($artist)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/songs/artist/' . rawurlencode($artist);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function songs_search_song_album($song, $album)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/songs/song/' . rawurlencode($song) . '/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function songs_search_album_song($album, $song)
    {
        return self::songs_search_song_album($song, $album);
    }
    
    public function songs_search_artist_album($artist, $album)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/songs/artist/' . rawurlencode($artist) . '/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function songs_search_album_artist($album,$artist)
    {
        return self::songs_search_artist_album($artist, $album);
    }
    
    public function songs_search_artist_song($artist, $song)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/songs/artist/' . rawurlencode($artist) . '/song/' . rawurlencode($song);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function songs_search_song_artist($song, $artist)
    {
        return self::songs_search_artist_song($artist,$song);
    }
    
    public function songs_search_artist_song_album($artist, $song, $album)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/songs/artist/' . rawurlencode($artist) . '/song/' . rawurlencode($song) . '/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function songs_search_song_album_artist($song, $album, $artist)
    {
        return self::songs_search_artist_song_album($artist, $song, $album);
    }
    
    public function songs_search_artist_album_song($artist, $album, $song)
    {
        return self::songs_search_artist_song_album($artist, $song, $album);
    }
    
    public function songs_search_song_artist_album($song, $artist, $album)
    {
        return self::songs_search_artist_song_album($artist, $song, $album);
    }
    
    public function songs_search_album_song_artist($album, $song, $artist)
    {
        return self::songs_search_artist_song_album($artist, $song, $album);
    }
    
    public function songs_search_album_artist_song($album, $artist, $song)
    {
        return self::songs_search_artist_song_album($artist, $song, $album);
    }
    
    public function albums_search_album($album)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/albums/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function albums_search_song($song)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/albums/song/' . rawurlencode($song);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function albums_search_artist($artist)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/albums/artist/' . rawurlencode($artist);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function albums_search_artist_album($artist, $album)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/albums/artist/' . rawurlencode($artist) . '/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function albums_search_album_artist($album, $artist)
    {        
        return self::albums_search_artist_album($artist, $album);
    }
    
    public function albums_search_song_album($song, $album)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/albums/song/' . rawurlencode($song) . '/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function albums_search_album_song($album, $song)
    {        
        return  self::albums_search_song_album($song, $album);
    }
    
    public function albums_search_song_artist($song, $artist)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/albums/song/' . rawurlencode($song) . '/artist/' . rawurlencode($artist);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function albums_search_artist_song($artist, $song)
    {        
        return self::albums_search_song_artist($song, $artist);
    }
    
    public function albums_search_song_artist_album($song, $artist, $album)
    {        
        //Without API key:
        $final_url = self::local_api_base . '/albums/song/' . rawurlencode($song) . '/artist/' . rawurlencode($artist) . '/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function albums_search_song_album_artist($song, $album, $artist)
    {        
        return self::albums_search_song_artist_album($song, $artist, $album);
    }
    
    public function albums_search_album_artist_song($album, $artist, $song)
    {        
        return self::albums_search_song_artist_album($song, $artist, $album);
    }
    
    public function albums_search_album_song_artist($album, $song, $artist)
    {        
        return self::albums_search_song_artist_album($song, $artist, $album);
    }
    
    public function albums_search_artist_album_song($artist, $album, $song)
    {        
        return self::albums_search_song_artist_album($song, $artist, $album);
    }
    
    public function albums_search_artist_song_album($artist, $song, $album)
    {        
        return self::albums_search_song_artist_album($song, $artist, $album);
    }
    
    public function artists_search_artist($artist)
    {
        //Without API key:
        $final_url = self::local_api_base . '/artists/artist/' . rawurlencode($artist);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        Log::info($final_url);
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function artists_search_song($song)
    {
        //Without API key:
        $final_url = self::local_api_base . '/artists/song/' . rawurlencode($song);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function artists_search_album($album)
    {
        //Without API key:
        $final_url = self::local_api_base . '/artists/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function artists_search_artist_album($artist, $album)
    {
        //Without API key:
        $final_url = self::local_api_base . '/artists/artist/' . rawurlencode($artist) . '/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function artists_search_album_artist($album, $artist)
    {
        return self::artists_search_artist_album($artist, $album);
    }
    
    public function artists_search_song_album($song, $album)
    {
        //Without API key:
        $final_url = self::local_api_base . '/artists/song/' . rawurlencode($song) . '/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function artists_search_album_song($album, $song)
    {
        return self::artists_search_song_album($song, $album);
    }
    
    public function artists_search_song_artist($song, $artist)
    {
        //Without API key:
        $final_url = self::local_api_base . '/artists/song/' . rawurlencode($song) . '/artist/' . rawurlencode($artist);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function artists_search_artist_song($artist, $song)
    {
        return self::artists_search_song_artist($song, $artist);
    }
    
    public function artists_search_song_artist_album($song, $artist, $album)
    {
        //Without API key:
        $final_url = self::local_api_base . '/artists/song/' . rawurlencode($song) . '/artist/' . rawurlencode($artist) . '/album/' . rawurlencode($album);
        if(self::use_local_api_key)
        {
            //With API key:
            $final_url .= "/api_key=" . self::api_key;
        }
        
        return response(MusicManager::curl($final_url), 200)->header('Content-Type', 'application/json');
    }
    
    public function artists_search_song_album_artist($song, $album, $artist)
    {
        return artists_search_song_artist_album($song, $artist, $album);
    }
    
    public function artists_search_album_song_artist($album, $song, $artist)
    {
        return artists_search_song_artist_album($song, $artist, $album);
    }
    
    public function artists_search_album_artist_song($album, $artist, $song)
    {
        return artists_search_song_artist_album($song, $artist, $album);
    }
    
    public function artists_search_artist_album_song($artist, $album, $song)
    {
        return artists_search_song_artist_album($song, $artist, $album);
    }
    
    public function artists_search_artist_song_album($artist, $song, $album)
    {
        return artists_search_song_artist_album($song, $artist, $album);
    }
}
