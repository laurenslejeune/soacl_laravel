<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/documentation',function()
{
    return view('apiDocumentationView');
});

//Route::get('/test/api_key={apikey}', 'GeneralController@testAPIKey')->middleware('APIKey');

$use_api_key = true;
if($use_api_key)
{
    $additional_url =  '/api_key={apikey}';
    //Translation routes:
    Route::get('/translate/en-nl/{id}' . $additional_url,'TranslationController@translateEnglishToDutchHtml')->middleware('APIKey');
    
    
    //Resources:
    Route::get('/playlists' . $additional_url,'PlaylistAPIController@index')->middleware('APIKey');
    Route::post('/playlists' . $additional_url,'PlaylistAPIController@store')->middleware('APIKey');
    Route::get('/playlists/{id}' . $additional_url,'PlaylistAPIController@show')->middleware('APIKey');
    Route::put('/playlists/{id}' . $additional_url,'PlaylistAPIController@update')->middleware('APIKey');
    Route::delete('/playlists/{id}' . $additional_url,'PlaylistAPIController@destroy')->middleware('APIKey');
    
    Route::get('/songs' . $additional_url,'SongAPIController@index')->middleware('APIKey');
    Route::post('/songs' . $additional_url,'SongAPIController@store')->middleware('APIKey');
    Route::get('/songs/{id}' . $additional_url,'SongAPIController@index')->middleware('APIKey');
    
    Route::get('/albums' . $additional_url,'AlbumAPIController@index')->middleware('APIKey');
    Route::post('/albums' . $additional_url,'AlbumAPIController@store')->middleware('APIKey');
    Route::get('/albums/{id}' . $additional_url,'AlbumAPIController@show')->middleware('APIKey');
    
    Route::get('/artists' . $additional_url,'ArtistAPIController@index')->middleware('APIKey');
    Route::post('/artists' . $additional_url,'ArtistAPIController@store')->middleware('APIKey');
    Route::get('/artists/{id}' . $additional_url,'ArtistAPIController@show')->middleware('APIKey');
    //Route::resource('/playlists' . $additional_url,'PlaylistAPIController')/*->middleware('APIKey')*/;
    //Route::resource('/albums'/* . $additional_url*/,'AlbumAPIController')/*->middleware('APIKey')*/;
    //Route::resource('/songs'/* . $additional_url*/,'SongAPIController')/*->middleware('APIKey')*/;
    //Route::resource('/artists'/* . $additional_url*/,'ArtistAPIController')/*->middleware('APIKey')*/;
    
    //General songs API functions
    //Specific search options
    //1 keyword
    Route::get('/songs/song/{song}' . $additional_url,'SongAPIController@search_song')->middleware('APIKey');
    Route::get('/songs/album/{album}' . $additional_url,'SongAPIController@search_album')->middleware('APIKey');
    Route::get('/songs/artist/{artist}' . $additional_url,'SongAPIController@search_artist')->middleware('APIKey');

    //2 keywords
    Route::get('/songs/artist/{artist}/album/{album}' . $additional_url,'SongAPIController@search_artist_album')->middleware('APIKey');
    Route::get('/songs/album/{album}/artist/{artist}' . $additional_url,'SongAPIController@search_album_artist')->middleware('APIKey');
    Route::get('/songs/album/{album}/song/{song}' . $additional_url,'SongAPIController@search_album_song')->middleware('APIKey');
    Route::get('/songs/song/{song}/album/{album}' . $additional_url,'SongAPIController@search_song_album')->middleware('APIKey');
    Route::get('/songs/song/{song}/artist/{artist}' . $additional_url,'SongAPIController@search_song_artist')->middleware('APIKey');
    Route::get('/songs/artist/{artist}/song/{song}' . $additional_url,'SongAPIController@search_artist_song')->middleware('APIKey');

    //3 keywords
    Route::get('/songs/artist/{artist}/song/{song}/album/{album}' . $additional_url,'SongAPIController@search_artist_song_album')->middleware('APIKey');
    Route::get('/songs/album/{album}/song/{song}/artist/{artist}' . $additional_url,'SongAPIController@search_album_song_artist')->middleware('APIKey');
    Route::get('/songs/song/{song}/artist/{artist}/album/{album}' . $additional_url,'SongAPIController@search_song_artist_album')->middleware('APIKey');
    Route::get('/songs/song/{song}/album/{album}/artist/{artist}' . $additional_url,'SongAPIController@search_song_album_artist')->middleware('APIKey');
    Route::get('/songs/album/{album}/artist/{artist}/song/{song}' . $additional_url,'SongAPIController@search_album_artist_song')->middleware('APIKey');
    Route::get('/songs/artist/{artist}/album/{album}/song/{song}' . $additional_url,'SongAPIController@search_artist_album_song')->middleware('APIKey');

    //Albums API
    
    Route::get('/albums/add/{id}' . $additional_url,'AlbumAPIController@addSongs')->middleware('APIKey');
    //Specific search options
    //1 keyword
    Route::get('/albums/song/{song}' . $additional_url,'AlbumAPIController@search_song')->middleware('APIKey');
    Route::get('/albums/album/{album}' . $additional_url,'AlbumAPIController@search_album')->middleware('APIKey');
    Route::get('/albums/artist/{artist}' . $additional_url,'AlbumAPIController@search_artist')->middleware('APIKey');

    //2 keywords
    Route::get('/albums/artist/{artist}/album/{album}' . $additional_url,'AlbumAPIController@search_artist_album')->middleware('APIKey');
    Route::get('/albums/album/{album}/artist/{artist}' . $additional_url,'AlbumAPIController@search_album_artist')->middleware('APIKey');
    Route::get('/albums/album/{album}/song/{song}' . $additional_url,'AlbumAPIController@search_album_song')->middleware('APIKey');
    Route::get('/albums/song/{song}/album/{album}' . $additional_url,'AlbumAPIController@search_song_album')->middleware('APIKey');
    Route::get('/albums/song/{song}/artist/{artist}' . $additional_url,'AlbumAPIController@search_song_artist')->middleware('APIKey');
    Route::get('/albums/artist/{artist}/song/{song}' . $additional_url,'AlbumAPIController@search_artist_song')->middleware('APIKey');

    //3 keywords
    Route::get('/albums/artist/{artist}/song/{song}/album/{album}' . $additional_url,'AlbumAPIController@search_artist_song_album')->middleware('APIKey');
    Route::get('/albums/album/{album}/song/{song}/artist/{artist}' . $additional_url,'AlbumAPIController@search_album_song_artist')->middleware('APIKey');
    Route::get('/albums/song/{song}/artist/{artist}/album/{album}' . $additional_url,'AlbumAPIController@search_song_artist_album')->middleware('APIKey');
    Route::get('/albums/song/{song}/album/{album}/artist/{artist}' . $additional_url,'AlbumAPIController@search_song_album_artist')->middleware('APIKey');
    Route::get('/albums/album/{album}/artist/{artist}/song/{song}' . $additional_url,'AlbumAPIController@search_album_artist_song')->middleware('APIKey');
    Route::get('/albums/artist/{artist}/album/{album}/song/{song}' . $additional_url,'AlbumAPIController@search_artist_album_song')->middleware('APIKey');


    //Artist API
    //Specific search options:
    //1 keyword
    Route::get('artists/artist/{artist}' . $additional_url,'ArtistAPIController@search_artist')->middleware('APIKey');
    Route::get('artists/song/{song}' . $additional_url,'ArtistAPIController@search_song')->middleware('APIKey');
    Route::get('artists/album/{album}' . $additional_url,'ArtistAPIController@search_album')->middleware('APIKey');

    //2 keywords
    Route::get('artists/artist/{artist}/song/{song}' . $additional_url,'ArtistAPIController@search_artist_song')->middleware('APIKey');
    Route::get('artists/song/{song}/artist/{artist}' . $additional_url,'ArtistAPIController@search_song_artist')->middleware('APIKey');
    Route::get('artists/artist/{artist}/album/{album}' . $additional_url,'ArtistAPIController@search_artist_album')->middleware('APIKey');
    Route::get('artists/album/{album}/artist/{artist}' . $additional_url,'ArtistAPIController@search_album_artist')->middleware('APIKey');
    Route::get('artists/album/{album}/song/{song}' . $additional_url,'ArtistAPIController@search_album_song')->middleware('APIKey');
    Route::get('artists/song/{song}/album/{album}' . $additional_url,'ArtistAPIController@search_song_album')->middleware('APIKey');

    //3 keywords
    Route::get('artists/song/{song}/album/{album}/artist/{artist}' . $additional_url,'ArtistAPIController@search_song_album_artist')->middleware('APIKey');
    Route::get('artists/album/{album}/song/{song}/artist/{artist}' . $additional_url,'ArtistAPIController@search_album_song_artist')->middleware('APIKey');
    Route::get('artists/album/{album}/artist/{artist}/song/{song}' . $additional_url,'ArtistAPIController@search_album_artist_song')->middleware('APIKey');
    Route::get('artists/artist/{artist}/album/{album}/song/{song}' . $additional_url,'ArtistAPIController@search_artist_album_song')->middleware('APIKey');
    Route::get('artists/song/{song}/artist/{artist}/album/{album}' . $additional_url,'ArtistAPIController@search_song_artist_album')->middleware('APIKey');
    Route::get('artists/artist/{artist}/song/{song}/album/{album}' . $additional_url,'ArtistAPIController@search_artist_song_album')->middleware('APIKey');
}
else
{
    //Translation routes:
    Route::get('/translate/en-nl/{id}','TranslationController@translateEnglishToDutchHtml');

    //Playlist API:
    Route::get('/playlists/','PlaylistAPIController@index');
    Route::post('/playlists/','PlaylistAPIController@store');
    Route::get('/playlists/{id}','PlaylistAPIController@show');
    Route::put('/playlists/{id}','PlaylistAPIController@update');
    //Route::resource('/playlists','PlaylistAPIController');

    //General songs API functions
    
    Route::get('/songs/','SongAPIController@index');
    Route::post('/songs/','SongAPIController@index');
    Route::get('/songs/{id}','SongAPIController@index');
    //Route::resource('/songs','SongAPIController');
    //Specific search options
    //1 keyword
    Route::get('/songs/song/{song}','SongAPIController@search_song');
    Route::get('/songs/album/{album}','SongAPIController@search_album');
    Route::get('/songs/artist/{artist}','SongAPIController@search_artist');

    //2 keywords
    Route::get('/songs/artist/{artist}/album/{album}','SongAPIController@search_artist_album');
    Route::get('/songs/album/{album}/artist/{artist}','SongAPIController@search_album_artist');
    Route::get('/songs/album/{album}/song/{song}','SongAPIController@search_album_song');
    Route::get('/songs/song/{song}/album/{album}','SongAPIController@search_song_album');
    Route::get('/songs/song/{song}/artist/{artist}','SongAPIController@search_song_artist');
    Route::get('/songs/artist/{artist}/song/{song}','SongAPIController@search_artist_song');

    //3 keywords
    Route::get('/songs/artist/{artist}/song/{song}/album/{album}','SongAPIController@search_artist_song_album');
    Route::get('/songs/album/{album}/song/{song}/artist/{artist}','SongAPIController@search_album_song_artist');
    Route::get('/songs/song/{song}/artist/{artist}/album/{album}','SongAPIController@search_song_artist_album');
    Route::get('/songs/song/{song}/album/{album}/artist/{artist}','SongAPIController@search_song_album_artist');
    Route::get('/songs/album/{album}/artist/{artist}/song/{song}','SongAPIController@search_album_artist_song');
    Route::get('/songs/artist/{artist}/album/{album}/song/{song}','SongAPIController@search_artist_album_song');

    //Albums API
    
    Route::get('/albums/','AlbumAPIController@index');
    Route::post('/albums/','AlbumAPIController@store');
    Route::get('/albums/{id}','AlbumAPIController@show');
    //Route::resource('/albums','AlbumAPIController');
    Route::get('/albums/add/{id}','AlbumAPIController@addSongs');
    //Specific search options
    //1 keyword
    Route::get('/albums/song/{song}','AlbumAPIController@search_song');
    Route::get('/albums/album/{album}','AlbumAPIController@search_album');
    Route::get('/albums/artist/{artist}','AlbumAPIController@search_artist');

    //2 keywords
    Route::get('/albums/artist/{artist}/album/{album}','AlbumAPIController@search_artist_album');
    Route::get('/albums/album/{album}/artist/{artist}','AlbumAPIController@search_album_artist');
    Route::get('/albums/album/{album}/song/{song}','AlbumAPIController@search_album_song');
    Route::get('/albums/song/{song}/album/{album}','AlbumAPIController@search_song_album');
    Route::get('/albums/song/{song}/artist/{artist}','AlbumAPIController@search_song_artist');
    Route::get('/albums/artist/{artist}/song/{song}','AlbumAPIController@search_artist_song');

    //3 keywords
    Route::get('/albums/artist/{artist}/song/{song}/album/{album}','AlbumAPIController@search_artist_song_album');
    Route::get('/albums/album/{album}/song/{song}/artist/{artist}','AlbumAPIController@search_album_song_artist');
    Route::get('/albums/song/{song}/artist/{artist}/album/{album}','AlbumAPIController@search_song_artist_album');
    Route::get('/albums/song/{song}/album/{album}/artist/{artist}','AlbumAPIController@search_song_album_artist');
    Route::get('/albums/album/{album}/artist/{artist}/song/{song}','AlbumAPIController@search_album_artist_song');
    Route::get('/albums/artist/{artist}/album/{album}/song/{song}','AlbumAPIController@search_artist_album_song');


    //Artist API
    Route::get('/artists/','ArtistAPIController@index');
    Route::post('/artists/','ArtistAPIController@store');
    Route::get('/artists/{id}','ArtistAPIController@show');
    //Route::resource('/artists','ArtistAPIController');
    //Specific search options:
    //1 keyword
    Route::get('artists/artist/{artist}','ArtistAPIController@search_artist');
    Route::get('artists/song/{song}','ArtistAPIController@search_song');
    Route::get('artists/album/{album}','ArtistAPIController@search_album');

    //2 keywords
    Route::get('artists/artist/{artist}/song/{song}','ArtistAPIController@search_artist_song');
    Route::get('artists/song/{song}/artist/{artist}','ArtistAPIController@search_song_artist');
    Route::get('artists/artist/{artist}/album/{album}','ArtistAPIController@search_artist_album');
    Route::get('artists/album/{album}/artist/{artist}','ArtistAPIController@search_album_artist');
    Route::get('artists/album/{album}/song/{song}','ArtistAPIController@search_album_song');
    Route::get('artists/song/{song}/album/{album}','ArtistAPIController@search_song_album');

    //3 keywords
    Route::get('artists/song/{song}/album/{album}/artist/{artist}','ArtistAPIController@search_song_album_artist');
    Route::get('artists/album/{album}/song/{song}/artist/{artist}','ArtistAPIController@search_album_song_artist');
    Route::get('artists/album/{album}/artist/{artist}/song/{song}','ArtistAPIController@search_album_artist_song');
    Route::get('artists/artist/{artist}/album/{album}/song/{song}','ArtistAPIController@search_artist_album_song');
    Route::get('artists/song/{song}/artist/{artist}/album/{album}','ArtistAPIController@search_song_artist_album');
    Route::get('artists/artist/{artist}/song/{song}/album/{album}','ArtistAPIController@search_artist_song_album');
}